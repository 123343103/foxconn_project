<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\TransactionShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 交易方式
 */
class TransactionSearch extends TransactionShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['tac_code','tac_sname'], 'safe'],
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
        $query = TransactionShow::find();

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
        $query->andFilterWhere(['like', "tac_code", $this->tac_code])
            ->andFilterWhere([
                'or',
                ['tac_sname' => $this->tac_sname],
                ['tac_sname' => $trans->c2t($this->tac_sname)],
                ['tac_sname' => $trans->t2c($this->tac_sname)]
            ]);
        return $dataProvider;
    }
}
