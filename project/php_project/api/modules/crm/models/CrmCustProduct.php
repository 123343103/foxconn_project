<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "crm_cust_product".
 *
 * @property integer $ccp_id
 * @property integer $cust_id
 * @property string $ccp_sname
 * @property string $ccp_model
 * @property string $ccp_annual
 * @property string $ccp_brand
 * @property string $ccp_remark
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCustProduct extends Common
{
    const STATUS_DELETE = 0;    //删除
    const STATUS_DEFAULT = 10;  //默认
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_cust_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'create_by', 'update_by','status'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['ccp_sname', 'ccp_model', 'ccp_annual', 'ccp_brand', 'ccp_remark'], 'string', 'max' => 255],
            ['status','default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ccp_id' => '主营产品ID',
            'cust_id' => '关联客户信息表',
            'ccp_sname' => '主营产品',
            'ccp_model' => '规格',
            'ccp_annual' => '年产量',
            'ccp_brand' => '品牌',
            'ccp_remark' => '备注',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
            'status' => '状态',
        ];
    }
    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }
}
