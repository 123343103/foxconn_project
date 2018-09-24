<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_menu_btn".
 *
 * @property string $mn_btn_pkid
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property RMenuBtnDt[] $rMenuBtnDts
 * @property RMenuBtnDtCopy[] $rMenuBtnDtCopies
 */
class RMenuBtn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_menu_btn';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mn_btn_pkid' => 'Mn Btn Pkid',
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
        return $this->hasMany(RMenuBtnDt::className(), ['mn_btn_pkid' => 'mn_btn_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRMenuBtnDtCopies()
    {
        return $this->hasMany(RMenuBtnDtCopy::className(), ['mn_btn_pkid' => 'mn_btn_pkid']);
    }
}
