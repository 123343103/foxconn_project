<?php
/**
 * 厂商模型
 */

namespace app\modules\ptdt\models;


use app\models\Common;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsFormCodeRule;
use Yii;
use app\modules\common\models\BsPubdata;
use app\behaviors\StaffBehavior;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
use app\modules\hr\models\HrStaff;
use app\behaviors\FormCodeBehavior;
use app\modules\common\models\BsCompany;
use yii\helpers\Html;

/**
 * This is the model class for table "pd_firm_info".
 *
 * @property string $firm_id
 * @property integer $firm_status
 * @property string $firm_code
 * @property string $firm_sname
 * @property string $firm_shortname
 * @property string $firm_ename
 * @property string $firm_eshortname
 * @property string $firm_brand
 * @property integer $firm_source
 * @property integer $firm_type
 * @property integer $firm_position
 * @property integer $firm_issupplier
 * @property string $firm_category_id
 * @property string $firm_comptype
 * @property string $firm_scale
 * @property string $firm_compaddress
 * @property string $firm_compprincipal
 * @property string $firm_comptel
 * @property string $firm_compfax
 * @property string $firm_compmail
 * @property string $firm_contaperson
 * @property string $firm_contaperson_tel
 * @property string $firm_contaperson_mobile
 * @property string $firm_contaperson_mail
 * @property string $firm_pddepid
 * @property string $firm_productperson
 * @property integer $firm_agentstype
 * @property integer $firm_pdtype
 * @property integer $firm_transacttype
 * @property integer $firm_agentstype2
 * @property integer $firm_agentslevel
 * @property integer $firm_agents_position
 * @property integer $firm_authorize_area
 * @property integer $firm_salarea
 * @property string $firm_authorize_bdate
 * @property string $firm_authorize_edate
 * @property string $firm_product
 * @property string $firm_remark1
 * @property string $firm_remark2
 * @property string $creator
 * @property string $create_at
 * @property string $updater
 * @property string $update_at
 * @property string $vdef1
 * @property string $vdef2
 * @property string $vdef3
 * @property string $vdef4
 * @property string $vdef5
 */
class PdFirm extends Common
{
    const STATUS_DELETE = 0;
    const STATUS_DEFAULT = 10;
    const PLAN_STATUS_ACTION = '20';        //拜访中
    const PLAN_STATUS_OVER = 30;            //拜访完成
    const STATUS_HALF = 40;                  //谈判中
    const STATUS_END  = 50;                  //谈判完成
    const CHECK_PENDING = 60;               //呈报中
    const DEVELOP_OVER = '70';              //开发完成

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_firm'; //表名称
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firm_sname','firm_issupplier','firm_source'], 'required'],
            [['firm_sname'],'unique','targetAttribute'=>'firm_sname','message'=>'{attribute}已经存在'],
            [[ 'firm_status','firm_district_id','company_id', 'firm_source', 'firm_type', 'firm_position', 'firm_issupplier', 'firm_pddepid', 'firm_productperson', 'firm_agentstype', 'firm_pdtype', 'firm_transacttype', 'firm_agentstype2', 'firm_agentslevel', 'firm_agents_position', 'firm_authorize_area', 'firm_salarea', 'create_by', 'update_by'], 'integer'],
            [['firm_authorize_bdate','firm_category_id', 'firm_authorize_edate', 'create_at', 'update_at'], 'safe'],
            [['firm_code',  'firm_comptype', 'firm_scale', 'firm_compprincipal', 'firm_comptel', 'firm_compfax', 'firm_compmail', 'firm_contaperson', 'firm_contaperson_tel', 'firm_contaperson_mobile', 'firm_contaperson_mail'], 'string', 'max' => 20],
            [['firm_sname', 'firm_shortname', 'firm_ename', 'firm_eshortname', 'firm_brand','firm_brand_english'], 'string', 'max' => 60],
            [['firm_compaddress', 'firm_annual_turnover', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],
            [['firm_remark1','firm_remark2'], 'string','max' => 2000],
            [['firm_brand'],
                'requiredByASpecial',
                'skipOnEmpty' => false, 'skipOnError' => false],

        ];
    }

    /**
     * 自定义验证两项必填一项
     * @return bool
     */
    public function requiredByASpecial(){
        if(empty($this->firm_brand) && empty($this->firm_brand_english) ){
            $this->addError('firm_brand',"两项必填一个");
            return false;
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'firm_id' => '厂商ID',
            'firm_status' => '厂商状态',
            'firm_code' => '厂商编码',
            'firm_sname' => '厂商注册名称',
            'firm_shortname' => '简称',
            'firm_ename' => '英文名称',
            'firm_eshortname' => '英文简称',
            'firm_brand' => '品牌',
            'firm_brand_english'=> '品牌英文名',
            'firm_source' => '来源',
            'firm_type' => '类型',
            'firm_position' => '地位',
            'firm_issupplier' => '是否为集团供应商',
            'firm_category_id' => '分级分类',
            'firm_district_id'=>'地址第五級',
            'company_id'=>'公司ID',
            'firm_comptype' => '企业类别',
            'firm_scale' => '公司规模',
            'firm_compaddress' => '公司地址',
            'firm_compprincipal' => '厂商负责人',
            'firm_comptel' => '公司联系电话',
            'firm_compfax' => '公司传真',
            'firm_compmail' => '邮箱',//公司郵箱
            'firm_contaperson' => '厂商联系人',
            'firm_contaperson_tel' => '联系人电话',
            'firm_contaperson_mobile' => '联系人手机',
            'firm_contaperson_mail' => '邮箱',//聯繫人郵箱
            'firm_pddepid' => '开发部门',
            'firm_productperson' => '商品经理人',
            'firm_agentstype' => '代理类别',
            'firm_pdtype' => '开发类型',
            'firm_transacttype' => '交易商品类别',
            'firm_agentstype2' => '代理类别2',
            'firm_agentslevel' => '代理等级',
            'firm_agents_position' => '代理商品定位',
            'firm_authorize_area' => '授权区域范围',
            'firm_salarea' => '销售范围',
            'firm_authorize_bdate' => '授权开始日期',
            'firm_authorize_edate' => '授权结束日期',
            'firm_annual_turnover' => '年营业额',
            'firm_remark1' => '备注',
            'firm_remark2' => '备注信息2',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '修改人',
            'update_at' => '修改时间',
            'vdef1' => 'Vdef1',
            'vdef2' => 'Vdef2',
            'vdef3' => 'Vdef3',
            'vdef4' => 'Vdef4',
            'vdef5' => 'Vdef5',
        ];
    }

    public  static function getOne($id,$companyId){
        return self::find()->where(['firm_id'=>$id])
            //->andWhere(["!=",'firm_status',self::REPORT_DELETE])
            ->andWhere(['in','company_id',BsCompany::getIdsArr($companyId)])->one();
    }
    /**
     * @inheritdoc
     * @return FirmQuery the active query used by this AR class.
     */
//    public static function find()
//    {
//        return new FirmQuery(get_called_class());
//    }
    /**
     * 關聯廠商類型
     * @return \yii\db\ActiveQuery
     */
    public function getFirmType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>"firm_type"] );
    }
    /**
     * 關聯廠商來源
     * @return \yii\db\ActiveQuery
     */
    public function getFirmSource(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_source']);
    }
    /**
     * 關聯廠商地位
     * @return \yii\db\ActiveQuery
     */
    public function getFirmPosition(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'firm_position']);
    }

    public function getFirmReport(){
        return $this->hasOne(PdFirmReport::className(),['firm_id'=>'firm_id'])->andWhere(['=','report_status',PdFirmReport::REPORT_COMPLETE])->orWhere(['=','report_status',PdFirmReport::REPORT_DELETE]);
    }

    public function getFirmReportOne(){
        return $this->hasOne(PdFirmReport::className(),['firm_id'=>'firm_id']);
    }

    /**
     * 關聯商品大類
     * @return \yii\db\ActiveQuery
     */
    public function getFirmCategoryId(){
        return $this->hasOne(PdProductType::className(),['type_id'=>"firm_category_id"] );
    }

    /**
     * 關聯創建人信息
     * @return \yii\db\ActiveQuery
     */
    public function getCreatorStaff(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
    }

    /**
     * 根据firm表中id查找出产品类别表中名称
     * @param $id
     * @return string
     */
    public function getCategoryName(){
        $firmMessage = $this->find()->where(['firm_id'=>$this->firm_id])->one();
        $firmCategory = $firmMessage->firm_category_id;
        if ($firmCategory!='') {
            $category = unserialize(Html::decode($firmCategory));
            $models=BsCategory::find()->select('category_sname')->where(['in','category_id',$category])->all();
            $typeName = '';
            foreach($models as $model){
                $typeName.=$model['category_sname'].',';
            }
            return rtrim($typeName,',');
        }else{
            return $typeName ='';
        }
    }

    /**
     * @关联谈判履历信息
     */
    public function getFirmNegotiate(){
        return $this->hasOne(PdNegotiation::className(),['firm_id'=>'firm_id']);
    }

    public static function getFirmById($id){
        return self::find()->where(['firm_id'=>$id])->one();
    }




    public function behaviors()
    {

        return [
//            "StaffBehavior" => [                           //為字段自動賦值（登錄用戶）
//                "class" => StaffBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['creator_by'],   //插入時自動賦值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['updater_by']  //更新時自動賦值字段
//                ]
//            ],
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']            //更新
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());          //賦值的值來源,如不同複寫
                }
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),     //計畫編號自動賦值
                'codeField'=>'firm_code',               //註釋字段名
                'formName'=>'pd_firm',               //註釋表名
                'model'=>$this

            ]
        ];
    }

    /**
     * 获取地址
     */
    public static function getAddress($id)
    {
        return BsDistrict::findOne($id);
    }
}
