<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * F3858995
 * 审批条件
 *
 * @property integer $condition_id
 * @property integer $rule_child_id
 * @property integer $column
 * @property string $condition_logic
 * @property integer $condtion_value
 */
class BsReviewCondition extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_review_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rule_child_id', 'column', 'condition_value'], 'integer'],
            [['column'], 'required'],
            [['condition_logic'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'condition_id' => 'Condition ID',
            'rule_child_id' => 'Rule Child ID',
            'column' => 'Column',
            'condition_logic' => 'Condition Logic',
            'condition_value' => 'Condtion Value',
        ];
    }
}
