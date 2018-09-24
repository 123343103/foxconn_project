<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_role_mn_btn".
 *
 * @property string $role_pkid
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property BsRole $rolePk
 * @property RRoleMnBtnDt[] $rRoleMnBtnDts
 * @property RRoleMnBtnDtCopy[] $rRoleMnBtnDtCopies
 */
class RRoleMnBtn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_role_mn_btn';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_pkid'], 'required'],
            [['role_pkid', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['opp_ip'], 'string', 'max' => 15],
            [['role_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsRole::className(), 'targetAttribute' => ['role_pkid' => 'role_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_pkid' => 'Role Pkid',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
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
    public function getRRoleMnBtnDts()
    {
        return $this->hasMany(RRoleMnBtnDt::className(), ['role_pkid' => 'role_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRRoleMnBtnDtCopies()
    {
        return $this->hasMany(RRoleMnBtnDtCopy::className(), ['role_pkid' => 'role_pkid']);
    }
}
