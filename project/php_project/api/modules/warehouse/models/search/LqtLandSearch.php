<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/9
 * Time: 上午 09:26
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\LqtLand;
use app\modules\warehouse\models\show\LqtLandShow;
use yii\data\ActiveDataProvider;

class LqtLandSearch extends LqtLand
{
    public function rules()
    {
        return [
            [['land_id',  'lqt_id','orderby', 'maxcharge', 'error_send','calcscope1',
                'calcscope2', 'rate', 'minicharge', 'taxrate','remarks','ap_date', 'update_date',
                'payterms', 'en_servicescope', 'itemcname', 'itemcode', 'trucktype', 'uom', 'taxtype',
                'ischoise','truckgroup', 'costtype', 'currency','issend','source'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = LqtLandShow::find();
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        }
        else {
            if (isset($params['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['=',LqtLand::tableName().'.lqt_id',$params['lqt_id']]);
        return $dataProvider;
    }
}