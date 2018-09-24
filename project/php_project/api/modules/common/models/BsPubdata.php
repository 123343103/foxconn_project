<?php
namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * 基本資料模型
 * F3858995
 * 2016.9.20
 *
 * @property string $bsp_id
 * @property string $bsp_sname
 * @property string $bsp_stype
 * @property string $bsp_svalue
 * @property integer $bsp_status
 * @property string $creator
 * @property string $create_at
 * @property string $updater
 * @property string $update_at
 */
class BsPubdata extends Common
{
    const STATUS_DELETE = 0;   //无效
    const STATUS_DEFAULT = 10; //有效，启用
    const STATUS_DISABLE = 11; //禁用

    //廠商信息_來源
    const FIRM_SOURCE = 'csly';
    //廠商信息_類型
    const FIRM_TYPE = 'cslx';
    //廠商信息_地位
    const FIRM_LEVEL = 'csdw';
    //廠商信息_集团供应商
    const GROUP_SUPPLIER = 'jtgys';
    //商品開發_計畫類型
    const PD_PLAN_TYPE = 'jhlx';
    //商品開發_拜訪目的
    const PD_VISIT_PUR = 'bfmd';
    //商品開發_談判配合度
    const PD_NEGOTIATION_COOPERATE = 'tpphd';
    //商品開發_談判結論
    const PD_NEGOTIATION_RESULT = 'tpjl';
    //商品開發_需求類型
    const PD_REQUIREMENT_TYPE = 'spxqlx';
    //商品開發_開發類型
    const PD_DEVELOP_TYPE = 'spkflx';
    //商品開發_商品定位
    const DP_PRODUCT_LEVEL = 'spdw';
    //商品開發_代理等級
    const PD_AGENTS_LEVEL = 'dldj';
    //商品開發_授權區域範圍
    const PD_AUTHORIZE_AREA = 'sqqufw';
    //商品開發_物流配送
    const PD_DELIVERY_WAY = 'wlps';
    //商品開發_售後服務
    const PD_SERVICE = 'shfw';
    //商品開發_售後服務
    const PD_SALE_AREA = 'xsfw';
    //商品開發_代理類型
    const PD_AGENTS_TYPE = 'dllx';
    //商品開發_緊急程度
    const PD_URGENCY_LEVEL = 'jjcd';
    //source类别
    const PD_SOURCE_TYPE = 'source_type';
    //新增类型
    const PD_SUPPLIER_COMPTYPE = 'xzlx';
    //客户类型
    const CRM_CUSTOMER_TYPE = 'khlx';
    //客户类别
    const CRM_CUSTOMER_CLASS = 'khlb';
    //经营类型
    const CRM_MANAGEMENT_TYPE = 'jylx';
    //公司属性
    const CRM_COMPANY_PROPERTY = 'gssx';
    //交易单位
    const PD_TRADING_UNIT = 'jydw';
    //交易模式
    const TRANSACT_PATTERN = 'transact_pattern';
    //供应商管理_评鉴类型
    const SUPPLIER_EVALUATE_TYPE = 'pjlx';
    //供应商管理_免评鉴条件
    const SUPPLIER_AVOID_EVALUATE_CONDITION = 'mpjtj';
    //供应商管理_交易币别
    const SUPPLIER_TRADE_CURRENCY = 'jybb';
    //供应商_交货条件
    const SUPPLIER_DELIVERY_COND = 'jhtj';
    //供应商_付款条件
    const SUPPLIER_PAY_COND = 'fktj';
    //供应商_来源类别
    const SUPPLIER_SOURCE_TYPE = 'lylb';

    //客户关系管理_拜访类型
    //供应商管理_综合等级
    const SUPPLIER_SYNTHESIS_LEVEL = 'zhdj';
    //供应商管理_评鉴结果
    const SUPPLIER_EVALUATE_RESULT = 'pjjg';
    //供应商管理_评鉴意见
    const SUPPLIER_EVALUATE_ADVICE = 'pjyj';
    //供应商_授权区域
    const SUPPLIER_AUTH_AREA = 'gyssqqy';
    //客户关系管理拜访类型
    const CRM_VISIT_TYPE = 'bflx';
    //客户管理_客户等级
    const CRM_CUSTOMER_LEVEL = 'khdj';
    //客户管理_注册网站
    const CRM_REGISTER_WEB = 'zcwz';
    //客户管理_客户来源
    const CRM_CUSTOMER_SOURCE = 'khly';
    //客户管理_经营模式
    const CRM_BUSINESS_MODEL = 'jyms';
    //客户管理_潜在需求
    const CRM_LATENT_DEMAND = 'qzxq';
    //客户管理_会员等级
    const CRM_MEMBER_LEVEL = 'hydj';
    //客户管理_公司规模
    const CRM_COMPANY_SCALE = 'gsgm';
    //客户管理_活动方式
    const CRM_ACTIVE_WAY = 'hdfs';
    //客户管理_参会身份
    const CRM_JOIN_IDENTITY = 'chsf';
    //客户管理_活动月份
    const CRM_ACTIVE_MONTH = 'hdyf';
    //客户管理_行业类别
    const CRM_INDUSTRY_TYPE = 'hylb';
    //客户管理_参与目的
    const CRM_JOIN_PURPOSE = 'cymd';
    //客户管理_发票类型
    const CRM_INVOICE_TYPE = 'fplx';
    //客户管理_活动类型
    const CRM_ACTIVE_TYPE = 'hdlx';
    //客户管理_载体
    const CRM_CARRIER_TYPE = 'ztlx';
    //客户管理_发票需求
    const CRM_INVOICE_NEEDS = 'fpxq';
    //客户管理_所属社群营销方式
    const CRM_SALE_WAY = 'sssqyxfs';
    //销售管理_销售点状态
    const CRM_STORE_STATUS = 'xsdzt';
    //销售管理_人力类型
    const CRM_EMPLOYEE_TYPE = 'rllx';
    //账信申请_付款方式
    const CRM_PAY_METHOD = 'fkfs';
    //账信申请_起算日
    const CRM_INITIAL_DAY = 'qsrq';
    //账信申请_付款日
    const CRM_PAY_DAY = 'fkrq';
    // 订单来源
    const ORDER_FROM = 'ddly';
    // 支付类型
    const PAY_TYPE = 'zfll';
    // 仓库异动类型
    const WAREHOUSE_CHANGE_TYPE = 'ckydlx';
    //职位职能
    const CUST_FUNCTION = 'khzwzn';
    //商品属性
    const GOODS_PROPERTY = 'SPSX';
    //问卷类别
    const QUESTION_TYPE = 'wjlb';
    //单据类型
    const REQ_DCT='DJLX';
    //请购形式
    const REQ_RQF='QGXS';
    //采购区域
    const AREA_ID='CQXX';
    //费用类型
    const CST_TYPE='FYLX';
    //紧急程度
    const URG_ID='jjcd';
    //采购方式
    const REQ_TYPE='CGFS';
    //物料归属
    const MTR_ASS='WLGS';
    //退款类型
    const REFUND_TYPE='tklx';
    //仓库性质
    const WH_NATURE='CKXZ';
    //仓库类别
    const WH_TYPE='CKLB';
    //仓库级别
    const WH_LEV='CKJB';
    //仓库属性
    const WH_ATTR='CKSX';
    //仓库标准操作-->操作类型
    const WH_STANDARD_PRICE='CKBZCZ';
    //出货通知单状态
    const SHP_TYPE='TZDZT';

    public static function tableName()
    {
        return 'bs_pubdata';
    }

    public static function getBsPubdataOne($id)
    {
        return self::find()->where(['bsp_id'=>$id])->one();
    }
    public static function getData($bspType)
    {
        $list = static::find()->select("bsp_id,bsp_svalue")->where(['bsp_stype' => $bspType, 'bsp_status' => 10])->orderBy('bsp_id ASC')->asArray()->all();
        foreach ($list as $key => $val) {
            $newList[$val['bsp_id']] = $val['bsp_svalue'];
        }
        return isset($newList) ? $newList : [];
    }

    public static function getList($bspType)
    {
        $list = static::find()->select("bsp_id,bsp_svalue")->where(['bsp_stype' => $bspType, 'bsp_status' => 10])->asArray()->all();
        return isset($list) ? $list : [];
    }

    public static function getExcelData($type)
    {
        $list = static::find()->select('bsp_id')->where(['bsp_svalue' => $type])->one();
        return $list;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['bsp_svalue', 'unique', 'targetAttribute' => ['bsp_stype', 'bsp_svalue'], 'message' => '参数值已存在', 'filter' => ['!=', 'bsp_status', self::STATUS_DELETE]],
            [['bsp_sname', 'bsp_stype', 'bsp_svalue'], 'required'],
            [['bsp_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['bsp_sname', 'bsp_stype', 'bsp_svalue'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bsp_id' => '參數代碼',
            'bsp_sname' => '參數名稱',
            'bsp_stype' => '代碼',
            'bsp_svalue' => '參數值',
            'bsp_status' => '狀態,0無效10有效11禁用',
            'create_by' => '創建人',
            'create_at' => '創建時間',
            'update_by' => '修改人',
            'update_at' => '修改時間',
        ];
    }
}
