<?php
/**
 * User: F3859386
 * Date: 2017/3/31
 * Time: 15:51
 */

namespace app\modules\ptdt\models\search;

use app\modules\ptdt\models\PdMaterialCode;
use yii\data\ActiveDataProvider;

class PdMaterialCodeSearch extends PdMaterialCode
{
//    public function scenarios()
//    {
//        // bypass scenarios() implementation in the parent class
//        return Model::scenarios();
//    }

    public function search($params){
        $query=PdMaterialCode::find();
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);
        //默認排序
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("report_date desc");
        }

        $query->andFilterWhere([
            'firm_issupplier'=>$this->firm_issupplier,
            'report_status'=>$this->report_status,
        ]);
        $query->andFilterWhere(["like",self::tableName().".part_no",isset($params['pdt_no'])?$params['pdt_no']:""]);
        return $dataProvider;
    }

}