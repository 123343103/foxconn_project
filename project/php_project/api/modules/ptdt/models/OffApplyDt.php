<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "off_apply_dt".
 *
 * @property string $off_app_id
 * @property string $l_pdt_pkid
 *
 * @property OffApply $offApp
 */
class OffApplyDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'off_apply_dt';
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
            [['off_app_id', 'l_pdt_pkid'], 'integer'],
            [['off_app_id'], 'exist', 'skipOnError' => true, 'targetClass' => OffApply::className(), 'targetAttribute' => ['off_app_id' => 'off_app_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'off_app_id' => 'Off App ID',
            'l_pdt_pkid' => 'L Pdt Pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffApp()
    {
        return $this->hasOne(OffApply::className(), ['off_app_id' => 'off_app_id']);
    }
}
