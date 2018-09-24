<?php

namespace app\modules\common\models;

use app\models\Common;
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
class BsForm extends Common
{

    const DATE_YYYY=1;
    const DATE_YY=2;
    const DATE_MM=3;
    const DATE_DD=4;
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
            [['code_type'],'default','value' => 10]
        ];
    }


    public static function getCode($form,$model){
        //默认编码规则
        if(isset($model->codeType)){
            $rule = BsFormCodeRule::find()->where(['form_id'=>$form])->andWhere(['code_type'=>$model->codeType])->one();
        }
        if(empty($rule)){
            $rule = BsFormCodeRule::find()->where(['form_id'=>$form])->andWhere(['code_type'=>10])->one();
        }
        $field_val='';
        $field_index='';
        $field=$rule->field;
        $fieldBegin = $rule->field_begin;
        $fieldEnd = $rule->field_end;
        if(!empty($field) && !empty($fieldBegin) && !empty($fieldEnd)){
            $field_index = $rule->field_index;
            if (!isset($model->$field)) {
                throw new \Exception($field . '字段可能不存在！');
            } else {
                $subStr = isset($model->$field) ? $model->$field : '';
                $field_val = self::getSubstr($subStr, $fieldBegin, $fieldEnd);
            }
        }
        $currentNum = BsFormCodeMax::findOne(['code_rule_id'=>$rule->rule_id]);
        $one  = $rule->rule_one;
        $two  = $rule->rule_two;
        $three= self::getDate($rule->rule_three).self::getDate($rule->rule_four).self::getDate($rule->rule_five);
        $number= $currentNum->current_number;
        $index = [
            $rule->rule_one_index => $one,
            $rule->rule_two_index => $two,
            $rule->rule_three_index => $three,
            $rule->start_index => $number,
            $field_index => $field_val,
        ];
        $code='';
        foreach ($index as $k => $v) {
            $edition[] = $k;
        }
        array_multisort($edition, SORT_ASC, $index);
        foreach ($index as $val){
            $code.=rtrim($val);
        }
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


    public static function getDate($date){
        switch ($date){
            case self::DATE_YYYY; return date("Y");
                break;
            case self::DATE_YY: return substr(date("Y"),-2);
                break;
            case self::DATE_MM: return date("m");
                break;
            case self::DATE_DD: return date("d");
                break;
            default:
                return $date;

        }
    }

    public static function getSubstr($field, $begin, $end){
        if ($begin>$end) {
            return '';
        }
        if ( min([$begin,$end]) < 1 ) {
            return '';
        }
        $data = substr($field, $begin-1, $end-$begin+1);
        $data = empty($data) ? '' : $data;
        return $data;
    }


    public static function getTree($pid = 0)
    {
        static $str = "";
        $tree = self::find()->andWhere(['form_pid' => $pid])->all();
        $selected=false;
        foreach ($tree as $key => $val) {
            $childs = self::find()->where(['form_pid' => $val['form_sid']])->one();
            if(!empty($val['form_id'])) {
                $selected=true;
            }
            $str .= "
               {  
                text :\"". $val['form_name'] . "\",
                form_id :\"". $val['form_id'] . "\",
                type :\"". $val['code_type'] . "\",
                selectable :\"". $selected . "\",";
            if ($childs) {
                $str .= "
                            nodes:[";
                self::getTree($val['form_sid']);
                $str .= "
                            ]},";
            } else {
                $str .= "
                },
                ";
            }
        }
        return $str;
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
