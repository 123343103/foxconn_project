<?php

namespace app\modules\warehouse\models;

use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "o_whpdt".
 *
 * @property string $o_whpkid
 * @property integer $o_whtype
 * @property string $buss_type
 * @property integer $app_depart
 * @property string $o_whcode
 * @property string $o_whid
 * @property string $o_whstatus
 * @property string $relate_packno
 * @property string $ord_id
 * @property string $logistics_no
 * @property string $o_date
 * @property integer $pdt_attr
 * @property string $plan_odate
 * @property integer $delivery_type
 * @property integer $logistics_type
 * @property string $use_for
 * @property string $reciver
 * @property string $reciver_tel
 * @property integer $district_id
 * @property string $address
 * @property string $remarks
 * @property string $can_reason
 * @property integer $opp_id
 * @property string $opp_date
 * @property string $opp_ip
 * @property integer $creator
 * @property string $creat_date
 * @property string $creat_ip
 * @property string $i_whid
 *
 * @property OWhpdtDt[] $oWhpdtDts
 */
class OWhpdt extends \yii\db\ActiveRecord
{
    //状态
    const WAIT_LIBRARY=10;//待出库(只是用于列表查询本身的待出库状态为0)
    const WAIT_RGOODS=1;//待收货
    const ALREADY_WAREHOUSE=2;//已收货
    const ALREADY_LIBRARY=3;//已出库
    const OUTSTOCK_CANCEL=4;//已取消
    const WAIT_COMMIT=5;//待提交
    const CHECK_ING=6;//审核中
    const CHECK_COMPLETE=7;//审核完成
    const REJECT_STATUS=8;//驳回
    const HAVE_BEEN_ABANDONED=9;//已作废

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_whpdt';
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
            [['o_whtype', 'app_depart', 'o_whid', 'ord_id', 'pdt_attr', 'delivery_type', 'logistics_type', 'district_id', 'opp_id', 'creator', 'i_whid'], 'integer'],
            [['o_date', 'plan_odate', 'opp_date', 'creat_date'], 'safe'],
            [['buss_type'], 'string', 'max' => 4],
            [['o_whcode', 'relate_packno', 'reciver'], 'string', 'max' => 30],
            [['o_whstatus'], 'string', 'max' => 2],
            [['logistics_no', 'reciver_tel', 'opp_ip', 'creat_ip'], 'string', 'max' => 20],
            [['use_for'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 100],
            [['remarks', 'can_reason'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'o_whpkid' => '出库id',
            'o_whtype' => '单据类型（其他出库用）公共字典中设置',
            'buss_type' => '出库类型（1：销售出库；2：其他）',
            'app_depart' => '申請部門',
            'o_whcode' => '出库单号',
            'o_whid' => '出仓仓库',
            'o_whstatus' => '出库单状态(0：待出库；1:待收货；2：已收货；3：已出库；4：已取消;5:待提交；6：审核中；7：审核完成；8：驳回；9：已作废)',
            'relate_packno' => '关联拣货单/关联单号',
            'ord_id' => '客户订单号(oms.ord_info.ord_id）',
            'logistics_no' => '物流订单号',
            'o_date' => '出库单日期',
            'pdt_attr' => '商品性质',
            'plan_odate' => '预出库日期',
            'delivery_type' => '配送方式',
            'logistics_type' => '运输方式',
            'use_for' => '用途',
            'reciver' => '收货人',
            'reciver_tel' => '联系方式',
            'district_id' => '地址最后一级id',
            'address' => '详细地址',
            'remarks' => '备注',
            'can_reason' => '取消原因',
            'opp_id' => '操作人',
            'opp_date' => '操作时间',
            'opp_ip' => '操作IP',
            'creator' => '申请人',
            'creat_date' => '申请时间',
            'creat_ip' => '申请IP',
            'i_whid' => '入庫倉庫(其他出库專用)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOWhpdtDts()
    {
        return $this->hasMany(OWhpdtDt::className(), ['o_whpkid' => 'o_whpkid']);
    }

    //获取状态
    public static function getStatus()
    {
        return [
            self::WAIT_LIBRARY=>'待出库',
            self::WAIT_RGOODS=>'待收货',
            self::ALREADY_WAREHOUSE=>'已收货',
            self::ALREADY_LIBRARY=>'已出库',
            self::OUTSTOCK_CANCEL=>'已取消',
            self::WAIT_COMMIT=>'待提交',
            self::CHECK_ING=>'审核中',
            self::CHECK_COMPLETE=>'审核完成',
            self::REJECT_STATUS=>'驳回',
            self::HAVE_BEEN_ABANDONED=>'已上架'
        ];
    }

    public static function getOutType(){
        return BsPubdata::find()->select("bsp_svalue")->indexBy("bsp_id")->where(['bsp_stype'=>'QTCKTYPE'])->asArray()->column();
    }

    public static function getTransType(){
        return BsTransport::find()->select("tran_sname")->indexBy("tran_id")->asArray()->column();
    }

    public static function getOrganization(){
        return HrOrganization::find()->select("organization_name")->where(["organization_state"=>10])->indexBy("organization_id")->asArray()->column();
    }

    public static function getStaff()
    {
        return HrStaff::find()->select('staff_name')->indexBy("staff_id")->asArray()->column();
    }
    public static function getStaffCode()
    {
        return HrStaff::find()->select('staff_code')->indexBy("staff_id")->asArray()->column();
    }

    public static function getDelveryType(){
        return BsDeliverymethod::find()->select("bdm_sname")->indexBy("bdm_id")->asArray()->column();
    }

    public static function getWareHouse(){
        return BsWh::find()->select("wh_id,wh_code,wh_name")->all();
    }
    public static function getSt(){
        return BsSt::find()->select("st_code")->indexBy("st_id")->asArray()->column();
    }
    public static function getBsCompany()
    {
        return BsCompany::find()->select("company_name")->indexBy("company_id")->asArray()->column();
    }
}
