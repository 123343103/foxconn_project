<?php

namespace app\modules\crm\models;
use app\models\Common;

/**
 * This is the model class for table "crm_turnover".
 *
 * @property integer $id
 * @property integer $cust_id
 * @property string $currency
 * @property string $year
 * @property string $turnover
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmTurnover extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_turnover';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id'], 'integer'],
            [['year'], 'safe'],
            [['currency'], 'string', 'max' => 20],
            [['turnover'], 'string', 'max' => 50],
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
            'currency' => '币别',
            'year' => '年限',
            'turnover' => '营业额',
        ];
    }

}
