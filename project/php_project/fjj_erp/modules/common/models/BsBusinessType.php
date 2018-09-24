<?php

namespace app\modules\common\models;

use Yii;

/**
 * F3858995
 * 2016.10.27
 *
 *
 * @property integer $business_type_id
 * @property string $business_code
 * @property string $business_type_desc
 * @property string $business_value
 * @property integer $status
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsBusinessType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_business_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_type_desc', 'business_value','business_code','business_code', 'create_at', 'update_at','status', 'create_by', 'update_by'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_type_id' => '主键ID',
            'business_code' => '业务代码',
            'business_type_desc' => '类别说明',
            'business_value' => '业务类别',
            'status' => '0,1',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }
}
