<?php

namespace app\modules\common\models;

use Yii;

/**
 * F3858995
 * 2016.10.17
 * 业务表
 * 
 *
 * @property string $business_code
 * @property string $business_desc
 */
class BsBusiness extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_business';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_code'], 'required'],
            [['business_code'], 'string', 'max' => 20],
            [['business_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'business_code' => '业务代码',
            'business_desc' => '业务',
        ];
    }
}
