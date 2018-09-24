<?php
/**
 * User: F1676624
 * Date: 2017//25
 * 库存异动控制器
 */

namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsForm;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsSt;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\InvChangel;
use app\modules\warehouse\models\LBsInvtList;
use app\modules\warehouse\models\LInvtRe;
use app\modules\warehouse\models\RcpNotice;
use app\modules\warehouse\models\RcpNoticeDt;
use app\modules\warehouse\models\search\InvChangehSearch;
use app\modules\warehouse\models\search\InvChangelSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use app\modules\common\models\BsDistrict;
use yii\web\NotFoundHttpException;
use Yii;
use app\modules\warehouse\models\show\InvChangehShow;
use app\modules\warehouse\models\search\LBsInvtListSearch;
use app\modules\common\models\BsBusinessType;
use app\modules\warehouse\models\BsWh;
use yii\helpers\Json;
use app\modules\common\models\BsPubdata;
use app\modules\warehouse\models\search\PartSearch;


class WarehouseChangeController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\InvChangeh';

    public function actionIndex()
    {
        $searchModel = new InvChangehSearch();
        $dataProvider = $searchModel->changeSearch(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionCreate()
    {
        $changeH = new InvChangeh();
        $changeH->codeType = InvChangeh::CODE_TYPE_YD;
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $changeH->load($post);
            if (!$changeH->save()) {
                throw new \Exception("新增异动单信息失败");
            }
            $chhId = $changeH->chh_id;
            if (!empty($post['changeL'])) {
                foreach ($post['changeL'] as $k => $v) {
                    $changeL = new InvChangel();
                    $value["InvChangel"] = $v;
                    $changeL->chh_id = $chhId;
                    if (!$changeL->load($value) || !$changeL->save()) {
                        throw new \Exception(current($changeL->getFirstErrors()));
                    }
                }
            }
            /*料号异动更新库存详情表 */
//            if (!empty($post['LBsInvtList'])) {
//                foreach ($post['LBsInvtList'] as $k => $v) {
//                    $model=LBsInvtList::findOne($v['invt_iid']);
//                    $model->L_invt_num=$v['L_invt_num'];
//                    if (!$model->save()) {
//                        throw new \Exception(current($model->getFirstErrors()));
//                    }
//                }
//            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $msg = array('id' => $chhId, 'msg' => '新增异动单');
        return $this->success('', $msg);
    }

    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $invChangeH = $this->getModel($id);
        $post = Yii::$app->request->post();
        try {
            /*客户信息*/
            $invChangeH->load(Yii::$app->request->post());
            if (!$invChangeH->save()) {
                throw new \Exception("修改异动单失败");
            }
            /*报废品信息修改*/
            InvChangel::deleteAll(['chh_id' => $id]);
            if (!empty($post['changeL'])) {
                foreach ($post['changeL'] as $k => $v) {
                    $changeL = new InvChangel();
                    $value["InvChangel"] = $v;
                    $changeL->chh_id = $id;
                    if (!$changeL->load($value) || !$changeL->save()) {
                        throw new \Exception(current($changeL->getFirstErrors()));
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        $msg = array('id' => $id, 'msg' => '修改异动单信息"' . $invChangeH["chh_id"] . '"');
        return $this->success('', $msg);
    }

    //选择商品
    public function actionSelectProduct()
    {
        $model = new LBsInvtListSearch();
        $dataProvider = $model->changeSearch(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }

    //选择储位
    public function actionSelectStore()
    {
        $model = new PartSearch();
        $dataProvider = $model->changeSearch(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }

    //加载子表
    public function actionGetProduct()
    {
        $params = Yii::$app->request->queryParams;
        $search = new InvChangehSearch();
        $invLModel = $search->searchInvL($params["id"])->getModels();
        return $invLModel;
    }

    public function actionGetDownList()
    {
        $downList = [];
        // 异动类型
        $downList['changeType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'wm05'])->orderBy(['business_type_id' => SORT_ASC])->all();
        //储位
        $downList['part'] = BsPart::find()->select(['part_code', 'part_name'])->all();
        // 仓库信息
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name', 'wh_attr'])->all();
        return $downList;
    }

    public function actionModel($id)
    {
        return InvChangehShow::findOne($id);
    }

    public function actionModels($id)
    {
        $search = new InvChangehSearch();
        $invLModel = $search->searchInvL($id)->getModels();
        return $invLModel;
    }

    protected function getModel($id)
    {
        if (($model = InvChangeh::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    //移仓入库申请
    public  function actionInWare($id,$staffid)
    {
        $sql01="SELECT *,
                    (SELECT b.wh_code FROM wms.bs_wh b WHERE b.wh_id=h.wh_id) Owhid,
                    (SELECT b.wh_code FROM wms.bs_wh b WHERE b.wh_id=h.wh_id2) Iwhid,
                    (SELECT c.st_code FROM wms.bs_st c WHERE c.st_id=l.st_id) stcode
                    from  wms.inv_changeh h 
                    LEFT JOIN wms.inv_changel l ON h.chh_id=l.chh_id
                    WHERE h.chh_id=:chh_id";
        $ret1=\Yii::$app->db->createCommand($sql01,['chh_id'=>$id])->queryAll();
//            return $ret1[0]['chh_code'];
        $transaction=RcpNotice::getDb()->beginTransaction();
        try{
            $model=new RcpNotice();
//            $model->rcpnt_no='1124567899';
            $model->rcpnt_status=1;
            $model->rcpnt_type=3;
            $model->i_whcode=$ret1[0]['Iwhid'];
            $model->o_whcode=$ret1[0]['Owhid'];
            $model->prch_no=$ret1[0]['chh_code'];
            $model->leg_id=$ret1[0]['comp_id'];
            $model->app_depno=$ret1[0]['depart_id'];
            $model->creator=$staffid;
            $model->creat_date=date('Y-m-d');
            if(!$model->save()){
                throw new Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            foreach ($ret1 as $key=>$val){
                $model2=new RcpNoticeDt();
                $model2->rcpnt_no=$model->rcpnt_no;
                $model2->ord_id=$val['chl_bach'];
                $model2->part_no=$val['pdt_no'];
                $model2->delivery_num=$val['chl_num'];
                $model2->before_stno=$val['stcode'];
                $model2->operator='1103';
                $model2->operate_date=date('Y-m-d');
                if(!$model2->save()){
                    throw new Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                }
            }
            $model3=InvChangeh::findOne($id);
            $model3->chh_status=InvChangeh::STATUS_INMOVE;
            if(!$model3->save()){
                throw new Exception(json_encode($model3->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            $transaction->commit();
            return $this->success('新增成功',[
                'id'=>$model->rcpnt_id
            ]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getFile()."--".$e->getLine()."--".$e->getMessage());
        }
    }


}
