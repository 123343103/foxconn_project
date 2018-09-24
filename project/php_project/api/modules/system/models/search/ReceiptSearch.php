<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\ReceiptShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 收货方式
 */
class ReceiptSearch extends ReceiptShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['rec_code','rec_sname'], 'safe'],
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
        $query = ReceiptShow::find();

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
        $query->andFilterWhere(['like', "rec_code", $this->rec_code])
            ->andFilterWhere([
                'or',
                ['rec_sname' => $this->rec_sname],
                ['rec_sname' => $trans->c2t($this->rec_sname)],
                ['rec_sname' => $trans->t2c($this->rec_sname)]
            ]);
        return $dataProvider;
    }
}
