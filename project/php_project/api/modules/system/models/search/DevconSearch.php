<?php
namespace app\modules\system\models\search;
use app\classes\Trans;
use app\modules\system\models\show\DevconShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 交货方式
 */
class DevconSearch extends DevconShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['dec_code','dec_sname'], 'safe'],
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
        $query = DevconShow::find();

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
        $query->andFilterWhere(['like', "dec_code", $this->dec_code])
            ->andFilterWhere([
                'or',
                ['dec_sname' => $this->dec_sname],
                ['dec_sname' => $trans->c2t($this->dec_sname)],
                ['dec_sname' => $trans->t2c($this->dec_sname)]
            ]);
        return $dataProvider;
    }
}
