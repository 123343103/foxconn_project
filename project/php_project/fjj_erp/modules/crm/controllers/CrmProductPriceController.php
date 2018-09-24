<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/1/11
 * Time: 下午 04:05
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;

class CrmProductPriceController extends BaseController
{
    public function actionIndex()
    {
        $url = $this->findApiUrl() . "ptdt/product-library/index";
        $params = \Yii::$app->request->queryParams;
        $url .= "?" . http_build_query($params);
        $res = $this->findCurl()->get($url);
//        var_dump($res);
        if (\Yii::$app->request->isAjax) {


            return $res;
        } else {
            //一阶分类
            $productType = $this->getProductTypeList();
            $productTypeIdToValue = [];
            foreach ($productType as $key => $val) {
                $productTypeIdToValue[$val['category_id']] = $val['category_sname'];
            }
            //页面已经有级联函数  此数据为了查询后能显示查询数据
            $type2 = [];
            $type3 = [];
            $type4 = [];
            $type5 = [];
            $type6 = [];
            if ($type_1 = Yii::$app->request->get('type_1')) {
                $type2 = $this->getTypeOption($type_1);
            };
            if ($type_2 = Yii::$app->request->get('type_2')) {
                $type3 = $this->getTypeOption($type_2);
            };
            if ($type_3 = Yii::$app->request->get('type_3')) {
                $type4 = $this->getTypeOption($type_3);
            };
            if ($type_4 = Yii::$app->request->get('type_4')) {
                $type5 = $this->getTypeOption($type_4);
            };
            if ($type_5 = Yii::$app->request->get('type_5')) {
                $type6 = $this->getTypeOption($type_5);
            };

            $statusType = array("未定价", "发起定价", "商品开发维护", "审核中", "已定价", "被驳回", "已逾期", "重新定价");
            return $this->render('index', [
                'productTypeIdToValue' => $productTypeIdToValue
                , 'type2' => $type2
                , 'type3' => $type3
                , 'type4' => $type4
                , 'type5' => $type5
                , 'type6' => $type6
                , 'statusType' => $statusType
            ]);
        }
    }


    public function actionPriceInfo()
    {
        $url = $this->findApiUrl() . "ptdt/partno-price-confirm/index";
        $params = \Yii::$app->request->bodyParams;
        $url .= "?" . http_build_query($params);
        $res = $this->findCurl()->get($url);
        return $res;
    }


    public function actionGetProductType($id)
    {

        $url = $this->findApiUrl() . "ptdt/product-library/product-types-children?id=" . $id;
        return $this->findCurl()->get($url);
    }

    /**
     * 一階分類選擇列表
     */
    protected function getProductTypeList()
    {
        $url = $this->findApiUrl() . "ptdt/product-library/" . "product-types";
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 获取子类
     */
    protected function getTypeOption($id)
    {
        $url = $this->findApiUrl() . "ptdt/product-library/" . "types-option?id=" . $id;
        return json_decode($this->findCurl()->get($url));
    }

    /**
     * @param $id
     * 获取模型
     */
    protected function getModel($id)
    {
        $url = $this->findApiUrl() . "ptdt/product-library/get-model?id=" . $id;
        return Json::decode($this->findCurl()->get($url), false);
    }
}