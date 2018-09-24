<?php
/**
 * User: F1678688
 * Date: 2017/3/01
 */
namespace app\modules\app\models\search;

use app\modules\ptdt\models\BsProduct;
use yii\data\ActiveDataProvider;
use app\classes\Trans;

class ProductSearch extends BsProduct
{
//
//    public $type_1;
//    public $type_2;
//    public $type_3;
//    public $type_4;
//    public $type_5;
//    public $type_6;

    public function fields()
    {
        $fields = parent::fields();
        $fields['type_1'] = function () {
            return isset($this->productType->parent->parent->parent->parent->parent->catg_name) ? $this->productType->parent->parent->parent->parent->parent->catg_name : "";
        };
        $fields['type_2'] = function () {
            return isset($this->productType->parent->parent->parent->parent->catg_name) ? $this->productType->parent->parent->parent->parent->catg_name : "";
        };
        $fields['type_3'] = function () {
            return isset($this->productType->parent->parent->parent->catg_name) ? $this->productType->parent->parent->parent->catg_name : "";
        };
        $fields['type_4'] = function () {
            return isset($this->productType->parent->parent->catg_name) ? $this->productType->parent->parent->catg_name : "";
        };
        $fields['type_5'] = function () {
            return isset($this->productType->parent->catg_name) ? $this->productType->parent->catg_name : "";
        };
        $fields['type_6'] = function () {
            return isset($this->productType->catg_name) ? $this->productType->catg_name : "";
        };
        $fields['company_name'] = function () {
//            return isset($this->company->company_name) ? $this->company->company_name : "";
            return "";
        };
        $fields['brand_name'] = function () {
            return isset($this->brand->BRAND_NAME_CN) ? $this->brand->BRAND_NAME_CN : "";
        };
        $fields['unit'] = function () {
            return $this->unite->bsp_sname;
        };
        return $fields;
    }


    public function AppSearch($params)
    {
        $query = ProductSearch::find();

        $content = $params['content'];
        //UTF8内简繁转换
        $go = new Trans;
        $content = $go->t2c($content);
        $ftcontent = $go->c2t($content);


        $query->andFilterWhere(['or',
            ['like', 'pdt_name', $content],
            ['like', 'pdt_name', $ftcontent],
            ['like', 'pdt_model', $content],
            ['like', 'pdt_model', $ftcontent],
            ['like', 'pdt_title', $content],
            ['like', 'pdt_title', $ftcontent],
            ['like', 'pdt_keyword', $content],
            ['like', 'pdt_keyword', $ftcontent],
            ['like', 'pdt_attribute', $content],
            ['like', 'pdt_attribute', $ftcontent],
            ['like', 'pdt_form', $content],
            ['like', 'pdt_no', $content],
        ]);

        $provider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => $params["rows"]
            ]
        ]);

        return $provider;

    }

    public function TypeSearch($params)
    {
        $query = ProductSearch::find();

        $type = $params['type'];
        $brand = $params['brand'];
        //UTF8内简繁转换
        $go = new Trans;
        $brand = $go->t2c($brand);
        $ftbrand = $go->c2t($brand);

        $query->andFilterWhere(['or', ["like", "pdt_title", $brand],
            ["like", "pdt_title", "$ftbrand"]]);
        $query->andFilterWhere(["like", "bs_category_id", $type]);

        $dataProvider = new ActiveDataProvider([
            "query" => $query,
            "pagination" => [
                "pageSize" => isset($params["rows"]) ? $params["rows"] : 10
            ]
        ]);

        return $dataProvider;

    }
}