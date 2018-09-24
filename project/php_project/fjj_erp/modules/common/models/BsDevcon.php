<?php
/**
 * 交货添加模型
 */
namespace app\modules\common\models;

use Yii;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "bs_devcon".
 *
 * @property integer $dec_id
 * @property string $dec_code
 * @property string $dec_sname
 * @property string $dec_othername
 * @property string $dec_status
 * @property integer $creator_by
 * @property string $create_at
 * @property integer $updater_by
 * @property string $update_at
 */
class BsDevcon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_devcon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dec_code','dec_sname'],'required'],
            [['create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at','remarks'], 'safe'],
            [['dec_code', 'dec_sname', 'dec_othername'], 'string', 'max' => 20],
            [['dec_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dec_id' => 'Dec ID',
            'dec_code' => '代码',
            'dec_sname' => '交货名称',
            'dec_othername' => '其它交货条件',
            'dec_status' => '交货状态',
            'remarks'=> '备注',
            'create_by' => '创建人',
            'create_at' => '创建时间',
            'update_by' => '修改人',
            'update_at' => '修改时间',
        ];
    }
    /**
     * 關聯員工表獲取姓名
     * @return \yii\db\ActiveQuery
     */
    public function getStaffName(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
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
