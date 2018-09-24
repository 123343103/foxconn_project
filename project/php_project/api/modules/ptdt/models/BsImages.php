<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "bs_images".
 *
 * @property string $img_id
 * @property string $pdt_PKID
 * @property integer $img_type
 * @property string $fl_old
 * @property string $fl_new
 * @property integer $orderby
 */
class BsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_images';
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
            [['pdt_pkid', 'img_type','fl_new', 'orderby'], 'required'],
            [['img_id', 'pdt_pkid', 'img_type', 'orderby'], 'integer'],
            [['fl_old', 'fl_new'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'img_id' => 'Img ID',
            'pdt_pkid' => 'Pdt  Pkid',
            'img_type' => 'Img Type',
            'fl_old' => 'Fl Old',
            'fl_new' => 'Fl New',
            'orderby' => 'Orderby',
        ];
    }


//    public function afterSave($insert,$changedAttributes){
//        $logModel=new LImages();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_pdt_pkid=$this->pdt_pkid;
//        $logModel->yn=0;
//        $logModel->fl_old=$logModel->fl_new;
//        return $logModel->save();
//    }
}
