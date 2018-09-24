<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "bs_bank_check".
 *
 * @property string $rbo_id
 * @property integer $state
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property RBankOrder[] $rBankOrders
 */
class BsBankCheck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_bank_check';
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
            [['state'], 'integer'],
            [['opp_date'], 'safe'],
            [['opper'], 'string', 'max' => 30],
            [['opp_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rbo_id' => 'Rbo ID',
            'state' => 'State',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRBankOrders()
    {
        return $this->hasMany(RBankOrder::className(), ['rbo_id' => 'rbo_id']);
    }
}
