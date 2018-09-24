<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "rcp_notice_dt".
 *
 * @property string $rcpdt_id
 * @property string $rcpnt_no
 * @property string $ord_id
 * @property string $part_no
 * @property string $req_type
 * @property string $ord_num
 * @property string $delivery_num
 * @property string $plan_date
 * @property integer $operator
 * @property string $operate_date
 * @property string $operate_ip
 * @property string $before_stno
 * @property string $chwh_num
 * @property string $invt_num
 *   @property string $remarks
 */
class RcpNoticeDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rcp_notice_dt';
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
            [['rcpnt_no'], 'required'],
            [['req_type', 'operator','prch_dt_id'], 'integer'],
            [['ord_num', 'delivery_num', 'chwh_num', 'invt_num'], 'number'],
            [['plan_date', 'operate_date'], 'safe'],
            [['rcpnt_no', 'before_stno'], 'string', 'max' => 30],
            [['remarks'],'string','max'=>100],
            [['ord_id', 'part_no', 'operate_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rcpdt_id' => '詳情id',
            'rcpnt_no' => '收貨通知單號',
            'ord_id' => '訂單項次/批次',
            'prch_dt_id'=>'采购单明细ID',
            'part_no' => '料號（pdt.bs_material.part_no）',
            'req_type' => '採購方式   erp.bs_pubdata.bsp_stype=&#039;&#039;CGFS&#039;&#039;&#039;',
            'ord_num' => '採購量',
            'delivery_num' => '送貨數量量/调拨数量',
            'plan_date' => '預計到貨日期',
            'operator' => '操作人',
            'operate_date' => '操作時間',
            'operate_ip' => '操作IP',
            'before_stno' => '移倉前儲位/调拨出仓储位',
            'chwh_num' => '移倉數量',
            'invt_num' => '库存量',
            'remarks'=>'备注'
        ];
    }
}
