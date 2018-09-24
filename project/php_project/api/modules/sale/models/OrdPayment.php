<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "ord_payment".
 *
 * @property string $pay_id
 * @property string $ord_pay_id
 * @property string $payment_date
 * @property string $payment_no
 * @property string $payment_price
 * @property string $check_date
 * @property integer $payment_id
 * @property string $smit_payment_amt
 * @property string $smit_payment_date
 * @property string $check_payment_date
 * @property string $payment_bank
 * @property string $transaction_no
 * @property string $account_title
 * @property string $ar_date
 * @property string $bank_username
 * @property string $bank_no
 * @property string $charges
 * @property string $re_amount
 * @property integer $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property OrdPay $ordPay
 */
class OrdPayment extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.ord_payment';
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
            [['pay_id'], 'required'],
            [['pay_id', 'ord_pay_id', 'payment_id', 'opper'], 'integer'],
            [['payment_date', 'check_date', 'smit_payment_date', 'check_payment_date', 'ar_date', 'opp_date'], 'safe'],
            [['payment_price', 'smit_payment_amt', 'charges', 're_amount'], 'number'],
            [['payment_no'], 'string', 'max' => 50],
            [['payment_bank', 'bank_username'], 'string', 'max' => 100],
            [['transaction_no'], 'string', 'max' => 200],
            [['account_title', 'opp_ip'], 'string', 'max' => 20],
            [['bank_no'], 'string', 'max' => 30],
            [['ord_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdPay::className(), 'targetAttribute' => ['ord_pay_id' => 'ord_pay_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pay_id' => 'Pay ID',
            'ord_pay_id' => 'Ord Pay ID',
            'payment_date' => 'Payment Date',
            'payment_no' => 'Payment No',
            'payment_price' => 'Payment Price',
            'check_date' => 'Check Date',
            'payment_id' => 'Payment ID',
            'smit_payment_amt' => 'Smit Payment Amt',
            'smit_payment_date' => 'Smit Payment Date',
            'check_payment_date' => 'Check Payment Date',
            'payment_bank' => 'Payment Bank',
            'transaction_no' => 'Transaction No',
            'account_title' => 'Account Title',
            'ar_date' => 'Ar Date',
            'bank_username' => 'Bank Username',
            'bank_no' => 'Bank No',
            'charges' => 'Charges',
            're_amount' => 'Re Amount',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
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
