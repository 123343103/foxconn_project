<?php
/**
 * 货币类别模型
 */
namespace app\modules\sync\models\member;

use Yii;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "bs_currency".
 *
 * @property integer $cur_id
 * @property string $cur_code
 * @property string $cur_sname
 * @property string $cur_shortname
 * @property string $remarks
 * @property string $cur_status
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsCurrency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_code','cur_sname'],'required'],
            [['create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['cur_code', 'cur_sname', 'cur_shortname', 'remarks', 'cur_status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_id' => 'Cur ID',
            'cur_code' => '代码',
            'cur_sname' => '货币名称',
            'cur_shortname' => '简称',
            'remarks' => '备注',
            'cur_status' => '货币状态',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '修改人',
            'update_at' => '修改时间',
        ];
    }
    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getAll(){
        return self::find()->joinWith(['staffName'])->asArray()->all();
    }
    /**
     * 關聯員工表獲取姓名
     * @return \yii\db\ActiveQuery
     */
    public function getStaffName(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
    }

    // 币别code获取id
    public static function getIdByCode($code){
        return self::find()->select('cur_id')->where(['cur_code'=>$code])->one();
    }

//    public function behaviors()
//    {
//        return [
//            "StaffBehavior" => [                           //為字段自動賦值（登錄用戶）
//                "class" => StaffBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_by'],   //插入時自動賦值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_by']  //更新時自動賦值字段
//                ]
//            ],
//            'timeStamp'=>[
//                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
//                'attributes' => [
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']            //更新
//                ],
//                'value'=>function(){
//                    return date("Y-m-d H:i:s",time());          //賦值的值來源,如不同複寫
//                }
//            ],
//        ];
//    }
}
