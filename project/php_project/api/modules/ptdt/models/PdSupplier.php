<?php

namespace app\modules\ptdt\models;

use app\behaviors\FormCodeBehavior;
use app\behaviors\StaffBehavior;
use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDevcon;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsTradConditions;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\bootstrap\Html;
use yii\db\BaseActiveRecord;
use yii\db\mssql\TableSchema;

class PdSupplier extends Common
{

    const STATUS_DELETE=0;  //删除
    const STATUS_DEFAULT=10;//待评鉴
    const STATUS_APPLY=20;  //待申请
    const STATUS_REVIEW=30; //待审核
    const STATUS_REJECT=40; //已驳回
    const STATUS_FINISH=50; //已完成
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firm_id', 'supplier_source', 'supplier_type', 'supplier_position', 'supplier_issupplier', 'supplier_pddepid', 'supplier_productperson', 'supplier_report_id', 'supplier_agentstype', 'supplier_pdtype', 'supplier_transacttype', 'supplier_agentstype2', 'supplier_agentslevel', 'supplier_agents_position', 'supplier_authorize_area', 'supplier_salarea', 'supplier_annual_turnover', 'supplier_trade_currency', 'supplier_trade_condition', 'supplier_pay_condition', 'supplier_pre_annual_sales', 'supplier_pre_annual_profit', 'supplier_patent_num', 'supplier_experimence', 'create_by', 'update_by','supplier_authorize_bdate', 'supplier_authorize_edate', 'supplier_create_date', 'create_at', 'update_at','supplier_code', 'supplier_category_id', 'supplier_comptype', 'supplier_scale', 'supplier_compprincipal', 'supplier_comptel', 'supplier_compfax', 'supplier_compmail', 'supplier_contaperson','source_type','supplier_sname', 'supplier_shortname', 'supplier_group_sname', 'supplier_ename', 'supplier_eshortname', 'supplier_brand','supplier_brand_english', 'supplier_main_product', 'outer_cus_object', 'cus_quality_require', 'supplier_web_site', 'supplier_factory_area','supplier_compaddress', 'supplier_remark1', 'supplier_remark2', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5','supplier_is_agents','requirement_description','fjj_chief_negotiator','fjj_chief_extension','supplier_chief_negotiator','supplier_chief_post','material_id','supplier_not_accepted','supplier_advantage','supplier_business','company_id'], 'safe'],
            [['supplier_status'], 'default', 'value' => self::STATUS_DEFAULT]
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
            'supplier_status' => '状态',
            'supplier_code' => '厂商编码',
            'supplier_sname' => '供应商全称',
            'supplier_shortname' => '供应商简称',
            'supplier_group_sname' => '供应商集团简称',
            'supplier_ename' => '英文全称',
            'supplier_eshortname' => '英文简称',
            'supplier_brand' => '品牌',
            'supplier_brand_english' => '商標英文名',
            'supplier_source' => '供应商来源',
            'supplier_type' => '供应商类型',
            'supplier_position' => '供应商地位',
            'supplier_issupplier' => '是否集团供应商',
            'supplier_category_id' => 'Commodity',
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
            'supplier_transacttype' => '代理商品类别',
            'supplier_agentstype2' => '代理类型，關聯公共數據字典',
            'supplier_agentslevel' => '代理等级',
            'supplier_agents_position' => '代理商品定位，關聯公共數據字典',
            'supplier_authorize_area' => '授权区域',
            'supplier_salarea' => '授权范围',
            'supplier_authorize_bdate' => '授权开始日期',
            'supplier_authorize_edate' => '授权结束日期',
            'supplier_main_product' => '主营范围',
            'supplier_annual_turnover' => '年度营业额(USD)',
            'supplier_trade_currency' => '交易币别',
            'supplier_trade_condition' => '交货条件',
            'supplier_pay_condition' => '付款条件',
            'supplier_pre_annual_sales' => '预计年销售额',
            'supplier_pre_annual_profit' => '预计年利润',
            'outer_cus_object' => '外部客户目标',
            'cus_quality_require' => '客戶品質等級要求',
            'supplier_nature' => '公司性质',
            'supplier_create_date' => '公司成立日期',
            'supplier_web_site' => '公司网址',
            'supplier_factory_area' => '厂房面积',
            'supplier_patent_num' => '专利数量',
            'supplier_experimence' => '行业经验,年',
            'supplier_remark1' => '备注信息1',
            'supplier_remark2' => '备注信息2',
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
    private function bsData($type){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>$type]);
    }
    /**
     * 根據ID獲取数据
     * @param $id
     */
    public static function getSupplierInfo($id){
        return static::find()->where(['supplier_id'=>$id])->one();
    }

    //关联主营商品
    public function getMainOne(){
        return $this->hasMany(BsVendorMainlist::className(),['vendor_id'=>'supplier_id'])->one();
    }
    //拟采购商品
    public function getMaterial(){
        $data[]='';
        if(!empty($this->material_id)){
            if($material=unserialize(Html::decode($this->material_id))){
                foreach($material as $val){
                    $data[] = PdMaterialCode::find()->where(['m_id'=>$val])->one();
                }
            }
        }
        return $data;

    }

    /**
     * 关联谈判分析表(最新的表)
     */
    public function GetAgentsAuthorize(){
        $info = $this->hasOne(PdAgentsAuthorize::className(),['firm_id'=>'firm_id']);
        return $info->orderBy("pdaa_id desc");
    }
    /**
     * 关联staff表
     */
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => "create_by"]);
    }
    //类型
    public function getSupplierType(){
        return $this->bsData('supplier_type');
    }
    //来源
    public function getSupplierSource(){
        return $this->bsData('supplier_source');
    }
    //来源类别
    public function getSourceType(){
        return $this->bsData('source_type');
    }
    //代理等级
    public function getAgentsLevel(){
        return $this->bsData('supplier_agentslevel');
    }
    //销售范围
    public function getSupplierSalarea(){
        return $this->bsData('supplier_salarea');
    }
    //销售区域
    public function getAuthorizeArea(){
        return $this->bsData('supplier_authorize_area');
    }
    //地位
    public function getPosition(){
        return $this->bsData('supplier_position');
    }
    //新增类型
    public function getSupplierAddType(){
        return $this->bsData('supplier_add_type');
    }

    //交货条件
    public function getDevcon(){
        return $this->hasOne(BsDevcon::className(),['dec_id'=>'supplier_trade_condition']);
    }

    //收款条件
    public function getPayCondition(){
        return $this->hasOne(BsPayCondition::className(),['pat_id'=>'supplier_pay_condition']);
    }

    //交易币别
    public function getTraceCurrency(){
        return $this->hasOne(BsCurrency::className(),['cur_id'=>'supplier_trade_currency']);
    }
    //代理商品类别
    public function getTransactType(){
        return $this->hasOne(PdProductType::className(),['type_id'=>'supplier_transacttype']);
    }
//    /**
//     * 獲取一階分類
//     */
//    public function getLevelOne($select = 'type_id,type_pid,type_name')
//    {
//        return BsPubdata::find()->select($select)->orderBy('type_index')->andWhere(['type_pid' => 0, 'is_valid' => 1])->all();
//    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class"=>FormCodeBehavior::className(),
                "codeField"=>'supplier_code',
                "formName"=>self::tableName()
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],           //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ],
            ],
        ];
    }

}
