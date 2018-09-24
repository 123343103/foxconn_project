<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_user_wh_dt".
 *
 * @property integer $user_id
 * @property string $wh_pkid
 * @property string $part_id
 *
 * @property RUserWh $user
 */
class RUserWhDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_user_wh_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'wh_pkid', 'part_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => RUserWh::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用戶pkid',
            'wh_pkid' => '倉庫pkid',
            'part_id' => '分區ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RUserWh::className(), ['user_id' => 'user_id']);
    }
}