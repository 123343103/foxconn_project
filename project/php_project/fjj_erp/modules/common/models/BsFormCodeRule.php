<?php

namespace app\modules\common\models;

use Yii;

/**
 * 表單編碼規則
 * F3858995
 * 2016.10.14
 *
 * @property integer $rule_id
 * @property integer $form_id
 * @property string $rule_one
 * @property string $rule_two
 * @property integer $rule_three
 * @property integer $rule_four
 * @property integer $rule_five
 * @property integer $serial_number
 * @property string $serial_number_start
 */
class BsFormCodeRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_form_code_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['serial_number_start'], 'required'],
            [[ 'rule_three'],"in","range"=>[0,1,2,3,4]],
            [[ 'rule_four', 'rule_five'], 'in',"range"=>range(0,1)],
            [['serial_number_start'], 'integer'],
            [['form_id'],'safe'],
            [['rule_one', 'rule_two'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rule_id' => 'Rule ID',
            'form_id' => 'Form ID',
            'rule_one' => '部門代碼',
            'rule_two' => '自定義前綴',
            'rule_three' => '年(位數)',
            'rule_four' => '月',
            'rule_five' => '日',
            'serial_number_start' => '流水號開始數',
        ];
    }
    public function getForm(){
        return $this->hasOne(BsForm::className(),["form_id"=>"form_id"]);
    }
}
