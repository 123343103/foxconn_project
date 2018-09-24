<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\common\models\BsBusinessType;
use Yii;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use app\modules\hr\models\HrOrganization;
/**
 * This is the model class for table "ic_invh".
 *
 * @property string $invh_id
 * @property integer $comp_id
 * @property string $invh_code
 * @property string $invh_date
 * @property integer $invh_status
 * @property string $cust_id
 * @property string $organization_id
 * @property integer $inout_type
 * @property string $bus_code
 * @property string $inout_flag
 * @property string $origin_type
 * @property string $whs_type
 * @property integer $whs_id
 * @property string $invh_aboutno
 * @property integer $invh_reperson
 * @property string $invh_repaddress
 * @property string $invh_sendperson
 * @property string $invh_sendaddress
 * @property string $logistics_type
 * @property string $is_logistics
 * @property string $logistics_comp
 * @property string $logistics_no
 * @property string $inout_unit
 * @property string $inout_addr
 * @property string $whs_operator
 * @property string $consignment_quat
 * @property string $is_recede
 * @property string $trans_type
 * @property string $pri
 * @property string $invh_remark
 * @property integer $review_by
 * @property string $rdate
 * @property integer $create_by
 * @property string $cdate
 * @property integer $update_by
 * @property string $udate
 * @property integer $whp_id
 * @property string $pre_outstock_date
 * @property string $use_for
 * @property string $corporate
 * @property integer $delivery_district
 * @property string $applicant
 * @property string $delivery_type
 * @property integer $product_property
 */
class IcInvh extends Common
{
    //状态
    const DELETE_STATUS=0;//删除
    const WAIT_COMMIT=10;//待提交
    const CHECK_ING=20;//审核中
    const CHECK_COMPLETE=30;//审核完成
    const WAIT_WAREHOUSE=40;//待入仓
    const ALREADY_WAREHOUSE=50;//已入仓
    const REJECT_STATUS=60;//驳回
    const OUTSTOCK_CANCEL=70;//单据作废
    //入库单出库单标志
    const STOCK_IN_FLAG='I';
    const STOCK_OUT_FLAG='O';

    //入库单出库单编码标志
    public $codeType;
    const CODE_TYPE_O=10;
    const CODE_TYPE_I=20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ic_invh';
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
            [['comp_id', 'invh_status', 'cust_id', 'organization_id', 'inout_type', 'whs_id', 'invh_reperson', 'review_by', 'create_by', 'update_by', 'whp_id', 'delivery_district', 'product_property'], 'integer'],
            [['invh_date', 'rdate', 'cdate', 'udate', 'pre_outstock_date'], 'safe'],
            [['invh_status'], 'required'],
            [['consignment_quat'], 'number'],
            [['invh_code', 'use_for', 'corporate', 'applicant'], 'string', 'max' => 50],
            [['bus_code', 'origin_type', 'invh_aboutno', 'invh_repaddress', 'invh_sendperson', 'invh_sendaddress', 'logistics_type', 'logistics_no', 'inout_unit', 'whs_operator', 'trans_type', 'pri', 'delivery_type'], 'string', 'max' => 20],
            [['inout_flag', 'whs_type', 'is_logistics', 'is_recede'], 'string', 'max' => 4],
            [['logistics_comp'], 'string', 'max' => 60],
            [['inout_addr', 'invh_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invh_id' => 'Invh ID',
            'comp_id' => '公司ID',
            'invh_code' => '編碼',
            'invh_date' => '日期',
            'invh_status' => '狀態',
            'cust_id' => '客商id',
            'organization_id' => '單位部門ID',
            'inout_type' => '(单据类型)出入庫類型 如銷售出庫，庫存報損出庫，調撥出庫，調撥入庫，內部領用出庫',
            'bus_code' => '業務對象編碼 如銷售發貨，採購入庫 關聯業務對象表',
            'inout_flag' => '出入庫標誌 i or o',
            'origin_type' => '源單類型 如內購外銷',
            'whs_type' => '倉庫類別 如自有倉',
            'whs_id' => '收發貨倉庫  關聯倉庫信息表',
            'invh_aboutno' => '關聯單號',
            'invh_reperson' => '收貨人',
            'invh_repaddress' => '收貨人聯繫方式',
            'invh_sendperson' => '(送货人)發貨人',
            'invh_sendaddress' => '發貨人聯繫方式',
            'logistics_type' => '物流方式',
            'is_logistics' => '是否已轉物流',
            'logistics_comp' => '物流公司',
            'logistics_no' => '物流單號',
            'inout_unit' => '收???貨單位',
            'inout_addr' => '收發貨地址',
            'whs_operator' => '倉庫業務員',
            'consignment_quat' => '發貨總數量',
            'is_recede' => '是否有退貨',
            'trans_type' => '運輸方式',
            'pri' => '優先級',
            'invh_remark' => '單據備註',
            'review_by' => '審核人',
            'rdate' => '審核日期',
            'create_by' => '制單人',
            'cdate' => '制單日期',
            'update_by' => '修改人',
            'udate' => '修改日期',
            'whp_id' => '關聯倉庫費用表(wh_price)',
            'pre_outstock_date' => 'Pre Outstock Date',
            'use_for' => 'Use For',
            'corporate' => '法人',
            'delivery_district' => 'Delivery District',
            'applicant' => 'Applicant',
            'delivery_type' => 'Delivery Type',
        ];
    }
    //根据出货单号获取出货id
    public static function getInvhId($invh_code)
    {
        return self::find()
            ->select([
                self::tableName().".invh_id"
            ])
            ->where([
                self::tableName().'.invh_code' => $invh_code
            ])
            ->asArray()
            ->one();
    }

    //行为
//    public function behaviors()
//    {
//        return [
//            'timeStamp'=>[
//                'class'=>TimestampBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT=>['cdate'],
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE=>['udate']
//                ]
//            ],
//            'formCode'=>[
//                'class'=>FormCodeBehavior::className(),
//                'codeField'=>'invh_code',
//                'formName'=>self::tableName(),
//                'model'=>$this
//            ]
//        ];
//    }

    //获取状态
    public static function getStatus()
    {
        return [
            self::WAIT_COMMIT=>'待提交',
            self::CHECK_ING=>'审核中',
            self::CHECK_COMPLETE=>'审核完成',
//            self::WAIT_WAREHOUSE=>'待入仓',
//            self::ALREADY_WAREHOUSE=>'已入仓',
            self::REJECT_STATUS=>'驳回',
            self::OUTSTOCK_CANCEL=>'单据作废'
        ];
    }


    public static function getInOutType(){
        return BsBusinessType::find()->select("business_type_desc")->indexBy("business_type_id")->where(["business_code"=>"wm02"])->asArray()->column();
    }

    public static function getTransType(){
        return BsTransport::find()->select("tran_sname")->indexBy("tran_id")->asArray()->column();
    }

    public static function getOrganization(){
        return HrOrganization::find()->select("organization_name")->where(["organization_state"=>10])->indexBy("organization_id")->asArray()->column();
    }

    public static function getDelveryType(){
        return BsDeliverymethod::find()->select("bdm_sname")->indexBy("bdm_id")->asArray()->column();
    }

    public static function getWareHouse(){
        return BsWh::find()->select("wh_name")->indexBy("wh_id")->asArray()->column();
    }
    public static function getSt(){
        return BsSt::find()->select("st_code")->indexBy("st_id")->asArray()->column();
    }

    //产品性质选项
    public static function getProductProperty(){
        return ["样品","非样品"];
    }
}
