<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_address".
 *
 * @property string $ba_id
 * @property string $cust_id
 * @property string $contact_name
 * @property string $contact_tel
 * @property string $ba_address
 * @property string $district
 * @property string $address
 * @property string $tel
 * @property string $code
 * @property string $ba_type
 * @property string $ba_status
 */
class BsAddress extends Common
{
    const TYPE_TITLE        = '10';       // 发票抬头地址类型
    const TYPE_SEND         = '11';       // 发票寄送地址类型
    const TYPE_DELIVERY     = '12';       // 收货地址
    const STATUS_VALID      = '10';       // 有效地址
    const STATUS_DEFAULT    = '11';       // 默认地址
    const STATUS_INVALID    = '12';       // 无效地址
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id','district'], 'integer'],
            [['contact_name', 'contact_tel','tel','code'], 'string', 'max' => 20],
            [['ba_address','address'], 'string', 'max' => 255],
            [['ba_type', 'ba_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ba_id' => 'Ba ID',
            'cust_id' => 'Cust ID',
            'district' => '区县id',
            'tel' => '固定电话',
            'code' => '邮编',
            'contact_name' => '联系人',
            'contact_tel' => '联系电话',
            'ba_address' => '总地址',
            'address' => '具体地址',
            'ba_type' => '地址类型  10发票抬头地址  11发票寄送地址  12收货地址',
            'ba_status' => '地址状态  10有效 11默认 12无效',
        ];
    }
}
