<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "l_crm_credit_file".
 *
 * @property string $l_credit_id
 * @property string $credit_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $file_type
 * @property integer $is_new
 *
 * @property LCrmCreditApply $lCredit
 */
class LCrmCreditFile extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_crm_credit_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_credit_id', 'file_type', 'is_new'], 'integer'],
            [['file_old', 'file_new'], 'string', 'max' => 100],
            [['l_credit_id'], 'exist', 'skipOnError' => true, 'targetClass' => LCrmCreditApply::className(), 'targetAttribute' => ['l_credit_id' => 'l_credit_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_credit_id' => '账信id',
            'file_old' => '附件原名称',
            'file_new' => '附件新名称',
            'file_type' => '附件类型',
            'is_new' => '是否最新',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLCredit()
    {
        return $this->hasOne(LCrmCreditApply::className(), ['l_credit_id' => 'l_credit_id']);
    }
}
