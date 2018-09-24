<?php
/**
 * 結算方式
 */
namespace app\modules\common\models;

use Yii;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "pd_settlement".
 *
 * @property integer $bnt_id
 * @property string $bnt_code
 * @property string $bnt_sname
 * @property string $bnt_othername
 * @property integer $bnt_status
 * @property string $remarks
 * @property integer $creator_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsSettlement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_settlement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bnt_code','bnt_sname'], 'required'],
            [['bnt_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['bnt_code', 'bnt_sname', 'bnt_othername', 'remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bnt_id' => 'Bnt ID',
            'bnt_code' => '代碼',
            'bnt_sname' => '結算方式',
            'bnt_othername' => '其它結算方式',
            'bnt_status' => '結算狀態',
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

    public static function getSettlementssss()
    {
        return 213;
//        return static::find()->select("bnt_id,bnt_sname")->andWhere(['bnt_status'=>10])->all();
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
