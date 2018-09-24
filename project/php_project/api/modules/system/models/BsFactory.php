<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "bs_factory".
 *
 * @property string $factory_id
 * @property string $factory_code
 * @property string $factory_name
 * @property string $fact_status
 * @property integer $operator
 * @property string $operate_date
 * @property string $operate_ip
 */
class BsFactory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_factory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['operator'], 'integer'],
            [['operate_date'], 'safe'],
            [['factory_code', 'operate_ip'], 'string', 'max' => 20],
            [['factory_name'], 'string', 'max' => 30],
            [['fact_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'factory_id' => '廠區ID',
            'factory_code' => '廠區編碼',
            'factory_name' => '廠區名稱',
            'fact_status' => '廠區狀態（1：有效；0：無效）',
            'operator' => '操作人',
            'operate_date' => '操作時間',
            'operate_ip' => '操作IP',
        ];
    }
}
