<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_warr".
 *
 * @property string $l_warr_pkid
 * @property string $l_prt_pkid
 * @property integer $item
 * @property string $wrr_prd
 * @property integer $wrr_fee
 * @property integer $cry
 * @property integer $yn
 */
class LWarr extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_warr';
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
            [['l_prt_pkid', 'yn'], 'required'],
            [['l_prt_pkid', 'item', 'wrr_prd', 'cry', 'yn'], 'integer'],
            [['wrr_fee'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_warr_pkid' => 'L Warr Pkid',
            'l_prt_pkid' => 'L Prt Pkid',
            'item' => 'Item',
            'wrr_prd' => 'Wrr Prd',
            'wrr_fee' => 'Wrr Fee',
            'cry' => 'Cry',
            'yn' => 'Yn',
        ];
    }
}
