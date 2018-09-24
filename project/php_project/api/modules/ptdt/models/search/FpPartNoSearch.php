<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/28
 * Time: 上午 10:24
 */
namespace app\modules\ptdt\models\search;
use app\modules\ptdt\models\FpPartNo;
use yii\data\ActiveDataProvider;
class FpPartNoSearch extends FpPartNo{
    public function rules(){
        return [
            [["pdt_name"],"safe"]
        ];
    }
    public function search($params){
        $model=FpPartNoSearch::find();
        $provider=new ActiveDataProvider([
            "query"=>$model,
            "pagination"=>[
                "pageSize"=>$params["rows"]
            ]
        ]);
        $this->load(["FpPartNoSearch"=>$params]);
        if(!$this->validate()){
            return $provider;
        }
        $model->filterWhere(["like","pdt_name",$this->pdt_name]);
        return $provider;

    }
}