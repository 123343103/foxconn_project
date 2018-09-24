<?php

namespace app\modules\sale\models\search;
use app\modules\sale\models\SaleCostType;
use app\modules\sale\models\show\SaleCostTypeShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostType]].
 *
 * @see \app\modules\sale\models\SaleCostType
 */
class SaleCostTypeSearch extends SaleCostType
{
    public function rules()
    {
        return [
            [['scost_id','create_by','update_by'], 'integer'],
            [['scost_code', 'scost_sname'], 'string', 'max' => 20],
            [['scost_status'], 'string', 'max' => 2],
            [['scost_remark','scost_description', 'scost_vdef1', 'scost_vdef2'], 'string', 'max' => 120],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }



    public function search($params)   //搜索方法
    {
        $query = SaleCostTypeShow::find();
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        //默認排序
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("scost_id desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }
        $query->andFilterWhere([        //根据字段搜索
            'scost_status' => $this->scost_status,
        ]);
        //根据字段模糊查询
        $query->andFilterWhere(['like', 'scost_code', $this->scost_code])
            ->andFilterWhere(['like','scost_description',$this->scost_description]);
        return $dataProvider;
    }
}
