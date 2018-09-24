<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "r_pdt_pdt".
 *
 * @property string $r_pdt_pdt_id
 * @property string $pdt_pkid
 * @property string $r_pdt_pkid
 */
class RPdtPdt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_pdt_pdt';
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
            [['pdt_pkid', 'r_pdt_pkid'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_pdt_pdt_id' => 'R Pdt Pdt ID',
            'pdt_pkid' => 'Pdt Pkid',
            'r_pdt_pkid' => 'R Pdt Pkid',
        ];
    }

//    public function afterSave($insert,$changedAttributes){
//        $logModel=new LPdtPdt();
//        $logModel->setAttributes($this->attributes);
//        $logModel->yn=0;
//        return $logModel->save();
//    }
}
