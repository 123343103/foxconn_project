<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "bs_ratio".
 *
 * @property string $ratio_no
 * @property string $ratio_type
 * @property string $upp_num
 * @property string $low_num
 * @property integer $yn
 * @property string $remark
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 */
class BsRatio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_ratio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ratio_no'], 'required'],
            [['ratio_type', 'yn', 'opper'], 'integer'],
            [['upp_num', 'low_num'], 'number'],
            [['opp_date'], 'safe'],
            [['ratio_no'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 250],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ratio_no' => 'Ratio No',
            'ratio_type' => 'Ratio Type',
            'upp_num' => 'Upp Num',
            'low_num' => 'Low Num',
            'yn' => 'Yn',
            'remark' => 'Remark',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }
}