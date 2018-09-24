<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * F3858995
 * 2016.10.17
 * 业务表
 * 
 *
 * @property string $business_code
 * @property string $business_desc
 * @property string $form_name
 * @property string $flag
 */
class BsBusiness extends Common
{
    const REVIEW_DISABLED = 10;     // 禁用审核  默认禁用
    const REVIEW_ENABLED = 11;      // 启用签核
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
            [['business_desc', 'form_name'], 'string', 'max' => 255],
            [['flag'], 'string', 'max' => 10],
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
            'business_code' => 'Business Code',
            'business_desc' => 'Business Desc',
            'form_name' => 'Form Name',
            'flag' => 'Flag',
        ];
    }
}
