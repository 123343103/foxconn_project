<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_community_child".
 *
 * @property integer $commu_iid
 * @property integer $commu_ID
 * @property string $commuc_datetime
 * @property integer $commuc_person
 * @property integer $upvote_num
 * @property integer $read_num
 * @property integer $share_num
 * @property string $commuc_cpa
 * @property string $commuc_cost
 * @property integer $commuc_perples
 * @property integer $commuc_add
 * @property integer $send_num
 * @property integer $effect_reply_num
 * @property string $commuc_remark
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $udpate_at
 * @property string $cust_intent
 * @property string $private_commu_start
 * @property string $private_commu_end
 * @property string $active_summary
 *
 * @property CrmCommunity $commu
 */
class CrmCommunityChild extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_community_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commu_ID', 'commuc_person', 'upvote_num', 'read_num', 'share_num', 'commuc_perples', 'commuc_add', 'send_num', 'effect_reply_num', 'create_by', 'update_by'], 'integer'],
            [['commuc_datetime', 'create_at', 'udpate_at', 'private_commu_start_time', 'private_commu_end_time'], 'safe'],
            [['commuc_cpa', 'commuc_cost'], 'number'],
            [['commuc_remark', 'interact_summary'], 'string', 'max' => 200],
            [['cust_intent'], 'string', 'max' => 100],
            [['commu_ID'], 'exist', 'skipOnError' => true, 'targetClass' => CrmCommunity::className(), 'targetAttribute' => ['commu_ID' => 'commu_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'commu_iid' => 'Commu Iid',
            'commu_ID' => 'Commu  ID',
            'commuc_datetime' => 'Commuc Datetime',
            'commuc_person' => 'Commuc Person',
            'upvote_num' => 'Upvote Num',
            'read_num' => 'Read Num',
            'share_num' => 'Share Num',
            'commuc_cpa' => 'Commuc Cpa',
            'commuc_cost' => 'Commuc Cost',
            'commuc_perples' => 'Commuc Perples',
            'commuc_add' => 'Commuc Add',
            'send_num' => 'Send Num',
            'effect_reply_num' => 'Effect Reply Num',
            'commuc_remark' => 'Commuc Remark',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'udpate_at' => 'Udpate At',
            'cust_intent' => 'Cust Intent',
            'private_commu_start' => 'Private Commu Start',
            'private_commu_end' => 'Private Commu End',
            'active_summary' => 'Active Summary',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommu()
    {
        return $this->hasOne(CrmCommunity::className(), ['commu_ID' => 'commu_ID']);
    }
}
