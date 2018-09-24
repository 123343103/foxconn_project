<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/19
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\show\StoreCostShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 查询固定费用
 */
class StoreCostSearch extends StoreCostShow
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
     * 查询当期固定费用
     */
    public function search($param)
    {
        $query = StoreCostShow::find();

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
        $query->andFilterWhere(['like', "storc_year", $this->year])
            ->andFilterWhere(['like', "storc_month", $this->month]);
//            ->andFilterWhere(['like', "ssc_no", $this->seller]);
//            ->orFilterWhere(['like', "sds_saname", $this->seller])
//            ->andFilterWhere(['like', "crm_bs_storesinfo.sts_sname", $this->store])
//            ->andFilterWhere(['CONCAT(sds_year,\'-\',sds_month)' => $this->month]);
        return $dataProvider;
    }

}

