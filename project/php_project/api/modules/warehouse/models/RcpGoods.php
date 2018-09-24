<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "rcp_goods".
 *
 * @property string $rcpg_id
 * @property string $rcpg_no
 * @property string $rcpnt_no
 * @property string $buss_code
 * @property string $rcpg_status
 * @property integer $rcpg_type
 * @property string $deliverer
 * @property string $deiver_tel
 * @property string $consignee
 * @property string $con_tel
 * @property string $in_whcode
 * @property string $cancel_reason
 * @property string $cancel_date
 * @property integer $creator
 * @property string $creat_date
 * @property integer $operator
 * @property string $operate_date
 * @property string $operate_ip
 */
class RcpGoods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rcp_goods';
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
            [['rcpg_type', 'creator', 'operator'], 'integer'],
            [['cancel_date', 'creat_date', 'operate_date'], 'safe'],
            [['creator', 'creat_date', 'operate_ip'], 'required'],
            [['rcpg_no', 'rcpnt_no', 'buss_code', 'deliverer', 'consignee', 'in_whcode'], 'string', 'max' => 30],
            [['rcpg_status'], 'string', 'max' => 2],
            [['deiver_tel', 'con_tel', 'operate_ip'], 'string', 'max' => 20],
            [['cancel_reason'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rcpg_id' => '收貨ID',
            'rcpg_no' => '收貨單號',
            'rcpnt_no' => '收貨通知單號',
            'buss_code' => '關聯採購單/移倉調撥單   (prch.bs_prch.prch_no/wms.inv_changeh.chh_code)',
            'rcpg_status' => '單據狀態(1:待入库;2:已入库;3:已取消;)',
            'rcpg_type' => '单据类型(1:采购;2:调拨;3:移仓;)',
            'deliverer' => '送貨人',
            'deiver_tel' => '送貨人聯繫方式',
            'consignee' => '收貨人',
            'con_tel' => '收貨人聯繫方式',
            'in_whcode' => '入倉倉庫（bs_wh.wh_code）',
            'cancel_reason' => '取消原因',
            'cancel_date' => '取消時間',
            'creator' => '創建人',
            'creat_date' => '創建時間',
            'operator' => '操作人',
            'operate_date' => '操作時間',
            'operate_ip' => '操作IP',
        ];
    }
}
