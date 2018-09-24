<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "repay_credit".
 *
 * @property string $repay_pkid
 * @property string $ord_pay_id
 * @property string $repay_fee
 * @property string $pat_sname
 * @property string $pat_code
 * @property string $user_id
 * @property string $user_date
 * @property integer $is_repay
 * @property string $repay_date
 * @property integer $repay_type
 * @property string $apper
 * @property string $app_date
 * @property string $confirm_date
 *
 * @property OrdPay $ordPay
 */
class RepayCredit extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.repay_credit';
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
            [['ord_pay_id', 'repay_fee', 'pat_sname', 'pat_code', 'user_id', 'user_date', 'is_repay'], 'required'],
            [['ord_pay_id', 'user_id', 'is_repay', 'repay_type'], 'integer'],
            [['repay_fee'], 'number'],
            [['user_date', 'repay_date', 'app_date', 'confirm_date'], 'safe'],
            [['pat_sname'], 'string', 'max' => 50],
            [['pat_code', 'apper'], 'string', 'max' => 30],
            [['ord_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdPay::className(), 'targetAttribute' => ['ord_pay_id' => 'ord_pay_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'repay_pkid' => 'Repay Pkid',
            'ord_pay_id' => 'Ord Pay ID',
            'repay_fee' => 'Repay Fee',
            'pat_sname' => 'Pat Sname',
            'pat_code' => 'Pat Code',
            'user_id' => 'User ID',
            'user_date' => 'User Date',
            'is_repay' => 'Is Repay',
            'repay_date' => 'Repay Date',
            'repay_type' => 'Repay Type',
            'apper' => 'Apper',
            'app_date' => 'App Date',
            'confirm_date' => 'Confirm Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdPay()
    {
        return $this->hasOne(OrdPay::className(), ['ord_pay_id' => 'ord_pay_id']);
    }
}
