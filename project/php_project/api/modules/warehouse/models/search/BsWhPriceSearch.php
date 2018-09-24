<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 04:27
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\show\BsWhPriceShow;
use yii\data\ActiveDataProvider;

class BsWhPriceSearch extends BsWhPriceShow
{
    public function search($param)
    {
        $query = BsWhPriceShow::find();
        if (isset($param['rows'])) {
            $pageSize = $param['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize
            ],
            'sort' => [
                'defaultOrder' => ['whpb_code' => SORT_DESC],
            ],
        ]);
        $this->load($param);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'whpb_code', empty($param['whpb_code']) ? '' : $param['whpb_code']])
            ->andFilterWhere(['=', 'stcl_status', (!isset($param['stcl_status'])) ? '' : $param['stcl_status']])
            ->andFilterWhere(['like', 'whpb_sname', empty($param['whpb_sname']) ? '' : $param['whpb_sname']]);
        return $dataProvider;
    }
}