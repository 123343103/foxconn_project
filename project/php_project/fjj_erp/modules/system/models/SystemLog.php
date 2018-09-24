<?php

namespace app\modules\system\models;
use app\modules\hr\models\HrStaff;
use yii\db\BaseActiveRecord;
use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * F3858995
 * 2016.10.18
 * 系統日誌模型
 *
 * @property string $log_id
 * @property string $staff_code
 * @property string $user_name
 * @property string $user_ip
 * @property string $description
 * @property string $time
 */
class SystemLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_id'], 'integer'],
            [['time'], 'safe'],
            [['staff_code','user_name'], 'string', 'max' => 20],
            [['user_ip', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'staff_code' => '操作人',
            'user_ip' => 'IP地址',
            'description' => '操作內容',
            'time' => '時間',
            'user_name'=>"操作账号"
        ];
    }
    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['time'],  //插入
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s", time());          //賦值的值來源,如不同複寫
                }
            ],
        ];
    }

    public function getStaff(){
        return $this->hasOne(HrStaff::className(),['staff_code'=>"staff_code"] );
    }


    public static function addLog($msg){
        $model = new self;
        $model->staff_code = Yii::$app->user->identity->staff->staff_code;
        $model->user_ip = Yii::$app->request->getUserIP();
        $model->description = $msg;
        $model->user_name = Yii::$app->user->identity->user_account;
        return $model->save();
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['staff_code']=function(){
            return $this->staff_code.'-'.HrStaff::getStaffByIdCode($this->staff_code)['staff_name'];
        };
        return $fields;
    }
}
