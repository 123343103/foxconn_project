<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/19
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\SaleSalercost;
use app\modules\sale\models\show\DirectSellerNumShow;
use app\modules\sale\models\show\IndirectTotalShow;
use app\modules\sale\models\show\SellerCostShow;
use app\modules\sale\models\show\SellerShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 查询人力成本
 */
class SellerCostSearch extends SellerCostShow
{
    public $year;
    public $month;
    public $seller;
    public $store;

    // 规则
    public function rules () {
        return [
            [['year', 'month', 'sale_date', 'seller', 'store'], 'safe'],
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
     * 查询当期销售人力成本
     */
    public function search($param)
    {
        $query = SellerCostShow::find();

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
//        $query->joinWith("area");
//        $query->joinWith("staffName");
        $query->andFilterWhere(['like', "ssc_year", $this->year])
            ->andFilterWhere(['like', "ssc_month", $this->month]);
//            ->andFilterWhere(['like', "ssc_no", $this->seller]);
//            ->orFilterWhere(['like', "sds_saname", $this->seller])
//            ->andFilterWhere(['like', "crm_bs_storesinfo.sts_sname", $this->store])
//            ->andFilterWhere(['CONCAT(sds_year,\'-\',sds_month)' => $this->month]);


        return $dataProvider;
    }

    // 统计当期间接人力成本总额
    public function indirectTotal() {
        $query = IndirectTotalShow::find()->select(['sum(real_wage) as indirectTotal']);
        $query->joinWith("roleInfo");
        $query->andFilterWhere(['sarole_type' => 2])            // 2 在销售角色表中表示间接人力
            ->andFilterWhere(['like', "ssc_year", $this->year])
            ->andFilterWhere(['like', "ssc_month", $this->month]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $dataProvider;
    }

    // 统计当期直接人力数
    public function directSellerNum() {
        $query = DirectSellerNumShow::find()->select(['count(ssc_id) as directSellerNum']);
        $query->joinWith("roleInfo");
        $query->andFilterWhere(['sarole_type' => 1])            // 1 在销售角色表中表示直接人力
        ->andFilterWhere(['like', "ssc_year", $this->year])
            ->andFilterWhere(['like', "ssc_month", $this->month]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        return $dataProvider;
    }


}

