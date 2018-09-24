<?php
/**
 * User: F1677929
 * Date: 2017/8/2
 */
namespace app\modules\warehouse\controllers;
use app\classes\Menu;
use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * 出入库查询控制器
 */
class InoutStockQueryController extends BaseController
{
    //出入库查询列表
    public function actionIndex()
    {
        $url=$this->findApiUrl().'warehouse/inout-stock-query/index';
        //导出
        if(Yii::$app->request->get('export')==1){
            $dataProvider=Json::decode($this->findCurl()->get($url));
            $this->exportFiled($dataProvider['rows']);
        }
        if(Yii::$app->request->isAjax){
            $url.='?'.http_build_query(Yii::$app->request->queryParams);
            $url.='&'.http_build_query(Yii::$app->request->queryParams);
            $dataProvider=Json::decode($this->findCurl()->get($url));
//            $dataProvider=$this->findCurl()->get($url);
            foreach ($dataProvider['rows'] as &$val){
                if ($val['inoutFlag']=='入库'){
                    switch ($val['orderStatus']){
                        case "1":
                            $val['orderStatus']='待提交';
                            break;
                        case "2":
                            $val['orderStatus']='审核中';
                            break;
                        case "3":
                            $val['orderStatus']='驳回';
                            break;
                        case "4":
                            $val['orderStatus']='已取消';
                            break;
                        case "5":
                            $val['orderStatus']='待上架';
                            break;
                        case "6":
                            $val['orderStatus']='已上架';
                            break;
                        default :
                            $val['orderStatus']='未知';
                            break;
                    }

                }else{
                    switch ($val['orderStatus']){
                        case "0":
                            $val['orderStatus']='待出库';
                            break;
                        case "1":
                            $val['orderStatus']='代收货';
                            break;
                        case "2":
                            $val['orderStatus']='已收货';
                            break;
                        case "3":
                            $val['orderStatus']='已出库';
                            break;
                        case "4":
                            $val['orderStatus']='已取消';
                            break;
                        case "5":
                            $val['orderStatus']='待提交';
                            break;
                        case "6":
                            $val['orderStatus']='审核中';
                            break;
                        case "7":
                            $val['orderStatus']='审核完成';
                            break;
                        case "8":
                            $val['orderStatus']='驳回';
                            break;
                        case "9":
                            $val['orderStatus']='已作废';
                            break;
                        default :
                            $val['orderStatus']='未知';
                            break;
                    }
                }
            }
            $dataProvider=Json::encode($dataProvider);
            return $dataProvider;
        }

        $downlist=$this->actionDownList();
        $data=$this->getField('/warehouse/inout-stock-query/index');
        return $this->render('index',['data'=>$data,'downlist'=>$downlist]);
    }

    //获取下拉框
    public function actionDownList()
    {
        $url=$this->findApiUrl().'warehouse/inout-stock-query/down-list';
        return Json::decode($this->findCurl()->get($url));
    }
    //获取单据类型
    public function actionGetOrderType($code)
    {
        $url=$this->findApiUrl().'warehouse/inout-stock-query/get-order-type';
        $url.='?code='.$code;
        return $this->findCurl()->get($url);
    }

    //获取仓库名称
    public function actionGetWarehouse($id)
    {
        $url=$this->findApiUrl().'warehouse/inout-stock-query/get-warehouse';
        $url.='?id='.$id;
        return $this->findCurl()->get($url);
    }

    //获取储位信息
    public function actionGetStorageLocation($id)
    {
        $url=$this->findApiUrl().'warehouse/inout-stock-query/get-storage-location';
        $url.='?id='.$id;
        return $this->findCurl()->get($url);
    }
}