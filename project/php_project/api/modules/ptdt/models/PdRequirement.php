<?php

namespace app\modules\ptdt\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCompany;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsPubdata;
use yii\db\BaseActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
use app\modules\ptdt\models\show\PdRequirementProductShow;

/**
 * 需求計畫模型
 * F3858995
 * 2016.9.20
 *
 * @property integer $pdq_id
 * @property string $pdq_code
 * @property string $pdq_date
 * @property integer $pdq_department
 * @property string $sale_pos
 * @property string $pdq_source_type
 * @property integer $pdq_status
 * @property string $develop_center
 * @property string $develop_department
 * @property string $develop_type
 * @property integer $commodity
 * @property integer $product_manager
 * @property string $sale_area
 * @property string $customer_name
 * @property string $customer_date
 * @property integer $customer_num
 * @property string $develop_reason
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class PdRequirement extends Common
{

    const STATUS_DELETE=0;  //删除
    const STATUS_DEFAULT=10;//新增
    const STATUS_REVIEW=20; //审核中
    const STATUS_FINISH=30; //通过
    const STATUS_REJECT=40; //驳回

    public static function tableName()
    {
        return 'pd_requirement';
    }
    
    public  static function getOne($id){
        return self::find()->where(['pdq_id'=>$id])
            ->andWhere(["!=",'pdq_status',self::STATUS_DELETE])->one();
    }

    public  static function getRequirementOne($id,$select=null){
        return self::find()->where(['pdq_id'=>$id])->select($select)->one();
    }
    /**
     * 關聯商品字表
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasMany(PdRequirementProduct::className(), ['requirement_id' => "pdq_id"]);
    }

    /**
     * 關聯需求來源
     * @return array
     */
    public function getSourceType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => "pdq_source_type"]);
    }

    /**
     * 關聯開發類型
     * @return \yii\db\ActiveQuery
     */
    public function getDevelopType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => "develop_type"]);
    }

    /**
     * 關聯開發中心
     * @return \yii\db\ActiveQuery
     */
    public function getDevelopCenter()
    {
        return $this->hasOne(HrOrganization::className(), ['organization_code' => "develop_center"]);
    }

    /**
     * 關聯商品大類
     * @return \yii\db\ActiveQuery
     */
    public function getCommodityType()
    {
        return $this->hasOne(BsCategory::className(), ['category_id' => "commodity"]);
    }

    /**
     * 關聯開發部門
     * @return \yii\db\ActiveQuery
     */
        public function getDevelopDepartment()
    {
        return $this->hasOne(HrOrganization::className(), ['organization_code' => "develop_department"]);
    }

    /**
     * 關聯商品經理人
     * @return array
     */
    public function getProductManager()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'product_manager']);
    }

    /**
     * 關聯提出人
     * @return \yii\db\ActiveQuery
     */
    public function getCreatorStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }

    /**
     * 關聯提报人
     * @return \yii\db\ActiveQuery
     *
     *
     */
    public function getOfferStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'offer_staff']);
    }
    /**
     * 關聯商品
     * @return \yii\db\ActiveQuery
     */
    public function getProductList()
    {
        return $this->hasMany(PdRequirementProductShow::className(), ['requirement_id' => 'pdq_id'])->orderBy('product_id desc');
    }
    /**
     * 關聯商品
     * @return \yii\db\ActiveQuery
     */
    public function getOneProduct()
    {
        return $this->hasone(PdRequirementProduct::className(), ['requirement_id' => 'pdq_id']);
    }

    public function behaviors()
    {
        return [

            "formCode" => [
                "class"=>FormCodeBehavior::className(),
                "codeField"=>'pdq_code',
                "formName"=>self::tableName(),
                'model'=>$this
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'pdq_date'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }


    public function rules()
    {
        return [
            [['develop_center', 'develop_department', 'pdq_source_type', 'commodity', 'develop_type', 'product_manager'], 'required'],
            ['pdq_status', 'default', 'value' => self::STATUS_DEFAULT],
            [['pdq_id', 'pdq_date', 'customer_date', 'create_at', 'update_at', 'create_by','update_by','company_id','cust_id','offer_staff','offer_date'], 'safe'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'pdq_id' => '主键ID',
            'pdq_code' => '計畫編號',
            'pdq_date' => '需求日期',
            'pdq_department' => '需求部门ID',
            'sale_pos' => '销售点',
            'pdq_source_type' => '需求類型',
            'pdq_status' => '状态',
            'develop_center' => '開發中心',
            'develop_department' => '開發部',
            'develop_type' => '开发类型',
            'commodity' => '商品大类',
            'offer_staff' => '提报人ID',
            'offer_date' => '提报日期',
            'product_manager' => '商品经理人',
            'sale_area' => '销售区域',
            'customer_name' => '客户名称',
            'customer_date' => '客户需求日期',
            'customer_num' => '客户需求量',
            'develop_reason' => '开发原因',
            'create_by' => '計畫創建人',
            'create_at' => '提出日期',
            'update_by' => '最后更新人',
            'update_at' => '最后更新日期',
        ];
    }

}
