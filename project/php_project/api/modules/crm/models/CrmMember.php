<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/5/25
 * Time: 9:30
 */
namespace app\modules\crm\models;

use app\modules\crm\models\CrmCustomerInfo;
class CrmMember extends CrmCustomerInfo
{
    public static function tableName()
    {
        return 'crm_bs_customer_info';
    }
    public function rules()
    {
        return [
            [['cust_sname'], 'required', 'message' => '{attribute}必填'],
            [['cust_sname'], 'unique', 'targetAttribute' => 'cust_sname', 'message' => '{attribute}已经存在'],
            [['member_name'], 'required', 'message' => '{attribute}必填'],
            [['member_name'], 'unique', 'targetAttribute' => 'member_name', 'message' => '{attribute}已经存在'],
            [['member_name'],'string','max'=>20]
            ];
    }
    public function attributeLabels()
    {
        return [
            'member_name'=>'会员名',
            'cust_sname'=>'公司名称'
        ];
    }
}