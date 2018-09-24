<?php

namespace app\modules\warehouse\models\search;
use app\modules\warehouse\models\InvChangel;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\warehouse\models\show\InvChangelShow;
/**
 * This is the ActiveQuery class for [[\app\modules\warehouse\models\InvChangel]].
 *
 * @see \app\modules\warehouse\models\InvChangel
 */
class InvChangelSearch extends InvChangel
{
    public function rules()
    {
        return [

        ];
    }
    public function search($params)   //搜索方法
    {
        $query = InvChangelShow::find()->where(['chh_id' => $params['id']]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        return $dataProvider;
    }
}
