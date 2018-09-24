<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\models;
use app\behaviors\FormCodeBehavior;
use app\models\Common;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
//活动报名表模型
class CrmActiveApply extends Common
{
    //状态
    const DELETE_STATUS=0;//删除
    const DEFAULT_STATUS=10;//默认

    //表名
    public static function tableName()
    {
        return 'crm_act_h';
    }

    //活动类型
    public function getActiveType(){
        return $this->hasOne(CrmActiveType::className(),['acttype_id'=>'acttype_id']);
    }
    //活动类型
    public function getActiveName(){
        return $this->hasOne(CrmActiveName::className(),['actbs_id'=>'actbs_id']);
    }

    /*签到人*/
    public function getActiveCheck(){
        return $this->hasMany(CrmActiveCheckIn::className(),['acth_id'=>'acth_id']);
    }

    //验证规格
    public function rules()
    {
        return [
            ['cust_id', 'unique', 'targetAttribute' => ['acttype_id', 'actbs_id', 'cust_id'], 'message' => '该客户已报名', 'filter' => ['!=', 'acth_status', self::DELETE_STATUS]],
            [['acth_date', 'actbs_date', 'create_at', 'update_at'], 'safe'],
            [['acttype_id', 'actbs_id', 'cust_id', 'member_id', 'acth_identity', 'acth_status', 'company_id', 'create_by', 'update_by'], 'integer'],
            [['acth_code', 'acth_email'], 'string', 'max' => 50],
            [['acth_name', 'acth_department', 'acth_position', 'acth_phone', 'acth_payamount'], 'string', 'max' => 20],
            [['acth_ismeal', 'acth_ispay', 'acth_payflag', 'acth_isbill', 'acth_ischeckin'], 'string', 'max' => 10],
            [['acth_nocheckinwhy', 'acth_require', 'acth_requiretype', 'acth_remark'], 'string', 'max' => 255],
        ];
    }

    //行为
    public function behaviors()
    {
        return [
            'timeStamp'=>[
                'class'=>TimestampBehavior::className(),
                'attributes'=>[
                    BaseActiveRecord::EVENT_BEFORE_INSERT=>['create_at','acth_date'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['update_at']
                ]
            ],
            'formCode'=>[
                'class'=>FormCodeBehavior::className(),
                'codeField'=>'acth_code',
                'formName'=>self::tableName(),
                'model'=>$this
            ]
        ];
    }
}