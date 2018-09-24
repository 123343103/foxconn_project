<?php
/**
 * Created by PhpStorm.
 * User: F1678797
 * Date: 2017/1/19
 * Time: 下午 03:51
 */
namespace app\modules\sale\models\search;
use app\modules\sale\models\show\CrmSaleRolesShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * 销售角色查询
 */
class CrmSaleRolesSearch extends CrmSaleRolesShow
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
     * 查询角色
     */
    public function search($params)
    {
        $query = CrmSaleRolesShow::find()->where(["sarole_status"=>10]);

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

        return $dataProvider;
    }
}

