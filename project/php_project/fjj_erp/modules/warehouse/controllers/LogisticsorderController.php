<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/11
 * Time: 下午 02:30
 */

namespace app\modules\warehouse\controllers;



use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class LogisticsorderController extends BaseController
{
    private $_url = "warehouse/logisticsorder/";  //对应api控制器URL
    //物流订单列表
    public function actionIndex(){
        $queryParam = Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."index";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            $dataProvider = Json::decode($dataProvider);
//            foreach ($dataProvider['rows'] as $key => $val) {
//                if (Menu::isAction('/warehouse/logisticsorder/view')) {
//                    $dataProvider['rows'][$key]['lg_no'] = '<a href="' . Url::to(['/warehouse/logisticsorder/view', 'id' => $val['ord_lg_id']]) . '">' . $dataProvider['rows'][$key]['lg_no'] . '</a>';
//                }
//            }
            return Json::encode($dataProvider);
        }
        return  $this->render('index',[
            'param'=>$queryParam,
        ]);
    }
    //修改物流订单
    public function actionUpdate($id)
    {
        if($post=Yii::$app->request->post()){
            $url=$this->findApiUrl().'warehouse/logisticsorder/update?id='.$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                    return json_encode([
                        'msg' => "保存成功",
                        'flag'=>1,
                        'billId'=>$id,//单据id
                        'billTypeId'=>61,//单据类型
                        "url" => Url::to(['view','id'=>$id])
                    ]);
            }
            else{
                return Json::encode(['msg' =>'发生未知错误', "flag" => 0]);
            }
        }
        else{
            $logorderinfo=$this->getLogOrderInfo($id);
            return $this->render('update',[
                'id'=>$id,
                'model'=>$logorderinfo
            ]);
        }

    }
    //获取物流订单信息
    public function getLogOrderInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . "log-order-info?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    //详情
    public function actionView($id)
    {
        $logorderinfo=$this->getLogOrderInfo($id);
        $verify=$this->getVerify('1',61);//查看審核狀態
        return $this->render('view',[
            'id'=>$id,
            'verify'=>$verify,
            'model'=>$logorderinfo
        ]);
    }

    //查看审核状态
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }
    //获取详细地址
    public function actionAddress($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'address?id=' . $id;
        return $this->findCurl()->get($url);
    }
    public function actionLogInfo($lgno)
    {
        $url = $this->findApiUrl() . $this->_url . 'log-info?lgno=' . $lgno;
        return $this->findCurl()->get($url);
    }
}