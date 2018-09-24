<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsAddress;
use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCompany;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerPersion;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\CategoryAttr;
use Yii;
use app\modules\crm\models\CrmCustomerInfo;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "sale_quotedprice_h".
 *
 * @property integer $saph_id
 * @property string $bus_code
 * @property integer $comp_id
 * @property string $saph_type
 * @property string $saph_code
 * @property string $saph_date
 * @property string $delivery_date
 * @property integer $corporate
 * @property integer $trade_mode
 * @property string $cust_contact
 * @property string $cust_tel
 * @property string $cust_attachment
 * @property string $seller_attachment
 * @property string $invoice_title
 * @property string $invoice_type
 * @property string $title_district
 * @property string $title_addr
 * @property integer $send_district
 * @property string $send_addr
 * @property string $delivery_addr
 * @property integer $cust_id
 * @property integer $organization_id
 * @property integer $sts_id
 * @property integer $staff_id
 * @property integer $DISTRICT_ID
 * @property string $csarea_id
 * @property integer $cur_id
 * @property integer $pat_id
 * @property integer $pac_id
 * @property integer $pay_type
 * @property integer $dec_id
 * @property string $saph_limdate
 * @property integer $district_id2
 * @property integer $sell_manager
 * @property string $consignment_ogan
 * @property string $quantity
 * @property string $bill_oamount
 * @property string $bill_camount
 * @property string $bill_freight
 * @property integer $sell_delegate
 * @property string $packtype_desc
 * @property string $contract_no
 * @property string $logistics_type
 * @property string $saph_description
 * @property string $origin_hid
 * @property string $saph_remark
 * @property integer $saph_reviewby
 * @property string $rdate
 * @property integer $saph_flag
 * @property string $saph_status
 * @property integer $create_by
 * @property string $cdate
 * @property integer $update_by
 * @property string $udate
 */
class SaleCustrequireH extends Common
{
    const NO_QUOTED = '1';        // 未转报价（默认）  分表后不需要此标志
    const TO_QUOTED = '2';        // 转报价
    const STATUS_DELETE = '0';    // 删除
    const STATUS_CREATE = '10';   // 新增 待报价
//    const STATUS_WAIT = '21';     // 待提交审核  需求单不需要审核没有此状态
//    const STATUS_CHECKING = '22'; // 审核中 需求单不需要审核没有此状态
    const STATUS_QUOTING = '11'; // 报价中
    const STATUS_FINISH = '23';   // 审核完成
    const STATUS_PREPARE = '24';  // 駁回
    const STATUS_CANCEL = '09';   // 取消
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.sale_custrequire_h';
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
            [['comp_id', 'saph_type', 'corporate', 'trade_mode', 'title_district', 'send_district', 'cust_id', 'organization_id', 'sts_id', 'staff_id', 'DISTRICT_ID', 'cur_id', 'pat_id', 'pac_id', 'pay_type', 'dec_id', 'district_id2', 'sell_manager', 'sell_delegate', 'origin_hid', 'saph_reviewby', 'saph_flag', 'create_by', 'update_by','invoice_type'], 'integer'],
            [['saph_date', 'delivery_date', 'saph_limdate', 'rdate', 'cdate', 'udate'], 'safe'],
            [['quantity', 'bill_oamount', 'bill_camount', 'bill_freight'], 'safe'],
            [['bus_code', 'saph_code', 'csarea_id', 'cust_contacts', 'cust_tel', 'contract_no', 'logistics_type'], 'string', 'max' => 20],
            [['cust_attachment', 'seller_attachment', 'invoice_title', 'title_addr', 'send_addr', 'delivery_addr'], 'string', 'max' => 255],
            [['saph_status'], 'string', 'max' => 2],
            [['consignment_ogan', 'packtype_desc', 'saph_description', 'saph_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'saph_id' => 'Saph ID',
            'bus_code' => '業務對象ID 關聯業務對象表',
            'comp_id' => '公司ID 關聯公司表',
            'saph_type' => '報價單類別 关联bs_business_type',
            'saph_code' => '報價單編號',
            'saph_date' => '下单时间',
            'delivery_date' => '预计交货日期',
            'corporate' => '交易法人',
            'trade_mode' => '交易模式',
            'cust_contacts' => '联系人',
            'cust_tel' => '联系电话',
            'cust_attachment' => '客户下单附件',
            'seller_attachment' => '销售员下单附件',
            'invoice_title' => '发票抬头',
            'invoice_type' => '发票类型',
            'title_district' => '发票抬头地址id',
            'title_addr' => '发票抬头地址',
            'send_district' => '发票寄送地址id',
            'send_addr' => '发票寄送地址',
            'delivery_addr' => '收货地址',
            'cust_id' => '客戶ID 關聯客戶信息表',
            'organization_id' => 'Organization ID',
            'sts_id' => '銷售?ID 關聯銷售點信息表',
            'staff_id' => '報價人 關聯銷售人員資料表',
            'DISTRICT_ID' => '分公司所在地區 關聯地區表',
            'csarea_id' => '分公司所在銷售區域 關聯銷售區域表',
            'cur_id' => '交易幣別 關聯幣別表',
            'pat_id' => '付款條件 關聯付款條件表',
            'pac_id' => '付款方式 關聯付款方式表',
            'pay_type' => '支付类型',
            'dec_id' => '交貨條件 關聯交貨條件表',
            'saph_limdate' => '報價有效期 sdf',
            'district_id2' => '銷售區域',
            'sell_manager' => '客戶經濟',
            'consignment_ogan' => '發貨組織',
            'quantity' => '訂單總數',
            'bill_oamount' => '訂單總額(原幣)',
            'bill_camount' => '訂單總額(本幣)',
            'bill_freight' => '总运费',
            'sell_delegate' => '銷售代表',
            'packtype_desc' => '包裝方式描述',
            'contract_no' => '合同號',
            'logistics_type' => '物流方式',
            'saph_description' => '其它說明',
            'origin_hid' => '订单来源 关联pubdata表',
            'saph_remark' => '備註',
            'saph_reviewby' => '送審人',
            'rdate' => '送審日期',
            'saph_flag' => '1需求單2為報價單',
            'saph_status' => '狀態 默认10 新增',
            'create_by' => '創建人',
            'cdate' => '創建日期',
            'update_by' => '修改人',
            'udate' => '修改日期',
        ];
    }

    /**
     * 关联銷售需求&報價單子表
     */
    public function getQuotedChild()
    {
        return $this->hasMany(SaleCustrequireL::className(),['saph_id'=>'saph_id']);
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
//                'codeField'=>'saph_code',
//                "formName" => self::tableName(),
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
}
