<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\ptdt\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\show\CrmCustomerApplyShow;
use Yii;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsIndustrytype;

class CrmCustomerInfo extends Common
{
    const CUSTOMER_INVALID = 0;      //  无效
    const CUSTOMER_NORMAL = 10;      //  正常
    const PERSONINCH_NO = 0;        //未认领
    const PERSONINCH_YES = 10;        //已认领
    const ASSIGN_STATUS_NO = 0;        //未认领
    const ASSIGN_STATUS_YES = 10;        //已认领
    const MEMBER_NO = 0;          //是否会员(否)
    const MEMBER_YES = 1;          //是否会员(是)
    const VISITFLAG_YES = 1;          //是否回访(是)
    const VISITFLAG_NO = 0;          //是否回访(否)
    /*招商开发*/
    const INVESTEMNT_UN = 10; //未开发
    const INVESTEMNT_IN = 20; //开发中
    const INVESTEMNT_SUCC = 30; //开发成功
    const INVESTEMNT_FAILURE = 40; //开发失败
    const INVESTEMNT_FAILUREs = ''; //开发失败

    const CODE_TYPE_INVESTMENT = 20; //招商客户
    const CODE_TYPE_POTENTIAL = 21; //潜在客户
    const CODE_TYPE_MEMBER = 22; //会员
    const CODE_TYPE_CUSTOMER = 23; //销售客户

    public $codeType;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bs_customer_info';
    }

    /*状态信息*/
    public function getStatus()
    {
        return $this->hasOne(CrmCustomerStatus::className(), ['customer_id' => 'cust_id']);
    }

    /*建档人*/
    public function getBuildStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by'])->from(['u2' => HrStaff::tableName()]);
    }

    /*客户类型*/
    public function getCustType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_type']);
    }

    /*客户类别*/
    public function getCustClass()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_class']);
    }

    /*发票需求*/
    public function getCompReq()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_compreq']);
    }

    /*客户等级*/
    public function getCustLevel()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_level']);
    }

    /*经营模式*/
    public function getBusinessType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_businesstype']);
    }

    /*行业类别*/
    public function getIndustryType()
    {
        return $this->hasOne(BsIndustrytype::className(), ['idt_id' => 'cust_industrytype']);
    }

    /*公司属性*/
    public function getCompvirtue()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_compvirtue']);
    }

    /*公司规模*/
    public function getCompscale()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_compscale']);
    }

    /*投资总额币别*/
    public function getTotalinvestment()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'total_investment_cur']);
    }

    /*实收总额币别*/
    public function getOfficialReceipts()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'official_receipts_cur']);
    }

    /*年营业额*/
    public function getMemberCompsum()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'compsum_cur']);
    }

    /*年采购额*/
    public function getCustPruchaseqty()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'pruchaseqty_cur']);
    }

    /*所在地区*/
    public function getArea()
    {
        return $this->hasOne(BsDistrict::className(), ['district_id' => 'cust_area']);
    }

    /*所在军区*/
    public function getSaleArea()
    {
        return $this->hasOne(CrmSalearea::className(), ['csarea_id' => 'cust_salearea']);
    }

    /*列表页联系人*/
    public function getContactPerson()
    {
        return $this->hasOne(CrmCustomerPersion::className(), ['cust_id' => 'cust_id'])->andFilterWhere(["=", "ccper_ismain", 1]);
    }

    /*主要联系人*/
    public function getContactPersons()
    {
        return $this->hasMany(CrmCustomerPersion::className(), ['cust_id' => 'cust_id'])->where(['!=', 'ccper_status', CrmCustomerPersion::STATUS_DELETE]);
    }

    /*其他联系人*/
    public function getContacts()
    {
        return $this->hasMany(CrmCustomerPersion::className(), ['cust_id' => 'cust_id'])->where(['and', ['!=', 'ccper_status', CrmCustomerPersion::STATUS_DELETE], ["=", "ccper_ismain", 0]]);
    }

    /*设备信息*/
    public function getCustDevice()
    {
        return $this->hasMany(CrmCustDevice::className(), ['cust_id' => 'cust_id']);
    }

    /*客户代码*/
    public function getCustApply()
    {
        return $this->hasOne(CrmCustomerApply::className(), ['cust_id' => 'cust_id']);
    }

    /*产品信息*/
    public function getCustProduct()
    {
        return $this->hasMany(CrmCustProduct::className(), ['cust_id' => 'cust_id']);
    }

    /*主要客户信息*/
    public function getCustCustomer()
    {
        return $this->hasMany(CrmCustCustomer::className(), ['cust_id' => 'cust_id']);
    }

    /*认领信息*/
    public function getPersoninch()
    {
        return $this->hasMany(CrmCustPersoninch::className(), ['cust_id' => 'cust_id']);
    }

    /*认领信息 单个*/
    public function getPersoninchOne()
    {
        return $this->hasOne(CrmCustPersoninch::className(), ['cust_id' => 'cust_id']);
    }

    /*客户经理人*/
    public function getManager()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'ccpich_personid'])->via('personinchOne');
    }

    /*业务关系*/
    public function getEmployee()
    {
        return $this->hasOne(CrmEmployee::className(), ['staff_code' => 'ccpich_personid'])->via('personinchOne');
    }

    /*账信申请*/
    public function getCreditApply()
    {
        return $this->hasOne(CrmCreditApply::className(), ['cust_id' => 'cust_id']);
    }

    /*账信申请*/
    public function getLcreditApply()
    {
        return $this->hasOne(LCrmCreditApply::className(), ['cust_id' => 'cust_id']);
    }

    public function getAllotman()
    {
        return CrmCustomerInfo::find()->select([HrStaff::tableName() . ".staff_name"])
            ->leftJoin(CrmCustPersoninch::tableName(), CrmCustPersoninch::tableName() . ".cust_id=" . CrmCustomerInfo::tableName() . ".cust_id")
            ->leftJoin(HrStaff::tableName(), HrStaff::tableName() . ".staff_id=" . CrmCustPersoninch::tableName() . ".ccpich_personid")
            ->where([
                CrmCustomerInfo::tableName() . ".personinch_status" => 10,
                CrmCustPersoninch::tableName() . ".ccpich_status" => 10,
                CrmCustomerInfo::tableName() . ".cust_id" => $this->cust_id
            ])
            ->asArray()
            ->scalar();
    }

    public static function getCustomerInfoOne($id)
    {
        return self::find()->where(['cust_id' => $id])->one();
    }

    /*获取单条信息*/
    public static function getOneInfo($id, $select = null)
    {
        return self::find()->where(['cust_id' => $id])->select($select)->one();
    }

    /*
     *反序列化等級名稱
     */
    public function getRegName()
    {
        return unserialize($this->cust_regname);
    }

    /*
     * 获取详细地址1
     */
    public function getDistrict()
    {
        $disId = BsDistrict::find()->where(['district_id' => $this->cust_district_2])->one();
        $name = $disId['district_name'];
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
//        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
        $arr = [$dis3, $dis2, $dis1, $disId];
        return $arr;
    }

    /*
     * 完整地址
     */
    public function getDistricts()
    {
        $disId = BsDistrict::find()->where(['district_id' => $this->cust_district_2])->one();
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
//        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
        $adress = $dis3['district_name'] . $dis2['district_name'] . $dis1['district_name'] . $disId['district_name'] . $this->cust_adress;
        return $adress;
    }

    /*
     * 获取总公司地址
     */
    public function getDistrictCompany()
    {
        $disId = BsDistrict::find()->where(['district_id' => $this->cust_district_3])->one();
        $name = $disId['district_name'];
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
//        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
        $arr = [$dis3, $dis2, $dis1, $disId];
        return $arr;
    }

    /*
      * 获取发票抬头地址
      */
    public function getInvoiceTitleDistrict()
    {
        $disId = BsDistrict::find()->where(['district_id' => $this->invoice_title_district])->one();
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
        $arr = [$dis3, $dis2, $dis1, $disId];
        return $arr;
    }

    /*
      * 获取发票邮寄地址
      */
    public function getInvoiceMailDistrict()
    {
        $disId = BsDistrict::find()->where(['district_id' => $this->invoice_mail_district])->one();
        $dis1 = BsDistrict::find()->where(['district_id' => $disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id' => $dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id' => $dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id' => $dis3['district_pid']])->one();
        $arr = [$dis3, $dis2, $dis1, $disId];
        return $arr;
    }

    //拜访信息
    public function getVisitInfo()
    {
        return $this->hasOne(CrmVisitRecord::className(), ['cust_id' => 'cust_id']);
    }

    //会员类型
    public function getMemberType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_type']);
    }

    //申请发票类型
    public function getinvoiceType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'invoice_type']);
    }

    //具备发票类型
    public function getinvoType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'HV_INV_TYPE']);
    }

    //会员等级
    public function getMemberLevel()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_level']);
    }

    //注册网站
    public function getRegWeb()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_regweb']);
    }

    //注册货币
    public function getRegCurrency()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_regcurr']);
    }

    //职位职能
    public function getCustFunction()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'cust_function']);
    }

    //交易货币
    public function getMemberCurr()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_curr']);
    }

    //客户来源
    public function getCustSource()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_source']);
    }

    //潜在需求
    public function getLatDemand()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'member_reqflag']);
    }

    //需求类目
    public function getProductType()
    {
        return $this->hasOne(BsCategory::className(), ['catg_id' => 'member_reqitemclass']);
    }

    //需求类别
    public function getProductTypes()
    {
        return $this->hasOne(BsCategory::className(), ['category_id' => 'member_reqdesription']);
    }

    //客户活动信息
    public function getActive()
    {
        return $this->hasOne(CrmActiveApply::className(), ["cust_id" => "cust_id"]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_sname'], 'required', 'message' => '{attribute}必填'],
            [['cust_sname'], 'unique', 'targetAttribute' => 'cust_sname', 'message' => '{attribute}已经存在'],
            [['staff_id', 'cust_area', 'cust_salearea', 'cust_district_1', 'cust_district_2', 'cust_district_3', 'custd_id', 'ccper_id', 'cpurch_id', 'personinch_status', 'cust_sales', 'cust_assistant', 'cust_verifyperson', 'cust_ismember', 'member_type', 'member_points', 'member_level', 'member_source', 'member_regweb', 'member_reqflag', 'member_visitflag', 'cust_regfunds', 'member_regcurr', 'member_curr', 'company_id', 'create_by', 'update_by', 'cust_businesstype', 'invoice_type', 'invoice_title_district', 'invoice_mail_district', 'cust_function', 'compsum_cur', 'pruchaseqty_cur', 'HV_INV_TYPE'], 'integer'],
            [['cust_regdate', 'cust_veriftydate', 'member_regtime', 'member_certification', 'create_at', 'update_at', 'data_from', 'assign_status', 'member_remark', 'total_investment', 'shareholding_ratio', 'cust_level', 'cust_type', 'cust_compvirtue', 'official_receipts', 'total_investment_cur', 'official_receipts_cur', 'member_reqitemclass'], 'safe'],
            [['cust_code', 'cust_class','cust_inchargeperson', 'cust_contacts', 'cust_tel1', 'cust_tel2', 'cust_fax', 'cust_compscale', 'cust_personqty', 'cust_filernumber', 'cust_industrytype', 'cust_pruchaseway', 'cust_bigseason', 'cust_brand', 'pac_id', 'pat_id', 'cust_credittype', 'cust_creditqty', 'cust_isverify', 'member_name', 'member_compzipcode', 'cust_position', 'cust_department'], 'string', 'max' => 20],
            [['cust_sname', 'cust_eshortname'], 'string', 'max' => 255],
            [['cust_shortname'], 'string', 'max' => 60],
            [['cust_email'], 'string', 'max' => 100],
            [['invoice_title'], 'string', 'max' => 50],
            [['cust_ename', 'member_reqdesription', 'member_businessarea', 'member_compwebside', 'member_marketing', 'member_compcust', 'member_compreq', 'member_reqcharacter'], 'string', 'max' => 200],
            [['cust_flag', 'cust_status', 'cust_islisted', 'three_to_one'], 'string', 'max' => 2],
            [['cust_readress', 'cust_adress', 'cust_parentcomp', 'cust_belinkcomp', 'cust_maingoods', 'invoice_title_address', 'invoice_mail_address', 'member_compsum', 'cust_pruchaseqty'], 'string', 'max' => 120],
            [['cust_headquarters_address', 'cust_key_customer', 'cust_tax_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cust_id' => '客户ID',
            'staff_id' => '關聯銷售人員資料表',
            'cust_code' => '客戶編碼',
            'cust_sname' => '客户全称',
            'cust_shortname' => '客户简称',
            'cust_ename' => '英文名称',
            'cust_eshortname' => '英文简称',
            'cust_level' => '客戶等級',
            'cust_type' => '客戶類型,集团客户纯外部客户',
            'cust_class' => '客戶類別,正式客户,会员客户',
            'cust_flag' => '客商标志',
            'cust_status' => '客戶狀態1正常,0無效',
            'cust_area' => '所在地區',
            'cust_salearea' => '所在軍區',
            'cust_cource' => '来源',
            'cust_compvirtue' => '私營,港資',
            'cust_inchargeperson' => '公司負責人',
            'cust_contacts' => '联系人',
            'cust_position' => '联系人职位',
            'cust_tel1' => '公司聯繫電話',
            'cust_tel2' => '客户联系人电话(注册手机)',
            'cust_district_1' => '收货地址区ID',
            'cust_readress' => '公司收貨地址',
            'cust_district_2' => '营业地址区ID',
            'cust_adress' => '公司營業地址',
            'cust_district_3' => '总公司地址区ID',
            'cust_headquarters_address' => '总公司地址',
            'cust_email' => '公司郵箱',
            'cust_fax' => '公司傳真',
            'cust_regdate' => '註冊時間',
            'cust_regfunds' => '註冊資金',
            'cust_key_customer' => '重要客户',
            'cust_islisted' => '是否上市公司',
            'cust_businesstype' => '经营类型',
            'cust_compscale' => '公司規模',
            'cust_personqty' => '員工人數',
            'cust_regname' => '登記證名稱',
            'cust_regnumber' => '登記證號碼',
            'cust_filernumber' => '檔案編號',
            'cust_tax_code' => '税籍编码',
            'cust_parentcomp' => '母公司',
            'cust_belinkcomp' => '關聯公司',
            'cust_industrytype' => '行業類別',
            'cust_maingoods' => '公司主要產品',
            'custd_id' => '關聯公司設備表ID',
            'ccper_id' => '關聯公司聯繫人員表ID',
            'cpurch_id' => '關聯公司主要採購商品表ID',
            'cust_pruchaseway' => '公司主要採購渠道',
            'cust_bigseason' => '旺季分佈',
            'cust_pruchaseqty' => '年採購額',
            'pruchaseqty_cur' => '年采购额幣別',
            'cust_brand' => '品牌',
            'pac_id' => '付款条件',
            'pat_id' => '付款方式',
            'personinch_status' => '认领状态',
            'cust_sales' => '銷售員',
            'cust_assistant' => '銷售業務助理',
            'cust_credittype' => '公司信用類型',
            'cust_creditqty' => '公司信用額度',
            'cust_isverify' => '1已審0未審',
            'cust_verifyperson' => '审核人',
            'cust_veriftydate' => '审核时间',
            'cust_ismember' => '是否会员',
            'member_name' => '会员名',
            'member_type' => '会员类别',
            'member_points' => '会员积分',
            'member_level' => '会员等级',
            'member_source' => '客户来源',
            'member_regtime' => '会员注册时间',
            'member_certification' => '认证完成时间',
            'member_regweb' => '注册网站',
            'member_reqdesription' => '需求类别',
            'member_reqitemclass' => '需求类目',
            'member_reqflag' => '潜在需求',
            'member_visitflag' => '是否已回访',
            'member_businessarea' => '经营范围',
            'member_compwebside' => '公司主页',
            'member_regcurr' => '注册币别',
            'member_curr' => '交易币种',
            'member_compsum' => '年营业额',
            'compsum_cur' => '年营业额幣別',
            'member_marketing' => '主要市场',
            'member_compcust' => '主要客户',
            'member_compreq' => '发票需求',
            'member_remark' => '会员备注',
            'company_id' => '所属厂商ID',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
            'three_to_one' => 'three_to_one',
            ' HV_INV_TYPE' => '具備发票类型'
        ];
    }


    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'cust_filernumber',
                "formName" => self::tableName(),
                "model" => $this,
            ],
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
