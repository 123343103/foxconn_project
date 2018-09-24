<?php

namespace app\modules\warehouse\models;

use app\modules\hr\models\HrStaff;
use app\modules\sale\models\OrdAddr;
use app\modules\sale\models\OrdDt;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use app\modules\sync\models\member\CrmCustomerInfo;
use Yii;

/**
 * This is the model class for table "ord_lgst".
 *
 * @property string $ord_lg_id
 * @property string $o_whpkid
 * @property string $ord_id
 * @property string $lg_no
 * @property string $lg_fee
 * @property string $lg_fee_tax
 * @property string $crr
 * @property integer $YN_FJJ
 * @property string $TRANSMODE
 * @property string $Fetch_date
 * @property string $rcpt_date
 * @property string $crter
 * @property string $crt_date
 * @property string $stt_ip
 * @property string $shp_cntct
 * @property string $shp_tel
 * @property string $shp_marks
 * @property string $rcv_cntct
 * @property string $rcv_tel
 * @property string $rcv_marks
 * @property integer $check_status
 * @property integer $status
 * @property string $stt_date
 * @property string $stter
 * @property string $sr_type
 * @property string $lgst_date
 * @property string $trade_act
 * @property string $trade_type
 * @property integer $YN_Fee
 * @property integer $YN_ins
 * @property string $cost_no
 * @property integer $ie_type
 * @property string $kd_car
 * @property string $marks
 * @property string $Re_lg_no
 */
class OrdLgst extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_lgst';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['o_whpkid', 'ord_id', 'crr', 'YN_FJJ', 'TRANSMODE', 'crter', 'check_status', 'status', 'stter', 'sr_type', 'trade_act', 'trade_type', 'YN_Fee', 'YN_ins', 'ie_type', 'kd_car'], 'integer'],
            [['lg_fee', 'lg_fee_tax'], 'number'],
            [['Fetch_date', 'rcpt_date', 'crt_date', 'stt_date', 'lgst_date'], 'safe'],
            [['lg_no', 'shp_cntct', 'shp_tel', 'rcv_tel', 'Re_lg_no'], 'string', 'max' => 30],
            [['stt_ip', 'rcv_cntct', 'cost_no'], 'string', 'max' => 20],
            [['shp_marks', 'rcv_marks', 'marks'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ord_lg_id' => '物流訂單PKID',
            'o_whpkid' => '出庫單PKID',
            'ord_id' => '客户订单编号',
            'lg_no' => '物流訂單號',
            'lg_fee' => '未稅物流費.默認為交易訂單中的費用',
            'lg_fee_tax' => '含稅物流費.默認為交易訂單中的費用',
            'crr' => '幣別',
            'YN_FJJ' => '是否富金機配送.0否，1是',
            'TRANSMODE' => '運輸模式',
            'Fetch_date' => '取件時間',
            'rcpt_date' => '客戶簽收時間',
            'crter' => '申請人',
            'crt_date' => '申請時間',
            'stt_ip' => '單據狀態變更人IP',
            'shp_cntct' => '出貨聯系人',
            'shp_tel' => '出貨聯系人電話',
            'shp_marks' => '出貨備注',
            'rcv_cntct' => '收貨聯系人',
            'rcv_tel' => '收貨聯系人電話',
            'rcv_marks' => '收貨備注',
            'check_status' => '物流訂單審核狀態（0：審核中；1：審核完成；-1：駁回；2：開立；3：發送物流）',
            'status' => '物流訂單狀態（棄用）',
            'stt_date' => '單據狀態變更時間',
            'stter' => '單據狀態變更人',
            'sr_type' => '來源單據類型',
            'lgst_date' => '預計出貨時間',
            'trade_act' => '貿易性質,是否保稅，0不保稅，1保稅。',
            'trade_type' => '運輸類型',
            'YN_Fee' => '是否無帳.0否，1是',
            'YN_ins' => '是否保價,0否，1是',
            'cost_no' => '費用代碼',
            'ie_type' => '進出口類型.0出口，1進口',
            'kd_car' => '車種',
            'marks' => '備注',
            'Re_lg_no' => '物流公司返回的訂單',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdLgstDts()
    {
        return $this->hasMany(OrdLgstDt::className(), ['ord_lg_id' => 'ord_lg_id']);
    }
//关联订单子表
    public function getOrdDt(){
        return $this->hasOne(OrdDt::className(), ['ord_dt_id' => 'ord_dt_id'])->via('ordLgstDts');
    }
    //关联订单主表
    public function getOrdInfo(){
        return $this->hasOne(OrdInfo::className(),['ord_id'=>'ord_id'])->via('ordDt');
    }
//    //关联订单地址表
//    public function getCrmCustomer(){
//        return $this->hasOne(CrmCustomerInfo::className(),['cust_code'=>'cust_code'])->via('ordInfo');
//    }
    //关联出库表
    public function getOwhpdt(){
        return $this->hasOne(OWhpdt::className(),['o_whpkid'=>'o_whpkid']);
    }
    //关联拣货表
    public function getBsPck()
    {
        return $this->hasOne(BsPck::className(),['pck_no'=>'relate_packno'])->via('owhpdt');
    }
    //关联出货通知表
    public function getShpNt()
    {
        return $this->hasOne(ShpNt::className(),['note_pkid'=>'note_pkid'])->via('bsPck');
    }
    //关联仓库表
    public function getBsWh(){
        return $this->hasOne(BsWh::className(),['wh_id'=>'o_whid'])->via('owhpdt');
    }
    //关联员工信息表
    public function getHrStaff()
    {
        return $this->hasOne(HrStaff::className(),['staff_id'=>'crter']);
    }
}
