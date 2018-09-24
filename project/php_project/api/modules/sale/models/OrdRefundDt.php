<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "ord_refund_dt".
 *
 * @property string $rfnd_dt_id
 * @property string $refund_id
 * @property string $sol_id
 * @property string $rfnd_type
 * @property string $rfnd_nums
 * @property string $rfnd_amount
 * @property string $remarks
 *
 * @property OrdRefund $refund
 */
class OrdRefundDt extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_refund_dt';
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
            [['refund_id','rfnd_dt_id' ,'sol_id', 'rfnd_type'], 'integer'],
            [['rfnd_nums', 'rfnd_amount'], 'number'],
            [['remarks'], 'string', 'max' => 300],
            [['refund_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdRefund::className(), 'targetAttribute' => ['refund_id' => 'refund_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rfnd_dt_id' => '退款明細PKID',
            'refund_id' => '退款申請pkid',
            'sol_id' => '訂單明細PKID',
            'rfnd_type' => '退款類型',
            'rfnd_nums' => '退/換貨數量',
            'rfnd_amount' => '退款金額',
            'remarks' => '退/換貨原因',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefund()
    {
        return $this->hasOne(OrdRefund::className(), ['refund_id' => 'refund_id']);
    }
}
