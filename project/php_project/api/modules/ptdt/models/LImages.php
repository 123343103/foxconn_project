<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "l_images".
 *
 * @property string $l_img_id
 * @property string $l_pdt_pkid
 * @property integer $img_type
 * @property string $fl_old
 * @property string $fl_new
 * @property integer $orderby
 * @property integer $yn
 */
class LImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_images';
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
            [['l_pdt_pkid', 'img_type', 'fl_old', 'fl_new', 'orderby', 'yn'], 'required'],
            [['l_pdt_pkid', 'img_type', 'orderby', 'yn'], 'integer'],
            [['fl_old', 'fl_new'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_img_id' => 'L Img ID',
            'l_pdt_pkid' => 'L Pdt Pkid',
            'img_type' => 'Img Type',
            'fl_old' => 'Fl Old',
            'fl_new' => 'Fl New',
            'orderby' => 'Orderby',
            'yn' => 'Yn',
        ];
    }
}
