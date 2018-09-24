<?php

namespace app\modules\common\models;

use app\models\Common;
use app\models\User;
use app\modules\system\models\AuthItem;
use Yii;

/**
 * This is the model class for table "bs_review_rule_child".
 *
 * @property integer $rule_child_id
 * @property integer $review_rule_id
 * @property integer $rule_child_index
 * @property integer $review_user_id
 * @property integer $review_role_id
 * @property integer $agent_one_id
 * @property integer $agent_two_id
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsReviewRuleChild extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_review_rule_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [[], 'required'],
            [['review_rule_id', 'rule_child_index', 'review_user_id', 'review_role_id', 'agent_one_id', 'agent_two_id', 'create_by', 'update_by'], 'integer'],
            [['review_rule_id', 'rule_child_index', 'review_user_id','create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rule_child_id' => 'Rule Child ID',
            'review_rule_id' => '审核流ID',
            'rule_child_index' => '审批序号',
            'review_user_id' => '审核人',
            'review_role_id' => '审核角色',
            'agent_one_id' => '代理人一',
            'agent_two_id' => '代理人二',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }

    /**
     * 关联审核用户
     * @return \yii\db\ActiveQuery
     */
    public function getReviewUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'review_user_id']);
    }

    /**
     * 关联审核角色
     * @return \yii\db\ActiveQuery
     */
    public function getReviewRole()
    {
        return $this->hasOne(AuthItem::className(), ['name' => "review_role_id"]);
    }

    public function getAgentOne()
    {
        return $this->hasOne(User::className(), ['user_id' => 'agent_one_id']);
    }

    public function getAgentTwo()
    {
        return $this->hasOne(User::className(), ['user_id' => 'agent_two_id']);
    }

}
