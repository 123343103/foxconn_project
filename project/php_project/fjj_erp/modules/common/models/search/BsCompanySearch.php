<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/29
 * Time: 下午 04:52
 */
namespace app\modules\common\models\search;
use app\modules\common\models\BsCompany;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class BsCompanySearch extends BsCompany{
    public function rules(){
        return [
            [["company_id","company_name"],"safe"]
        ];
    }
    public function search($params){
        $model=BsCompanySearch::find();
        $dataProvider=new ActiveDataProvider([
            "query"=>$model,
            "pagination"=>[
                "pageSize"=>8,
            ]
        ]);
        $this->load(["BsCompanySearch"=>$params]);
        if(!$this->validate()){
            return $dataProvider;
        }
        $model->filterWhere(["like","company_name",$this->company_name]);
        $res["rows"]=$dataProvider->getModels();
        $res["total"]=$dataProvider->totalCount;
         return $res;
    }

}