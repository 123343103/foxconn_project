<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\hr\models\HrStaff;
use Yii;

/**
 * This is the model class for table "sale_details".
 *
 * @property string $sdl_id
 * @property string $sdl_type
 * @property string $sdl_comp
 * @property string $part_no
 * @property string $cust_code
 * @property string $cust_shortname
 * @property string $sdl_sacode
 * @property string $sdl_saname
 * @property string $produce_org
 * @property string $sale_date
 * @property string $sale_code
 * @property string $recede_code
 * @property string $sale_type
 * @property string $sdl_qty
 * @property string $sdl_unit
 * @property string $unit_cvs
 * @property string $unit_price
 * @property string $cur_code
 * @property string $bill_oamount
 * @property string $bill_camount
 * @property string $stan_cost
 * @property string $sale_cost
 */
class SaleDetailsTemp extends Common
{
    /**
     * @inheritdoc
     */
    const NEIGOUNEIXIAO = 10;       // 内购内销
    const NEIGOUWAIXIAO = 11;       // 内购外销
    const WAIGOUNEIXIAO = 12;       // 外购内销
    const WAIGOUWAIXIAO = 13;       // 外购外销

    public $amountSum;          // 当期销单总金额
    public $costSum;            // 当期销单总成本
    public $changeCost;         // 变动成本

    public static function tableName()
    {
        return 'sale_details_temp';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sdl_type', 'sdl_comp', 'part_no', 'cust_code', 'cust_shortname', 'sdl_sacode', 'sdl_saname', 'sale_date', 'sale_code', 'sale_type', 'sdl_qty', 'sdl_unit', 'unit_cvs', 'unit_price', 'cur_code', 'bill_oamount', 'bill_camount', 'stan_cost', 'sale_cost'], 'required'],
            [['sale_date'], 'safe'],
            [['sdl_qty', 'unit_cvs', 'unit_price', 'bill_oamount', 'bill_camount', 'stan_cost', 'sale_cost'], 'number'],
            [['sdl_type', 'sdl_comp', 'cust_code', 'cust_shortname', 'sdl_sacode', 'sdl_saname', 'produce_org', 'sale_code', 'recede_code', 'sdl_unit', 'cur_code'], 'string', 'max' => 20],
            [['sale_type'], 'string', 'max' => 4],
            [['part_no'], 'string', 'max' => 50],
            [['sale_code', 'part_no'], 'unique', 'targetAttribute' => ['sale_code', 'part_no'], 'message' => 'The combination of 料號 and 銷單/內交單號 has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sdl_id' => 'ID',
            'sdl_type' => '銷單銷退調整',
            'sdl_comp' => '公司法人',
            'part_no' => '料號',
            'cust_code' => '客戶代碼',
            'cust_shortname' => '客戶簡稱',
            'sdl_sacode' => '業務員工號',
            'sdl_saname' => '業務員姓名',
            'produce_org' => '製造部門',
            'sale_date' => '銷貨日期',
            'sale_code' => '銷單/內交單號',
            'recede_code' => '銷單/退編碼',
            'sale_type' => '銷售類型',
            'sdl_qty' => '銷貨數量',
            'sdl_unit' => '單位',
            'unit_cvs' => '單位換算率',
            'unit_price' => '單價',
            'cur_code' => '幣別',
            'bill_oamount' => '銷單金額(原幣)',
            'bill_camount' => '銷單金額(本幣)',
            'stan_cost' => '標准成本',
            'sale_cost' => '銷單成本',
        ];
    }

    /**
     * 从客户信息表获取客户信息表（用于销单法人资料）
     */
//    public function getCustInfo()
//    {
//        return $this->hasOne(CrmCustomerInfo::className(),['cust_id'=>'sdl_comp']);
//    }

    /**
     * 从员工信息表获取销售员工号、姓名
     */
//    public function getStaff()
//    {
//        return $this->hasOne(HrStaff::className(),['staff_code'=>'sdl_sacode']);
//    }

    /**
     * 获取销售点信息（三表关联）
     * getSellerInfo 通过销单明细获取员工信息
     * 通过getSellerInfo获取的员工信息进一步获取销售点信息
     */
    public function getSellerInfo()
    {
        return $this->hasOne(CrmEmployee::className(),['staff_code'=>'sdl_sacode']);
    }
    public function getStoreInfo()
    {
        return $this->hasOne(CrmStoresinfo::className(),['sts_id'=>'sts_id'])->via('sellerInfo');
    }

//    public function getChangeCost()
//    {
//        return $this->;
//    }

    /**
     * 通过销售点获取订单明细列表
     */
//    public function getOrdersByStore($sts_code)
//    {
//        return $orderList = self::find()->where(['sts_code'=>$sts_code])->asArray()->all();
//    }


    // 是否跨月份
    public static  function getCountMonths()
    {
        $result = static::find()->groupBy(['DATE_FORMAT(sale_date,\'%Y %m\')'])->count();
        return isset($result) ? $result:[];
    }

    // 获取月份
    public static  function getMonth()
    {
        $result = static::find('sale_date')->one();
        return isset($result) ? $result:[];
    }


    // 获取月份
    public static  function getJobNumbers()
    {
        $result = static::find('sdl_sacode')->all();
        return isset($result) ? $result:[];
    }

}
