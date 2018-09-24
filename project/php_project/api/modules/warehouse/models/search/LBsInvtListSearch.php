<?php
/**
 * User: F1676624
 * Date: 2017/7/25
 */
namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\show\LBsInvtListShow;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class LBsInvtListSearch extends LBsInvtListShow
{
    //仓库异动商品选择
    public function changeSearch($params)   //搜索方法
    {
        $query = LBsInvtListShow::find();
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
            ]
        ]);
        $query->andFilterWhere(['l_bs_invt_list.whs_id' => $params['whId']])
            ->andFilterWhere(['like', 'l_bs_invt_list.st_id', isset($params['store']) ? $params['store'] : ""])
            ->andFilterWhere(['like', 'l_bs_invt_list.part_no', isset($params['kwd']) ? $params['kwd'] : ""]);

        return $dataProvider;

    }
}