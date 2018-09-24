<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_pickingh".
 *
 * @property string $soph_id
 * @property integer $comp_id
 * @property string $bus_code
 * @property integer $origin_id
 * @property integer $p_bill_id
 * @property string $bill_type
 * @property string $cust_id
 * @property string $poh_id
 * @property string $soph_no
 * @property string $soph_date
 * @property string $pick_date
 * @property integer $whs_id
 * @property string $organization_id
 * @property integer $pick_by
 * @property string $pri
 * @property string $pick_note
 * @property integer $review_by
 * @property string $rdate
 * @property integer $create_by
 * @property string $cdate
 * @property integer $update_by
 * @property string $udate
 */
class SalePickingh extends Common
{
    const SALE_OUT      = '1';  // 销售出库通知类型
    const PURCHASE_IN   = '2';  // 采购入库通知类型
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_pickingh';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['soph_id', 'comp_id', 'origin_id', 'p_bill_id', 'cust_id', 'poh_id', 'whs_id', 'organization_id', 'pick_by', 'review_by', 'create_by', 'update_by'], 'integer'],
            [['soph_date', 'pick_date', 'rdate', 'cdate', 'udate'], 'safe'],
            [['bus_code', 'bill_type', 'soph_no', 'pri'], 'string', 'max' => 20],
            [['pick_note'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'soph_id' => 'Soph ID',
            'comp_id' => '公司ID 關聯公司表bs_comp',
            'bus_code' => '業務對象編碼 關聯業務對象表',
            'origin_id' => '源单ID 订单ID',
            'p_bill_id' => '通知单ID 上级单ID',
            'bill_type' => '單據類型 銷售出貨通知或採購入庫通知',
            'cust_id' => '客商ID',
            'poh_id' => '訂單ID 關聯採購訂單主表(po_orderH)',
            'soph_no' => '揀貨單號',
            'soph_date' => '揀貨單日期',
            'pick_date' => 'Pick Date',
            'whs_id' => '倉庫id',
            'organization_id' => '部門',
            'pick_by' => '揀貨人',
            'pri' => '優先級',
            'pick_note' => '揀貨說明',
            'review_by' => '審核人',
            'rdate' => '審核日期',
            'create_by' => '創建日期',
            'cdate' => '創建人',
            'update_by' => '修改人',
            'udate' => '修改日期',
        ];
    }
}
