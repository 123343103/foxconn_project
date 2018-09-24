<?php

namespace app\modules\sale\controllers;

use app\controllers\BaseController;
use app\models\UploadForm;
use app\modules\common\tools\ImportFile;
use yii\web\UploadedFile;
use yii\helpers\Json;
use app\modules\common\tools\ExcelToArr;
use yii;
use yii\helpers\Url;
use yii\helpers\BaseJson;

class SaleCommisionController extends BaseController
{
    private $_url = "sale/sale-commision/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = $this->findCurl()->get($url);

        if (Yii::$app->request->isAjax) {
            return $list;
        }
        $stores = $this->getStores();
//        $list = Json::decode($list);
//        dumpE($list);
        return $this->render('index', [
            'list' => $list,
            'stores' => $stores
        ]);
    }

    /**
     * 导入excel 订单明细
     *
     */
    public function actionImportSaleDetails()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $fileName = $model->file->baseName;
                $fileExt = $model->file->extension;
                $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);
            }
        }
        if (!empty($resultSave)) {
            $url = $this->findApiUrl() . $this->_url . "import-sale-details";
            $e2a = new ExcelToArr();
            $arr = $e2a->excel2arry($fileName, $fileExt);
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($arr));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(["msg" => "导入成功!", "flag" => 1, "url" => Url::to(['index?import=y'])]);
            } else {
                return Json::encode(["msg" => "导入失败", "flag" => 0]);
            }
        }
    }

    // 临时表数据存储到销单明细表
    public function actionTempToDetail()
    {
        $url = $this->findApiUrl() . $this->_url . 'temp-to-detail';
        $result = $this->findCurl()->get($url);
//        $result = BaseJson::decode($result,true);
//        dumpE($result);
        return $result;
    }

    // 导入人力薪资
    public function actionImportSellerCost()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $fileName = $model->file->baseName;
                $fileExt = $model->file->extension;
                $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);
            }
        }
        if (!empty($resultSave)) {
            $url = $this->findApiUrl() . $this->_url . "import-seller-cost";
            $e2a = new ExcelToArr();
            $arr = $e2a->excel2arry($fileName, $fileExt);
            $arr = array_slice($arr, 1);   // 处理第一行标题
            $arr = array_chunk($arr, 100); // 防止一次post传输的数据过大
            foreach ($arr as $v) {
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($v));
                $data = Json::decode($curl->post($url));
            }
//            dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(["msg" => "导入成功", "flag" => 1, "url" => Url::to(['detail-to-sum'])]);
            } else {
                return Json::encode(["msg" => "导入失败", "flag" => 0]);
            }
        }
    }


    /*从销单明细表查询计算每个销售员某月汇总数据 并存入销售汇总表*/
    public function actionDetailToSum()
    {
        $url = $this->findApiUrl() . $this->_url . 'detail-to-sum';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = $this->findCurl()->get($url); // 得到条件查询的汇总数据
        if (Yii::$app->request->isAjax) {
            return $list;
        }
        $stores = $this->getStores();
//        $list = BaseJson::decode($list,true);
//        dumpE($list);
        return $this->render('detail-to-sum', [
            'detailToSum' => $list,
            'stores' => $stores
        ]);
    }

    /*计算每个销售员当期人力成本*/
    public function actionSellerCost()
    {
        $url = $this->findApiUrl() . $this->_url . 'seller-cost';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = $this->findCurl()->get($url); // 得到条件查询的包含人力成本的汇总数据
        if (Yii::$app->request->isAjax) {
            return $list;
        }
        $stores = $this->getStores();
//        $list = BaseJson::decode($list,true);
//        dumpE($list);
        return $this->render('seller-cost', [
            'sellerCost' => $list,
            'stores' => $stores
        ]);
    }

    // 导入固定费用（门店费用）
    public function actionImportStoreCost()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $fileName = $model->file->baseName;
                $fileExt = $model->file->extension;
                $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);
            }
        }
        if (!empty($resultSave)) {
            $url = $this->findApiUrl() . $this->_url . "import-store-cost";
            $e2a = new ExcelToArr();
            $arr = $e2a->excel2arry($fileName, $fileExt);
            $arr = array_slice($arr, 1);   // 处理第一行标题
            $arr = array_chunk($arr, 100); // 防止一次post传输的数据过大
            foreach ($arr as $v) {
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($v));
                $data = Json::decode($curl->post($url));
            }
//            dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(["msg" => "导入成功", "flag" => 1, "url" => Url::to(['seller-cost'])]);
            } else {
                return Json::encode(["msg" => "导入失败,请检查导入的excel表格！", "flag" => 0]);
            }
        } else {
            return Json::encode(["msg" => "上传excel文件失败！", "flag" => 0]);
        }
    }

    // 计算销售点分摊固定费用 分摊固定費用 = 当期銷售點固定費用總和 / 当期銷售點直接人力數量
    public function actionStoreCost()
    {
        $url = $this->findApiUrl() . $this->_url . 'store-cost';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = $this->findCurl()->get($url); // 得到条件查询的包含人力成本的汇总数据
        if (Yii::$app->request->isAjax) {
            return $list;
        }
        $stores = $this->getStores();
//        $list = BaseJson::decode($list,true);
//        dumpE($list);
        return $this->render('store-cost', [
            'storeCost' => $list,
            'stores' => $stores,
        ]);
    }

    // 导入业务费用 （系统交际出差模块尚未完成，暂时先用Excel导入业务费用，以后可能直接统计系统的出差和交际等费用作为业务费用）
    public function actionImportOperateCost()
    {
        $model = new UploadForm();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate()) {
                $fileName = $model->file->baseName;
                $fileExt = $model->file->extension;
                $resultSave = $model->file->saveAs('uploads/' . $fileName . '.' . $fileExt);
            }
        }
        if (!empty($resultSave)) {
            $url = $this->findApiUrl() . $this->_url . "import-operate-cost";
            $e2a = new ExcelToArr();
            $arr = $e2a->excel2arry($fileName, $fileExt);
            $arr = array_slice($arr, 1);   // 处理第一行标题
            $arr = array_chunk($arr, 100); // 防止一次post传输的数据过大
            foreach ($arr as $v) {
                $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($v));
                $data = Json::decode($curl->post($url));
            }
//            dumpE($data);
            if ($data['status'] == 1) {
                return Json::encode(["msg" => "导入成功", "flag" => 1, "url" => Url::to(['store-cost'])]);
            } else {
                return Json::encode(["msg" => "导入失败,请检查导入的excel表格！", "flag" => 0]);
            }
        } else {
            return Json::encode(["msg" => "上传excel文件失败！", "flag" => 0]);
        }
    }

    // 计算显示业务费用
    public function actionOperateCost()
    {
        $url = $this->findApiUrl() . $this->_url . 'operate-cost';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = $this->findCurl()->get($url); // 得到条件查询的包含人力成本的汇总数据
        if (Yii::$app->request->isAjax) {
            return $list;
        }
//        $roles = Yii::$app->runAction( $this->_url.'get-roles');
        $roles = $this->getRoles();
        $stores = $this->getStores();
//        $list = BaseJson::decode($stores,true);
//        dumpE($stores);
        return $this->render('operate-cost', [
            'operateCost' => $list,
            'queryParam' => $queryParam,
            'roles' => $roles,
            'stores' => $stores
        ]);
    }

    // 计算提成
    public function actionCalculate()
    {
        $url = $this->findApiUrl() . $this->_url . 'calculate';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $list = $this->findCurl()->get($url); // 得到条件查询的包含人力成本的汇总数据
        if (Yii::$app->request->isAjax) {
            return $list;
        }
//        $list = BaseJson::decode($list,true);
//        dumpE($list);
        return $this->render('operate-cost', [
            'calculate' => $list,
        ]);
    }

    // 获取角色列表
    public function getRoles()
    {
        $url = $this->findApiUrl() . $this->_url . 'get-roles';
        $list = $this->findCurl()->get($url);
        $list = BaseJson::decode($list,true);
        return $list;
    }

    // 获取销售点列表
    public function getStores()
    {
        $url = $this->findApiUrl() . $this->_url . 'get-stores';
        $list = $this->findCurl()->get($url);
        $list = BaseJson::decode($list,true);
        return $list;
    }

    // 数据完整性检查
    public function actionCheckData()
    {
//        return json_encode('abc');
        // 初始化result
        $result['flag'] = 1;
        $result['msg'] = '导入数据符合要求。';

        $isSingleMonth = $this->isSingleMonth();
        if (!$isSingleMonth['flag']) {
            return $result = json_encode($isSingleMonth); // 有跨月数据不允许导入
        }
//        dumpE($isSingleMonth);

        $isNearestMonth = $this->isNearestMonth();
        if (!$isNearestMonth['flag']) {
            return $result = json_encode($isNearestMonth); // 导入被限制的月份
        }
//        dumpE($isNearestMonth);

        $isEmptyData = $this->isEmptyData();
        if (!$isEmptyData['flag']) {
            return $result = json_encode($isEmptyData);  // 有数据为空
        }
//        dumpE($isEmptyData);

        $isSellerExist = $this->isSellerExist();
        if (!$isSellerExist['flag']) {
            return $result = json_encode($isSellerExist); // 销售人员不存在！
        }
//        dumpE($isSellerExist);

        $isStoreExist = $this->isStoreExist();
        if (!$isStoreExist['flag']) {
            return $result = json_encode($isStoreExist);  // 销售人员所对应的销售点不存在
        }
//        dumpE($isStoreExist);

        $result = json_encode($result);
        if (Yii::$app->request->isAjax) {
            return $result;
        }

        return $this->render('index', [
            'checkData' => $result,
        ]);

    }

    // 检查是否有跨月销单
    public function isSingleMonth()
    {
        $url = $this->findApiUrl() . $this->_url . 'is-single-month';
        $result = $this->findCurl()->get($url);
        $result = BaseJson::decode($result,true);
        if ($result>1) {
            $res['flag'] = 0;
            $res['msg'] = '有跨月的销单！';
            return $res;
        } else {
            $res['flag'] = 1;
            $res['msg'] = '没有跨月的销单！';
            return $res;
        }
    }

    // 检查导入的月份是否数据表中最大/最近月份
    public function isNearestMonth()
    {
        $url = $this->findApiUrl() . $this->_url . 'get-month';
        $importMonth = $this->findCurl()->get($url);
        $importMonth = BaseJson::decode($importMonth,true);

        $url = $this->findApiUrl() . $this->_url . 'get-nearest-month';
        $nearestMonth = $this->findCurl()->get($url);
        $nearestMonth = BaseJson::decode($nearestMonth,true);

        $iYear = substr($importMonth,0,4);
        $iMonth = substr($importMonth,5,6);
        if (!empty($nearestMonth)) {
            $nYear = substr($nearestMonth,0,4);
            $nMonth = substr($nearestMonth,5,6);
        }

        $iMonth = ($iYear-$nYear==1) ? ($iMonth+12) : $iMonth; // 处理跨年
        $result = $iMonth-$nMonth;
        if ($result==1 || empty($nearestMonth)) {
            $res['flag'] = 1;
            $res['msg'] = '导入新的数据！';
            return $res;
        } elseif ($result==0) {
            $res['flag'] = 1;
            $res['msg'] = '修改数据！';
            return $res;
        } else {
            $res['flag'] = 0;
            $res['msg'] = '只允许导入最近导入月份和最近导入月份的下一个月份！';
            return $res;
        }
    }

    // 检查销售点是否都存在（通过员工工号查找员工对应的销售点是否存在）
    public function isStoreExist()
    {
        $url = $this->findApiUrl() . $this->_url . 'is-store-exist';
        $result = $this->findCurl()->get($url);
        $result = BaseJson::decode($result,true);
        return $result;
    }

    // 检查销售员是否都存在
    public function isSellerExist()
    {
        $url = $this->findApiUrl() . $this->_url . 'is-seller-exist';
        $result = $this->findCurl()->get($url);
        $result = BaseJson::decode($result,true);
        return $result;
    }

    // 检查导入的关键数据是否为空
    public function isEmptyData()
    {
        $url = $this->findApiUrl() . $this->_url . 'is-empty-data';
        $result = $this->findCurl()->get($url);
        $result = BaseJson::decode($result,true);
        return $result;
    }

    public function actionTest()
    {
        return $this->render('test');
    }
}

