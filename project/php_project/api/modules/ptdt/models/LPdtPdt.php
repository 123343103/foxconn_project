<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "l_pdt_pdt".
 *
 * @property string $l_pdt_pdt_id
 * @property string $l_pdt_pkid
 * @property string $r_l_pdt_pkid
 * @property integer $yn
 */
class LPdtPdt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_pdt_pdt';
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
            [['l_pdt_pkid', 'r_l_pdt_pkid', 'yn'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_pdt_pdt_id' => 'L Pdt Pdt ID',
            'l_pdt_pkid' => 'L Pdt Pkid',
            'r_l_pdt_pkid' => 'R L Pdt Pkid',
            'yn' => 'Yn',
        ];
    }
}
