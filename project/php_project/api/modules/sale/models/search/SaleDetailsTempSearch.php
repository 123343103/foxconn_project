<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/11
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\SaleDetailsTemp;
use app\modules\sale\models\show\SaleDetailsTempShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 销单明细临时表
 */
class SaleDetailsTempSearch extends SaleDetailsTemp
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
    public function search()
    {
        $query = SaleDetailsTempShow::find();

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
        $query->joinWith('storeInfo');
        $query->andFilterWhere(['!=','sts_status',16]);
//        $query->groupBy('sdl_sacode');
//        $query->andFilterWhere(['or',['like', "sale_details.sdl_sacode", $this->seller],['like', "sale_details.sdl_saname", $this->seller]])
//                ->andFilterWhere(['crm_bs_storesinfo.sts_id'=>$this->store]);
//        if ($this->saleStartDate && !$this->saleEndDate) {
//            $query->andFilterWhere([">=", "sale_date", $this->saleStartDate]);
//        }
//        if ($this->saleEndDate && !$this->saleStartDate) {
//            $query->andFilterWhere(["<=", "sale_date", date("Y-m-d H:i:s", strtotime($this->saleEndDate . '+1 day'))]);
//        }
//        if ($this->saleEndDate && $this->saleStartDate) {
//            $query->andFilterWhere(["between", "sale_details.sale_date", $this->saleStartDate, date("Y-m-d H:i:s", strtotime($this->saleEndDate . '+1 day'))]);
//        }
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();
        return $dataProvider;
    }

}
