<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/7
 * Time: 17:26
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmVisitPlan;
use yii\helpers\Html;

class CrmVisitPlanShow extends CrmVisitPlan
{

    public function fields()
    {
        $field = parent::fields();
        //获取客户信息
        $field['customerInfo'] = function () {
            if (empty($this->crmCustomer)) {
                return null;
            }
            $customerId=$this->crmCustomer->cust_id;
            $customerManager=\Yii::$app->db->createCommand("select group_concat(b.staff_name) customerManager from erp.crm_bs_customer_personinch a left join erp.hr_staff b on b.staff_id = a.ccpich_personid where a.ccpich_status = 10 and a.ccpich_stype = 1 and a.cust_id = {$customerId} group by a.cust_id")->queryOne();
            $customerManager=$customerManager['customerManager'];
            return [
                'customerId' => $this->crmCustomer->cust_id, //id
                'customerName' => $this->crmCustomer->cust_sname,//客户名
                'customerDistrict' => $this->crmCustomer->districts,//详细地址
                'customerNumber' => $this->crmCustomer->cust_filernumber,     //档案编号
                'customerType' => $this->crmCustomer->custType['bsp_svalue'],//公司类型
//                'customerManager' => $this->crmCustomer->manager ? $this->crmCustomer->manager['staff_name'] : "",//客户经理人
                'customerManager'=>$customerManager,
                'customerContacts' => $this->crmCustomer->cust_contacts,//联系人
                'customerTel' => $this->crmCustomer->cust_tel1,//公司电话
                'customerTel2' => $this->crmCustomer->cust_tel2,//联系人电话
                'customerSaleArea' => $this->crmCustomer->saleArea ? $this->crmCustomer->saleArea['csarea_name'] : "",//军区
            ];
        };
        //客户名称
        $field['cust_sname'] = function () {
            return $this->crmCustomer['cust_sname'];
        };

        //获取拜访状态
        $field['status'] = function () {
            switch ($this->svp_status) {
                case self::STATUS_DEFAULT :
                    return '待实施';
                    break;
                case self::VISIT_PLAN_COMPLETE :
                    return '已实施';
                    break;
                case self::STATUS_CANCEL :
                    return '已取消';
                    break;
                case self::STATUS_BUSY :
                    return '实施中';
                    break;
                case self::STATUS_STOP :
                    return '已终止';
                    break;
                case self::STATUS_END :
                    return '已结束';
                    break;
                default :
                    return null;
            }
        };

        //获取拜访人
        $field['visitPerson'] = function () {
            return !empty($this->visitPerson) ? $this->visitPerson->staff_name : null;
        };
        $field['custName'] = function () {
            return !empty($this->crmCustomer) ? $this->crmCustomer->cust_sname : null;
        };

        //获取创建人
        $field['createPerson'] = function () {
            return !empty($this->createPerson) ? $this->createPerson->staff_name : null;
        };

        //获取拜访类型
        $field['visitType'] = function () {
            return !empty($this->visitType) ? $this->visitType->bsp_svalue : null;
        };

        //title F1678086
        $field['title'] = function () {
            return $this->title;
        };


        return $field;
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->create_at = date("Y-m-d", strtotime($this->create_at));
    }
}