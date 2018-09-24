<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "r_bank_order".
 *
 * @property string $r_b_or_id
 * @property string $TRANSID
 * @property string $rbo_id
 * @property string $ord_pay_id
 * @property string $remark
 *
 * @property BsBankInfo $tRANS
 * @property OrdPay $ordPay
 * @property BsBankCheck $rbo
 */
class RBankOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_bank_order';
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
            [['rbo_id', 'ord_pay_id'], 'integer'],
            [['TRANSID'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 250],
            [['TRANSID'], 'exist', 'skipOnError' => true, 'targetClass' => BsBankInfo::className(), 'targetAttribute' => ['TRANSID' => 'TRANSID']],
            [['ord_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdPay::className(), 'targetAttribute' => ['ord_pay_id' => 'ord_pay_id']],
            [['rbo_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsBankCheck::className(), 'targetAttribute' => ['rbo_id' => 'rbo_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_b_or_id' => 'R B Or ID',
            'TRANSID' => 'Transid',
            'rbo_id' => 'Rbo ID',
            'ord_pay_id' => 'Ord Pay ID',
            'remark' => 'Remark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTRANS()
    {
        return $this->hasOne(BsBankInfo::className(), ['TRANSID' => 'TRANSID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdPay()
    {
        return $this->hasOne(OrdPay::className(), ['ord_pay_id' => 'ord_pay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRbo()
    {
        return $this->hasOne(BsBankCheck::className(), ['rbo_id' => 'rbo_id']);
    }
}
