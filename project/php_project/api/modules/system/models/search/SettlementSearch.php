<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\SettlementShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 结算方式
 */
class SettlementSearch extends SettlementShow
{
    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['bnt_code','bnt_sname'], 'safe'],
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
        $query = SettlementShow::find();

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
        $query->andFilterWhere(['like', "bnt_code", $this->bnt_code])
            ->andFilterWhere([
                'or',
                ['bnt_sname' => $this->bnt_sname],
                ['bnt_sname' => $trans->c2t($this->bnt_sname)],
                ['bnt_sname' => $trans->t2c($this->bnt_sname)]
            ]);
        return $dataProvider;
    }
}
