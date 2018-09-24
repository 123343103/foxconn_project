<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use app\modules\common\models\BsCategory;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use app\behaviors\StaffBehavior;
use yii\db\BaseActiveRecord;

/**
 * F3858995
 * 2016.10.22
 * 商品经理人模型
 *
 * @property integer $pm_code
 * @property integer $staff_code
 * @property integer $parent_id
 * @property integer $pm_status
 * @property integer $pm_level
 * @property string $pm_desc
 * @property string $create_at
 * @property integer $create_by
 * @property string $update_at
 * @property integer $update_by
 */
class PdProductManager extends Common
{
    public static $levelOption = ['1' => "商品负责人", '2' => '商品经理人'];

    const PM_FZR = 1;  //商品负责人 level
    const PM_JLR = 2;  //商品经理人 level

    public static function tableName()
    {
        return 'pd_product_manager';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['staff_code', 'required'],
            ['staff_code','unique','targetAttribute'=>['staff_code'],'message'=>'商品经理人已存在'],
            ['staff_code','exist','targetClass'=>HrStaff::className(),'targetAttribute'=>['staff_code'],'message'=>'用户不存在'],
//            ['pm_level', 'required'],
            ['pm_level', 'integer'],
            [['create_at', 'update_at','category_id','parent_id','create_by','update_by'], 'safe'],
            ['pm_desc', "string", 'max' => 255],
            [['pm_status'], 'default', "value" => 1]
        ];
    }

//    public function scenarios()
//    {
//        $scenarios = parent::scenarios();
//        $scenarios['add']=['staff_code','pm_level','parent_id','pm_desc','pm_status'];
//        $scenarios['edit']=['pm_level','parent_id','pm_desc'];
//        return $scenarios;
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pm_id' => 'Pm ID',
            'staff_code' => '姓名',
            'parent_id' => '上级',
            'pm_status' => '状态',
            'pm_level' => '类型',
            'pm_desc' => '说明',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }

    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at','update_at'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ],
            ],
        ];
    }

    /**
     * 关联staff表
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_code' => "staff_code"]);
    }

//    /**
//     *关联商品分类
//     */
    public function getProductType(){
        return $this->hasOne(BsCategory::className(),['category_id'=>'category_id']);
    }

    public function getParent()
    {
        return $this->hasOne(self::className(), ['pm_id' => "parent_id"]);
    }

    public function getUpdator(){
        return $this->hasOne(HrStaff::className(),["staff_id"=>"update_by"]);
    }

    /*
     * 获取前台select 选择数组
     */
    public static function getOptions($items="")
    {
        $groups=[];
        $itemsArr=array_filter(explode(",",$items));
        $data=BsCategory::find()->where(["catg_level"=>1,"isvalid"=>1])->asArray()->all();
        $groups["category"]=array_combine(array_column($data,"catg_id"),array_column($data,"catg_name"));
        $data = self::find()->joinWith("staff",false)
            ->where([
                self::tableName().'.pm_level' =>self::PM_FZR,
                self::tableName().'.pm_status' => 1
            ])
            ->select([
                self::tableName().'.pm_id',
                self::tableName().'.staff_code',
                HrStaff::tableName().".staff_name"
            ])
            ->asArray()
            ->all();
        $groups["leader"]=array_combine(array_column($data,"pm_id"),array_column($data,"staff_name"));
        $groups["type"]=self::$levelOption;
        if(count($itemsArr)>0){
            $result="";
            foreach($itemsArr as $item){
                $result[$item]=isset($groups[$item])?$groups[$item]:"";
            }
            return $result;
        }
        return $groups;
    }

}
