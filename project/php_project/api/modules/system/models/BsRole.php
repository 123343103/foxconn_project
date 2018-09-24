<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "bs_role".
 *
 * @property string $role_pkid
 * @property string $role_no
 * @property string $role_name
 * @property string $role_des
 * @property integer $role_state
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property RRoleMnBtn $rRoleMnBtn
 * @property RUserRoleDt[] $rUserRoleDts
 */
class BsRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_des'], 'string'],
            [['role_state', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['role_no'], 'string', 'max' => 20],
            [['role_name'], 'string', 'max' => 200],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_pkid' => 'Role Pkid',
            'role_no' => 'Role No',
            'role_name' => 'Role Name',
            'role_des' => 'Role Des',
            'role_state' => 'Role State',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRRoleMnBtn()
    {
        return $this->hasOne(RRoleMnBtn::className(), ['role_pkid' => 'role_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRUserRoleDts()
    {
        return $this->hasMany(RUserRoleDt::className(), ['role_pkid' => 'role_pkid']);
    }
}
