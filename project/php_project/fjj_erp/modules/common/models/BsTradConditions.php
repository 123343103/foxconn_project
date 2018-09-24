<?php
/**
 * 交易條件
 */
namespace app\modules\common\models;

use Yii;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrStaff;
/**
 * This is the model class for table "pd_trad_conditions".
 *
 * @property integer $tcc_id
 * @property string $tcc_code
 * @property string $tcc_sname
 * @property string $tcc_othername
 * @property integer $tcc_status
 * @property string $remark
 * @property integer $create_by
 * @property string $create_at
 * @property integer $update_by
 * @property string $update_at
 */
class BsTradConditions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_trad_conditions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tcc_code','tcc_sname'], 'required'],
            [[ 'tcc_status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['tcc_code', 'tcc_sname'], 'string', 'max' => 10],
            [['tcc_othername'], 'string', 'max' => 20],
            [['remarks'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tcc_id' => 'Tcc ID',
            'tcc_code' => '代碼',
            'tcc_sname' => '交易條件',
            'tcc_othername' => '其它交易條件',
            'tcc_status' => '交易狀態',
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
