<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\crm\models\CrmCreditMaintain;
use Yii;

/**
 * This is the model class for table "price_pay".
 *
 * @property string $price_id
 * @property integer $pac_id
 * @property integer $pay_type
 * @property integer $pat_id
 * @property integer $credit_id
 * @property integer $stag_times
 * @property string $stag_date
 * @property string $stag_cost
 * @property string $stag_desrition
 *
 * @property PriceInfo $price
 */
class PricePay extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.price_pay';
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
            [['price_id', 'pac_id', 'pay_type', 'pat_id', 'credit_id', 'stag_times'], 'integer'],
            [['stag_date'], 'safe'],
            [['stag_cost'], 'number'],
            [['stag_desrition'], 'string', 'max' => 200],
            [['price_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceInfo::className(), 'targetAttribute' => ['price_id' => 'price_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_id' => 'Price ID',
            'pac_id' => 'Pac ID',
            'pay_type' => 'Pay Type',
            'pat_id' => 'Pat ID',
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
    public function getPrice()
    {
        return $this->hasOne(PriceInfo::className(), ['price_id' => 'price_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 信用额度类型
     */
    public function getCreditMaintain(){
        return $this->hasOne(CrmCreditMaintain::className(),['id'=>'credit_id']);
    }
}
