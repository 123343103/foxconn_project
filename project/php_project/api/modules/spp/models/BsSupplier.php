<?php

namespace app\modules\spp\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_supplier".
 *
 * @property string $spp_id
 * @property integer $company_id
 * @property integer $type_id
 * @property integer $data_from
 * @property string $apply_code
 * @property string $spp_code
 * @property string $group_code
 * @property integer $spp_status
 * @property string $spp_fname
 * @property string $spp_sname
 * @property string $spp_gsname
 * @property string $spp_brand
 * @property string $commodify
 * @property string $add_type
 * @property integer $isvalid
 * @property string $group_spp
 * @property string $spp_type
 * @property string $spp_type_dsc
 * @property string $spp_source
 * @property string $spp_source_dsc
 * @property integer $spp_addr_id
 * @property string $spp_addr_det
 * @property string $spp_legal_per
 * @property string $spp_position
 * @property integer $trade_cy
 * @property string $year_turn
 * @property integer $year_turn_cy
 * @property string $sale_turn
 * @property integer $sale_turn_cy
 * @property string $sale_profit
 * @property integer $sale_profit_cy
 * @property integer $delivery_cond
 * @property integer $pay_cond
 * @property string $source_type
 * @property string $main_business
 * @property string $target_customer
 * @property string $customer_quality
 * @property string $agency_auth
 * @property string $auth_stime
 * @property string $auth_etime
 * @property string $agency_level
 * @property string $auth_product
 * @property string $auth_area
 * @property string $auth_scope
 * @property string $spp_neg
 * @property string $spp_neg_p
 * @property string $fox_neg
 * @property string $fox_neg_t
 * @property string $requ_desc
 * @property string $advantage
 * @property string $business
 * @property string $cause
 * @property string $oper_id
 * @property string $oper_time
 */
class BsSupplier extends Common
{
    public $codeType;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_supplier';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('spp');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'spp_fname'], 'required'],
            [['company_id', 'type_id', 'data_from', 'spp_status', 'commodify', 'add_type', 'isvalid', 'spp_type', 'spp_source', 'spp_addr_id', 'spp_position', 'trade_cy', 'year_turn_cy', 'sale_turn_cy', 'sale_profit_cy', 'delivery_cond', 'pay_cond', 'source_type', 'agency_level', 'auth_product', 'auth_area', 'auth_scope', 'oper_id'], 'integer'],
            [['year_turn', 'sale_turn', 'sale_profit'], 'number'],
            [['auth_stime', 'auth_etime', 'oper_time'], 'safe'],
            [['apply_code', 'spp_code', 'group_code', 'spp_fname'], 'string', 'max' => 100],
            [['spp_sname', 'spp_brand', 'spp_addr_det'], 'string', 'max' => 50],
            [['spp_gsname'], 'string', 'max' => 20],
            [['group_spp', 'agency_auth'], 'string', 'max' => 1],
            [['spp_type_dsc', 'spp_source_dsc', 'main_business', 'target_customer', 'customer_quality', 'requ_desc', 'advantage', 'business', 'cause'], 'string', 'max' => 200],
            [['spp_legal_per', 'spp_neg', 'spp_neg_p', 'fox_neg', 'fox_neg_t'], 'string', 'max' => 10],
            ['spp_fname', 'unique', 'message' => '已存在', 'filter' => ['!=', 'spp_status', 0]],
            [['spp_code', 'group_code'], 'unique', 'message' => '已存在'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spp_id' => '供应商id',
            'company_id' => '公司id',
            'type_id' => '单据类型id(关联erp.bs_bussiness_type.business_type_id)',
            'data_from' => '数据来源(1:erp表单新增;2:定价系统抓取;)',
            'apply_code' => '申请编码',
            'spp_code' => '供应商编码',
            'group_code' => '集团编码',
            'spp_status' => '供应商状态(0:删除,1:未提交,2:审核中,3:审核完成,4:驳回)',
            'spp_fname' => '供应商全称',
            'spp_sname' => '供应商简称',
            'spp_gsname' => '供应商集团简称',
            'spp_brand' => '供应商品牌',
            'commodify' => '十八大类(关联pdt.bs_category.catg_id)',
            'add_type' => '新增类型(关联erp.bs_pubdata.bsp_id)',
            'isvalid' => '是否有效(0:封存,1:正常)',
            'group_spp' => '集团供应商(Y:是,N:否)',
            'spp_type' => '供应商类型(关联erp.bs_pubdata.bsp_id)',
            'spp_type_dsc' => '供应商类型为其它时说明',
            'spp_source' => '供应商来源(关联erp.bs_pubdata.bsp_id)',
            'spp_source_dsc' => '供应商来源为其它时说明',
            'spp_addr_id' => '供应商地址id(关联erp.bs_district.district_id)',
            'spp_addr_det' => '供应商地址详情',
            'spp_legal_per' => '供应商法人',
            'spp_position' => '供应商地位(关联erp.bs_pubdata.bsp_id)',
            'trade_cy' => '交易币别(关联erp.bs_currency.cur_id)',
            'year_turn' => '年营业额',
            'year_turn_cy' => '年营业额币别(关联erp.bs_currency.cur_id)',
            'sale_turn' => '预计年销售额',
            'sale_turn_cy' => '预计年销售额币别(关联erp.bs_currency.cur_id)',
            'sale_profit' => '预计年销售利润',
            'sale_profit_cy' => '预计年销售利润币别(关联erp.bs_currency.cur_id)',
            'delivery_cond' => '交货条件(关联erp.bs_devcon.dec_id)',
            'pay_cond' => '付款条件(关联erp.bs_payment.pac_id)',
            'source_type' => '来源类别(关联erp.bs_pubdata.bsp_id)',
            'main_business' => '主营范围',
            'target_customer' => '外部目标客戶',
            'customer_quality' => '客戶品质等级要求',
            'agency_auth' => '是否取得代理授权(Y:是,N:否)',
            'auth_stime' => '授权开始日期',
            'auth_etime' => '授权结束日期',
            'agency_level' => '代理等级(关联erp.bs_pubdata.bsp_id)',
            'auth_product' => '授权商品类别',
            'auth_area' => '授权区域(关联erp.bs_pubdata.bsp_id)',
            'auth_scope' => '授权范围(关联erp.bs_pubdata.bsp_id)',
            'spp_neg' => '供应商主谈人',
            'spp_neg_p' => '供应商主谈人职务',
            'fox_neg' => '富金机主谈人',
            'fox_neg_t' => '富金机主谈人分机',
            'requ_desc' => '新增需求说明',
            'advantage' => '优势',
            'business' => '商机',
            'cause' => '未取得受理原因',
            'oper_id' => '操作人(关联erp.hr_staff.staff_id)',
            'oper_time' => '操作时间',
        ];
    }
}
