<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/22
 * Time: 上午 09:27
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\OrdLgst;
use app\modules\warehouse\models\show\OrdLgstDtShow;
use app\modules\warehouse\models\show\OrdLgstShow;
use yii\data\ActiveDataProvider;

class OrdLgstSearch extends OrdLgst
{
    public function rules()
    {
        return [
            [['o_whpkid', 'crr', 'YN_FJJ', 'TRANSMODE', 'crter', 'check_status', 'status', 'stter',
                'sr_type', 'trade_act', 'trade_type', 'YN_Fee', 'YN_ins', 'ie_type', 'kd_car', 'lg_fee',
                'lg_fee_tax', 'Fetch_date', 'rcpt_date', 'crt_date', 'stt_date', 'lgst_date', 'lg_no',
                'shp_cntct', 'shp_tel', 'rcv_tel', 'stt_ip', 'rcv_cntct', 'cost_no', 'shp_marks', 'rcv_marks',
                'marks',], 'safe']
        ];
    }
    public function search($params){
        $query = OrdLgstShow::find();
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
        $query->andFilterWhere(['like','ord_lgst.lg_no',empty($params['lg_no']) ? '' : $params['lg_no']]);
        $query->andFilterWhere(['=','ord_lgst.TRANSMODE',empty($params['transmodel']) ? '' : $params['transmodel']]);
        $query->andFilterWhere(['=','ord_lgst.check_status',empty($params['check_status']) ? '' : $params['check_status']]);
        $query->andFilterWhere(['=','ord_lgst.Re_lg_no',empty($params['Re_lg_no'])?'':$params['Re_lg_no']]);
        $query->andFilterWhere(['=','ord_lgst.crter',empty($params['crter'])?'':$params['crter']]);
        $query->andFilterWhere(['>=','ord_lgst.lgst_date',empty($params['startdate'])?'':$params['startdate']]);
        $query->andFilterWhere(['<=','ord_lgst.lgst_date',empty($params['enddate'])?'':$params['enddate']]);
        $query->orderBy(['ord_lgst.crt_date'=>SORT_DESC]);
        return $dataProvider;
    }
}