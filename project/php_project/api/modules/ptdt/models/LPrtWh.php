<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "l_prt_wh".
 *
 * @property string $l_prt_pkid
 * @property string $wh_id
 */
class LPrtWh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_prt_wh';
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
            [['l_prt_pkid', 'wh_id'], 'required'],
            [['l_prt_pkid', 'wh_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_prt_pkid' => 'L Prt Pkid',
            'wh_id' => 'Wh ID',
        ];
    }
}
