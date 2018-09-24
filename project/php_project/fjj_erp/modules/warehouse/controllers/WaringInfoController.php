<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/9
 * Time: 上午 10:50
 */

namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;
use app\modules\common\models\BsBusinessType;


class WaringInfoController extends BaseController
{
    private $_url = "warehouse/waring-info/";  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'wm07'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
        if (Yii::$app->request->isAjax) { //如果是分页获取数据则直接返回数据
            $data = Json::decode($dataProvider);
            foreach ($data['rows'] as &$val) {
                $val['OPP_DATE'] = date('Y/m/d', strtotime($val['OPP_DATE']));
                $val['inv_id']="<a onclick='window.location.href=\"".Url::to(['view','biw_h_pkid'=>$val['biw_h_pkid']])."\";event.stopPropagation();'>".$val['inv_id']."</a>";
            }
            return Json::encode($data);
        }
        $downList = $this->downList();
        $colnums = $this->getField("/warehouse/waring-info/index");
        $colnumsChilde = $this->getField("/warehouse/waring-info/product-list");
        return $this->render('index', [
            'model' => Json::decode($dataProvider),
            'downList' => $downList,
            'colnums' => $colnums,
            'colnumsChilde'=>$colnumsChilde,
            'typeId'=>$typeId
        ]);

    }

    //详情
    public function actionView($biw_h_pkid)
    {
        $url = $this->findApiUrl() . $this->_url . "view?biw_h_pkid=" . $biw_h_pkid;
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'wm07'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
        $dataProvider = $this->findCurl()->get($url);
        $data = Json::decode($dataProvider);
        $id = $data["rows"][0]['biw_h_pkid'];
        $verify = $this->getVerify($id, 20);//審核信息
        if (Yii::$app->request->isAjax) { //如果是分页获取数据则直接返回数据
            $data = Json::decode($dataProvider);
            return Json::encode($data);
        }
        return $this->render('view', [
            'model' => $data,
            'typeId'=>$typeId,
            'verify' => $verify,
            'biw_h_pkid'=>$biw_h_pkid
        ]);
    }

    public function getVerify($id, $type)
    {
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id . "&type=" . $type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }


    //修改
    public function actionEdit($biw_h_pkid)
    {
        if (Yii::$app->request->isPost) {
            $url = $this->findApiUrl() . $this->_url . "edit?biw_h_pkid=" . $biw_h_pkid;
            $postData = Yii::$app->request->post();
            $postData['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
            $postData['OPP_DATE'] = date('Y-m-d H:i:s', time());//操作时间
            $postData['OPP_IP'] = Yii::$app->request->getUserIP();//'//获取ip地址
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            var_dump($postData);
            $data = Json::decode($curl->post($url));
//            var_dump($data);
//            var_dump(Url::to(['index']));
            if ($data['status']) {
                return Json::encode(['msg' => "修改仓库存储信息成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . "waring?biw_h_pkid=" . $biw_h_pkid;
            $priceList = json_decode($this->findCurl()->get($url));
            $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'wm07'])->asArray()->one();
            $typeId = $typeId['business_type_id'];
            return $this->render("edit", [
                'priceList' => $priceList,
                'typeId'=>$typeId
            ]);
        }
    }

    public function actionSave()
    {

        $url = $this->findApiUrl() . $this->_url . "save";
        $postData['warmInfo'] = json_decode($_POST['warmInfo'],true);
        $postData['action'] = $_POST['action'];//操作
        $postData['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
        $postData['OPP_DATE'] = date('Y-m-d H:i:s', time());//操作时间
        $postData['OPP_IP'] = Yii::$app->request->getUserIP();//'//获取ip地址
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = $curl->post($url);
        return $data;
    }

    public function actionCreatesave()
    {
        $wh_id = $_POST ['wh_id'];
        $part_no = $_POST ["part_no1"];
        $down_nums = $_POST ["down_nums"];
        $save_num = $_POST ["save_num"];
        $up_nums = $_POST ["up_nums"];
        $remarks = $_POST ["remarks"];
        $action = $_POST["action"];
        $url = $this->findApiUrl() . $this->_url . "createsave";
        $postData['wh_id'] = $wh_id;//仓库ID
        $postData['part_no'] = $part_no;//料号
        $postData['down_nums'] = $down_nums;//库存下限
        $postData['save_nums'] = $save_num;//安全库存
        $postData['up_nums'] = $up_nums;//库存上限
        $postData['remarks'] = $remarks;//备注
        $postData['action'] = $action;
        $postData['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
        $postData['OPP_DATE'] = date('Y-m-d H:i:s', time());//操作时间
        $postData['OPP_IP'] = Yii::$app->request->getUserIP();//'//获取ip地址
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = $curl->post($url);
        return $data;
    }


    public function actionCreate()
    {
        $typeId = BsBusinessType::find()->select('business_type_id')->where(['business_code' => 'wm07'])->asArray()->one();
        $typeId = $typeId['business_type_id'];
        return $this->render('create',["typeId"=>$typeId]);
    }

    //查询该仓库有没有在审核中的预警
    public function actionGettypebywhid()
    {
        $wh_id = $_POST["wh_id"];
        $url = $this->findApiUrl() . $this->_url . "gettypebywhid?wh_id=" . $wh_id;
        $postData["wh_id"] = $wh_id;
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = json_decode($curl->post($url));
        return $data;

    }

    //添加
    public function actionAdd()
    {
        $downList = $this->downList();
        $id = $downList['whname'][0]['wh_id'];
        if (Yii::$app->request->getIsGet()) {
            $this->layout = '@app/views/layouts/ajax';
            $url = $this->findApiUrl() . $this->_url . 'add';
            $queryParam = Yii::$app->request->queryParams;
            $queryParam["id"] = $id;
            if (!empty($queryParam)) {
                $url .= "?" . http_build_query($queryParam);
            }
            $dataProvider = $this->findCurl()->get($url);
            $get = Yii::$app->request->get();
            if (!isset($get['PartSearch'])) {
                $get['PartSearch'] = null;
            }
            if (Yii::$app->request->isAjax) {
                $data = Json::decode($dataProvider);
                return Json::encode($data);
            }
            $productType = $downList['productTypes'];
            $productTypeIdToValue = [];
            foreach ($productType as $key => $val) {
                $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
            }
            $downList['productTypes'] = $productTypeIdToValue;

            return $this->render('add', [
                'model' => Json::decode($dataProvider),
                'downList' => $downList,
                'id' => $id,
                'get' => $get
            ]);
        }
    }


    private function downList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $a = $this->findCurl()->get($url);
        return Json::decode($this->findCurl()->get($url), true);

    }

    //获取预警信息详情
    public function actionLoadWaring($inv_id, $wh_id)
    {
        $url = $this->findApiUrl() . $this->_url . "waring?inv_id=" . $inv_id . "&wh_id=" . $wh_id;
        $priceList = json_decode($this->findCurl()->get($url));
        return $this->renderPartial('load-waring', ['priceList' => $priceList]);
    }

    //获取预警信息详情
    public function actionNewLoadWaring($biw_h_pkid)
    {
        $url = $this->findApiUrl() . $this->_url . "waring?biw_h_pkid=" . $biw_h_pkid;
        $priceList = json::decode($this->findCurl()->get($url));
        $dataProvider = Json::encode($priceList);         //如果是分页获取数据则直接返回数据
        return $dataProvider;
    }

    //获取仓库id上一次有效的预警的料号信息
    public function actionWarinfo()
    {
        $wh_id = $_POST['wh_id'];
        $url = $this->findApiUrl() . $this->_url . "warinfo";
        $postData['wh_id'] = $wh_id;//庫存ID
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
        $data = Json::decode($curl->post($url));
        $tr = json_encode($data);
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return urldecode($json);

    }

    //删除
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $res = Json::decode($this->findCurl()->delete($url), false);
        if ($res->status == 1) {
            return Json::encode(['msg' => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "删除失败", "flag" => 0]);
        }
    }
    //送审(单个/批量)
    public function actionReviewer($type, $id, $url = null)
    {
        if ($post = Yii::$app->request->post()) {
            $id = explode('-', $id);
            unset($id[count($id) - 1]);
            foreach ($id as $item) {
                $post['id'] = $item;    //单据ID
                $post['type'] = $type;  //审核流类型
                $post['staff'] = Yii::$app->user->identity->staff_id;//送审人ID
                $verifyUrl = $this->findApiUrl() . "/system/verify-record/verify-record";
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
                $data = Json::decode($curl->post($verifyUrl));
                if (!$data['status']) {
                    throw  new \Exception('单据ID:' . $item . '送审失败!');
                }
            }
            if (!empty($url)) {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1, "url" => $url]);
            } else {
                return Json::encode(['msg' => "送审完成,等待审核", "flag" => 1]);
            }
        }
        $urls = $this->findApiUrl() . "/system/verify-record/reviewer?type=" . $type . '&staff_id=' . Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }
}