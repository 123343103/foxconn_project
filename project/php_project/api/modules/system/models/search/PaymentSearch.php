<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\PaymentShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 付款方式
 */
class PaymentSearch extends PaymentShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['pac_code','pac_sname'], 'safe'],
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
        $query = PaymentShow::find();

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
        $query->andFilterWhere(['like', "pac_code", $this->pac_code])
            ->andFilterWhere([
                'or',
                ['pac_sname' => $this->pac_sname],
                ['pac_sname' => $trans->c2t($this->pac_sname)],
                ['pac_sname' => $trans->t2c($this->pac_sname)]
            ]);
        return $dataProvider;
    }
}
