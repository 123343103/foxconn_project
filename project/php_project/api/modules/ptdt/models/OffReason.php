<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "off_reason".
 *
 * @property integer $rs_id
 * @property string $rs_mark
 * @property string $opper
 * @property string $op_date
 * @property string $opp_ip
 *
 * @property OffApply[] $offApplies
 */
class OffReason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'off_reason';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opper'], 'integer'],
            [['op_date'], 'safe'],
            [['rs_mark'], 'string', 'max' => 200],
            [['opp_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rs_id' => 'Rs ID',
            'rs_mark' => 'Rs Mark',
            'opper' => 'Opper',
            'op_date' => 'Op Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffApplies()
    {
        return $this->hasMany(OffApply::className(), ['rs_id' => 'rs_id']);
    }
}
