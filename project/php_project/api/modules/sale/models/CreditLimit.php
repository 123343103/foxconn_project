<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_credit_limit".
 *
 * @property string $limit_id
 * @property string $credit_id
 * @property string $credit_type
 * @property string $credit_limit
 * @property string $approval_limit
 * @property string $used_limit
 * @property string $surplus_limit
 * @property string $validity_date
 * @property integer $is_approval
 * @property integer $limit_status
 * @property string $remark
 * @property string $standby1
 * @property string $standby2
 * @property string $standby3
 *
 * @property CreditApply $credit
 */
class CreditLimit extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'erp.crm_credit_limit';
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
            [['credit_id', 'is_approval', 'limit_status'], 'integer'],
            [['credit_limit', 'approval_limit', 'used_limit', 'surplus_limit'], 'number'],
            [['validity_date'], 'safe'],
            [['credit_type', 'standby1', 'standby2', 'standby3'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 255],
            [['credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => CreditApply::className(), 'targetAttribute' => ['credit_id' => 'credit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'limit_id' => 'Limit ID',
            'credit_id' => 'Credit ID',
            'credit_type' => 'Credit Type',
            'credit_limit' => 'Credit Limit',
            'approval_limit' => 'Approval Limit',
            'used_limit' => 'Used Limit',
            'surplus_limit' => 'Surplus Limit',
            'validity_date' => 'Validity Date',
            'is_approval' => 'Is Approval',
            'limit_status' => 'Limit Status',
            'remark' => 'Remark',
            'standby1' => 'Standby1',
            'standby2' => 'Standby2',
            'standby3' => 'Standby3',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredit()
    {
        return $this->hasOne(CreditApply::className(), ['credit_id' => 'credit_id']);
    }
}
