<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_industrytype".
 *
 * @property integer $idt_id
 * @property string $idt_code
 * @property string $idt_sname
 * @property string $idt_othername
 * @property string $idt_status
 */
class BsIndustrytype extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_industrytype';
    }

    public static function getIndustryType()
    {
        $list = static::find()->select("idt_id,idt_sname")->where(['idt_status'=>10])->asArray()->all();
        return isset($list) ? $list:[];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idt_id'], 'required'],
//            [['idt_id'], 'integer'],
            [['idt_code', 'idt_sname', 'idt_othername'], 'string', 'max' => 20],
            [['idt_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idt_id' => 'Idt ID',
            'idt_code' => 'Idt Code',
            'idt_sname' => 'Idt Sname',
            'idt_othername' => 'Idt Othername',
            'idt_status' => 'Idt Status',
        ];
    }

}
