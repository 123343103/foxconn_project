<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/16
 * Time: 上午 11:40
 */

namespace app\modules\warehouse\models\search;


use app\modules\sale\models\OrdInfo;
use app\modules\warehouse\models\OWhpdtDt;
use app\modules\warehouse\models\show\OWhpdtDtShow;
use yii\data\ActiveDataProvider;

class OWhpdtDtSearch extends OWhpdtDt
{
//根据订单号查询商品信息
    public function search($params)
    {
        $query = OWhpdtDtShow::find();
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
        $SohCode = 0;
        if (!empty($params['soh_code'])) {
            $info = OrdInfo::find()->select(['ord_id'])->where(['ord_no' => $params['soh_code']])->one();
            // $info = SaleOrderh::getSohId($params['soh_code']);
            $SohCode = (int)$info['ord_id'];
        }
        //dumpE($SohCode);
        //$query->joinWith('saleOrderh');
        // $query->joinWith(['product']);
        $query->joinWith('oWhpk', $eagerLoading = true, $joinType = 'RIGHT JOIN');
        $query->andFilterWhere(['=', 'wms.o_whpdt.ord_id', $SohCode]);
        // $query->andFilterWhere(['=', 'ic_invh.invh_code', empty($params['invh_code'])?'':$params['invh_code']]);
        file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
}