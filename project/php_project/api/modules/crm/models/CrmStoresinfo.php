<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use app\modules\hr\models\HrStaff;
use app\behaviors\StaffBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\common\models\BsPubdata;
use yii;

/**
 * This is the model class for table "crm_bs_storesinfo".
 *
 * @property integer $sts_id
 * @property string $sts_code
 * @property string $sts_sname
 * @property integer $DISTRICT_ID
 * @property integer $csarea_id
 * @property integer $sz_staff_id
 * @property integer $dz_staff_id
 * @property integer $sts_count
 * @property string $sts_address
 * @property integer $sts_status
 * @property integer $creator
 * @property string $cdate
 * @property integer $editor
 * @property string $edate
 */
class CrmStoresinfo extends Common
{
    /**
     * @inheritdoc
     */
    const STATUS_NORMAL = 10;       // 营业中
    const STATUS_PREPARE = 11;      // 筹备中
//    const STATUS_SHUTOUT = 12;      // 已停业
    const STATUS_SHUTUPSTORE = 13;  // 已歇业
    const STATUS_PAUSE = 14;        // 已暂停
    const STATUS_CLOSE = 15;        // 已关闭
    const STATUS_DELETE = 16;       // 已刪除

    public static function tableName()
    {
        return 'crm_bs_storesinfo';
    }

    public function behaviors()
    {
        return [
//            "StaffBehavior" => [                           //為字段自動賦值（登錄用戶）
//                "class" => StaffBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['creator'],   //插入時自動賦值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['editor']  //更新時自動賦值字段
//                ],
//                'value'=>function(){
//                    return Yii::$app->user->identity->staff->staff_id;  // 測試出錯
//                    return 694; // 測試ok
//                }
//            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['cdate'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['edate']            //更新
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s", time());          //賦值的值來源,如不同複寫
                }
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sts_code'], 'required', 'message' => '{attribute}必填'],
            [['sts_code'], 'unique', 'filter' => ['!=','sts_status',CrmStoresinfo::STATUS_DELETE],'targetAttribute' => 'sts_code', 'message' => '{attribute}已经存在'],
            [['sts_id', 'district_id', 'csarea_id', 'sz_staff_id', 'dz_staff_id', 'sts_count', 'sts_status', 'creator', 'editor','sts_count_limit'], 'integer'],
            [['kpi'], 'number'],
            [['cdate', 'edate','sts_code'], 'safe'],
            [['sts_sname'], 'string', 'max' => 40],
            [['sts_address'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sts_id' => 'ID',
            'sts_code' => '销售点代码',
            'sts_sname' => '門店名稱',
            'district_id' => '關聯行政地區表',
            'csarea_id' => '所在銷售軍區',
            'sz_staff_id' => '關聯人員表',
            'dz_staff_id' => '關聯人員表',
            'sts_count' => '店員數量',
            'sts_address' => '詳細地址 手寫',
            'sts_status' => '狀態',
            'kpi' => 'KPI',
            'creator' => '創建人',
            'cdate' => '創建時間',
            'editor' => '修改人',
            'edate' => '修改時間',
        ];
    }

    /*关联军区*/
    public function getArea()
    {
        return $this->hasOne(CrmSalearea::className(), ['csarea_id' => 'csarea_id']);
    }

    /*关联省长*/
    public function getSz()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'sz_staff_id']);
    }

    /*关联店长*/
    public function getDz()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'dz_staff_id']);
    }

    /*关联行政区域地址*/
    public function getAddr()
    {
        return $this->hasOne(BsDistrict::className(), ['district_id' => 'district_id']);
    }

    /*档案建立人信息*/
    public function getCreateBy()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'creator']);
    }

    /*修改人信息*/
    public function getEditBy()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'editor']);
    }

    /*销售点状态*/
    public function getStatus()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'sts_status']);
    }

    /*销售点信息*/
    public static function getStoreInfo()
    {
        $list = static::find()->select("sts_id,sts_sname")->where(['sts_status' => self::STATUS_NORMAL])->orWhere(['sts_status' => self::STATUS_PREPARE])->asArray()->all();
        return isset($list) ? $list : [];
    }
}
