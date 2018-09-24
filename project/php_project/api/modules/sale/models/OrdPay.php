<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "ord_pay".
 *
 * @property string $ord_pay_id
 * @property string $ord_id
 * @property integer $pac_id
 * @property integer $pay_type
 * @property integer $pat_id
 * @property integer $yn_pay
 * @property integer $credit_id
 * @property integer $stag_times
 * @property string $stag_date
 * @property string $stag_cost
 * @property string $stag_desrition
 *
 * @property OrdInfo $ord
 * @property OrdPayment[] $ordPayments
 */
class OrdPay extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.ord_pay';
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
            [['ord_id', 'pac_id', 'pay_type', 'pat_id', 'yn_pay', 'credit_id', 'stag_times'], 'integer'],
            [['stag_date'], 'safe'],
            [['stag_cost'], 'number'],
            [['stag_desrition'], 'string', 'max' => 200],
            [['ord_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdInfo::className(), 'targetAttribute' => ['ord_id' => 'ord_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ord_pay_id' => 'Ord Pay ID',
            'ord_id' => 'Ord ID',
            'pac_id' => 'Pac ID',
            'pay_type' => 'Pay Type',
            'pat_id' => 'Pat ID',
            'yn_pay' => 'Yn Pay',
            'credit_id' => 'Credit ID',
            'stag_times' => 'Stag Times',
            'stag_date' => 'Stag Date',
            'stag_cost' => 'Stag Cost',
            'stag_desrition' => 'Stag Desrition',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrd()
    {
        return $this->hasOne(OrdInfo::className(), ['ord_id' => 'ord_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdPayments()
    {
        return $this->hasMany(OrdPayment::className(), ['ord_pay_id' => 'ord_pay_id']);
    }
}
