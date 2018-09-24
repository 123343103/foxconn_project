<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "off_apply".
 *
 * @property string $off_app_id
 * @property integer $rs_id
 * @property string $other_reason
 * @property integer $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property OffReason $rs
 * @property OffApplyDt[] $offApplyDts
 * @property OffFile[] $offFiles
 */
class OffApply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'off_apply';
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
            [['rs_id', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['other_reason'], 'string', 'max' => 200],
            [['opp_ip'], 'string', 'max' => 15],
            [['rs_id'], 'exist', 'skipOnError' => true, 'targetClass' => OffReason::className(), 'targetAttribute' => ['rs_id' => 'rs_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'off_app_id' => 'Off App ID',
            'rs_id' => 'Rs ID',
            'other_reason' => 'Other Reason',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRs()
    {
        return $this->hasOne(OffReason::className(), ['rs_id' => 'rs_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffApplyDts()
    {
        return $this->hasMany(OffApplyDt::className(), ['off_app_id' => 'off_app_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffFiles()
    {
        return $this->hasMany(OffFile::className(), ['off_app_id' => 'off_app_id']);
    }
}
