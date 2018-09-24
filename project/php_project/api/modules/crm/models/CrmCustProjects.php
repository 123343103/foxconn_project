<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_cust_projects".
 *
 * @property integer $pro_id
 * @property integer $cust_id
 * @property string $pro_code
 * @property string $pro_sname
 * @property string $pro_child
 * @property string $pro_issue
 * @property string $pro_schedule
 * @property string $pro_close
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCustProjects extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_cust_projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_id'], 'required'],
            [['pro_id', 'cust_id'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['pro_code'], 'string', 'max' => 30],
            [['pro_sname', 'pro_child'], 'string', 'max' => 60],
            [['pro_issue', 'pro_schedule', 'pro_close'], 'string', 'max' => 200],
            [['create_by', 'update_by'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pro_id' => 'id',
            'cust_id' => '客戶id',
            'pro_code' => '項目專案編號',
            'pro_sname' => '專案名稱',
            'pro_child' => '子專案',
            'pro_issue' => '問題描述',
            'pro_schedule' => '進度說明',
            'pro_close' => '結案說明',
            'create_by' => '創建人',
            'create_at' => '創建日期',
            'update_by' => '更新人',
            'update_at' => '更新时间',
        ];
    }
}
