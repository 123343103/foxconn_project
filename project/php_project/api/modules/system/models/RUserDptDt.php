<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_user_dpt_dt".
 *
 * @property integer $user_id
 * @property string $dpt_pkid
 *
 * @property RUserDpt $user
 */
class RUserDptDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_user_dpt_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'dpt_pkid'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => RUserDpt::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用戶pkid',
            'dpt_pkid' => '部門pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RUserDpt::className(), ['user_id' => 'user_id']);
    }
}
