<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 下午 01:53
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\show\BsPartShow;
use yii\data\ActiveDataProvider;

class BsPartSearch extends BsPart
{
    public $wh_name;

    public function rules()
    {
        return [
            [['part_name', 'part_code', 'wh_name', 'YN', 'wh_code'], 'safe']
        ];
    }

    public function search($param)
    {
        $query = BsPartShow::find();
        if (isset($param['rows'])) {
            $pageSize = $param['rows'];
        } else {
            if (isset($param['export'])) {
                $pageSize = false;
            } else {
                $pageSize = 10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize
            ],
            'sort' => [
                'defaultOrder' => ['OPP_DATE' => SORT_DESC],
            ],
        ]);
        $this->load($param);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['=', 'bs_part.wh_code', empty($param['wh_name']) ? '' : $param['wh_name']])
            ->andFilterWhere(['like', 'bs_part.wh_code', empty($param['wh_code']) ? '' : $param['wh_code']])
            ->andFilterWhere(['=', 'bs_part.YN', (!isset($param['type'])||$param['type']==3) ? '' : $param['type']])
            ->andFilterWhere(['like', 'bs_part.part_code', empty($param['part_code']) ? '' : $param['part_code']])
            ->andFilterWhere(['like', 'bs_part.part_name', empty($param['part_name']) ? '' : $param['part_name']]);
        return $dataProvider;
    }

}