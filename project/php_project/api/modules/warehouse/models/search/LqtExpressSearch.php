<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/9
 * Time: 上午 09:27
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\LqtExpress;
use app\modules\warehouse\models\show\LqtExpressShow;
use yii\data\ActiveDataProvider;

class LqtExpressSearch extends LqtExpress
{
    public function rules()
    {
        return [
            [['lqt_id','itemno','weightmin', 'firstprice', 'nextweight', 'next_rate',
                'min_value', 'max_value', 'chargemin', 'chargemax','effectdate',
                'expiredate', 'costconfirmeddate','costno', 'uom', 'transittime',
                'STATUS', 'transittime1', 'transittime2','costname','remark'], 'safe']
            ];
    }

    public function search($params)
    {
        $query = LqtExpressShow::find();
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
        $query->andFilterWhere(['=',LqtExpress::tableName().'.lqt_id',$params['lqt_id']]);
        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
}