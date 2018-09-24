<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_tripapply_l".
 *
 * @property string $stal_id
 * @property string $stah_id
 * @property string $stcl_id
 * @property string $stcl_count
 * @property string $stcl_description
 * @property string $stcl_dateqty
 * @property string $stcl_plan_tripcostqty
 * @property string $stcl_tripcostqty
 * @property string $stcl_diffqty
 * @property string $stcl_diffdescription
 * @property string $stcl_realtripcostqty
 * @property string $stcl_remark
 */
class SaleTripapplyChild extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_tripapply_l';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stal_id'], 'required'],
            [['stal_id', 'stah_id', 'stcl_id'], 'integer'],
            [['stcl_count', 'stcl_dateqty', 'stcl_plan_tripcostqty', 'stcl_tripcostqty', 'stcl_diffqty', 'stcl_realtripcostqty'], 'number'],
            [['stcl_description', 'stcl_diffdescription', 'stcl_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stal_id' => 'Stal ID',
            'stah_id' => '關聯銷售出差費用支出申請主表',
            'stcl_id' => '費用ID 關聯費用類型表',
            'stcl_count' => '費用標準金額',
            'stcl_description' => '出差線路描述(用於日報銷明細)',
            'stcl_dateqty' => '出差天數',
            'stcl_plan_tripcostqty' => '預計差旅費(借)',
            'stcl_tripcostqty' => '實際差旅費',
            'stcl_diffqty' => '差異數',
            'stcl_diffdescription' => '差異描述',
            'stcl_realtripcostqty' => '實報銷金額 報銷報告反寫',
            'stcl_remark' => '備註',
        ];
    }
}
