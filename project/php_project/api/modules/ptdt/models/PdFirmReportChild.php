<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsSettlement;
use app\modules\hr\models\HrStaff;
use Yii;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\show\PdFirmReportProductShow;

class PdFirmReportChild extends Common
{
    const STATUS_DEL=0;
    const STATUS_DEFAULT=10;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_firm_report_child';
    }



    public static function getChildById($id){
        return static::find()->where(['pfr_id'=>$id])->andWhere(["!=",'pfrc_status',self::STATUS_DEL])->orderBy('pfrc_date desc')->orderBy('pfrc_time desc')->one();
    }

    public static function getChildOne($id){
        return self::find()->where(['pfrc_id'=>$id])->one();
    }

    public static function getNewChildById($id){
        return self::find()->where(['pfr_id'=>$id])->one();
    }
    /**
     * 商品定位
     */
    public function getProductLevel(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'product_level']);
    }

    public function getFirmReport(){
        return $this->hasOne(PdFirmReport::className(),['pfr_id'=>'pfr_id']);
    }
    /**
     * 关联谈判分析表
     */
    public function getNegotiationAnalysis(){
        return $this->hasOne(PdNegotiationAnalysis::className(),['pdna_id'=>'pdna_id']);
    }
    public function getCooperateDegree(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdna_cooperate_degree'])->via('negotiationAnalysis');
    }
    public function getAnalysisFirm(){
        return $this->hasOne(PdFirm::className(),['firm_id'=>'firm_id'])->via('negotiationAnalysis');
    }
    public function getAnalysisDegree(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdna_cooperate_degree'])->via('negotiationAnalysis');
    }
    public function getGoodsLoction(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdna_loction'])->via('negotiationAnalysis');
    }
    public function getFirmPosition(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdna_position'])->via('negotiationAnalysis');
    }

    /**
     * 關聯代理授權表
     */
    public function getAgentsAuthorize(){
        return $this->hasOne(PdAgentsAuthorize::className(),['pdaa_id'=>'pdaa_id']);
    }
    public function getAgentsGrade(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdaa_agents_grade'])->via('agentsAuthorize');
    }
    public function getAuthorizeArea(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdaa_authorize_area'])->via('agentsAuthorize');
    }
    public function getSaleArea(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdaa_sale_area'])->via('agentsAuthorize');
    }
    public function getFirmService(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdaa_service'])->via('agentsAuthorize');
    }
    public function getFirmDeliveryWay(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdaa_delivery_way'])->via('agentsAuthorize');
    }
    public function getSettlement(){
        return $this->hasOne(BsSettlement::className(),['bnt_id'=>'pdaa_settlement'])->via('agentsAuthorize');
    }

    public function getConcluse(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'negotiate_concluse']);
    }

    //关联主谈人(商品经理人)
    public function getPdProductPerson(){
        return $this->hasOne(HrStaff::className(),['staff_code'=>'pfrc_person']);
    }
//    public function getProductStaff(){
//        return $this->hasOne(HrStaff::className(),['staff_code'=>'staff_code'])->via('pdProductPerson');
//    }
    //关联厂商主谈人
    public function getFirmReception(){
        return $this->hasOne(PdReception::className(),['l_id'=>'pfrc_id'])->where(['rece_main'=>1,'rece_type'=>2]);
    }
    //关联陪同人员信息表
    public function getPdAccompany(){
        return $this->hasMany(PdAccompany::className(),['l_id'=>'pfrc_id'])->where(['vacc_type'=>3]);
    }

    //关联员工
    public function getStaff(){
        return $this->hasMany(HrStaff::className(),['staff_code'=>'staff_code'])->via('pdAccompany');
    }

    public function getProduct(){
        return $this->hasMany(PdFirmReportProductShow::className(),['pfrc_id'=>'pfrc_id']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pfrc_date','pfrc_time','pfrc_location','pfrc_person'],'required'],
            [['pfr_id', 'pdna_id', 'pdaa_id', 'rece_id','pfrc_status'], 'integer'],
            [['pfrc_date', 'pfrc_time'], 'safe'],
            //[['pfrc_status'], 'string', 'max' => 3],
            [['pfrc_code', 'pfrc_person'], 'string', 'max' => 30],
            [['pfrc_receid'], 'string', 'max' => 100],
            [['pfrc_location', 'process_descript', 'trace_matter', 'next_notice', 'negotiate_others', 'remark'], 'string', 'max' => 200],
            [['vacc_id'], 'string', 'max' => 255],
            [['negotiate_concluse'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pfrc_id' => 'id',
            'pfr_id' => '主表ID',
            'pdna_id' => '谈判分析ID',
            'pdaa_id' => '代理授权Id',
            'pfrc_status' => '每一條履歷的狀態,10正常0取消',
            'pfrc_code' => '呈报子編號',
            'pfrc_date' => '談判日期',
            'pfrc_time' => '談判時間',
            'pfrc_receid' => '廠商主談人',
            'rece_id' => '廠商接待人員',
            'pfrc_location' => '谈判地點',
            'pfrc_person' => '主談人',
            'vacc_id' => '主谈陪同人員表id',
            'process_descript' => '過程描述',
            'negotiate_concluse' => '談判結論',
            'trace_matter' => '追蹤事項',
            'next_notice' => '下次訪談注意事項',
            'negotiate_others' => '其他(談判事項)',
            'remark' => '备注',
        ];
    }
    public function behaviors()
    {
        return [

//            'timeStamp'=>[
//                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['report_child_date'],  //插入
//                ],
//                'value'=>function(){
//                    return date("Y-m-d H:i:s",time());       //賦值的值來源,如不同複寫
//                }
//            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),     //計畫編號自動賦值
                'codeField'=>'pfrc_code',                    //註釋字段名
                'formName'=>'pd_firm_report_child',               //註釋表名
                'model'=>$this
            ]
        ];
    }
}
