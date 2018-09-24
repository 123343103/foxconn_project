<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\show\PdNegotiationProductShow;
use Yii;
use app\behaviors\FormCodeBehavior;
use app\modules\hr\models\HrStaff;
/**
 * F3859386
 * 2016.10.22
 * 厂商谈判子表模型
 *
 * @property integer $pdnc_id
 * @property integer $pdn_id
 * @property integer $vih_id
 * @property integer $vil_id
 * @property string $pdnc_code
 * @property string $pdnc_date
 * @property string $pdnc_time
 * @property integer $pdnc_receid
 * @property integer $rece_id
 * @property string $pdnc_location
 * @property string $pdnc_product_person
 * @property integer $vacc_id
 * @property string $process_descript
 * @property string $negotiate_concluse
 * @property string $trace_matter
 * @property string $next_notice
 * @property string $pdnc_status
 * @property string $negotiate_others
 * @property string $remark
 * @property integer $pdnc_person
 *
 * @property PdVisitResume $vih
 */
class PdNegotiationChild extends Common
{

    const STATUS_DEL=0; //删除
    const STATUS_DEFAULT=10;//默认
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_negotiation_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['negotiate_concluse','pdnc_location','process_descript', 'trace_matter', 'next_notice', 'negotiate_others', 'remark','pdnc_person', 'pdnc_code','pdn_id', 'attachment', 'rece_id','process_descript', 'negotiate_concluse','next_notice','negotiate_others','trace_matter','vacc_id', 'pdnc_receid','attachment_name','vih_id', 'vil_id','pdnc_code','pdnc_status','pdnc_date', 'pdnc_location','pdnc_time','pdnc_person','visit_planID'], 'safe'],
//            [['negotiate_concluse','pdnc_location','process_descript', 'trace_matter', 'next_notice', 'negotiate_others', 'remark','pdnc_person', 'pdnc_code','pdn_id', 'attachment', 'rece_id','process_descript', 'negotiate_concluse','next_notice','negotiate_others','trace_matter','vacc_id', 'pdnc_receid','attachment_name','vih_id', 'vil_id','pdnc_code','pdnc_status','pdnc_date', 'pdnc_location','pdnc_time','pdnc_person'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'],
            [['pdnc_status'], 'default', 'value' => self::STATUS_DEFAULT],
            [['vih_id'], 'exist', 'skipOnError' => true, 'targetClass' => PdVisitResume::className(), 'targetAttribute' => ['vih_id' => 'vih_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdnc_id' => 'Pdnc ID',
            'pdn_id' => 'Pdn ID',
            'vih_id' => 'Vih ID',
            'vil_id' => 'Vil ID',
            'pdnc_code' => '谈判履历子编号',
            'pdnc_date' => '谈判日期',
            'pdnc_time' => '谈判时间',
            'pdnc_receid' => '厂商主谈人',
            'rece_id' => '厂商接待人员',
            'pdnc_location' => '谈判地点',
            'vacc_id' => '主谈陪同人员表id',
            'process_descript' => '过程描述',
            'negotiate_concluse' => '谈判结论',
            'trace_matter' => '追踪事项',
            'next_notice' => '下次访谈注意事项',
            'pdnc_status' => '状态',
            'negotiate_others' => '其他(谈判事项)',
            'remark' => '备注',
            'pdnc_person' => '主谈人',
            'staff_title' => '职位',
            'attachment' => '附件',
            'attachment_name' => '附件名',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVih()
    {
        return $this->hasOne(PdVisitResume::className(), ['vih_id' => 'vih_id']);
    }

    //获取谈判结论
    public function getConcluse(){
        return $this->getData('negotiate_concluse');
    }
    //关联公共库
    public function getData($type){
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => $type]);
    }
    //关联主谈人(商品经理人)
    public function getPdProductPerson(){
        return $this->hasOne(HrStaff::className(),['staff_code'=>'pdnc_person']);
    }
    //关联厂商主谈人
    public function getFirmReception(){
        return $this->hasOne(PdReception::className(),['l_id'=>'pdnc_id'])->where(['rece_main'=>1,'rece_type'=>2]);
    }
    //关联陪同人员信息表
    public function getPdAccompany(){
        return $this->hasMany(PdAccompany::className(),['l_id'=>'pdnc_id'])->where(['vacc_type'=>3]);
    }
    //关联商品表
    public function getProduct(){
        return $this->hasMany(PdNegotiationProductShow::className(),['pdnc_id'=>'pdnc_id'])->where(['pdnp_status'=>PdNegotiationProduct::STATUS_DEFAULT]);
    }
    //关联员工
    public function getStaff(){
        return $this->hasMany(HrStaff::className(),['staff_code'=>'staff_code'])->via('pdAccompany');
    }

    /*关联谈判分析表*/
    public function getNegotiationAnalysis(){
        return $this->hasOne(PdNegotiationAnalysis::className(),['pdnc_id'=>'pdnc_id']);
    }
    
    public function getCooperateDegree(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'pdna_cooperate_degree'])->via('negotiationAnalysis');
    }

    public function behaviors()
    {
        return [
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),     //计画编号自动赋值
                'codeField'=>'pdnc_code',                   //注释字段名
                'formName'=>self::tableName()         //注释表名
            ]
        ];
    }
}
