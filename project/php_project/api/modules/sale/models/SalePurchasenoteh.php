<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "sale_purchasenoteh".
 *
 * @property string $ponh_id
 * @property integer $origin_id
 * @property integer $p_bill_id
 * @property integer $comp_id
 * @property string $bill_type
 * @property string $cust_id
 * @property integer $bill_id
 * @property string $notify_no
 * @property string $notity_date
 * @property string $notify_foganid
 * @property integer $notify_from
 * @property string $pri
 * @property string $notify_toganid
 * @property integer $notify_to
 * @property string $notify_descr
 * @property string $notfy_status
 * @property integer $create_by
 * @property string $cdate
 * @property integer $review_by
 * @property string $rdate
 * @property integer $update_by
 * @property string $udate
 */
class SalePurchasenoteh extends Common
{
    const STATUS_CANCEL     = '0';      // 取消通知状态
    const STATUS_DEFAULT    = '1';      // 默认状态 待处理
    const STATUS_PURCHASING = '2';      // 采购中
    const STATUS_PURCHASED  = '3';      // 已采购  采购单回写
    const PRI_GENERAL       = '1';      // 一般
    const PRI_URGENT        = '2';      // 紧急
    const PRI_EXTRA_URGENT  = '3';      // 特急
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_purchasenoteh';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ponh_id', 'origin_id', 'p_bill_id', 'comp_id', 'cust_id', 'bill_id', 'notify_foganid', 'notify_from', 'notify_toganid', 'notify_to', 'create_by', 'review_by', 'update_by'], 'integer'],
            [['notity_date', 'cdate', 'rdate', 'udate'], 'safe'],
            [['bill_type', 'notify_no', 'pri'], 'string', 'max' => 20],
            [['notify_descr'], 'string', 'max' => 200],
            [['notfy_status'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ponh_id' => 'Ponh ID',
            'origin_id' => '源单ID',
            'p_bill_id' => '上级单ID',
            'comp_id' => '公司ID',
            'bill_type' => '訂單類型 銷售出庫通知或採購入庫通知',
            'cust_id' => '客商ID 關聯客戶信息表crm_bs_customer_info',
            'bill_id' => '銷售訂單id',
            'notify_no' => '通知單號',
            'notity_date' => '通知日期',
            'notify_foganid' => '通知部門',
            'notify_from' => '通知人',
            'pri' => '通知優先級',
            'notify_toganid' => '被通知部門',
            'notify_to' => '被通知人',
            'notify_descr' => '通知描述',
            'notfy_status' => '通知單狀態:0新通知,1已生成PO',
            'create_by' => '創建人',
            'cdate' => '創建日期',
            'review_by' => '審核人',
            'rdate' => '审核日期',
            'update_by' => '修改人',
            'udate' => '修改日期',
        ];
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "formName" => self::tableName(),
                'codeField'=>'notify_no',
                "model" => $this
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['cdate', 'udate', 'notity_date'],           //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['udate']            //更新
                ]
            ],
        ];
    }
}
