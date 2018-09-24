<?php

namespace app\modules\common\models;

use app\models\Common;
use app\modules\ptdt\models\CategoryAttr;
use app\modules\ptdt\models\PdSupplier;
use Yii;

/**
 * This is the model class for table "bs_product".
 *
 * @property integer $pdt_id
 * @property string $pdt_no
 * @property integer $bs_category_id
 * @property integer $ID
 * @property integer $CATEGORY_ATTR_ID
 * @property integer $CATEGORY_ID
 * @property integer $brandid
 * @property string $pdt_name
 * @property string $unit
 * @property string $pdt_model
 * @property string $pdt_title
 * @property string $pdt_keyword
 * @property string $pdt_attribute
 * @property string $pdt_form
 * @property integer $status
 * @property integer $is_valid
 * @property integer $pdt_isprice
 * @property string $pdt_safeqty
 * @property integer $pdt_attributes
 * @property integer $pdt_isbatch
 * @property integer $pdt_whsid
 * @property string $pdt_weight
 * @property integer $pdt_weightunit
 * @property string $pdt_vol
 * @property integer $pdt_colseid
 * @property integer $pdt_purchase_cycle
 * @property string $pdt_savedate
 * @property integer $pdt_issalebom
 * @property string $createdate
 * @property string $creater
 * @property integer $editor
 * @property string $editdate
 */
class BsProduct extends Common
{
    const STATUS_REJECT=-1;//驳回
    const STATUS_CHECKING=0;//审核中
    const STATUS_SELLING=1;//销售中
    const STATUS_DOWNSHELF=2;//已下架
    const STATUS_UPSHELFING=3;//未上架

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_product';
    }


    public function fields(){
        $fields=parent::fields();
        $fields['brand_name']=function(){
            return $this->brand->BRAND_NAME_CN;
        };
        $fields['status']=function(){
            return $this->brand->BRAND_NAME_CN;
        };
        return $fields;
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pdt_id', 'pdt_no', 'pdt_name'], 'required'],
            [['pdt_id', 'ID','unit', 'CATEGORY_ATTR_ID', 'CATEGORY_ID', 'brand_id', 'status', 'is_valid', 'pdt_isprice', 'pdt_attributes', 'pdt_isbatch', 'pdt_whsid', 'pdt_weightunit', 'pdt_colseid', 'pdt_purchase_cycle', 'pdt_issalebom', 'editor'], 'integer'],
            [['pdt_safeqty', 'pdt_weight', 'pdt_vol'], 'number'],
            [['createdate', 'editdate'], 'safe'],
            [['pdt_no'], 'string', 'max' => 20],
            [['pdt_no'], 'exist', 'filter' => ['status' => 1], 'targetAttribute' => 'pdt_no', 'message' => '料号不存在!'],
            [['pdt_name'], 'string', 'max' => 50],
            [['pdt_model'], 'string', 'max' => 10],
            [['pdt_title', 'pdt_keyword'], 'string', 'max' => 100],
            [['bs_category_id','pdt_attribute', 'pdt_form', 'pdt_savedate', 'creater'], 'string', 'max' => 20],
            [['pdt_id','pdt_no','pdt_manager','bs_category_id','ID','pdt_level','CATEGORY_ATTR_ID','CATEGORY_ID','brand_id','pdt_name','unit','pdt_model','pdt_title','pdt_keyword','pdt_attribute','pdt_form','status','is_valid','pdt_isprice','pdt_safeqty','pdt_attributes','pdt_isbatch','pdt_whsid','pdt_weight','pdt_weightunit','pdt_vol','pdt_colseid','pdt_purchase_cycle','pdt_savedate','pdt_issalebom','createdate','creater','editor','editdate','company_id','sale_area','iskz','isonlinesell','risk_level','istitle','isproxy'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdt_id' => 'ID',
            'pdt_no' => '按商編規則自動生成',
            'bs_category_id' => '類別id',
            'ID' => '類別單位ID',
            'CATEGORY_ATTR_ID' => '類別屬性ID',
            'CATEGORY_ID' => '關聯商品類型表',
            'brandid' => '關聯品牌表',
            'pdt_name' => '品名',
            'unit' => '與類別單位表關聯',
            'pdt_model' => '具体商品型?',
            'pdt_title' => '標題中可以包含品牌?性、?格??、品名等內容，并可以使用描述性的文字???必需包含商品品名，不得夸大描述。不得超?30',
            'pdt_keyword' => '商品關鍵詞，多個用|隔開',
            'pdt_attribute' => '參數：普通物料、危險品、恆溫品、冷藏品、普通設備、精密設備',
            'pdt_form' => '參數：固體、液體、氣體、粉末、含鋰電池',
            'status' => '1正常0封存',
            'is_valid' => '0.無效1有效',
            'pdt_isprice' => 'Pdt Isprice',
            'pdt_safeqty' => 'Pdt Safeqty',
            'pdt_attributes' => 'Pdt Attributes',
            'pdt_isbatch' => 'Pdt Isbatch',
            'pdt_whsid' => 'Pdt Whsid',
            'pdt_weight' => 'Pdt Weight',
            'pdt_weightunit' => 'Pdt Weightunit',
            'pdt_vol' => 'Pdt Vol',
            'pdt_colseid' => 'Pdt Colseid',
            'pdt_purchase_cycle' => 'Pdt Purchase Cycle',
            'pdt_savedate' => 'Pdt Savedate',
            'pdt_issalebom' => 'Pdt Issalebom',
            'createdate' => 'Createdate',
            'creater' => 'Creater',
            'editor' => 'Editor',
            'editdate' => 'Editdate',
        ];
    }

    public static function getProductInfoOne($where = [], $select = 'pdt_id,pdt_name', $asArray = true)
    {
        return static::find()->select($select)->asArray($asArray)->where($where)->andWhere(['status' => 10])->all();
    }



    public function getProductType()
    {
        return $this->hasOne(BsCategory::className(), ['category_id' => "bs_category_id"]);
    }

    public function getCompany(){
        return $this->hasOne(BsCompany::className(),['company_id'=>'company_id']);
    }

    public function getBrand(){
        return $this->hasOne(BsBrand::className(),['BRAND_ID'=>'brand_id']);
    }

    public function getUnite(){
        return $this->hasOne(BsCategoryUnit::className(),['id'=>'unit']);
    }
    public function getAttr(){
        return $this->hasOne(CategoryAttr::className(),['CATEGORY_ATTR_ID'=>'tp_spec']);
    }

    public function getCategory(){
        return $this->hasOne(BsCategory::className(),["category_id"=>"bs_category_id"]);
    }

    public function getSupplier(){
        return $this->hasOne(PdSupplier::className(),["supplier_code"=>"supplier_code"]);
    }


    public static function options($default=[]){
        $list["status"]=[
            '0'=>'未定价',
            '1'=>'发起定价',
            '2'=>'商品开发维护',
            '3'=>'审核中',
            '4'=>'已定价',
            '5'=>'被驳回',
            '6'=>'已逾期',
            '7'=>'重新定价'
        ];
        $list["price_type"]=[
            "0"=>"新增",
            "1"=>"降价",
            "2"=>"涨价",
            "3"=>"定价不变",
            "4"=>"利润率变更",
            "5"=>"延期",
        ];
        $list["price_from"]=[
            "0"=>"自主开发",
            "1"=>"crd/prd"
        ];
        $list["pdt_level"]=[
            "0"=>"高",
            "1"=>"中",
            "2"=>"低"
        ];
        $list["risk_level"]=[
            "0"=>"高",
            "1"=>"中",
            "2"=>"低"
        ];
        if(!empty($default)){
            foreach($list as &$item){
                $item=array_merge($default,$item);
            }
        }
        return $list;
    }

}
