<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/28
 * Time: 上午 10:24
 */
namespace app\modules\ptdt\models\search;
use app\classes\Trans;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsProduct;
use app\modules\common\models\BsBrand;
use app\modules\ptdt\models\CategoryAttr;
use app\modules\ptdt\models\PartnoPrice;
use yii\data\ActiveDataProvider;
class BsProductSearch extends BsProduct {

    public $type_1;
    public $type_2;
    public $type_3;
    public $type_4;
    public $type_5;
    public $type_6;

    public function fields()
    {
        $fields = parent::fields();
//        $fields['type_1'] = function () {
//            return isset($this->productType->parent->parent->parent->parent->parent->category_sname)?$this->productType->parent->parent->parent->parent->parent->category_sname:"";
//        };
//        $fields['type_2'] = function () {
//            return isset($this->productType->parent->parent->parent->parent->category_sname)?$this->productType->parent->parent->parent->parent->category_sname:"";
//        };
//        $fields['type_3'] = function () {
//            return isset($this->productType->parent->parent->parent->category_sname)?$this->productType->parent->parent->parent->category_sname:"";
//        };
//        $fields['type_4'] = function () {
//            return isset($this->productType->parent->parent->category_sname)?$this->productType->parent->parent->category_sname:"";
//        };
//        $fields['type_5'] = function () {
//            return isset($this->productType->parent->category_sname)?$this->productType->parent->category_sname:"";
//        };
//        $fields['type_6'] = function () {
//            return isset($this->productType->category_sname)?$this->productType->category_sname:"";
//        };
//        $fields['company_name']=function(){
//            return isset($this->company->company_name)?$this->company->company_name:"";
//        };
        $fields["category"]=function(){
            $result=[];
            $cat=BsCategory::findOne($this->bs_category_id);
            $result[]=$cat->category_sname;
            while($cat->p_category_id!='0'){
                $cat=BsCategory::findOne($cat->p_category_id);
                $result[]=$cat->category_sname;
            }
            krsort($result);
            return implode(">",array_slice($result,0,3));
        };
        $fields['brand_name']=function(){
            return isset($this->brand->BRAND_NAME_CN)?$this->brand->BRAND_NAME_CN:"";
        };
        $fields['unit']=function(){
            return $this->unite->unit_name;
        };
//        $fields['iskz']=function(){
//            return $this->iskz==1?"Y":"N";
//        };
//        $fields['isproxy']=function(){
//            return $this->isproxy==1?"Y":"N";
//        };
//        $fields['isonlinesell']=function(){
//            return $this->isonlinesell==1?"Y":"N";
//        };
//        $fields['risk_level']=function(){
//            if(!$this->risk_level){
//                return "高";
//            }else if($this->risk_level==1){
//                return "中";
//            }else if($this->risk_level==2){
//                return "低";
//            }
//        };
//        $fields['istitle']=function(){
//            return $this->istitle?"Y":"N";
//        };
        $fields["tp_spec"]=function(){
            return $this->attr->ATTR_NAME;
        };
        $fields["attr_name"]=function(){
            return $this->attr->ATTR_NAME;
        };
        $fields["price_info"]=function(){
            return PartnoPrice::findOne(["part_no"=>$this->pdt_no,"status"=>4]);
        };
        $fields['status']=function(){
            switch($this->status){
                case '0':
                    return "封存";break;
                case 1:
                    return "正常";break;
                default:
                    return "/";
                    break;
            }
        };
        $fields["supplier_name"]=function(){
            return isset($this->supplier->supplier_name)?$this->supplier->supplier_name:"";
        };
        return $fields;
    }


    public function search($params){
        $trans=new Trans();
        $model=self::find()->joinWith("brand");
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $provider=new ActiveDataProvider([
            "query"=>$model,
            "pagination"=>[
                "pageSize"=>$pageSize
            ]
        ]);
        $model->andFilterWhere(["like","pdt_no",isset($params['pdt_no'])?$params['pdt_no']:""]);
        if(isset($params['pdt_name'])){
            $model->andFilterWhere([
                "or",
                ["like","pdt_name",$params['pdt_name']],
                ["like","pdt_name",$trans->c2t($params['pdt_name'])],
                ["like","pdt_name",$trans->t2c($params['pdt_name'])]
            ]);
        }
        $model->andFilterWhere(["status"=>isset($params['status'])?$params['status']:""]);
        if(isset($params["brand_name"])){
            $model->andFilterWhere([
                "or",
                ["BRAND_NAME_CN"=>$params["brand_name"]],
                ["BRAND_NAME_CN"=>$trans->c2t($params["brand_name"])],
                ["BRAND_NAME_CN"=>$trans->t2c($params["brand_name"])]
            ]);
        }
        if(isset($params["pdt_manager"])){
            $model->andFilterWhere([
                "or",
                ["pdt_manager"=>$params["pdt_manager"]],
                ["pdt_manager"=>$trans->c2t($params["pdt_manager"])],
                ["pdt_manager"=>$trans->t2c($params["pdt_manager"])]
            ]);
        }
        $types=[];
        $types[]=isset($params['type_1'])?$params['type_1']:"";
        $types[]=isset($params['type_2'])?$params['type_2']:"";
        $types[]=isset($params['type_3'])?$params['type_3']:"";
        $types[]=isset($params['type_4'])?$params['type_4']:"";
        $types[]=isset($params['type_5'])?$params['type_5']:"";
        $types[]=isset($params['type_6'])?$params['type_6']:"";
        $types=array_filter($types);
        $type=count($types)>0?$types[count($types)-1]:"";
        $model->andFilterWhere(["like","bs_category_id",$type]);

        if (isset($params['startDate']) && !isset($params['endDate'])) {
            $model->andFilterWhere([">=", "createdate", $params['startDate']]);
        } else if (isset($params['endDate']) && !isset($params['startDate'])) {
            $model->andFilterWhere(["<=", "createdate", date("Y-m-d H:i:s", strtotime($params['endDate'] . '+1 day'))]);
        } else if (isset($params['endDate']) && isset($params['startDate'])) {
            $model->andFilterWhere(["between", "createdate", $params['startDate'], date("Y-m-d", strtotime($params['endDate'] . '+1 day'))]);
        }
        return $provider;

    }
}