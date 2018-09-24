<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\common\models\BsExchangeRate;
use app\modules\sale\models\show\BsExchangeRateShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 汇率表查询
 */
class BsExchangeRateSearch extends BsExchangeRate
{
    /**
     * 规则
     */
    public function rules()
    {
        return [
            [[], 'safe'],
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
     * 搜索销单明细
     */
    public function search($param)
    {
        $query = BsExchangeRateShow::find();

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
        $query->andFilterWhere(['ber_origin'=>$param['origin']])
            ->andFilterWhere(['ber_year'=>$param['year']])
            ->andFilterWhere(['ber_month'=>$param['month']]);
        return $dataProvider;
    }

}
