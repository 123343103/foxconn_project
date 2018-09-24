<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/20
 * Time: 上午 08:32
 */

namespace app\modules\warehouse\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCurrency;
use app\modules\hr\models\HrOrganization;
use app\modules\ptdt\models\BsMaterial;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\BsWhPrice;
use app\modules\warehouse\models\IcInvCosth;
use app\modules\warehouse\models\IcInvCostlist;
use app\modules\warehouse\models\OWhpdt;
use app\modules\warehouse\models\OWhpdtDt;
use app\modules\warehouse\models\search\IcInvCosthSearch;
use app\modules\warehouse\models\WhPrice;
use app\modules\warehouse\models\WhPricel;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Query;

class WhCostConfirmController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\IcInvCosth';

    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new IcInvCosthSearch();
        $dataProvoder = $searchModel->search($param);
        $model = $dataProvoder->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvoder->totalCount;
        return $list;
    }

    public function actionWhCostConfirm($o_whpkid)
    {
        $sql = "SELECT
	ow.o_whcode,
	ow.o_date,
	bw.wh_name,
	ow.o_whid,
	bw.wh_code,
	bc.company_name
FROM
	wms.o_whpdt ow
LEFT JOIN wms.bs_wh bw ON bw.wh_id = ow.o_whid
LEFT JOIN oms.ord_info oi ON oi.ord_id = ow.ord_id
LEFT JOIN erp.bs_company bc ON bc.company_id = oi.corporate
where ow.o_whpkid={$o_whpkid}";
        $IcInvh['OWhpdt'] = Yii::$app->getDb('wms')->createCommand($sql)->queryOne();
//        dumpE($IcInvh);
        //根据仓库id查询该仓库标准价格
        $query = (new Query())->select([
            'wp.whp_id',
            'wpl.whpl_id',
            'wpl.whpb_id',
            'bwp.whpb_sname',
            'wpl.whpb_num',
            'wpl.whpb_curr',
        ])->from(['wp' => WhPrice::tableName()])
            ->leftJoin(WhPricel::tableName() . '  wpl', 'wp.whp_id = wpl.whp_id')
            ->leftJoin(BsWhPrice::tableName() . '  bwp', 'bwp.whpb_id = wpl.whpb_id');
        $dataProvider = new  ActiveDataProvider([
            "query" => $query,

        ]);
        $query->andFilterWhere(['wp.wh_id' => $IcInvh['OWhpdt']['o_whid']]);
        $query->andFilterWhere(['wp.op_id' => WhPrice::OUT_WH]);
        $data['WhPrice'] = $dataProvider->getModels();
        $downList['BsCurrency'] = BsCurrency::getList();//币别
        $IcInvh = array_merge($IcInvh, $data, $downList);
        return $IcInvh;
    }

    //新增数据
    public function actionCreate1()
    {
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $post = Yii::$app->request->post();
//            return $post;
            //根据部门code查询部门id
            $HO = HrOrganization::byCodeOrg($post['IcInvCosth']['organization_code']);
            $IcInvCosth = new IcInvCosth();
            $IcInvCosth->invh_id = $post['IcInvCosth']['invh_id'];
            $IcInvCosth->organization_id = $HO['organization_id'];
            $IcInvCosth->audit_status = IcInvCosth::STATUS_ADD;
            $IcInvCosth->create_by = $post['IcInvCosth']['create_by'];
            $IcInvCosth->create_at = $post['IcInvCosth']['create_at'];
            if (!$IcInvCosth->save()) {
                throw new Exception(json_encode($IcInvCosth->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            $invch_id = $IcInvCosth->attributes['invch_id'];
            foreach ($post['IcInvCostlist'] as $val) {
                $IcInvCostlist = new IcInvCostlist();
                $IcInvCostlist->invch_id = $invch_id;
                $IcInvCostlist->whp_id = $val['whp_id'];
                $IcInvCostlist->whpl_id = $val['whpl_id'];
                $IcInvCostlist->invcl_nprice = $val['invcl_nprice'];
                $IcInvCostlist->subitem_remark = $val['subitem_remark'];
                if (!$IcInvCostlist->save()) {
                    throw new Exception(json_encode($IcInvCostlist->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
//            }
            $transaction->commit();
            //根据审核编码设置审核id
            $bbt = BsBusinessType::find()->where(['business_code' => IcInvCosth::VERIFY_CODE])->one();
            return $this->success('新增成功', [
                'id' => $invch_id,
                'code' => $bbt->business_type_id
            ]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }

    }

    //查询出库单号对应的商品信息
    public function actionGetPdt($invh_id)
    {
        $query = (new Query())->select([
            'owd.part_no',
            'owd.o_whnum real_oquantity',
            'bm.pdt_name',
            'bm.brand',
            'bm.tp_spec',
            'bm.unit',
        ])->from(['owd' => 'wms.o_whpdt_dt'])
            ->leftJoin('pdt.bs_material  bm', 'owd.part_no = bm.part_no');
        $dataProvider = new  ActiveDataProvider([
            "query" => $query,

        ]);
        $query->andFilterWhere(['owd.o_whpkid' => $invh_id]);
        return $dataProvider->getModels();
    }

    //根据出库主键查询出仓费用
    public function actionGetCost($invh_id)
    {
        $query = (new Query())->select([
            'b.invcl_id',
            'b.whpl_id',
            'd.whpb_sname',
            'b.invcl_nprice',
        ])->from(['a' => IcInvCosth::tableName()])
            ->leftJoin(IcInvCostlist::tableName() . '  b', 'a.invch_id = b.invch_id')
            ->leftJoin(WhPricel::tableName() . '  c', 'b.whpl_id = c.whpl_id')
            ->leftJoin(BsWhPrice::tableName() . '  d', 'c.whpb_id = d.whpb_id');
        $dataProvider = new  ActiveDataProvider([
            "query" => $query,

        ]);
        $query->andFilterWhere(['a.invh_id' => $invh_id]);
        return $dataProvider->getModels();
    }

    //修改价格
    public function actionUpdateNprice($invcl_id, $invcl_nprice)
    {
        try {
            $IcInvCostlist = IcInvCostlist::findOne($invcl_id);
            $IcInvCostlist->invcl_nprice = $invcl_nprice;
            if (!$IcInvCostlist->save()) {
                throw new Exception(json_encode($IcInvCostlist->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

    public function actionModel($invh_id)
    {
        return 1;
    }

    //下拉框的值
    public function actionDownList()
    {
        return [
            "status" => IcInvCosth::getStatus(),    //状态
            "type" => IcInvCosth::getOutType(),                      //单据类型
            "company" => OWhpdt::getBsCompany(),//法人
            "wh_attr" => BsWh::getWhAttr()//仓库属性

        ];
    }
}