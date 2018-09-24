<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_user_role_dt".
 *
 * @property integer $user_id
 * @property string $role_pkid
 *
 * @property BsRole $rolePk
 * @property RUserRole $user
 */
class RUserRoleDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_user_role_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'role_pkid'], 'integer'],
            [['role_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsRole::className(), 'targetAttribute' => ['role_pkid' => 'role_pkid']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => RUserRole::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用戶pkid',
            'role_pkid' => '角色pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolePk()
    {
        return $this->hasOne(BsRole::className(), ['role_pkid' => 'role_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RUserRole::className(), ['user_id' => 'user_id']);
    }
}
