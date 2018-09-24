<?php
/**
 * Created by PhpStorm.
 * User: F3858997
 * Date: 2017/7/10
 * Time: 下午 03:54
 * 商品异动控制器
 */

namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\modules\hr\models\HrOrganization;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\InvChangel;
use app\modules\warehouse\models\LInvtRe;
use app\modules\warehouse\models\search\InvChangehSearch;
use app\modules\warehouse\models\search\InvChangelSearch;
use app\modules\warehouse\models\show\InvChangehShow;
use app\modules\warehouse\models\show\InvChangelShow;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use app\modules\common\models\BsDistrict;
use yii\web\NotFoundHttpException;
use Yii;
use app\modules\sale\models\search\SaleCustrequireHSearch;
use app\modules\common\models\BsBusinessType;
use app\modules\warehouse\models\BsWh;
use yii\helpers\Json;


class InvChangehController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\InvChangeh';

    public function actionIndex()
    {
        $searchModel = new InvChangehSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return array
     * 新增报废单信息
     */
    public function actionCreate()
    {
        $changeH = new InvChangeh();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            /*报废单信息新增*/
            $changeH->load($post);
            if (!$changeH->save()) {
                throw new \Exception("新增报废单信息失败");
            }
            $chhId = $changeH->chh_id;
            /*报废品信息新增*/
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
            $transaction->commit();
            return $this->success('新增成功！',['id'=>$changeH->chh_id,'chh_type'=>$changeH->chh_type]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
//        $msg = array('id'=>$chhId,'msg'=>'新增报废单');
//        return $this->success('',$msg);
    }

    /**
     * @param $id
     * @return array
     * 修改报废单信息
     */
    public function actionUpdate($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $invChangeH = $this->getModel($id);
        $post = Yii::$app->request->post();
        try {
            /*客户信息*/
            $invChangeH->load(Yii::$app->request->post());
            if (!$invChangeH->save()) {
                throw new \Exception("修改报废单失败");
            }
            /*报废品信息修改*/
            InvChangel::deleteAll(['chh_id'=>$id]);
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
        return $this->success('修改成功！',['id'=>$invChangeH->chh_id,'chh_type'=>$invChangeH->chh_type]);
//        $msg = array('id'=>$id,'msg'=>'修改报废单信息"'.$invChangeH["chh_id"].'"');
//        return $this->success('',$msg);
    }
    /**
     * @param $id
     * @return array
     * 查看
     */
    public function actionView($id){
        $invHModel = InvChangehShow::findOne($id);
        $search = new InvChangehSearch();
        $invLModel=$search->searchInvL($id)->getModels();
        $list[] = $invHModel;
        $list[] = $invLModel;
        return $list;
    }
    /**
     * @param $id
     * @return array
     * 删除
     */
    public function actionDeleteInv($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //報廢单主表
            $mainModel=InvChangeh::findOne($id);
            $mainModel->chh_status=InvChangeh::STATUS_DELETE;
            if(!$mainModel->save()){
                throw new Exception(json_encode($mainModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //报废单子表
            $childModel=InvChangel::findAll(['chh_id'=>$id]);
            if(!empty($childModel)){
                foreach($childModel as $val){
//                    $val->chl_status=InvChangel::DELETE_STATUS;
                    if(!$val->delete()){
                        throw new Exception(json_encode($val->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success('删除成功！',$mainModel->chh_code);
        }catch(Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
//    public function actionDeleteInv($id)
//    {
////        $model = $this->getModel($id);
//        $model = InvChangeh::findOne($id);
//        $model->chh_status = InvChangeh::STATUS_DELETE;
////        dumpE($model->save());
//        if ($model->save()) {
//            return $this->success();
//        } else {
//            return $this->error();
//        }
//    }
    //选择商品
    public function actionSelectProduct()
    {
        $model = new SaleCustrequireHSearch();
        $dataProvider = $model->searchProduct(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }
    //加载子表
    public function actionGetProduct()
    {
        $params = Yii::$app->request->queryParams;
        $model = new InvChangelSearch();
        $model = $model->search($params);
        return $model;
    }
    public function actionBusinessType()
    {
        $businessType = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'wm04'])->all();
        foreach ($businessType as $k=>$v) {
            $data[$v['business_type_id']] = $v['business_value'];
        }
        return $data;
    }

    public function actionGetDownList()
    {
        $downList = [];
        //報廢类别
        $downList['changeType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'wm04'])->all();
        //申请部门
        $downList['organization'] = HrOrganization::find()->select(['organization_id','organization_name','organization_code'])->all();
        //储位
        $downList['part'] = BsPart::find()->select(['part_code','part_name'])->all();
        //报废方式
        $downList['scrap'] = [
            0 => '垃圾回收',
            1 => '销毁',
            2 => '废料变卖',
            4 => '低价转让',
        ];

        // 订单类型
//        $downList['status'] = [
//            SaleQuotedpriceH::STATUS_CREATE => '新增',
//            SaleQuotedpriceH::STATUS_WAIT => '转报价',
//            SaleQuotedpriceH::STATUS_CHECKING => '审核中',
//            SaleQuotedpriceH::STATUS_FINISH => '已报价',
//            SaleQuotedpriceH::STATUS_PREPARE => '报价驳回',
//        ];
//        // 订单来源
//        $downList['orderFrom'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::ORDER_FROM])->all();
//        // 发票类型
//        $downList['invoiceType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::CRM_INVOICE_TYPE])->all();
//        // 交易币别
//        $downList['currency'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::SUPPLIER_TRADE_CURRENCY])->all();
//        // 运输方式
//        $downList['transport'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->all();
//        // 配送方式
//        $downList['dispatching'] = BsDeliverymethod::find()->select(['bdm_id', 'bdm_code', 'bdm_sname'])->all();
        // 仓库信息
        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        // 仓库信息
        $downList['warehousebf'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->where(['wh_yn'=>'N'])->all();

        // 单位
//        $downList['unit'] = BsCategoryUnit::find()->select(['id', 'unit_name'])->all();
        return $downList;
    }
    protected function getModel($id)
    {
        if (($model = InvChangeh::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionModel($id){
        return InvChangeh::findOne($id);
    }

    public function actionModels($id){
        $invHModel = InvChangehShow::findOne($id);
        $search = new InvChangehSearch();
        $invLModel=$search->searchInvL($id)->getModels();
        $list[] = $invHModel;
        $list[] = $invLModel;
        return $list;
    }

    //报废单作废

    public function actionCanReason($id)
    {
        $model=InvChangeh::findOne($id);
        $model->load(Yii::$app->request->post());
        $model->chh_status=InvChangeh::STATUS_MOVE;
        if(!$model->save()){
            throw new Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }else{
            return $this->success('操作成功');
        }
    }

    //获取仓库代码
    public function actionWhCode($id)
    {
        $retss=BsWh::getBsWhcn($id);
        return $retss;
    }

}
