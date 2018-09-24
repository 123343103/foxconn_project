<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "in_whpdt".
 *
 * @property string $invh_id
 * @property integer $comp_id
 * @property string $invh_code
 * @property string $invh_date
 * @property integer $invh_status
 * @property string $cust_id
 * @property string $organization_id
 * @property integer $inout_type
 * @property string $bus_code
 * @property integer $inout_flag
 * @property string $wh_code
 * @property string $invh_aboutno
 * @property integer $invh_reperson
 * @property string $invh_repaddress
 * @property string $invh_sendperson
 * @property string $invh_sendaddress
 * @property string $recive_date
 * @property string $consignment_quat
 * @property string $invh_remark
 * @property integer $review_by
 * @property string $rdate
 * @property integer $create_by
 * @property string $cdate
 * @property integer $update_by
 * @property string $udate
 * @property string $op_ip
 * @property string $can_reason
 */
class InWhpdt extends \yii\db\ActiveRecord
{
    public $codeType;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'in_whpdt';
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
            [['comp_id', 'invh_status', 'cust_id', 'organization_id', 'inout_type', 'inout_flag', 'invh_reperson', 'review_by', 'create_by', 'update_by'], 'integer'],
            [['invh_date', 'recive_date', 'rdate', 'cdate', 'udate'], 'safe'],
            [['invh_status'], 'required'],
            [['consignment_quat'], 'number'],
            [['invh_code', 'wh_code'], 'string', 'max' => 50],
            [['bus_code', 'invh_repaddress', 'invh_sendperson', 'invh_sendaddress', 'op_ip'], 'string', 'max' => 20],
            [['invh_aboutno'], 'string', 'max' => 30],
            [['invh_remark', 'can_reason'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invh_id' => 'Invh ID',
            'comp_id' => '公司ID',
            'invh_code' => '入库单号',
            'invh_date' => '入库单日期',
            'invh_status' => '状态(1:待提交;2:审核中;3:驳回;4:已取消;5:待上架;6:已上架;)',
            'cust_id' => '客商id',
            'organization_id' => '單位部門ID',
            'inout_type' => '单据类型(1:采购;2:调拨;3:移仓;)',
            'bus_code' => '关联单号',
            'inout_flag' => '单据类型，其他入库新增用到',
            'wh_code' => '入仓仓库',
            'invh_aboutno' => '关联收货单号',
            'invh_reperson' => '收货人',
            'invh_repaddress' => '收货人联系方式',
            'invh_sendperson' => '送货人',
            'invh_sendaddress' => '送货人联系方式',
            'recive_date' => '收货日期',
            'consignment_quat' => '收货总数',
            'invh_remark' => '备注',
            'review_by' => '审核人',
            'rdate' => '审核时间',
            'create_by' => '制单人',
            'cdate' => '制单时间',
            'update_by' => '修改人',
            'udate' => '修改日期',
            'op_ip' => '操作IP',
            'can_reason' => '取消原因',
        ];
    }
}
