<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/9
 * Time: 上午 09:27
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\LqtHead;
use app\modules\warehouse\models\show\LqtHeadShow;
use yii\data\ActiveDataProvider;

class LqtHeadSearch extends LqtHead
{
    public function rules()
    {
        return [
            [['EFFECTDATE', 'EXPIREDATE', 'GSP_DATE', 'lqt_type', 'STATUS', 'CNCY', 'remarks',
                'lqt_no', 'TRANSMODE', 'TRANSTYPE', 'FR_DIST', 'FR_CITY', 'FR_DVS', 'FR_TWN',
                'TO_DIST', 'TO_CITY', 'TO_DVS', 'TO_TWN', 'logqt_cmp', 'QT_CLASS', 'ORIGIN',
                'DST_NATION', 'TQ_UNIT'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = LqtHeadShow::find();
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
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

        $query->andFilterWhere(['=', LqtHead::tableName() . '.lqt_no', empty($params['salesquotationno']) ? '' : $params['salesquotationno']])
            ->andFilterWhere(['=', LqtHead::tableName() . '.FR_CITY', empty($params['startcityid']) ? '' : $params['startcityid']])
            ->andFilterWhere(['=', LqtHead::tableName() . '.TO_CITY', empty($params['endcityid']) ? '' : $params['endcityid']])
            ->andFilterWhere(['=',LqtHead::tableName().'.TRANSMODE',empty($params['transmodel'])?'':$params['transmodel']])
            ->andFilterWhere(['=',LqtHead::tableName().'.TRANSTYPE',empty($params['transtype'])?'':$params['transtype']])
            ->andFilterWhere(['=',LqtHead::tableName().'.STATUS',empty($params['lqtstatus'])?'':$params['lqtstatus']]);
        return $dataProvider;
    }
}