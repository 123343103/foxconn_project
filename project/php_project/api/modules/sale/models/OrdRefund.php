<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "ord_refund".
 *
 * @property string $refund_id
 * @property string $refund_no
 * @property string $ord_no
 * @property string $manger
 * @property string $mg_tel
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 * @property integer $rfnd_status
 * @property string $tax_fee
 * @property string $yn_check
 * @property string $check_date
 * @property integer $checkor
 *
 * @property OrdRefundDt[] $ordRefundDts
 */
class OrdRefund extends \app\models\Common
{
//    const STATUS_DELETE = 0;           //删除
    const STATUS_CANCLE_REFUND= 10;          //已取消
    const STATUS_IN_REVIEW = 11;          //审核中
    const STATUS_PASS_REVIEW = 12;          //审核完成
    const STATUS_REJECT_REVIEW = 13;          //审核驳回
    const STATUS_REFUND = 14;            //已退款
    const STATUS_DEFAULT= 15;            //默认
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_refund';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manger', 'opper', 'rfnd_status', 'checkor'], 'integer'],
            [['opp_date', 'check_date'], 'safe'],
            [['tax_fee'], 'number'],
            [['refund_no', 'mg_tel'], 'string', 'max' => 30],
            [['ord_no'], 'string', 'max' => 20],
            [['opp_ip'], 'string', 'max' => 16],
            [['yn_check'], 'string', 'max' => 2],
            [['rfnd_status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'refund_id' => '退款申請pkid',
            'refund_no' => '退款單號',
            'ord_no' => '關聯訂單號',
            'manger' => '訂單負責人',
            'mg_tel' => '負責??電話',
            'opper' => '操作人',
            'opp_date' => '操作時間',
            'opp_ip' => '操作IP',
            'rfnd_status' => '退款審核狀態',
            'tax_fee' => '退款總金額',
            'yn_check' => '是否已確認退款（1：已確認）審核通過后變為0',
            'check_date' => '確認退款時間',
            'checkor' => '確認退款人',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdRefundDts()
    {
        return $this->hasMany(OrdRefundDt::className(), ['refund_id' => 'refund_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 订单信息
     */
    public function getOrdInfo(){
        return $this->hasOne(OrdInfo::className(),['ord_no'=>'ord_no']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * 订单负责人
     */
    public function getManager(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'manger']);
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'refund_no',
                "formName" => self::tableName(),
                "model" => $this,
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['opp_date'],            //插入
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['opp_date']            //更新
                ]
            ],
        ];
    }
}
