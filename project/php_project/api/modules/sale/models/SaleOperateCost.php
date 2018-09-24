<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_operate_cost".
 *
 * @property string $soc_id
 * @property string $soc_no
 * @property string $soc_year
 * @property string $soc_month
 * @property string $operate_cost
 */
class SaleOperateCost extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_operate_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operate_cost'], 'number'],
            [['soc_no'], 'string', 'max' => 20],
            [['soc_year', 'soc_month'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'soc_id' => 'ID',
            'soc_no' => '員工工號',
            'soc_year' => '年',
            'soc_month' => '月',
            'operate_cost' => '業務費用',
        ];
    }
}
