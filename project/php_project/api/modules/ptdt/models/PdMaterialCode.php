<?php

namespace app\modules\ptdt\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\ptdt\models\show\PdMaterialCodeShow;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use app\behaviors\StaffBehavior;

/**
 * This is the model class for table "pd_material_code".
 *
 * @property integer $m_id
 * @property string $material_code
 * @property string $pro_brand
 * @property string $pro_name
 * @property string $pro_size
 * @property string $pro_sku
 * @property integer $source_code
 * @property integer $group_code
 * @property string $birth_year
 * @property string $status
 * @property string $pro_pic
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 * @property integer $pro_main_type_id
 * @property integer $pro_type_id
 * @property integer $other_group_code
 * @property integer $pro_second_type_id
 * @property integer $pro_third_type_id
 * @property integer $pro_fourth_type_id
 * @property integer $pro_fifth_type_id
 * @property integer $pro_sixth_type_id
 * @property integer $pro_serial_number
 * @property string $pro_other_name
 * @property string $pro_level
 */
class PdMaterialCode extends Common
{
    const STATUS_DISUSE = 0;     //废弃状态
    const STATUS_NORMAL = 1;     //正常状态
    const STATUS_CHECKING = 2;   //审核状态
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_material_code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['material_code','pro_name','other_group_code'], 'required'],
            [['material_code','pro_brand','pro_name','pro_size','pro_sku','source_code','group_code','birth_year','status','pro_pic'],'safe'],
            [['source_code', 'group_code', 'pro_main_type_id', 'other_group_code', 'pro_second_type_id', 'pro_third_type_id', 'pro_fourth_type_id', 'pro_fifth_type_id', 'pro_sixth_type_id', 'pro_serial_number'], 'integer'],
            [['birth_year', 'create_at', 'update_at'], 'safe'],
            [['material_code'], 'string', 'max' => 32],
            [['pro_brand', 'pro_size', 'pro_pic'], 'string', 'max' => 255],
            [['pro_name'], 'string', 'max' => 100],
            [['pro_sku', 'pro_level'], 'string', 'max' => 64],
            [['status', 'create_by', 'update_by', 'pro_other_name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'm_id'=>'主键ID',
            'material_code'=>'生成的料号',
            'pro_brand'=>'品牌',
            'pro_name'=>'品名',
            'pro_size'=>'型号规格',
            'pro_sku'=>'库存单位S',
            'source_code'=>'来源码',
            'group_code'=>'分群码',
            'birth_year'=>'出厂年份',
            'status'=>'新旧程度',
            'pro_pic'=>'上传附件',
            'create_by'=>'创建者',
            'create_at'=>'创建时间',
            'update_by'=>'更新者',
            'update_at'=>'更新时间',
            'pro_main_type_id'=>'料号原则说明',
            'other_group_code'=>'其他分群码四',
            'pro_second_type_id'=>'第二',
            'pro_third_type_id'=>'第三',
            'pro_fourth_type_id'=>'第四',
            'pro_fifth_type_id'=>'第五',
            'pro_sixth_type_id'=>'第六',
            'pro_serial_number'=>'流水号',
            'pro_other_name'=>'大分头',
            'pro_level'=>'分级分类',
            'm_status'=>'料号状态',
        ];
    }

//    public function behaviors()
//    {
//        return [
//            "StaffBehavior" => [                           //為字段自動賦值（登錄用戶）
//                "class" => StaffBehavior::className(),
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_by'],   //插入時自動賦值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_by']  //更新時自動賦值字段
//                ]
//            ],
//            'timeStamp' => [
//                'class' => TimestampBehavior::className(),    //時間字段自動賦值
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at', 'birth_year'],  //插入
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
//                ]
//            ],
//            "formCode" => [
//                "class"=>FormCodeBehavior::className(),
//                "codeField"=>'material_code',
//                "formName"=>self::tableName()
//            ]
//        ];
//    }
//


    public static function getList($id){
         return  static::find()->where(['m_id'=>$id])->all();
    }
    //拟採购商品
    public static function getMaterialList($arr){
        $materialInfo= [];
        foreach($arr as $val){
            $materialInfo[]=PdMaterialCodeShow::find()->where(['m_id'=>$val])->one();
        }
        return $materialInfo;
    }
    /**
     * 商品分类
     */
    protected function getData($type){
        return $this->hasOne(PdProductType::className(),['type_id'=>$type]);
    }
    public function getProductTypeOne(){
        return $this->getData('pro_main_type_id');
    }
    public function getProductTypeSix(){
        return $this->getData('pro_sixth_type_id');
    }

    public static function getTypeNo($where=[],$select='type_no',$asArray=true){
        return static::find()->select($select)->asArray($asArray)->where($where);
    }

    public function getFenLeiList($pid)
    {
        $model = PdProductType::findAll(array('type_pid'=>$pid,'is_valid'=>1));
        return ArrayHelper::map($model, 'type_id', 'type_name');
    }

    public function getProductType(){
        return $this->hasOne(PdProductType::className(),['type_id'=>'pro_sixth_type_id']);
    }

//    /*关联品牌*/
//    public function getBrand(){
//        return $this->hasOne(PdBrand::className(),['b_id'=>'pro_brand']);
//    }


}
