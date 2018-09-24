<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "crm_accompany".
 *
 * @property string $id
 * @property integer $type
 * @property string $pid
 * @property string $acc_id
 * @property string $acc_mobile
 */
class CrmAccompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_accompany';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'pid', 'acc_id', 'acc_mobile'], 'required'],
            [['type', 'pid', 'acc_id'], 'integer'],
            [['acc_mobile'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键',
            'type' => '1:计划陪同;2:记录陪同',
            'pid' => '父id(关联erp.crm_visit_plan/erp.crm_visit_info_child)',
            'acc_id' => '陪同人(关联erp.hr_satff)',
            'acc_mobile' => '陪同人电话',
        ];
    }
}
