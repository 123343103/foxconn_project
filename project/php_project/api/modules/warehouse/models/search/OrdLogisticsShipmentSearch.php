<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/21
 * Time: 上午 10:44
 */

namespace app\modules\warehouse\models\search;


use app\modules\warehouse\models\OrdLogisticsShipment;
use app\modules\warehouse\models\show\OrdLogisticsShipmentShow;
use yii\data\ActiveDataProvider;

class OrdLogisticsShipmentSearch extends OrdLogisticsShipment
{
    public function rules()
    {
        return [
            //[['ORDERNO', 'ORDERNO_ITEM', 'RECEIPT_NO','QTY','DETAIL_ID','UPDATE_DATE','HHPN','ORDER_NO',], 'safe']

            [['ship_id', 'orderno_item', 'pdt_id', 'saleorder_id', 'saleorder_detailid', 'invh_id', 'invl_id', 'Carr_id', 'create_by',
                'qty', 'freight',
                'update_date', 'create_at',
                'orderno', 'receipt_no',
                'pdt_no',
                'ship_status'], 'safe']
        ];

    }

    public function search($params)
    {
        $query = OrdLogisticsShipmentShow::find();
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
        $query->joinWith('ordLogisticLog');
        $query->andWhere(['=', 'ord_logistics_shipment.pdt_id', empty($params['pdtid']) ? '' :$params['pdtid']]);
        $query->andWhere(['=', 'ord_logistics_shipment.invh_id', empty($params['invh_id']) ? '' :$params['invh_id']]);
       // file_put_contents('log.txt',$query->createCommand()->getRawSql());
        return $dataProvider;
    }

}