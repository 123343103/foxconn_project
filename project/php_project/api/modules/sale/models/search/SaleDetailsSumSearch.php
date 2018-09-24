<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/2/10
 * Time: 下午 04:36
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\show\SaleDetailsSumShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class SaleDetailsSumSearch extends SaleDetailsSumShow
{
    public $seller;
    public $month;
    public $store;

    // 规则
    public function rules () {
        return [
            [['month', 'sale_date', 'seller', 'store'], 'safe'],
        ];
    }

    // 场景
    public function scenarios()
    {
        return Model::scenarios();
    }

    // 搜索
    public function search ($param)
    {
        $query = SaleDetailsSumShow::find();
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
        $query->andFilterWhere(['or',['like', "sds_sacode", $this->seller],['like', "sds_saname", $this->seller]])
//                ->andFilterWhere(['or',['like', "crm_bs_storesinfo.sts_sname", $this->store],['like', "crm_bs_storesinfo.sts_code", $this->store]])
                ->andFilterWhere(['crm_bs_storesinfo.sts_id'=>$this->store])
                ->andFilterWhere(['CONCAT(sds_year,\'-\',sds_month)' => $this->month]);

//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;

    }
}