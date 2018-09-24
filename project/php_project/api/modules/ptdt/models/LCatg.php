<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "l_catg".
 *
 * @property string $catg_id
 * @property string $catg_r_id
 * @property string $opper
 * @property string $op_date
 * @property string $opp_ip
 * @property integer $yn
 */
class LCatg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_catg';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catg_id', 'catg_r_id', 'opper', 'yn'], 'integer'],
            [['op_date'], 'safe'],
            [['opp_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'catg_id' => 'Catg ID',
            'catg_r_id' => 'Catg R ID',
            'opper' => 'Opper',
            'op_date' => 'Op Date',
            'opp_ip' => 'Opp Ip',
            'yn' => 'Yn',
        ];
    }
}
