<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "l_credit_file".
 *
 * @property integer $l_credit_id
 * @property string $file_old
 * @property string $file_new
 */
class LCreditFile extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_credit_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_credit_id'], 'integer'],
            [['file_old', 'file_new'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_credit_id' => '关联账信申请表',
            'file_old' => '附件原名称',
            'file_new' => '附件新名称',
        ];
    }
}
