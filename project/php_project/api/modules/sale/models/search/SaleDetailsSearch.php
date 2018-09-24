<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\show\SaleDetailsShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\sale\models\SaleDetails;
/**
 * 销单明细列表
 */
class SaleDetailsSearch extends SaleDetails
{
    public $seller;
    public $saleStartDate;
    public $saleEndDate;
    public $store;
    public $month;
    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['seller','saleStartDate','saleEndDate','store','month', 'sale_date'], 'safe'],
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
        $query = SaleDetailsShow::find();

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
        $query->joinWith('storeInfo');
        $query->andFilterWhere(['or',['like', "sale_details.sdl_sacode", $this->seller],['like', "sale_details.sdl_saname", $this->seller]])
                ->andFilterWhere(['crm_bs_storesinfo.sts_id'=>$this->store]);
        if ($this->saleStartDate && !$this->saleEndDate) {
            $query->andFilterWhere([">=", "sale_date", $this->saleStartDate]);
        }
        if ($this->saleEndDate && !$this->saleStartDate) {
            $query->andFilterWhere(["<=", "sale_date", date("Y-m-d H:i:s", strtotime($this->saleEndDate . '+1 day'))]);
        }
        if ($this->saleEndDate && $this->saleStartDate) {
            $query->andFilterWhere(["between", "sale_details.sale_date", $this->saleStartDate, date("Y-m-d H:i:s", strtotime($this->saleEndDate . '+1 day'))]);
        }
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

    public function searchSum($param)
    {
        $query = SaleDetailsShow::find()->select(['*', 'sum(bill_camount) as amountSum', 'sum(stan_cost) as costSum', 'SUM(IF(sale_type=12,bill_camount*0.01,bill_camount*0.03)) as changeCost']);
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
//        $query->andFilterWhere(['like', "sale_details.sdl_sacode", $this->seller])
//                ->orFilterWhere(['like', "sale_details.sdl_saname", $this->seller])
//                ->andFilterWhere(['DATE_FORMAT(sale_date,\'%Y-%m\')' => $this->month])
//                ->groupBy(['sale_details.sdl_sacode', 'DATE_FORMAT(sale_date,\'%Y %m\')']);

        //不加其他条件直接把某月销售明细统计到销售汇总表里
        $query->andFilterWhere(['DATE_FORMAT(sale_date,\'%Y-%m\')' => $this->month])
                ->groupBy(['sale_details.sdl_sacode', 'DATE_FORMAT(sale_date,\'%Y %m\')']);
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }
}
