<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_deliv".
 *
 * @property string $log_id
 * @property string $l_prt_pkid
 * @property integer $item
 * @property integer $country_no
 * @property string $country_name
 * @property integer $province_no
 * @property string $province_name
 * @property integer $city_no
 * @property string $city_name
 * @property integer $yn
 */
class LDeliv extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_deliv';
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
            [['l_prt_pkid'], 'required'],
            [['l_prt_pkid', 'item', 'country_no', 'province_no', 'city_no', 'yn'], 'integer'],
            [['country_name', 'province_name'], 'string', 'max' => 30],
            [['city_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'l_prt_pkid' => 'L Prt Pkid',
            'item' => 'Item',
            'country_no' => 'Country No',
            'country_name' => 'Country Name',
            'province_no' => 'Province No',
            'province_name' => 'Province Name',
            'city_no' => 'City No',
            'city_name' => 'City Name',
            'yn' => 'Yn',
        ];
    }
}
