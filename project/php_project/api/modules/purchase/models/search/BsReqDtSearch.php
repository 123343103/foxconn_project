<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/13
 * Time: 上午 11:24
 */

namespace app\modules\purchase\models\search;


use app\modules\purchase\models\BsReq;
use app\modules\purchase\models\BsReqDt;
use app\modules\purchase\models\show\BsReqDtShow;
use yii\data\ActiveDataProvider;

class BsReqDtSearch extends BsReqDt
{
    public function rules()
    {
        return [
            [['req_id', 'prt_pkid', 'spp_id', 'exp_account', 'req_nums', 'req_price',
                'total_amount', 'org_price', 'rebat_rate', 'req_date', 'prj_no', 'remarks', 'req_id'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = BsReqDtShow::find();
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
        $query->joinWith('req', $eagerLoading = true)
       ->andFilterWhere(['=', BsReq::tableName() . '.req_status', 38])
       ->andFilterWhere(['=',BsReqDt::tableName().'.prch_id','0'])
       ->andFilterWhere(['=', BsReq::tableName() . '.app_id', empty($params['staff']) ? '' : $params['staff']])
       ->andFilterWhere(['=', BsReq::tableName() . '.req_no', empty($params['req_no']) ? '' : $params['req_no']])
       ->andFilterWhere(['=', BsReq::tableName() . '.req_dct', empty($params['req_dct']) ? '' : $params['req_dct']])
       ->andFilterWhere(['=', BsReq::tableName() . '.req_rqf', empty($params['req_rqf']) ? '' : $params['req_rqf']])
       ->andFilterWhere(['=', BsReq::tableName() . '.spp_dpt_id', empty($params['spp_dpt_id']) ? '' : $params['spp_dpt_id']])
       ->andFilterWhere(['=', BsReq::tableName() . '.leg_id', empty($params['leg_id']) ? '' : $params['leg_id']])
       ->andFilterWhere(['>', BsReq::tableName() . '.app_date', empty($params['starttime']) ? '' : $params['starttime']])
       ->andFilterWhere(['<', BsReq::tableName() . '.app_date', empty($params['endtime']) ? '' : $params['endtime']]);
        file_put_contents('log.txt', $query->createCommand()->getRawSql());
        return $dataProvider;
    }

}