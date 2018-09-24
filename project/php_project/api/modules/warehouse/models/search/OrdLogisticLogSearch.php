<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 上午 10:41
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\OrdLogisticLog;
use app\modules\warehouse\models\OrdLogisticsShipment;
use app\modules\warehouse\models\show\OrdLogisticLogShow;
use yii\data\ActiveDataProvider;

class OrdLogisticLogSearch extends OrdLogisticLog
{
    public function rules()
    {
        return [

            [['ship_id', 'ship_iid', 'itemno', 'DETAIL_ID',
            'RCVDATE', 'CREATEDATE', 'UPDATE_DATE',
            'orderno', 'FORWARDCODE', 'STATUSCODE', 'DELIVERY_MAN', 'CUSTOMERID', 'CARRIERNO', 'TMS_ORD_CODE',
                'TMS_ORDER_CODE', 'CUSTOMER_SHOP', 'CREATE_BY', 'TRANS_MODE', 'bdm_code',
            'EXPRESSNO', 'TRANSACTIONID', 'ONWAYSTATUS_DATE',
            'STATION', 'ONWAYSTATUS', 'ERRORMEMO', 'FILE_NAME', 'REMARK',
           'FLAG', 'ACK_FLAG', 'SOURCEFROM'],'safe']

        ];
    }
    public function search($params)
    {
        $query = OrdLogisticLogShow::find();
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

        $orderno = "";
        if (!empty($params['partno'])&& !empty($params['o_whdtid'])) {
            $info = OrdLogisticsShipment::getOrderno($params['partno'],$params['o_whdtid']);
            $orderno = $info['orderno'];
            $shipid=$info['ship_id'];
        }
        $query->joinWith('ordLogisticsShipment',$eagerLoading = true, $joinType = 'RIGHT JOIN');
        //$query->andWhere(['=', 'ord_logistics_shipment.pdt_id', empty($params['pdtid']) ? '' :$params['pdtid']]);
        //$query->andWhere(['=', 'ord_logistics_shipment.invh_id', empty($params['invh_id']) ? '' :$params['invh_id']]);
        $query->andFilterWhere(['=', 'ord_logistic_log.orderno', $orderno]);
        $query->andFilterWhere(['=','ord_logistic_log.ship_id',$shipid]);
         //file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }
}