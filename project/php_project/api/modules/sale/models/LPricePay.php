<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "l_price_pay".
 *
 * @property string $l_price_id
 * @property integer $pac_id
 * @property integer $pay_type
 * @property integer $pat_id
 * @property integer $credit_id
 * @property integer $stag_times
 * @property string $stag_date
 * @property string $stag_cost
 * @property string $stag_desrition
 *
 * @property LPriceInfo $lPrice
 */
class LPricePay extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_price_pay';
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
            [['l_price_id', 'pac_id', 'pay_type', 'pat_id', 'credit_id', 'stag_times'], 'integer'],
            [['stag_date'], 'safe'],
            [['stag_cost'], 'number'],
            [['stag_desrition'], 'string', 'max' => 200],
            [['l_price_id'], 'exist', 'skipOnError' => true, 'targetClass' => LPriceInfo::className(), 'targetAttribute' => ['l_price_id' => 'l_price_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_price_id' => '報價日志pkid，由此產生審核流程',
            'pac_id' => '支付方式:信用額度、預付款。erp. bs_payment',
            'pay_type' => '支付類型:是否全款，0:全額；1：分期.',
            'pat_id' => '付款條件,bs_pay_condition,月結30天',
            'credit_id' => '額度類型，自保,erp.crm_credit_maintain',
            'stag_times' => '第几期，除預付款外都為1',
            'stag_date' => '約定每期付款日期，除預付款外都為空',
            'stag_cost' => '約定每期付款費用',
            'stag_desrition' => '備注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLPrice()
    {
        return $this->hasOne(LPriceInfo::className(), ['l_price_id' => 'l_price_id']);
    }
}
