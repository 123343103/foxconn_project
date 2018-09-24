<?php
namespace app\modules\ptdt\models;

use app\behaviors\StaffBehavior;
use app\models\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * 主营商品模型
 * F3859386
 * 2016.11.11
 *
 * @property string $vmainl_id
 * @property string $vendor_id
 * @property string $category_id
 * @property string $vmainl_product
 * @property string $vmainl_model
 * @property string $vmainl_superiority
 * @property string $vmainl_salesway
 * @property string $vmainl_yqty
 * @property string $vmainl_marketshare
 * @property string $vmainl_isopensale
 * @property string $vmainl_isagent
 * @property string $vmanil_status
 * @property string $vmainl_description
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class BsVendorMainlist extends Common
{

    const STATUS_DEFAULT=10;
    const STATUS_DELETE=0;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_vendor_mainlist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['vmainl_id', 'vendor_id', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at','vmainl_yqty'], 'safe'],
            [['category_id', 'vmainl_isopensale', 'vmainl_isagent'], 'string', 'max' => 20],
            [['vmainl_product', 'vmainl_marketshare'], 'string', 'max' => 40],
            [['vmainl_model', 'vmainl_superiority', 'vmainl_salesway'], 'string', 'max' => 140],
//            [['vmanil_status'], 'string', 'max' => 2],
            [['vmanil_status'], 'default', 'value' => self::STATUS_DEFAULT ],
            [['vmainl_description'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vmainl_id' => 'Vmainl ID',
            'vendor_id' => 'Vendor ID',
            'category_id' => 'Category ID',
            'vmainl_product' => 'Vmainl Product',
            'vmainl_model' => 'Vmainl Model',
            'vmainl_superiority' => 'Vmainl Superiority',
            'vmainl_salesway' => 'Vmainl Salesway',
            'vmainl_yqty' => 'Vmainl Yqty',
            'vmainl_marketshare' => 'Vmainl Marketshare',
            'vmainl_isopensale' => 'Vmainl Isopensale',
            'vmainl_isagent' => 'Vmainl Isagent',
            'vmanil_status' => 'Vmanil Status',
            'vmainl_description' => 'Vmainl Description',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
    }
//    public function behaviors()
//    {
//        return [
//            "StaffBehavior" => [
//                //为字段自动赋值（登录用户）
//
//                "class" => StaffBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_by'],   //插入时自动赋值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_by']  //更新时自动赋值字段
//                ]
//            ],
//            'timeStamp'=>[
//                'class'=>TimestampBehavior::className(),    //时间字段自动赋值
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']  //更新
//                ],
//                'value'=>function(){
//                    return date("Y-m-d H:i:s",time());       //赋值的值来源,如不同复写
//                }
//            ]
//        ];
//    }

}
