<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\CurrencyShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 货币类别
 */
class CurrencySearch extends CurrencyShow
{
    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['cur_code','cur_sname'], 'safe'],
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
        $query = CurrencyShow::find();

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
        $query->andFilterWhere(['like', "cur_code", $this->cur_code])
            ->andFilterWhere([
                'or',
                ['cur_sname' => $this->cur_sname],
                ['cur_sname' => $trans->c2t($this->cur_sname)],
                ['cur_sname' => $trans->t2c($this->cur_sname)]
            ]);
        return $dataProvider;
    }
}
