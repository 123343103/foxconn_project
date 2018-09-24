<?php

namespace app\modules\sale\controllers;

use app\controllers\BaseController;
use app\modules\common\models\BsBusinessType;
use app\modules\system\models\SystemLog;
use Yii;
use app\modules\sale\models\OrdRefund;
use app\modules\sale\models\search\OrdRefundSearch;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdRefundController implements the CRUD actions for OrdRefund model.
 */
class OrdRefundController extends BaseController
{
    private $_url = 'sale/ord-refund/'; //对应api


    /**
     * Lists all OrdRefund models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = Json::decode($this->findCurl()->get($url), true);
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['refund_no'] = '<a href="' . Url::to(['view', 'id' => $val['refund_id']]) . '">' . $val['refund_no'] . '</a>';
            }
            return Json::encode($dataProvider);
        }
        $downList = $this->getDownList();
        $columns = $this->getField("/sale/ord-refund/index");
        $child_columns = $this->getField("/sale/ord-refund/get-product");
        $typeId =  BsBusinessType::find()->select(['business_type_id'])->where(['business_code' => 'refund'])->one();
        return $this->render('index',[
            'downList'=>$downList,
            'columns' => $columns,
            'child_columns' => $child_columns,
            'queryParam' => $queryParam,
            'typeId' => $typeId['business_type_id']
        ]);
    }
    /**
     * 导出退款列表
     */
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . "index?export=1";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        \Yii::$app->controller->action->id = 'index';
        SystemLog::addLog('导出退款列表');
        return $this->exportFiled($dataProvider['rows']);
    }
    /**
     * @return string
     * 新增退款单
     */
    public function actionCreate($id=null){
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
            $post['OrdRefund']['opper'] = Yii::$app->user->identity->user_id;
            $post['OrdRefund']['opp_ip'] = Yii::$app->request->userIP;
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($post['ord_no'].'退款保存成功');
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id'],'is_apply'=>1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view','id'=>$data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $data = $this->getOrd($id);
        $downList = $this->getDownList();
//        dumpE($data);
        return $this->render('create',[
            'data' => $data['info'],
            'dt' => $data['dt'],
            'downList' => $downList,
            'id' => $id
        ]);
    }

    /**
     * @param $id
     * @return string
     * 退款单驳回修改送审
     */
    public function actionUpdate($id){
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
            $status = Yii::$app->request->get('status');
            $post['OrdRefund']['opper'] = Yii::$app->user->identity->user_id;
            $post['OrdRefund']['opp_ip'] = Yii::$app->request->userIP;
            $url = $this->findApiUrl() . $this->_url . "update?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($post['ord_no'].'退款保存成功');
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view','id'=>$id,'is_apply'=>1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view','id'=>$id])]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $model = $this->getModel($id);
        $data = $this->getOrd($model['ord_id']);
        $dt = $this->getOrdDt($id);
//        dumpE($dt);
        $downList = $this->getDownList();
//        dumpE($data);
        return $this->render('update',[
            'model' => $model,
            'data' => $data['info'],
            'dt' => $dt,
            'downList' => $downList,
            'id' => $id
        ]);
    }

    /**
     * @param $id
     * @return string
     * 退款单审核详情页
     */
    public function actionView($id){
        $url = $this->findApiUrl().$this->_url.'view?id='.$id;
        $data = Json::decode($this->findCurl()->get($url));
        $isApply = Yii::$app->request->get('is_apply');
        $typeId =  BsBusinessType::find()->select(['business_type_id'])->where(['business_code' => 'refund'])->one();
        $verify = $this->getVerify($id,$typeId['business_type_id']);//審核信息
//        dumpE($typeId);
        return $this->render('view',[
            'data' => $data['refund'],
            'dt'   => $data['dt'],
            'isApply'=>$isApply,
            'id' => $id,
            'verify'=>$verify,
            'typeId' => $typeId['business_type_id']
        ]);
    }

    /**
     * @return string
     * 取消报价
     */
    public function actionCancleQuote($id){
        if(Yii::$app->request->getIsPost()){
            $post = Yii::$app->request->post();
//            $post['PriceInfo']['caner'] = Yii::$app->user->identity->staff_id;
//            $post['PriceInfo']['can_ip'] = Yii::$app->request->getUserIP();
//            $post['PriceInfo']['can_date'] = date('Y-m-d H:i:s',time());
            $url = $this->findApiUrl() . $this->_url . "cancle-quote?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg'].'取消退款');
                return Json::encode(['msg' => "取消成功", "flag" => 1,"url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('_canclequote', [
            'id' => $id,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 确认退款
     */
    public function actionConfirm($id){
        $url = $this->findApiUrl().$this->_url.'confirm?id='.$id;
        return $this->findCurl()->get($url);
    }

    /**
     * 获取审核记录
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function getDownList(){
        $url = $this->findApiUrl().$this->_url.'down-list';
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * @return mixed
     * 获取订单信息
     */
    public function getOrd($id){
        $url = $this->findApiUrl().$this->_url.'ord?id='.$id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * @return mixed
     * 获取退款单子表信息
     */
    public function getOrdDt($id){
        $url = $this->findApiUrl().$this->_url.'ord-dt?id='.$id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * @return mixed
     * 获取商品信息
     */
    public function actionGetProduct($id){
        $url = $this->findApiUrl().$this->_url.'get-product?id='.$id;
        return $this->findCurl()->get($url);
    }

    /**
     * @param $id
     * @return mixed
     * 获取退款主表信息
     */
    public function getModel($id){
        $url = $this->findApiUrl().$this->_url.'get-model?id='.$id;
        return Json::decode($this->findCurl()->get($url));
    }
}
