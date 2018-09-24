<?php

namespace app\modules\ptdt\models;
use app\models\Common;
use app\modules\ptdt\models\show\PdFirmShow;
use Yii;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\behaviors\FormCodeBehavior;

use app\modules\common\models\BsCompany;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

class PdFirmReport extends Common
{
    const REPORT_DELETE = 0;  //删除
    const REPORT_PREPARE = 10;//被驳回
    const REPORT_ADD = 20;    //新增呈报
    const CHECK_PENDING = 40;//待审核
    const REPORT_COMPLETE = 50; //呈报完成
    const FIRM_REPORT=12;
    public static function tableName()
    {
        return 'pd_firm_report';
    }

    public  static function getOne($id,$companyId){
        return self::find()->where(['pfr_id'=>$id])
            ->andWhere(["!=",'report_status',self::REPORT_DELETE])
            ->andWhere(['in','company_id',BsCompany::getIdsArr($companyId)])->one();
    }
    public  static function getReportOne($id,$select=null){
        return self::find()->where(['firm_id'=>$id])->select($select)->one();
    }

    public static function getFirmReport($id,$select=null){
        return self::find()->where(['pfr_id'=>$id])->select($select)->one();
    }

    /**
     * 查询厂商信息
     */
    public function getFirmMessage(){
        return $this->hasOne(PdFirmShow::className(),['firm_id'=>'firm_id']);
    }
    /**
     * 關聯廠商來源
     * @return \yii\db\ActiveQuery
     */
    public function getFirmSource(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_source'])->via('firmMessage');
    }
    /**
     * 查詢厂商类型
     */
    public function getFirmType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_type'])->via('firmMessage');
    }
    /**
     * 获取厂商地位
     */
    public function getFirmPosition(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_position'])->via('firmMessage');
    }
    /**
     * @return $this
     * 关联子表
     */
    public function getChildUndelete(){
        return $this->hasMany(PdFirmReportChild::className(),['pfr_id'=>'pfr_id'])->andFilterWhere(['!=','pfrc_status','0'])->orderBy(['pfrc_date'=>SORT_DESC,'pfrc_time'=>SORT_DESC]);
    }

    /**
     * 关联创建人
     */
    public function getBuildStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
    }
    /*代理類型*/
    public function getAgentsType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'report_agents_type']);
    }
    /*開發類型*/
    public function getDevelopType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'report_develop_type']);
    }
    /*緊急程度*/
    public function getUrgencyDegree(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'report_urgency_degree']);
    }
    /*查询厂商对比分析表*/
    public function getFirmCompared(){
        return $this->hasMany(PdFirmReportCompared::className(),['pfr_id'=>'pfr_id','pfr_code'=>'report_code'])->orderBy('prc_id');
    }
    public function getStatus(){
        switch ($this->report_status){
            case PdFirmReport::REPORT_PREPARE:
                return "被驳回";break;
            case PdFirmReport::REPORT_ADD:
                return "新增呈报";break;
//            case PdFirmReport::REPORTING:
//                return "呈报中";break;
            case PdFirmReport::CHECK_PENDING:
                return "待审核";break;
            case PdFirmReport::REPORT_COMPLETE:
                return "呈报完成";break;

        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firm_id', 'report_status', 'pdn_id', 'pdnc_id', 'pdna_id', 'pdaa_id', 'report_verifyter', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['report_date', 'report_senddate', 'report_verifydate', 'create_at', 'update_at'], 'safe'],
            [['report_code', 'report_agents_type', 'report_develop_type', 'report_urgency_degree'], 'safe'],
            [['report_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pfr_id' => 'id',
            'report_code' => '呈报编号',
            'report_agents_type' => '關聯公共數據字典表,代理类型',
            'report_develop_type' => '關聯公共數據字典表,开发类型',
            'report_urgency_degree' => '紧急程度',
            'firm_id' => '厂商ID',
            'report_date' => '呈报日期',
            'report_status' => '呈报状态',
            'pdn_id' => '關聯談判主表id',
            'pdnc_id' => '關聯談判履歷表id,取得代理權的談判履歷',
            'pdna_id' => '關聯談判分析表',
            'pdaa_id' => '關聯代理授權表',
            'report_senddate' => '呈报提交日期',
            'report_verifyter' => '审核人',
            'report_verifydate' => '审核日期',
            'report_remark' => '备注',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'update_at' => '更新时间',
            'update_by' => '更新人',
        ];
    }
    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //时间字段自动赋值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at','report_date'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']  //更新
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());       //赋值的值来源,如不同複写
                }
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),     //计画编号自动赋值
                'codeField'=>'report_code',                    //注释字段名
                'formName'=>'pd_firm_report',               //注释表名
                'model'=>$this
            ]
        ];
    }

}
