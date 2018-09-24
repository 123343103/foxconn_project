<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 *
 * 客户状态表
 * This is the model class for table "crm_bs_customer_status".
 *
 * @property integer $customer_id
 * @property integer $sale_status
 * @property integer $member_status
 * @property integer $investment_status
 */
class CrmCustomerStatus extends Common
{
    const STATUS_DEFAULT = 10;     //默认
    const STATUS_OTHER = 20;     //其他
    const STATUS_DEL = 0;            //删除

    /*招商开发状态*/
    const INVESTMENT_UN = 10; //未开发
    const INVESTMENT_IN = 20; //开发中
    const INVESTMENT_SUCC = 30; //开发成功
    const INVESTMENT_FAILURE = 40; //开发失败

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bs_customer_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id', 'potential_status', 'sale_status', 'member_status', 'investment_status', 'apply_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'sale_status' => '销售状态',
            'member_status' => '会员状态',
            'investment_status' => '招商状态',
        ];
    }

//    public function getCrmCustStatus($customerid)
//    {
//        $result = CrmCustomerStatus::find(['customer_id' => $customerid]);
//        return $result;
//    }
}
