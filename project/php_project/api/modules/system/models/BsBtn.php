<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "bs_btn".
 *
 * @property string $btn_pkid
 * @property string $btn_no
 * @property string $btn_name
 * @property integer $btn_yn
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property RMenuBtnDt[] $rMenuBtnDts
 */
class BsBtn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_btn';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['btn_yn', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['btn_no'], 'string', 'max' => 20],
            [['btn_no'], 'unique', 'targetAttribute' => 'btn_no', 'message' => '{attribute}已经存在'],
            [['btn_name'], 'string', 'max' => 200],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'btn_pkid' => 'Btn Pkid',
            'btn_no' => '按钮名称',
            'btn_name' => 'Btn Name',
            'btn_yn' => 'Btn Yn',
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
        return $this->hasMany(RMenuBtnDt::className(), ['btn_pkid' => 'btn_pkid']);
    }
}
