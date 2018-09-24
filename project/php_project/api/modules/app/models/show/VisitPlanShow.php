<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/7
 * Time: 17:26
 */
namespace app\modules\app\models\show;

use app\modules\crm\models\CrmVisitPlan;
use Yii;
use app\modules\common\models\BsCompany;
use yii\data\ActiveDataProvider;
use app\classes\Trans;

class VisitPlanShow extends CrmVisitPlan
{

    public function fields()
    {
        $field = parent::fields();
//        //供应商信息
//        $field['customer']=function(){
//          return $this->crmCustomer;
//        };
        //获取客户信息
        $field['customerInfo'] = function () {
            if (empty($this->crmCustomer)) {
                return null;
            }
            return [
                'cust_id' => isset($this->crmCustomer->cust_id) ?
                    $this->crmCustomer->cust_id : "",
                'cust_sname' => isset($this->crmCustomer->cust_sname) ?
                    $this->crmCustomer->cust_sname : "",
                'address' => isset($this->crmCustomer->District['0']['district_name']) ? $this->crmCustomer->District['0']['district_name'] . $this->crmCustomer->District['1']['district_name'] . $this->crmCustomer->District['2']['district_name'] . $this->crmCustomer->District['3']['district_name'] . $this->crmCustomer->cust_adress : $this->crmCustomer->cust_adress,
                'custType' => isset($this->crmCustomer->custType->bsp_svalue) ?
                    $this->crmCustomer->custType->bsp_svalue : "",
                'managerName' => isset($this->crmCustomer->manager->staff_name) ?
                    $this->crmCustomer->manager->staff_name : "",
                'cust_contacts' => $this->crmCustomer->cust_contacts,
                'cust_tel2' => $this->crmCustomer->cust_tel2,
                'saleArea' => isset($this->crmCustomer->saleArea->csarea_name) ?
                    $this->crmCustomer->saleArea->csarea_name : "",
            ];
        };
//        //客户名称
//        $field['name']=function(){
//            return $this->crmCustomer->cust_sname;
//        };

        //获取拜访状态
        $field['status'] = function () {
            switch ($this->svp_status) {
                case self::STATUS_DEFAULT :
                    return '待实施';
                    break;
                case self::VISIT_PLAN_COMPLETE :
                    return '已实施';
                    break;
                default :
                    return null;
            }
        };

        //获取拜访人
        $field['visitPerson'] = function () {
            return !empty($this->visitPerson) ? $this->visitPerson->staff_name : null;
        };

        //获取创建人
        $field['createPerson'] = function () {
            return !empty($this->createPerson) ? $this->createPerson->staff_name : null;
        };

        //获取拜访类型
        $field['visitType'] = function () {
            return !empty($this->visitType) ? $this->visitType->bsp_svalue : null;
        };

        return $field;
    }

    /**
     * 移动端搜索拜访计划
     */
    public function AppSearch($params, $companyId)
    {
        $query = VisitPlanShow::find()->select('svp_content,crm_visit_plan.cust_id,svp_id,start,end')->where(['!=', 'svp_status', self::STATUS_DELETE])
            ->andWhere(['in', 'crm_visit_plan.company_id', BsCompany::getIdsArr($companyId)]);

        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);

        //默认排序
        if (!Yii::$app->request->get('sort')) {
            $query->orderBy("crm_visit_plan.create_at desc");
        }
        $id = $params['id'];
        $staffCode = $params['staffCode'];
        $content = $params['content'];
        //UTF8内简繁转换
        $go = new Trans;
        $content = $go->t2c($content);
        $ftcontent = $go->c2t($content);


        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('crmCustomer');
        $query->andFilterWhere(['svp_staff_code' => $staffCode]);
        $query->andFilterWhere(['or',
            ['like', 'title', $content],
            ['like', 'title', $ftcontent],
            ['like', "crm_bs_customer_info.cust_sname", $content],
            ['like', "crm_bs_customer_info.cust_sname", $ftcontent],
            ['like', 'svp_content', $content],
            ['like', 'svp_content', $ftcontent]
        ]);
        $query->andFilterWhere(['crm_visit_plan.cust_id' => $id]);
        return $dataProvider;
    }
}