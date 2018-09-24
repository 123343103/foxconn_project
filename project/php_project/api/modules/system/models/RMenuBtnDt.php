<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_menu_btn_dt".
 *
 * @property string $dt_pkid
 * @property string $menu_pkid
 * @property string $btn_pkid
 * @property string $mn_btn_pkid
 *
 * @property RMenuBtn $mnBtnPk
 * @property BsMenu $menuPk
 * @property BsBtn $btnPk
 * @property RRoleMnBtnDt[] $rRoleMnBtnDts
 * @property RRoleMnBtnDtCopy[] $rRoleMnBtnDtCopies
 */
class RMenuBtnDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_menu_btn_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_pkid', 'btn_pkid', 'mn_btn_pkid'], 'integer'],
            [['mn_btn_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => RMenuBtn::className(), 'targetAttribute' => ['mn_btn_pkid' => 'mn_btn_pkid']],
            [['menu_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsMenu::className(), 'targetAttribute' => ['menu_pkid' => 'menu_pkid']],
            [['btn_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsBtn::className(), 'targetAttribute' => ['btn_pkid' => 'btn_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dt_pkid' => 'Dt Pkid',
            'menu_pkid' => 'Menu Pkid',
            'btn_pkid' => 'Btn Pkid',
            'mn_btn_pkid' => 'Mn Btn Pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMnBtnPk()
    {
        return $this->hasOne(RMenuBtn::className(), ['mn_btn_pkid' => 'mn_btn_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuPk()
    {
        return $this->hasOne(BsMenu::className(), ['menu_pkid' => 'menu_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBtnPk()
    {
        return $this->hasOne(BsBtn::className(), ['btn_pkid' => 'btn_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRRoleMnBtnDts()
    {
        return $this->hasMany(RRoleMnBtnDt::className(), ['dt_pkid' => 'dt_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRRoleMnBtnDtCopies()
    {
        return $this->hasMany(RRoleMnBtnDtCopy::className(), ['dt_pkid' => 'dt_pkid']);
    }
}
