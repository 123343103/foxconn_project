<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\TradConditionShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 交易条件
 */
class TradConditionSearch extends TradConditionShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['tcc_code','tcc_sname'], 'safe'],
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
        $query = TradConditionShow::find();

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
        $query->andFilterWhere(['like', "tcc_code", $this->tcc_code])
            ->andFilterWhere([
                'or',
                ['tcc_sname' => $this->tcc_sname],
                ['tcc_sname' => $trans->c2t($this->tcc_sname)],
                ['tcc_sname' => $trans->t2c($this->tcc_sname)]
            ]);
        return $dataProvider;
    }
}
