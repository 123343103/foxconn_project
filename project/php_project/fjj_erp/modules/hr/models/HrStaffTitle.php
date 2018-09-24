<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 上午 08:56
 */
namespace app\modules\hr\models;
use Yii;
use app\modules\hr\models\HrStaff;
use app\behaviors\StaffBehavior;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
/*This is the model class for table "staff_title"*/
class HrStaffTitle extends \yii\db\ActiveRecord{

    public static function tableName(){
        return 'hr_staff_title';
    }

    public function behaviors()
    {
        return [
            "StaffBehavior" => [                           //為字段自動賦值（登錄用戶）
                "class" => StaffBehavior::className(),
                'attributes'=>[
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_by'],   //插入時自動賦值字段
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_by']  //更新時自動賦值字段
                ]
            ],
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['update_at']            //更新
                ],
                'value'=>function(){
                    return date("Y-m-d H:i:s",time());          //賦值的值來源,如不同複寫
                }
            ]
        ];
    }


    public function rules(){
        return [
            [['title_name','title_description'],'required'],
            [['title_name'],'string','max'=>32],
            [['title_description'],'string'],
        ];
    }
    public function attributeLabels(){
        return [
            'title_id'=>'岗位ID',
            'title_name'=>'岗位名称',
            'title_level'=>'岗位级别',
            'title_description'=>'岗位描述',
            'title_status'=>'岗位状态',
            'create_by'=>'创建者',
            'create_at'=>'创建于',
            'update_by'=>'更新者',
            'update_at'=>'更新于',
        ];
    }

    public function getHrStaff()
    {
        //同样第一个参数指定关联的子表模型类名
        //
        return $this->hasMany(HrStaff::className(), ['title_name' => 'position']);
    }

}