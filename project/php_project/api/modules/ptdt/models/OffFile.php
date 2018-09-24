<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "off_file".
 *
 * @property string $off_app_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $sorts
 *
 * @property OffApply $offApp
 */
class OffFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'off_file';
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
            [['off_app_id', 'sorts'], 'integer'],
            [['file_old', 'file_new'], 'string', 'max' => 50],
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
            'file_old' => 'File Old',
            'file_new' => 'File New',
            'sorts' => 'Sorts',
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
