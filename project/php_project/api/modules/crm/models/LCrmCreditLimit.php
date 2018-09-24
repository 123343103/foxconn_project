<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "l_crm_credit_limit".
 *
 * @property string $l_limit_id
 * @property string $l_credit_id
 * @property string $limit_id
 * @property string $credit_id
 * @property string $credit_type
 * @property string $volume_trade
 * @property string $ap_credit_limit
 * @property string $ap_approval_limit
 * @property string $ap_used_limit
 * @property string $ap_surplus_limit
 * @property string $ap_validity_date
 * @property string $unsurety_credit_limit
 * @property string $unsurety_approval_limit
 * @property string $unsurety_used_limit
 * @property string $unsurety_surplus_limit
 * @property string $unsurety_validity_date
 * @property string $used_limit
 * @property string $surplus_limit
 * @property string $payment_clause
 * @property string $payment_method
 * @property string $initial_day
 * @property string $pay_day
 * @property integer $is_approval
 * @property integer $limit_status
 * @property string $remark
 * @property string $standby1
 * @property string $standby2
 * @property string $standby3
 *
 * @property LCrmCreditApply $lCredit
 */
class LCrmCreditLimit extends \app\models\Common
{
    const NO_APPROVAL = '1';        //是否授权--否
    const YES_APPROVAL = '2';       //是否授权--是
    const DEFAULT_APPROVAL = '3';        //是否授权--默认
    const NO_NEW = '1';     //是否最新--否
    const YES_NEW = '2';     //是否最新--是
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_crm_credit_limit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_credit_id', 'limit_status','is_approval','l_limit_id'], 'integer'],
            [['credit_limit', 'approval_limit'], 'number'],
            [['validity_date'], 'safe'],
            [['credit_type', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
            [['l_credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => LCrmCreditApply::className(), 'targetAttribute' => ['l_credit_id' => 'l_credit_id']],
            [['is_approval'],'default','value'=>self::DEFAULT_APPROVAL],
            [['limit_status'],'default','value'=>self::YES_NEW],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_limit_id' => 'ID',
            'l_credit_id' => '关联账信申请表',
            'credit_type' => '信用额度类型',
            'credit_limit' => '申请额度',
            'approval_limit' => '批复额度',
            'validity_date' => '额度有效期',
            'is_approval' => '是否授予',
            'limit_status' => '状态',
            'remark' => '备注',
            'standby1' => '备用字段1',
            'standby2' => '备用字段2',
            'standby3' => '备用字段3',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLCredit()
    {
        return $this->hasOne(LCrmCreditApply::className(), ['l_credit_id' => 'l_credit_id']);
    }
}
