<?php

namespace app\modules\sale\models\search;
use app\modules\sale\models\SaleCostList;
use app\modules\sale\models\show\SaleCostListShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * This is the ActiveQuery class for [[\app\modules\sale\models\SaleCostList]].
 *
 * @see \app\modules\sale\models\SaleCostList
 */
class SaleCostListSearch extends SaleCostList
{
//    public $stcl_code;
//    public $stcl_sname;
//    public $stcl_status;

    public function rules()
    {
        return [
            [['stcl_id', 'scost_id'], 'integer'],
            [['stcl_code'], 'string', 'max' => 20],
            [['stcl_sname'], 'string', 'max' => 60],
            [['stcl_description', 'stcl_remark', 'vdef1', 'vder2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],
            [['stcl_status'], 'string', 'max' => 2],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    /**
     * Creates data provider instance with search query applied
     *
     *
     * @return ActiveDataProvider
     */
    public function search($params)   //搜索方法
    {
        $query = SaleCostListShow::find();
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
            $query->orderBy("stcl_id desc");
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
            'stcl_status' => $this->stcl_status,
        ]);
        //根据字段模糊查询
        $query->andFilterWhere(['like', 'stcl_code', $this->stcl_code])
            ->andFilterWhere(['like','stcl_sname',$this->stcl_sname]);
        return $dataProvider;
    }

}
