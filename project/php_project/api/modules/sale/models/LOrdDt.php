<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_ord_dt".
 *
 * @property string $l_ord_dt_id
 * @property string $l_ord_id
 * @property string $prt_pkid
 * @property integer $is_gift
 * @property string $sapl_quantity
 * @property string $uprice_ntax_o
 * @property string $uprice_tax_o
 * @property string $uprice_ntax_c
 * @property string $uprice_tax_c
 * @property string $tprice_ntax_o
 * @property string $tprice_tax_o
 * @property string $tprice_ntax_c
 * @property string $tprice_tax_c
 * @property string $pack_type
 * @property string $whs_id
 * @property string $cess
 * @property string $discount
 * @property string $distribution
 * @property string $tax_freight
 * @property string $freight
 * @property integer $transport
 * @property integer $sapl_status
 * @property string $suttle
 * @property string $gross_weight
 * @property string $request_date
 * @property string $consignment_date
 * @property string $sapl_remark
 * @property integer $reprice_mark
 */
class LOrdDt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.l_ord_dt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_ord_id', 'prt_pkid', 'is_gift', 'whs_id', 'distribution', 'transport', 'sapl_status'], 'integer'],
            [['sapl_quantity', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'cess', 'discount', 'tax_freight', 'freight', 'suttle', 'gross_weight'], 'number'],
            [['request_date', 'consignment_date'], 'safe'],
            [['pack_type'], 'string', 'max' => 20],
            [['sapl_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_ord_dt_id' => 'L Ord Dt ID',
            'l_ord_id' => 'L Ord ID',
            'prt_pkid' => 'Prt Pkid',
            'is_gift' => 'Is Gift',
            'sapl_quantity' => 'Sapl Quantity',
            'uprice_ntax_o' => 'Uprice Ntax O',
            'uprice_tax_o' => 'Uprice Tax O',
            'uprice_ntax_c' => 'Uprice Ntax C',
            'uprice_tax_c' => 'Uprice Tax C',
            'tprice_ntax_o' => 'Tprice Ntax O',
            'tprice_tax_o' => 'Tprice Tax O',
            'tprice_ntax_c' => 'Tprice Ntax C',
            'tprice_tax_c' => 'Tprice Tax C',
            'pack_type' => 'Pack Type',
            'whs_id' => 'Whs ID',
            'cess' => 'Cess',
            'discount' => 'Discount',
            'distribution' => 'Distribution',
            'tax_freight' => 'Tax Freight',
            'freight' => 'Freight',
            'transport' => 'Transport',
            'sapl_status' => 'Sapl Status',
            'suttle' => 'Suttle',
            'gross_weight' => 'Gross Weight',
            'request_date' => 'Request Date',
            'consignment_date' => 'Consignment Date',
            'sapl_remark' => 'Sapl Remark',
        ];
    }
}
