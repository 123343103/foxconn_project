<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_details".
 *
 * @property string $l_dt_pkid
 * @property string $l_pdt_pkid
 * @property string $l_prt_pkid
 * @property string $details
 * @property integer $yn
 */
class LDetails extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_details';
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
            [['l_pdt_pkid'], 'required'],
            [['l_pdt_pkid', 'l_prt_pkid', 'yn'], 'integer'],
            [['details'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_dt_pkid' => 'L Dt Pkid',
            'l_pdt_pkid' => 'L Pdt Pkid',
            'l_prt_pkid' => 'L Prt Pkid',
            'details' => 'Details',
            'yn' => 'Yn',
        ];
    }
}
