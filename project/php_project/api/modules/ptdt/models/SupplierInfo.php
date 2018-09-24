<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "supplier_info".
 *
 * @property string $supplier_id
 * @property integer $firm_id
 * @property integer $supplier_status
 * @property string $supplier_code
 * @property string $supplier_sname
 * @property string $supplier_shortname
 * @property string $supplier_group_sname
 * @property string $supplier_ename
 * @property string $supplier_eshortname
 * @property string $supplier_brand
 * @property string $supplier_brand_english
 * @property integer $supplier_source
 * @property integer $supplier_type
 * @property string $supplier_add_type
 * @property integer $supplier_position
 * @property integer $supplier_issupplier
 * @property string $supplier_category_id
 * @property string $supplier_comptype
 * @property string $supplier_scale
 * @property string $supplier_compaddress
 * @property string $supplier_compprincipal
 * @property string $supplier_comptel
 * @property string $supplier_compfax
 * @property string $supplier_compmail
 * @property string $supplier_contaperson
 * @property string $supplier_pddepid
 * @property string $supplier_productperson
 * @property integer $supplier_report_id
 * @property integer $supplier_agentstype
 * @property integer $supplier_pdtype
 * @property integer $supplier_transacttype
 * @property integer $supplier_agentstype2
 * @property integer $supplier_agentslevel
 * @property integer $supplier_agents_position
 * @property string $supplier_is_agents
 * @property integer $supplier_authorize_area
 * @property integer $supplier_salarea
 * @property string $supplier_authorize_bdate
 * @property string $supplier_authorize_edate
 * @property string $supplier_main_product
 * @property integer $supplier_annual_turnover
 * @property integer $supplier_trade_currency
 * @property integer $supplier_trade_condition
 * @property integer $supplier_pay_condition
 * @property string $supplier_pre_annual_sales
 * @property string $supplier_pre_annual_profit
 * @property string $source_type
 * @property string $outer_cus_object
 * @property string $cus_quality_require
 * @property string $supplier_nature
 * @property string $supplier_create_date
 * @property string $supplier_web_site
 * @property string $supplier_factory_area
 * @property integer $supplier_patent_num
 * @property string $supplier_experimence
 * @property string $requirement_description
 * @property string $supplier_advantage
 * @property string $supplier_business
 * @property string $supplier_not_accepted
 * @property string $supplier_chief_negotiator
 * @property string $supplier_chief_post
 * @property string $fjj_chief_negotiator
 * @property string $fjj_chief_extension
 * @property string $material_id
 * @property string $supplier_remark1
 * @property string $supplier_remark2
 * @property string $company_id
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 * @property string $vdef1
 * @property string $vdef2
 * @property string $vdef3
 * @property string $vdef4
 * @property string $vdef5
 */
class SupplierInfo extends Common
{
    const STATUS_NORMAL     = '50';     // 审核完成(与供应商申请对应) 有效/正常状态
    const STATUS_SEAL       = '60';     // 封存

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firm_id', 'supplier_status', 'supplier_source', 'supplier_type', 'supplier_position', 'supplier_issupplier', 'supplier_pddepid', 'supplier_productperson', 'supplier_report_id', 'supplier_agentstype', 'supplier_pdtype', 'supplier_transacttype', 'supplier_agentstype2', 'supplier_agentslevel', 'supplier_agents_position', 'supplier_authorize_area', 'supplier_salarea', 'supplier_annual_turnover', 'supplier_trade_currency', 'supplier_trade_condition', 'supplier_pay_condition', 'supplier_pre_annual_sales', 'supplier_pre_annual_profit', 'supplier_patent_num', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['supplier_authorize_bdate', 'supplier_authorize_edate', 'supplier_create_date', 'create_at', 'update_at'], 'safe'],
            [['requirement_description', 'supplier_advantage', 'supplier_business', 'supplier_not_accepted'], 'string'],
            [['supplier_code', 'supplier_category_id', 'supplier_comptype', 'supplier_scale', 'supplier_compprincipal', 'supplier_comptel', 'supplier_compfax', 'supplier_compmail', 'supplier_contaperson'], 'string', 'max' => 20],
            [['supplier_sname', 'supplier_shortname', 'supplier_group_sname', 'supplier_ename', 'supplier_eshortname', 'supplier_brand'], 'string', 'max' => 60],
            [['supplier_brand_english', 'supplier_add_type', 'supplier_main_product', 'outer_cus_object', 'cus_quality_require', 'supplier_web_site', 'supplier_factory_area', 'material_id'], 'string', 'max' => 255],
            [['supplier_compaddress', 'supplier_remark1', 'supplier_remark2', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],
            [['supplier_is_agents'], 'string', 'max' => 5],
            [['source_type', 'supplier_chief_negotiator', 'fjj_chief_negotiator'], 'string', 'max' => 55],
            [['supplier_nature'], 'string', 'max' => 64],
            [['supplier_experimence'], 'string', 'max' => 11],
            [['supplier_chief_post'], 'string', 'max' => 30],
            [['fjj_chief_extension'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'supplier_id' => '主鍵',
            'firm_id' => '关联廠商ID',
            'supplier_status' => '状态 0:删除 10:默认',
            'supplier_code' => '申请编号',
            'supplier_sname' => '供应商全称',
            'supplier_shortname' => '供应商简称',
            'supplier_group_sname' => '供应商集团简称',
            'supplier_ename' => '英文全称',
            'supplier_eshortname' => '英文简称',
            'supplier_brand' => '品牌',
            'supplier_brand_english' => '商標英文名',
            'supplier_source' => '来源,關聯公共數據字典',
            'supplier_type' => '类型,關聯公共數據字典',
            'supplier_add_type' => '新增类型',
            'supplier_position' => '地位,關聯公共數據字典',
            'supplier_issupplier' => '是否集团供应商',
            'supplier_category_id' => '分级分类',
            'supplier_comptype' => '企业类别',
            'supplier_scale' => '公司规模',
            'supplier_compaddress' => '供应商地址',
            'supplier_compprincipal' => '公司负责人',
            'supplier_comptel' => '公司联系电话',
            'supplier_compfax' => '公司传真',
            'supplier_compmail' => '公司邮箱',
            'supplier_contaperson' => '联络人ID',
            'supplier_pddepid' => '开发部门',
            'supplier_productperson' => '商品经理人',
            'supplier_report_id' => '代理呈报表ID',
            'supplier_agentstype' => '代理类别，關聯公共數據字典',
            'supplier_pdtype' => '开发类型，關聯公共數據字典',
            'supplier_transacttype' => '交易商品类别，關聯公共數據字典',
            'supplier_agentstype2' => '代理类型，關聯公共數據字典',
            'supplier_agentslevel' => '代理等级，關聯公共數據字典',
            'supplier_agents_position' => '代理商品定位，關聯公共數據字典',
            'supplier_is_agents' => '是否取得代理',
            'supplier_authorize_area' => '授权区域范围，關係行政地區表',
            'supplier_salarea' => '销售范围，關係行政地區表',
            'supplier_authorize_bdate' => '授权开始日期',
            'supplier_authorize_edate' => '授权结束日期',
            'supplier_main_product' => '厂商主营商品范围',
            'supplier_annual_turnover' => '年营业额',
            'supplier_trade_currency' => '交易币种ID',
            'supplier_trade_condition' => '交货条件',
            'supplier_pay_condition' => '付款条件',
            'supplier_pre_annual_sales' => '预计年销售额',
            'supplier_pre_annual_profit' => '预计年利润',
            'source_type' => '来源类别',
            'outer_cus_object' => '外部客户目标',
            'cus_quality_require' => '客戶品質等級要求',
            'supplier_nature' => '公司性质',
            'supplier_create_date' => '公司成立日期',
            'supplier_web_site' => '公司网址',
            'supplier_factory_area' => '厂房面积',
            'supplier_patent_num' => '专利数量',
            'supplier_experimence' => '行业经验,年',
            'requirement_description' => '需求说明',
            'supplier_advantage' => '优势',
            'supplier_business' => '商机',
            'supplier_not_accepted' => '未取得受理原因',
            'supplier_chief_negotiator' => '供应商主谈人',
            'supplier_chief_post' => '供应商主谈人职务',
            'fjj_chief_negotiator' => '富金机主谈人',
            'fjj_chief_extension' => '富金机主谈人分机',
            'material_id' => '拟采购商品ID',
            'supplier_remark1' => '备注信息1',
            'supplier_remark2' => '备注信息2',
            'company_id' => '创建人公司',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
            'vdef1' => 'Vdef1',
            'vdef2' => 'Vdef2',
            'vdef3' => 'Vdef3',
            'vdef4' => 'Vdef4',
            'vdef5' => 'Vdef5',
        ];
    }
}
