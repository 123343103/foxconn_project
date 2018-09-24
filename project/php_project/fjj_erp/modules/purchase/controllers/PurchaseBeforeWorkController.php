<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 08:15
 */

namespace app\modules\purchase\controllers;


use app\classes\Menu;
use app\controllers\BaseController;
use app\modules\system\controllers\DataUpdate;
use app\modules\system\models\SystemLog;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;

class PurchaseBeforeWorkController extends BaseController
{
    private $_url = "purchase/purchase-before-work/";  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        $staff=Yii::$app->user->identity->staff_id;
        $buyaddr=$this->getBuyAddr($staff);//获取采购区域
        $receipt=$this->getReceipt();//获取收货中心
        $DownList = $this->getDownList();//获取请购形式、付款方式
        $ReqDcts=$this->getReqDcts();//获取单据类型
        $sppdpt = $this->getSppDpt();//获取请购部门
        $comman = $this->getComMan();//获取法人
        //分页与默认行数
        if (count($queryParam) <=2)//单据类型与法人、采购区域、收货中心默认值
        {
            $queryParam['req_dct'] = $ReqDcts[0]['bsp_id'];
            $queryParam['leg_id'] = $comman[0]['company_id'];
            $queryParam['area_id'] = $buyaddr[0]['factory_id'];
            $queryParam['addr']=$receipt[0]['rcp_no'];
       }
        $queryParam['staff'] = $staff;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {
            $dataProvider = Json::decode($this->findCurl()->get($url));
            foreach ($dataProvider['rows'] as $key => $val) {
                if (Menu::isAction('/purchase/purchase-apply/view')) {
                    $dataProvider['rows'][$key]['req_no'] = '<a href="' . Url::to(['/purchase/purchase-apply/view', 'id' => $val['req_id']]) . '">' . $dataProvider['rows'][$key]['req_no'] . '</a>';
                }
            }
            return Json::encode($dataProvider);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $fields = $this->getField("/purchase/purchase-before-work/index");

        return $this->render('index', [
            'param' => $queryParam,
            'fields' => $fields,
            'downList'=>$DownList,
            'ReqDcts' => $ReqDcts,
            'sppdpt' => $sppdpt,
            'comman' => $comman,
            'receipt'=>$receipt,
            'buyaddr'=>$buyaddr
        ]);
    }

//采购信息确认
    public function actionProcurement()
    {
        $url = $this->findApiUrl() . $this->_url . "procurement";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $newmodel = array();//合并重复料号后的新数据
        foreach ($dataProvider as $key => $v) {
            //将prt_pkid看成唯一,用prt_pkid作为key
            if (!isset($newmodel[$v['pkmt_id']])) {
                $newmodel[$v['pkmt_id']] = $v;
            }
            else {
                $newmodel[$v['pkmt_id']]['req_nums'] += $v['req_nums'];
                $newmodel[$v['pkmt_id']]['req_no'] = $newmodel[$v['pkmt_id']]['req_no'] . ",".$newmodel[$v['pkmt_id']]['req_id'].";" . $v['req_no'].",".$v['req_id'];//多个相同料号合并以后的请购单号关联问题，将请购单号与请购单id用逗号隔开，再将其用分号隔开(用于前台页面的关联请购单)
                $newmodel[$v['pkmt_id']]['req_dt_id']= $newmodel[$v['pkmt_id']]['req_dt_id'].','.$v['req_dt_id'];
            }
        }
        $newmodel = array_values($newmodel);//重新排序
        if (Yii::$app->request->isAjax) {
            return Json::encode($newmodel);
        }
        $buyerinfo = $this->getBuyerInfo($queryParam['buyer']);
        $reqdct = $this->getReqDct($queryParam['reqdct']);//单据类型
        $areaid = $this->getReqDct($queryParam['areaid']);//采购区域
        $legid = $this->getComname($queryParam['legid']);//法人信息
       // $DownList = $this->getDownList();//获取付款方式
       // $Currency = $this->getCurrency();//获取币别
        return $this->render('procurement', [
            'param' => $queryParam,
            'model' => $newmodel,
            'buyerinfo' => $buyerinfo,
            'reqdct' => $reqdct,
            'areaid' => $areaid,
            'legid' => $legid
        ]);
    }

    //请购转采购单
    public function actionCreate()
    {
        $postData = Yii::$app->request->post();
        if (Yii::$app->request->getIsPost()) {
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url),true);
            if ($data['status'] == 1) {
                SystemLog::addLog($data['data']['msg']);
                return json_encode([
                    'msg' => "保存成功",
                    'flag'=>1,
                    'billId'=>$data['data']['id'],//单据id
                    'billTypeId'=>55,//单据类型
                    "url" => Url::to(['/purchase/purchase-notify/index'])
                ]);
            } else {
                return Json::encode(['msg' => "发生未知错误，保存失败", "flag" => 0]);
            }
        }
    }

    //获取单据类型与请购形式、付款方式
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    public function getReqDcts()
    {
        $url = $this->findApiUrl() . $this->_url . "req-dcts";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
    //获取请购部门
    public function getSppDpt()
    {
        $url = $this->findApiUrl() . $this->_url . "spp-dpt";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    //获取法人信息
    public function getComMan()
    {
        $url = $this->findApiUrl() . $this->_url . 'com-man';
        return Json::decode($this->findCurl()->get($url));
    }

//获取采购人员信息
    public function getBuyerInfo($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'buyer-info?id=' . $id;
        return Json::decode($this->findCurl()->get($url));
    }

//获取料号对应的供应商
    public function actionSppPartno()
    {
        $url = $this->findApiUrl() . $this->_url . 'spp-partno';
        $url .= "?".http_build_query(Yii::$app->request->queryParams);
        $data = $this->findCurl()->get($url);
        return $data;
    }

    //获取具体单据类型与采购区域
    public function getReqDct($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'req-dct?id=' . $id;
        $data = $this->findCurl()->get($url);
        return Json::decode($data);
    }

    //获取法人的具体信息
    public function getComname($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-comname?id=' . $id;
        $data = $this->findCurl()->get($url);
        return Json::decode($data);
    }

//获取币别
    public function getCurrency()
    {
        $url = $this->findApiUrl() . $this->_url . 'currency';
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }
    //获取收货中心信息
    public function getReceipt()
    {
        $url = $this->findApiUrl() . $this->_url . 'receipt';
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }
    //获取采购区域(用户权限下的采购区域)
    public function getBuyAddr($staff)
    {
        $url = $this->findApiUrl() . $this->_url . 'buy-addr?staff='.$staff;
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }
    //获取交货条件
    public function actionDelivery()
    {
        $url = $this->findApiUrl() . $this->_url . 'delivery';
        $url .= "?".http_build_query(Yii::$app->request->queryParams);
        $data = $this->findCurl()->get($url);
        return $data;
    }
    //获取付款条件
//    public function actionPay($id,$deliv)
//    {
//        $url = $this->findApiUrl() . $this->_url . 'pay?id='.$id.'&deliv='.$deliv;
//        $data = $this->findCurl()->get($url);
//        return $data;
//    }
    //获取价格
    public function actionPrice()
    {
        $url = $this->findApiUrl() . $this->_url . 'price';
        $url .= "?".http_build_query(Yii::$app->request->queryParams);
        $data = $this->findCurl()->get($url);
        return $data;
    }
    //获取数量区间
    public function actionRatioInfo()
    {
        $url = $this->findApiUrl() . $this->_url . 'ratio-info';
        $url .= "?".http_build_query(Yii::$app->request->queryParams);
        $data = $this->findCurl()->get($url);
        return $data;
    }
}