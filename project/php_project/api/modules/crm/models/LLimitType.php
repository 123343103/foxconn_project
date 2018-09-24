<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "l_limit_type".
 *
 * @property integer $l_limit_id
 * @property integer $l_credit_id
 * @property string $credit_type
 * @property string $credit_limit
 * @property string $approval_limit
 * @property string $validity_date
 * @property string $file_old
 * @property string $file_new
 */
class LLimitType extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_limit_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_limit_id'], 'required'],
            [['l_limit_id', 'l_credit_id'], 'integer'],
            [['credit_limit', 'approval_limit'], 'number'],
            [['validity_date'], 'safe'],
            [['credit_type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_limit_id' => '临时表额度ID',
            'l_credit_id' => '关联账信申请表',
            'credit_type' => '额度类型',
            'credit_limit' => '申请额度',
            'approval_limit' => '批复额度',
            'validity_date' => '额度有效期',
        ];
    }
}
