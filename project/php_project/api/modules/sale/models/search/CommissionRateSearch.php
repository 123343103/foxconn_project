<?php
namespace app\modules\sale\models\search;

use app\modules\sale\models\show\CommissionRateShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 提成系数
 */
class CommissionRateSearch extends CommissionRateShow
{
    public $year;
    public $month;
    public $seller;
    public $store;

    // 规则
    public function rules () {
        return [
            [['scommi_begin', 'scommi_end'], 'safe'],
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
     * 查询提成系数 升序
     */
    public function search()
    {
        $query = CommissionRateShow::find()->orderBy('scommi_begin ASC');

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

