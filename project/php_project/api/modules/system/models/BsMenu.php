<?php

namespace app\modules\system\models;

use app\modules\system\models\show\RMenuBtnDtShow;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "bs_menu".
 *
 * @property string $menu_pkid
 * @property string $menu_name
 * @property string $menu_url
 * @property string $p_menu_pkid
 * @property integer $menu_level
 * @property integer $menu_sort
 * @property integer $yn
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property RMenuBtnDt[] $rMenuBtnDts
 * @property RMenuBtnDtCopy[] $rMenuBtnDtCopies
 */
class BsMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['p_menu_pkid', 'menu_level', 'menu_sort', 'yn', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['menu_name', 'menu_url'], 'string', 'max' => 50],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'menu_pkid' => 'Menu Pkid',
            'menu_name' => 'Menu Name',
            'menu_url' => 'Menu Url',
            'p_menu_pkid' => 'P Menu Pkid',
            'menu_level' => 'Menu Level',
            'menu_sort' => 'Menu Sort',
            'yn' => 'Yn',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRMenuBtnDts()
    {
        return $this->hasMany(RMenuBtnDt::className(), ['menu_pkid' => 'menu_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRMenuBtnDtCopies()
    {
        return $this->hasMany(RMenuBtnDtCopy::className(), ['menu_pkid' => 'menu_pkid']);
    }




}
