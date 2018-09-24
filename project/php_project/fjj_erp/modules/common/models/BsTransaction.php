<?php
/**
 * 交易方式
 */
namespace app\modules\common\models;

use Yii;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;

/**
 * This is the model class for table "pd_transaction".
 *
 * @property integer $tac_id
 * @property string $tac_code
 * @property string $tac_sname
 * @property string $tac_othername
 * @property integer $tac_status
 * @property integer $create_by
 * @property string $create_at
 * @property integer $updater_by
 * @property string $update_at
 */
class BsTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tac_code', 'tac_sname'], 'required'],
            [['tac_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at','remarks'], 'safe'],
            [['tac_code', 'tac_sname', 'tac_othername'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tac_id' => 'Tac ID',
            'tac_code' => '代碼',
            'tac_sname' => '交易方式',
            'tac_othername' => '其它交易方式',
            'remarks'=> '備註',
            'tac_status' => '交易狀態',
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
    public function getStaffName(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'create_by']);
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
