<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\CrmStoresinfo;
use Yii;

/**
 * This is the model class for table "sale_salercost".
 *
 * @property string $ssc_id
 * @property string $ssc_no
 * @property string $ssc_year
 * @property string $ssc_month
 * @property string $stan_wage
 * @property string $real_wage
 * @property string $bill_oamount
 * @property string $bill_camount
 * @property integer $cur_id
 * @property string $appt_indcost
 * @property string $appt_dircost
 */
class SaleSalercost extends Common
{
    public $indirectTotal;
    public $directSellerNum;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_salercost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stan_wage', 'real_wage', 'bill_oamount', 'bill_camount', 'appt_indcost', 'appt_dircost'], 'number'],
            [['cur_id'], 'integer'],
            [['ssc_no'], 'string', 'max' => 20],
            [['ssc_year', 'ssc_month'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ssc_id' => 'Ssc ID',
            'ssc_no' => '銷售人員工號',
            'ssc_year' => '間期(年)',
            'ssc_month' => '間期(月)',
            'stan_wage' => '標準薪資',
            'real_wage' => '實際薪資',
            'bill_oamount' => '營銷總額(原幣)',
            'bill_camount' => '營銷總額(本幣)',
            'cur_id' => '幣別',
            'appt_indcost' => '分攤間接人力費用',
            'appt_dircost' => '分攤固定費用',
        ];
    }

    /**
     * 获取销售点信息（三表关联）
     * getSellerInfo 通过销单明细获取员工信息
     * 通过getSellerInfo获取的员工信息进一步获取销售点信息
     */
    public function getSellerInfo()
    {
        return $this->hasOne(CrmEmployee::className(),['staff_code'=>'ssc_no']);
    }
    public function getStoreInfo()
    {
        return $this->hasOne(CrmStoresinfo::className(),['sts_id'=>'sts_id'])->via('sellerInfo');
    }

    // 获取角色信息
    public function getRoleInfo()
    {
        return $this->hasOne(CrmSaleRoles::className(),['sarole_id'=>'sarole_id'])->via('sellerInfo');
    }

}
