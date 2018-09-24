<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * 表單當前流水號
 * F3858995
 * 2016.10.14
 * 
 * @property integer $number_id
 * @property integer $code_rule_id
 * @property integer $current_number
 */
class BsFormCodeMax extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_form_code_max';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['code_rule_id', 'current_number'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'number_id' => '主鍵ID',
            'code_rule_id' => '編碼規則ID',
            'current_number' => '當前流水號',
        ];
    }
}
