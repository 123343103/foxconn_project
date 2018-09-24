<?php

namespace app\modules\common\models;

use Yii;

/**
 * 基本表單
 * F3858995
 * 2016.10.14
 *
 * @property integer $form_id
 * @property string $form_code
 * @property string $form_name
 */
class BsForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_code', 'form_name'], 'string', 'max' => 255],
        ];
    }

    
    public static function getCode($form){
        $rule = BsFormCodeRule::findOne(['form_id'=>$form]);
        $currentNum = BsFormCodeMax::findOne(['code_rule_id'=>$rule->rule_id]);
        $code = $rule->rule_one.$rule->rule_two;
        if( $rule->rule_three != 0 ){
            $code .= substr(date("Y"),-$rule->rule_three);
        }
        if( $rule->rule_four){
            $code .= date("m");
        }
        if( $rule->rule_five){
            $code .= date("d");
        }
        $code .= $currentNum->current_number;
        //使當前流水號加1
        $length = strlen($currentNum->current_number);
        $numLength = strlen( intval($newNumber = $currentNum->current_number+1));
        $index = $length-$numLength;
        if( $index> 0){
            for($i=1;$i<=$index;$i++){
                $newNumber = "0".strval($newNumber);
            }
        }
        $currentNum->current_number = $newNumber;
        $currentNum->save();
        return $code;

    } 
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'form_id' => '主鍵ID',
            'form_code' => '表單代碼',
            'form_name' => '表單名稱',
        ];
    }
}
