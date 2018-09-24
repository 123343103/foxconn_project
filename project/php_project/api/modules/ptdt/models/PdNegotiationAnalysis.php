<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use Yii;

/**
 * F3859386
 * 2016.10.22
 * 厂商谈判分析模型
 *
 * @property string $pdna_id
 * @property string $pdnc_id
 * @property string $pdn_id
 * @property string $pdn_code
 * @property string $pdnc_code
 * @property string $firm_id
 * @property string $pdnc_date
 * @property string $pdnc_time
 * @property string $pdna_position
 * @property string $pdna_annual_sales
 * @property string $pdna_influence
 * @property string $pdna_cooperate_degree
 * @property string $pdna_technology_service
 * @property string $pdna_pdtype
 * @property string $pdna_loction
 * @property string $pdna_others
 * @property string $pdna_customer_base
 * @property string $pdna_goods_certificate
 * @property string $pdna_demand_trends
 * @property string $pdna_market_share
 * @property string $sales_advantage
 * @property string $profit_analysis
 * @property string $value_frim
 * @property string $value_fjj
 * @property string $pdna_status
 * @property string $pdna_description
 * @property string $pdna_remark
 */
class PdNegotiationAnalysis extends Common
{

    const STATUS_DEL=0;
    const STATUS_DEFAULT=10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_negotiation_analysis';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdna_position','pdna_influence','pdna_annual_sales','pdna_cooperate_degree','pdna_technology_service','pdna_others','pdna_pdtype','pdna_loction','pdna_customer_base','pdna_goods_certificate','pdna_demand_trends','pdna_market_share','sales_advantage','profit_analysis'], 'safe'],
            [['pdna_id', 'pdnc_id', 'pdn_id', 'firm_id','pdnc_date', 'pdnc_time','pdna_status','pdnc_code','pdna_description', 'pdna_remark', 'value_frim', 'value_fjj'], 'safe'],
            [['pdna_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdna_id' => 'Pdna ID',
            'pdnc_id' => 'Pdnc ID',
            'pdn_id' => 'Pdn ID',
            'pdn_code' => 'Pdn Code',
            'pdnc_code' => 'Pdnc Code',
            'firm_id' => 'Firm ID',
            'pdnc_date' => '谈判日期',
            'pdnc_time' => '谈判时间',
            'pdna_position' => '地位',
            'pdna_annual_sales' => '厂商年营业额',
            'pdna_influence' => '业界影响力/排名',
            'pdna_cooperate_degree' => '厂商配合度',
            'pdna_technology_service' => '技术实力/技术服务',
            'pdna_pdtype' => '商品类别',
            'pdna_loction' => '商品定位',
            'pdna_others' => '其他',
            'pdna_customer_base' => '客户群（by 产业）',
            'pdna_goods_certificate' => '商品认证/厂商认证',
            'pdna_demand_trends' => '市场需求趋势',
            'pdna_market_share' => '销量/市占率',
            'sales_advantage' => '价格优势',
            'profit_analysis' => '利润分析',
            'value_frim' => '代理价值(厂商)',
            'value_fjj' => '代理价值(富金机)',
            'pdna_status' => '状态',
            'pdna_description' => '分析描述',
            'pdna_remark' => '备注',
        ];
    }

    public static function getAnalysisById($id){
        return static::find()->where(['firm_id'=>$id])->orderBy(['pdnc_date'=>SORT_DESC,'pdnc_time' => SORT_DESC])->one();
    }
    /**
     * 关联厂商
     * @return \yii\db\ActiveQuery
     */
    public function getFirm(){
        return $this->hasOne(PdFirm::className(),['firm_id'=>'firm_id']);
    }

    //获取代理商品定位
    public function getLoction(){
        return $this->getData('pdna_loction');
    }
    public function getDegree(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdna_cooperate_degree']);
    }
    //获取厂商配合度
    public function getCooperateDegree(){
        return $this->getData('pdna_cooperate_degree');
    }
    //获取厂商地位
    public function getPosition(){
        return $this->getData('pdna_position');
    }
    //关联商品类型
    //F1678086 -- 龚浩晋 修改[type_id=>type_no]
    public function getProductType(){
        return $this->hasOne(PdProductType::className(), ['type_no' => 'pdna_pdtype']);
    }
    //关联公共库
    public function getData($type){
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => $type]);
    }
    //关联厂商
    public function getAnalysisFirm(){
        return $this->hasOne(PdFirm::className(),['firm_id'=>'firm_id']);
    }
}
