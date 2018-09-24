<?php
/**
 * User: F1677929
 * Date: 2016/12/19
 */
namespace app\modules\app\models\show;

use app\modules\crm\models\CrmVisitRecordChild;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmVisitRecord;

/**
 * 拜访记录显示模型
 */
class VisitInfoChildShow extends CrmVisitRecordChild
{
    public function fields()
    {
        $field = parent::fields();
        //获取拜访人
        $field['visitPerson'] = function () {
            return !empty($this->visitPerson) ? $this->visitPerson->staff_name : null;

        };
        //获取客户信息
        $field['customerInfo'] = function () {
            if (empty($this->visitInfo->crmCustomer)) {
                return null;
            }
            return [
                'customerId' => $this->visitInfo->crmCustomer->cust_id,
                'customerCode' => $this->visitInfo->crmCustomer->cust_code,
                'customerName' => $this->visitInfo->crmCustomer->cust_sname,
                'customerType' => isset($this->visitInfo->customerType->bsp_svalue) ? $this->visitInfo->customerType->bsp_svalue : "",
                'customerManager' => isset($this->visitInfo->crmCustomer->manager->staff_name) ? $this->visitInfo->crmCustomer->manager->staff_name : "",
                'salesArea' => isset($this->visitInfo->salesArea->csarea_name) ? $this->visitInfo->salesArea->csarea_name : "",
                'contactPerson' => $this->visitInfo->crmCustomer->cust_contacts,
                'contactTel' => $this->visitInfo->crmCustomer->cust_tel2,
                'customerAddress' => isset($this->visitInfo->crmCustomer->district[0]->district_name) ?$this->visitInfo->crmCustomer->district[0]->district_name .
                    $this->visitInfo->crmCustomer->district[1]->district_name .
                    $this->visitInfo->crmCustomer->district[2]->district_name .
                    $this->visitInfo->crmCustomer->district[3]->district_name .
                    $this->visitInfo->crmCustomer->cust_adress:$this->visitInfo->crmCustomer->cust_adress
            ];
        };
        //获取拜访类型
        $field['visitType'] = function () {
            return [
                'visitTypeName' => !empty($this->visitType) ? $this->visitType->bsp_svalue : null,
                'visitTypeId' => !empty($this->visitType) ? $this->visitType->bsp_id : null,
            ];
        };
        //获取拜访用时
        $field['visitUseTime'] = function () {
            $time = strtotime($this->end) - strtotime($this->start);
            $day = floor($time / (3600 * 24));
            $remainder = $time % (3600 * 24);
            $hour = floor($remainder / 3600);
            $remainder = $remainder % 3600;
            $minute = floor($remainder / 60);
            $remainder = $remainder % 60;
            return $day . '天' . $hour . '小时' . $minute . '分' . $remainder . '秒';
        };
        //获取拜访计划
        $field['visitPlan'] = function () {
            return [
                'planCode' => !empty($this->planInfo) ? $this->planInfo->svp_code : null,
                'planId' => !empty($this->planInfo) ? $this->planInfo->svp_id : null,
                'title' => !empty($this->planInfo) ? $this->planInfo->title : null,
                'svp_content' => !empty($this->planInfo) ? $this->planInfo->svp_content : null,
            ];
        };
        //获取创建人
        $field['createPerson'] = function () {
            return !empty($this->createPerson) ? $this->createPerson->staff_name : null;
        };
        $field['img'] = function () {
            return unserialize($this->img_url);
        };
//        //关联客户信息
//        $field['customer']=function(){
//            return $this->visitInfo->crmCustomer;
//        };
        //客户名称
//        $field['name']=function(){
//            return $this->visitInfo->crmCustomer['cust_sname'];
//        };
//        $field['customerInfo']=function(){
//            return [
//                'customerName' => $this->visitInfo->crmCustomer['cust_sname'],
//                'customerType' => $this->visitInfo->customerType['bsp_svalue'],
//                'customerManager' => $this->visitInfo->customerManager['staff_name'],
//                'saleArea' => $this->visitInfo->salesArea['csarea_name'],
//            ];
//        };
//        //关联联系人
        $field['contactPerson'] = function () {
            return [
//                "name" => $this->visitInfo->contactPerson['ccper_name'],
//                "tel" => $this->visitInfo->contactPerson['ccper_tel'],
//                "id" => $this->visitInfo->contactPerson['ccper_id'],
            ];
        };
        return $field;
    }

    /*移动端查询*/
    public function AppSearch($params)
    {
        $sih_id = CrmVisitRecord::find()->where(['cust_id' => $params['id']])->andWhere(['<>', 'sih_status', CrmVisitRecord::STATUS_DELETE])->one()->sih_id;
        $query = self::find()->where(['and', ['sih_id' => $sih_id], ['!=', 'sil_status', CrmVisitRecordChild::STATUS_DELETE]])->orderBy(['create_at' => SORT_DESC]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }

        $startDate = $params['startDate'];
        $endDate = $params['endDate'];
        $staff_code = $params['staffCode'];

        $query->andFilterWhere(["sil_staff_code" => $staff_code]);
        //临时记录
        if (isset($params['type']) && !empty($params['type'])) {
            $query->andFilterWhere(["type" => $params['type']]);
        }

        if (isset($startDate) && !empty($startDate)) {
            $query->andFilterWhere([">=", "start", $startDate]);
        }
        if (isset($endDate) && !empty($endDate)) {
            $query->andFilterWhere(["<=", "end", date("Y-m-d H:i:s", strtotime($endDate . '+1 day'))]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        return $dataProvider;
    }

    /*移动端临时拜访查询*/
    public function TempSearch($params)
    {
        $query = self::find()->where(['and', ['type' => self::TYPE_LINSHI], ['!=', 'sil_status', CrmVisitRecordChild::STATUS_DELETE]])->orderBy(['start' => SORT_DESC]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }

        $content = $params['content'];
        $id = $params['id'];

        $query->andFilterWhere(['or',
            ['like', 'title', $content],
            ['like', 'sil_id', $content],
            ['like', 'sil_code', $content],
            ['like', 'type', $content],
            ['like', 'sil_interview_conclus', $content]]);
        $query->joinWith('visitInfo');
        $query->andFilterWhere(['crm_visit_info.cust_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        return $dataProvider;
    }
}