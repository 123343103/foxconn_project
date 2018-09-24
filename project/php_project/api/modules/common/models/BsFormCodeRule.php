<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * 表單編碼規則
 * F3858995
 * 2016.10.14
 *
 * @property integer $rule_id
 * @property integer $form_id
 * @property string $rule_one
 * @property string $rule_one_index
 * @property string $rule_two
 * @property string $rule_two_index
 * @property integer $rule_three
 * @property integer $rule_three_index
 * @property integer $rule_four
 * @property integer $rule_five
 * @property integer $serial_number
 * @property string $serial_number_start
 * @property string $start_index
 * @property string $field_digit
 * @property string $field_index
 * @property string $field
 */
class BsFormCodeRule extends Common
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
//            [['serial_number_start'], 'required'],
            [['serial_number_start','rule_one_index','rule_two_index','rule_three_index','start_index','field_begin','field_end'], 'integer'],
            [['form_id','field','field_index','field_digit','field_begin','field_end','code_type'],'safe'],
            [['rule_one', 'rule_two','rule_three','rule_four','rule_four','rule_five'], 'string', 'max' => 15],
            [['code_type'],'default','value' => 10]
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
            'rule_one_index' => '部門代碼',
            'rule_two' => '自定義前綴',
            'rule_two_index' => '自定義前綴',
            'rule_three_index' => '年(位數)',
            'rule_four' => '月',
            'rule_five' => '日',
            'serial_number_start' => '流水號開始數',
            'code_type'=>'编码类型',
            'start_index' => '流水號開始數',
        ];
    }
    public function getForm(){
        return $this->hasOne(BsForm::className(),["form_id"=>"form_id"]);
    }
}
