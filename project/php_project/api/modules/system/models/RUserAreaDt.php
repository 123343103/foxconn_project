<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_user_area_dt".
 *
 * @property integer $user_id
 * @property string $area_pkid
 *
 * @property RUserArea $user
 */
class RUserAreaDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_user_area_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'area_pkid'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => RUserArea::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用戶pkid',
            'area_pkid' => '廠區pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RUserArea::className(), ['user_id' => 'user_id']);
    }
}
