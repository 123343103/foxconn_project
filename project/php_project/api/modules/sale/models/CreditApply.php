<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\crm\models\CrmCreditFile;
use app\modules\crm\models\CrmCreditLimit;
use Yii;

/**
 * This is the model class for table "crm_credit_apply".
 *
 * @property string $credit_id
 * @property string $l_credit_id
 * @property integer $cust_id
 * @property string $credit_code
 * @property string $currency
 * @property string $credit_type
 * @property string $volume_trade
 * @property string $total_amount
 * @property string $credit_amount
 * @property string $allow_amount
 * @property string $used_limit
 * @property string $surplus_limit
 * @property string $grand_total_limit
 * @property string $payment_type
 * @property string $payment_clause
 * @property string $payment_clause_day
 * @property string $payment_method
 * @property string $initial_day
 * @property string $pay_day
 * @property string $apply_remark
 * @property string $credit_date
 * @property string $credit_people
 * @property string $credit_status
 * @property string $file_old
 * @property string $file_new
 * @property integer $company_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 * @property string $standby1
 * @property string $standby2
 * @property string $standby3
 *
 * @property CrmCreditFile[] $crmCreditFiles
 * @property CrmCreditLimit[] $crmCreditLimits
 */
class CreditApply extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'erp.crm_credit_apply';
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
            [['l_credit_id', 'cust_id', 'credit_people', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['volume_trade', 'total_amount', 'credit_amount', 'allow_amount', 'used_limit', 'surplus_limit', 'grand_total_limit'], 'number'],
            [['credit_date', 'create_at', 'update_at'], 'safe'],
            [['credit_code', 'currency', 'credit_type', 'payment_type', 'payment_clause', 'payment_clause_day', 'payment_method', 'initial_day', 'pay_day', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['apply_remark'], 'string', 'max' => 255],
            [['credit_status'], 'string', 'max' => 2],
            [['file_old', 'file_new'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'credit_id' => 'Credit ID',
            'l_credit_id' => 'L Credit ID',
            'cust_id' => 'Cust ID',
            'credit_code' => 'Credit Code',
            'currency' => 'Currency',
            'credit_type' => 'Credit Type',
            'volume_trade' => 'Volume Trade',
            'total_amount' => 'Total Amount',
            'credit_amount' => 'Credit Amount',
            'allow_amount' => 'Allow Amount',
            'used_limit' => 'Used Limit',
            'surplus_limit' => 'Surplus Limit',
            'grand_total_limit' => 'Grand Total Limit',
            'payment_type' => 'Payment Type',
            'payment_clause' => 'Payment Clause',
            'payment_clause_day' => 'Payment Clause Day',
            'payment_method' => 'Payment Method',
            'initial_day' => 'Initial Day',
            'pay_day' => 'Pay Day',
            'apply_remark' => 'Apply Remark',
            'credit_date' => 'Credit Date',
            'credit_people' => 'Credit People',
            'credit_status' => 'Credit Status',
            'file_old' => 'File Old',
            'file_new' => 'File New',
            'company_id' => 'Company ID',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
            'standby1' => 'Standby1',
            'standby2' => 'Standby2',
            'standby3' => 'Standby3',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrmCreditFiles()
    {
        return $this->hasMany(CrmCreditFile::className(), ['credit_id' => 'credit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCrmCreditLimits()
    {
        return $this->hasMany(CrmCreditLimit::className(), ['credit_id' => 'credit_id']);
    }
}
