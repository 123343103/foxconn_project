<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_correspondent_bank".
 *
 * @property integer $id
 * @property integer $cust_id
 * @property string $bank_name
 * @property string $account_num
 * @property string $curremt_project
 * @property string $remark
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCorrespondentBank extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_correspondent_bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id'], 'integer'],
//            [['create_at', 'update_at'], 'safe'],
            [['bank_name', 'account_num', 'curremt_project'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cust_id' => '关联客户ID',
            'bank_name' => '银行名称',
            'account_num' => '银行账号',
            'curremt_project' => '往来项目',
            'remark' => '备注',
//            'create_by' => '创建人',
//            'create_at' => '创建时间',
//            'update_by' => '更新人',
//            'update_at' => '更新时间',
        ];
    }
}
