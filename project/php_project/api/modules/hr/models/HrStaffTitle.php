<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 上午 08:56
 */
namespace app\modules\hr\models;
use app\models\Common;
use Yii;
use app\modules\hr\models\HrStaff;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
/*This is the model class for table "staff_title"*/
class HrStaffTitle extends Common {

    const TITLE_STATUS_DEL = 0;//删除状态

    const TITLE_STATUS_DEFAULT  = 10;//正常

    public static function tableName(){
        return 'hr_staff_title';
    }

    public function behaviors()
    {
        return [
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
           /* [['title_name','title_description','title_code'],'required'],*/
            [['title_name'],'string','max'=>32],
            [['title_description','title_code'],'string'],
            [['title_status'],'default','value'=>self::TITLE_STATUS_DEFAULT]
        ];
    }
    public function attributeLabels(){
        return [
            'title_id'=>'岗位ID',
            'title_name'=>'岗位名称',
            'title_level'=>'岗位级别',
            'title_description'=>'岗位描述',
            'title_code'=>'岗位编号',
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

    public static function getStaffTitleAll($select='title_id,title_name'){
           return self::find()->select($select)->asArray()->all();
    }

    public static function getTitleId($name){
        return self::find()->where(['title_name'=>$name])->select('title_id')->asArray()->one();
    }

    public static function getTitleByCode($code){
        return self::find()->where(['title_code'=>$code])->select('title_id,title_name,title_code,title_description')->asArray()->one();
    }
}