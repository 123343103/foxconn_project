<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\PayConditionShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 付款条件
 */
class PayConditionSearch extends PayConditionShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['pat_code','pat_sname'], 'safe'],
        ];
    }

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 搜索
     */
    public function search($param)
    {
        $query = PayConditionShow::find();

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        $this->load($param);
        $trans = new Trans();
        $query->andFilterWhere(['like', "pat_code", $this->pat_code])
            ->andFilterWhere([
                'or',
                ['pat_sname' => $this->pat_sname],
                ['pat_sname' => $trans->c2t($this->pat_sname)],
                ['pat_sname' => $trans->t2c($this->pat_sname)]
            ]);
        return $dataProvider;
    }
}
