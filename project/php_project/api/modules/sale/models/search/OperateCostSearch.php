<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/19
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\show\OperateCostShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 业务费用查询
 */
class OperateCostSearch extends OperateCostShow
{
    public $year;
    public $month;
    public $seller;
    public $store;

    // 规则
    public function rules () {
        return [
            [['year', 'month'], 'safe'],
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
     * 查询当期业务费用
     */
    public function search($param)
    {
        $query = OperateCostShow::find();

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
        $query->andFilterWhere(['like', "soc_year", $this->year])
            ->andFilterWhere(['like', "soc_month", $this->month]);
        return $dataProvider;
    }
}

