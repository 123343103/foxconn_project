<?php
/**
 * User: F1676624
 * Date: 2017/6/19
 */
namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;


class SaleOrderh extends Common
{
    //const STATUS_DEFAULT = '10';    //已下单
    const STATUS_OUT = '20';        //已出货
    // 以下状态设置和报价单一致
    const NO_QUOTED = 1;        // 未转报价（默认）
    const TO_QUOTED = 2;        // 转报价
    const STATUS_DELETE = 0;    // 删除
    const STATUS_CREATE = 10;   // 新增
    const STATUS_WAIT = 21;     // 待审核
    const STATUS_CHECKING = 22; // 审核中
    const STATUS_FINISH = 23;   // 审核完成
    const STATUS_PREPARE = 24;  // 駁回
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_orderh';
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
            [['origin_id', 'p_bill_id', 'comp_id', 'saph_type', 'corporate', 'trade_mode', 'invoice_type', 'cust_id', 'organization_id', 'sts_id', 'staff_id', 'district_id2', 'title_district', 'send_district', 'cur_id', 'pat_id', 'pac_id', 'pay_type', 'dec_id', 'district_id2', 'sell_manager', 'sell_delegate', 'origin_hid', 'saph_reviewby', 'saph_flag', 'create_by', 'update_by', 'os_id'], 'integer'],
            [['saph_date', 'delivery_date', 'saph_limdate', 'rdate', 'cdate', 'udate', 'os_date'], 'safe'],
            [['quantity', 'bill_oamount', 'bill_camount', 'bill_freight'], 'number'],
            [['bus_code', 'saph_code', 'cust_contacts', 'cust_tel', 'csarea_id', 'contract_no', 'logistics_type', 'os_ip'], 'string', 'max' => 20],
            [['cust_attachment', 'seller_attachment', 'invoice_title', 'title_addr', 'send_addr', 'delivery_addr'], 'string', 'max' => 255],
            [['consignment_ogan', 'packtype_desc', 'saph_description', 'saph_remark'], 'string', 'max' => 200],
            [['saph_status'], 'string', 'max' => 2],
            [['os_opper'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'soh_id' => '订单id',
            'origin_id' => '源单ID',
            'p_bill_id' => '上级单ID',
            'bus_code' => '业务对象表',
            'comp_id' => '關聯公司表',
            'saph_type' => '銷售類型 如內購內銷，內購外銷',
            'saph_code' => '订单号',
            'saph_date' => '下单时间',
            'delivery_date' => '预计交货日期',
            'corporate' => '交易法人',
            'trade_mode' => '交易模式',
            'cust_contacts' => '联系人',
            'cust_tel' => '联系电话',
            'cust_attachment' => '客户下单附件',
            'seller_attachment' => '销售下单附件',
            'invoice_title' => '发票抬头',
            'invoice_type' => '发票类型',
            'title_district' => '发票抬头地址id',
            'title_addr' => '发票抬头地址',
            'send_district' => '发票寄送地址id',
            'send_addr' => '发票寄送地址',
            'delivery_addr' => '收货地址',
//            'receiver' => '收货人',
//            'receiver_tel' => '收货人电话',
            'cust_id' => '客户ID',
            'organization_id' => '部门ID',
            'sts_id' => '销售点ID',
            'staff_id' => '员工ID',
            'district_id2' => 'District  ID',
            'csarea_id' => '区域ID',
            'cur_id' => '币别',
            'pat_id' => '付款条件',
            'pac_id' => '付款方式',
            'pay_type' => '支付类型',
            'dec_id' => '交货条件',
            'saph_limdate' => '报价有效期',
            'district_id2' => 'District Id2',
            'sell_manager' => '客户经理人',
            'consignment_ogan' => '发货组织',
            'quantity' => '订单总数',
            'bill_oamount' => '订单总额（原币）',
            'bill_camount' => '订单总额（本币）',
            'bill_freight' => '总运费',
            'sell_delegate' => '销售代表',
            'packtype_desc' => '包装方式描述',
            'contract_no' => '合同号',
            'logistics_type' => '物流方式',
            'saph_description' => '其他说明',
            'origin_hid' => '订单来源 审核完成时将saph_id复制过来',
            'saph_remark' => '备注',
            'saph_reviewby' => '送审人',
            'rdate' => '送审日期',
            'saph_flag' => '1为需求单 2为报价单',
            'saph_status' => '状态',
            'create_by' => '创建人',
            'cdate' => '创建日期',
            'update_by' => '修改人',
            'udate' => '修改日期',
            'OS_ID' => '訂單狀態，來源ord_status',
            'OS_OPPER' => '訂單狀態更改人，狀態修改，必更新',
            'OS_DATE' => '訂單狀態更改時間，狀態更改必更新',
            'OS_IP' => '操作人IP',
        ];
    }

//    public function getSaleOrderl(){
//        return $this->hasMany(SaleOrderl::className(),['soh_id'=>'soh_id']);
//    }
//
//    /*销售代表*/
//    public function getSaleDelegate(){
//        return $this->hasOne(CrmEmployee::className(),['staff_code'=>'sell_delegate']);
//    }

    /**
     * 关联銷售需求&報價單子表
     */
    public function getQuotedChild()
    {
        return $this->hasMany(SaleOrderl::className(),['soh_id'=>'soh_id']);
    }
    /**
     * 关联客户
     */
    public function getCustomerInfo()
    {
        return $this->hasOne(CrmCustomerInfo::className(),['cust_id'=>'cust_id']);
    }
    /**
     * 关联订单类型
     */
    public function getOrderType()
    {
        return $this->hasOne(BsBusinessType::className(),['business_type_id'=>'saph_type']);
    }
    /**
     * 关联创建人
     */
    public function getCreatorStaff()
    {
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
    }
    /**
     * 关联修改人
     */
    public function getUpdateStaff()
    {
        return $this->hasOne(HrStaff::className(),['staff_id'=>'update_by']);
    }
    /**
     * 关联客户编码申请表
     */
    public function getCustomerApply()
    {
        return $this->hasOne(CrmCustomerApply::className(),['cust_id'=>'cust_id']);
    }
    /**
     * 关联交易法人
     */
    public function getCorporateCompany()
    {
        return $this->hasOne(BsCompany::className(),['company_id'=>'corporate']);
    }
    /**
     * 关联客户经理人表
     */
    public function getCustomerManager()
    {
        return $this->hasOne(CrmCustPersoninch::className(),['cust_id'=>'cust_id']);
    }
    public function getCustomerManagerName()
    {
        return $this->hasOne(HrStaff::className(),['staff_id'=>'ccpich_personid'])->via('customerManager');
    }

    /**
     * 关联地址表（发票抬头地址，发票寄送地址，收货地址）
     */
//    public function getAddress()
//    {
//        return $this->hasOne(BsAddress::className(),['cust_id'=>'cust_id']);
//    }
    public function behaviors()
    {
        return [
//            "formCode" => [
//                "class" => FormCodeBehavior::className(),
//                "formName" => self::tableName(),
//                'codeField'=>'saph_code',
//                "model" => $this
//            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['cdate'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['udate']            //更新
                ]
            ],
        ];
    }

//    public static function getSohId($soh_code)
//{
//    return self::find()
//        ->select([
//            self::tableName().".soh_id",
//            //self::tableName().".soh_status"
//        ])
//        ->where([
//            self::tableName().'.saph_code' => $soh_code
//        ])
//        ->asArray()
//        ->one();
//}
    //获取客户id
//    public static function getCusId($soh_code)
//    {
//        return self::find()
//            ->select([
//                self::tableName().".cust_id"
//            ])
//            ->where([
//                self::tableName().'.saph_code' => $soh_code
//            ])
//            ->asArray()
//            ->one();
//    }


}