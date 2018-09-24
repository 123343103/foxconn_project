<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_part".
 *
 * @property string $part_code
 * @property string $wh_code
 * @property string $part_name
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $opp_ip
 * @property string $YN
 */
class BsPart extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_part';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wh_code', 'part_code', 'part_name'], 'required'],
            [['YN'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['wh_code', 'part_code', 'OPPER'], 'string', 'max' => 30],
            [['part_name'], 'string', 'max' => 200],
            [['remarks'], 'string', 'max' => 250],
            [['opp_ip'], 'string', 'max' => 20],
            [['part_code'], 'unique'],
            [['part_name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'part_id' => 'Part ID',
            'wh_code' => 'Wh Code',
            'part_code' => 'Part Code',
            'part_name' => 'Part Name',
            'YN' => 'Yn',
            'remarks' => 'Remarks',
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWhCode()
    {
        return $this->hasOne(BsWh::className(), ['wh_code' => 'wh_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getBsSt()
//    {
//        return $this->hasMany(BsSt::className(), ['part_code' => 'part_code']);
//    }

}
