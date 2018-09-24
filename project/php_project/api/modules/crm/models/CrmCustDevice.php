<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
/**
 * This is the model class for table "crm_bs_cust_device".
 *
 * @property string $custd_id
 * @property string $cust_id
 * @property string $type
 * @property string $code
 * @property string $sname
 * @property integer $nqty
 * @property string $brand
 * @property string $stutas
 * @property string $description
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmCustDevice extends Common
{
    const STATUS_DELETE = 0;
    const STATUS_DEFAULT = 10;
    public static function tableName()
    {
        return 'crm_bs_customer_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'nqty', 'status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['type', 'code', 'sname', 'brand'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 200],
            ['status', 'default', 'value' => self::STATUS_DEFAULT ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'custd_id' => '设备表ID',
            'cust_id' => '关联客户信息表',
            'type' => '设备类型',
            'code' => '设备代码',
            'sname' => '设备名称',
            'nqty' => '设备数量',
            'brand' => '设备品牌',
            'status' => '状态',
            'description' => '描述',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '更新人',
            'update_at' => '更新时间',
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
