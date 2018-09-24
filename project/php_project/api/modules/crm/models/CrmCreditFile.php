<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "crm_credit_file".
 *
 * @property string $credit_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $file_type
 * @property integer $is_new
 *
 * @property CrmCreditApply $credit
 */
class CrmCreditFile extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_credit_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['credit_id', 'file_type', 'is_new'], 'integer'],
            [['file_old', 'file_new'], 'string', 'max' => 100],
            [['credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => CrmCreditApply::className(), 'targetAttribute' => ['credit_id' => 'credit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'credit_id' => '账信id',
            'file_old' => '附件原名称',
            'file_new' => '附件新名称',
            'file_type' => '附件类型',
            'is_new' => '是否最新',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredit()
    {
        return $this->hasOne(CrmCreditApply::className(), ['credit_id' => 'credit_id']);
    }
}
