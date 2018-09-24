<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmSaleRoles;
use app\modules\crm\models\CrmStoresinfo;
use Yii;

/**
 * This is the model class for table "sale_details_sum".
 *
 * @property string $sds_id
 * @property string $sds_comp
 * @property string $sds_year
 * @property string $sds_month
 * @property string $sds_sacode
 * @property string $sds_saname
 * @property string $sts_code
 * @property string $sale_type
 * @property string $bill_camount
 * @property string $sale_cost
 * @property string $gross_profit
 * @property string $change_cost
 * @property string $operation_cost
 * @property string $indirect_cost
 * @property string $direct_cost
 * @property string $fixed_cost
 * @property string $profit
 * @property string $profit_margin
 */
class SaleDetailsSum extends Common
{
    public $ticheng4;
    public $ticheng3;
    public $ticheng2;
    public $ticheng1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_details_sum';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sds_id'], 'integer'],
            [['sds_comp', 'sds_sacode', 'sds_saname', 'sts_code'], 'string', 'max' => 20],
            [['bill_camount', 'sale_cost', 'gross_profit', 'change_cost', 'operation_cost', 'indirect_cost', 'direct_cost', 'fixed_cost', 'profit', 'profit_margin'], 'number'],
            [['sds_year', 'sds_month', 'sale_type'], 'string', 'max' => 4],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sds_id' => 'ID',
            'sds_comp' => '公司法人',
            'sds_year' => '銷單期間(year)',
            'sds_month' => '銷單期間(month)',
            'sds_sacode' => '業務員工號',
            'sds_saname' => '業務員姓名',
            'sts_code' => '銷售點代碼',
            'sale_type' => '銷售類型',
            'bill_camount' => '銷售金額(本幣)',
            'sale_cost' => '銷單成本',
            'gross_profit' => '毛利',
            'change_cost' => '變動成本',
            'operation_cost' => '業務費用',
            'indirect_cost' => '間接人力成本',
            'direct_cost' => '直接人力成本',
            'fixed_cost' => '固定費用分攤',
            'profit' => '利潤',
            'profit_margin' => '利潤率',
        ];
    }

    // 获取销售点信息
    public function getStoreInfo()
    {
        return $this->hasOne(CrmStoresinfo::className(),['sts_code'=>'sts_code']);
    }

    // 关联销售人员表
    public function getSellerInfo()
    {
        return $this->hasOne(CrmEmployee::className(),['staff_code'=>'sds_sacode']);
    }

    // 获取角色信息
    public function getRoleInfo()
    {
        return $this->hasOne(CrmSaleRoles::className(),['sarole_id'=>'sarole_id'])->via('sellerInfo');
    }
}
