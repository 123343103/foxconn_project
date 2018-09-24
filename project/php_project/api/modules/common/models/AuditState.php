<?php

namespace app\modules\common\models;

use Yii;

/**
 * This is the model class for table "audit_state".
 *
 * @property integer $audit_id
 * @property string $audit_name
 * @property string $audit_marks
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 */
class AuditState extends \app\models\Common
{
    const STATUS_DEL = 0;   //删除
    const STATUS_DEFAULT = 10;   //待提交
    const STATUS_UNDER_REVIEW = 11;   //审核中
    const STATUS_REVIEW_OVER = 12;   //审核完成
    const STATUS_REVIEW_REJECT = 13;   //驳回
    const STATUS_CANCLE_OFFER = 14;   //取消报价
    const STATUS_WAIT_ORRER = 15;   //待报价
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audit_state';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['audit_id'], 'required'],
            [['audit_id', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['audit_name'], 'string', 'max' => 10],
            [['audit_marks'], 'string', 'max' => 200],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'audit_id' => '狀態pkid',
            'audit_name' => '狀態名稱',
            'audit_marks' => '狀態說明',
            'opper' => '修改人',
            'opp_date' => '修改時間',
            'opp_ip' => '修改人IP',
        ];
    }
}
