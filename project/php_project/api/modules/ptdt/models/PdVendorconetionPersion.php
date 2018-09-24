<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "pd_vendorconetion_persion".
 *
 * @property string $vcper_id
 * @property string $vendor_id
 * @property string $vcper_name
 * @property string $vcper_sex
 * @property string $vcper_birthplace
 * @property string $vcper_age
 * @property string $vcper_post
 * @property string $vcper_tel
 * @property string $vcper_mobile
 * @property string $vcper_mail
 * @property string $vcper_qq
 * @property string $vcper_hobby
 * @property string $vcper_relationship
 * @property string $vcper_isshareholder
 * @property string $vcper_ispost
 * @property string $vcper_status
 * @property string $vcper_remark
 */
class PdVendorconetionPersion extends Common
{

    const STATUS_DEFAULT=10;
    const STATUS_DELETE=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_vendorconetion_persion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['vcper_id'], 'required'],
            [['vcper_id', 'vendor_id', 'vcper_age'], 'integer'],
            [['vcper_name', 'vcper_birthplace', 'vcper_post', 'vcper_tel', 'vcper_mobile', 'vcper_mail', 'vcper_qq', 'vcper_relationship','vcper_isshareholder', 'vcper_ispost', 'vcper_status'], 'safe'],
            [['vcper_sex'], 'string', 'max' => 4],
            [['vcper_hobby'], 'string', 'max' => 120],
            [['vcper_remark'], 'string', 'max' => 200],
            [['vcper_status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vcper_id' => 'Vcper ID',
            'vendor_id' => '供应商',
            'vcper_name' => '姓名',
            'vcper_sex' => '性别',
            'vcper_birthplace' => '籍贯',
            'vcper_age' => '年龄',
            'vcper_post' => '职务',
            'vcper_tel' => '电话',
            'vcper_mobile' => '手机',
            'vcper_mail' => '邮箱',
            'vcper_qq' => 'qq',
            'vcper_hobby' => '爱好',
            'vcper_relationship' => '社会关系',
            'vcper_isshareholder' => '是否股东r',
            'vcper_ispost' => '是否在职',
            'vcper_status' => 'Vcper Status',
            'vcper_remark' => 'Vcper Remark',
        ];
    }
}
