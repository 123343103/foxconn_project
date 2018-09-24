<?php
/**
 * 付款方式
 */
namespace app\modules\common\models;

use Yii;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "pd_pay_condition".
 *
 * @property integer $pat_id
 * @property string $pat_code
 * @property string $pat_sname
 * @property string $pat_othername
 * @property integer $pat_status
 * @property string $remarks
 * @property integer $creator_by
 * @property string $create_at
 * @property integer $updater_by
 * @property string $update_at
 */
class BsPayCondition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_pay_condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pat_code','pat_sname'], 'required'],
            [['pat_id', 'pat_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['pat_code', 'pat_sname', 'pat_othername', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pat_id' => 'Pat ID',
            'pat_code' => '代碼',
            'pat_sname' => '付款方式',
            'pat_othername' => '其它付款方式',
            'pat_status' => '付款狀態',
            'remarks' => '備註',
            'create_by' => '創建人',
            'create_at' => '創建時間',
            'update_by' => '修改人',
            'update_at' => '修改時間',
        ];
    }
    /**
     * 關聯員工表獲取姓名
     * @return \yii\db\ActiveQuery
     */
    public function getStaffName()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
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
            ],
        ];
    }
}
