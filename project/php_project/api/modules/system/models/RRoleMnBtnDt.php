<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_role_mn_btn_dt".
 *
 * @property string $role_pkid
 * @property string $dt_pkid
 *
 * @property RMenuBtnDt $dtPk
 * @property RRoleMnBtn $rolePk
 */
class RRoleMnBtnDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_role_mn_btn_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_pkid', 'dt_pkid'], 'integer'],
            [['dt_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => RMenuBtnDt::className(), 'targetAttribute' => ['dt_pkid' => 'dt_pkid']],
            [['role_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => RRoleMnBtn::className(), 'targetAttribute' => ['role_pkid' => 'role_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_pkid' => 'Role Pkid',
            'dt_pkid' => 'Dt Pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDtPk()
    {
        return $this->hasOne(RMenuBtnDt::className(), ['dt_pkid' => 'dt_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRolePk()
    {
        return $this->hasOne(RRoleMnBtn::className(), ['role_pkid' => 'role_pkid']);
    }
}
