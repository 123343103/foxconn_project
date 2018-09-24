<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "r_prt_attr".
 *
 * @property string $r_prt_attr_id
 * @property string $prt_pkid
 * @property string $catg_attr_id
 * @property string $attr_name
 * @property string $attr_val_id
 * @property string $attr_value
 */
class RPrtAttr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_prt_attr';
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
            [['prt_pkid'], 'required'],
            [['prt_pkid', 'catg_attr_id', 'attr_val_id'], 'integer'],
            [['attr_name', 'attr_value'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_prt_attr_id' => 'R Prt Attr ID',
            'prt_pkid' => 'Prt Pkid',
            'catg_attr_id' => 'Catg Attr ID',
            'attr_name' => 'Attr Name',
            'attr_val_id' => 'Attr Val ID',
            'attr_value' => 'Attr Value',
        ];
    }

//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LPrtAttr();
//        $logModel->setAttributes($this->attributes);
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("规格参数日志数据保存失败");
//        }
//        return true;
//    }
}
