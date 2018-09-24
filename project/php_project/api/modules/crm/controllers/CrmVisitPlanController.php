<?php

namespace app\modules\crm\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmAccompany;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmVisitPlanSearch;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\crm\models\show\CrmVisitPlanShow;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;

/**
 * CrmVisitPlanController implements the CRUD actions for CrmVisitPlan model.
 */
class CrmVisitPlanController extends BaseActiveController
{

    public $modelClass = 'app\modules\crm\models\CrmVisitPlan';

    /**
     * 计划列表
     */
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.svp_id,
                     a.svp_code,
                     a.svp_content,
                     b.cust_id,
                     b.cust_sname custName,
                     a.start,
                     a.end,
                     c.staff_name visitPerson,
                     d.bsp_svalue visitType,
                     case a.svp_status when 10 then '待实施' when 20 then '已实施' when 30 then '已取消' when 40 then '实施中' when 50 then '已终止' when 60 then '已结束' when 10 then '待实施' else '未知' end status,
                     e.staff_name createPerson,
                     a.create_at,
                     f.customerManager
              from erp.crm_visit_plan a
              left join erp.crm_bs_customer_info b on b.cust_id = a.cust_id
              left join erp.hr_staff c on c.staff_code = a.svp_staff_code
              left join erp.bs_pubdata d on d.bsp_id = a.svp_type
              left join erp.hr_staff e on e.staff_id = a.create_by
              left join (select a1.cust_id,
                                group_concat(b1.staff_name) customerManager 
                         from erp.crm_bs_customer_personinch a1 
                         left join erp.hr_staff b1 on b1.staff_id = a1.ccpich_personid 
                         where a1.ccpich_status = 10 
                         and a1.ccpich_stype = 1
                         group by a1.cust_id
                        ) f on f.cust_id = b.cust_id
              where a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $sql.=$val.',';
        }
        $sql=trim($sql,',').')';
        if(!empty($params['uid'])){
            $sql.=" and role_auth({$params['uid']}, b.cust_id) IN ('1', '2')";
        }
        if(!empty($params['cust_sname'])){
            $trans=new Trans();
            $params['cust_sname']=str_replace(['%','_'],['\%','\_'],$params['cust_sname']);
            $queryParams[':cust_sname1']='%'.$params['cust_sname'].'%';
            $queryParams[':cust_sname2']='%'.$trans->c2t($params['cust_sname']).'%';
            $queryParams[':cust_sname3']='%'.$trans->t2c($params['cust_sname']).'%';
            $sql.=" and (b.cust_sname like :cust_sname1 or b.cust_sname like :cust_sname2 or b.cust_sname like :cust_sname3)";
        }
        if(!empty($params['svp_status'])){
            $queryParams[':svp_status']=$params['svp_status'];
            $sql.=" and a.svp_status = :svp_status";
        }
        if(!empty($params['svp_type'])){
            $queryParams[':svp_type']=$params['svp_type'];
            $sql.=" and a.svp_type = :svp_type";
        }
        if(!empty($params['start'])){
            $queryParams[':start']=date('Y-m-d H:i:s',strtotime($params['start']));
            $sql.=" and a.start >= :start";
        }
        if(!empty($params['end'])){
            $queryParams[':end']=date('Y-m-d H:i:s',strtotime($params['end'].'+1 day'));
            $sql.=" and a.end < :end";
        }
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $sql.=" order by a.svp_id desc";
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>empty($queryParams)?[]:$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //新增拜访计划
    public function actionCreate()
    {
        $data=Yii::$app->request->post();
        $transaction=Yii::$app->db->beginTransaction();
        try{
            //同一拜访人，同一时间段内只能添加一条拜访计划
            $bindParams=[
                ':staff'=>$data['CrmVisitPlan']['svp_staff_code'],
                ':start_time'=>$data['CrmVisitPlan']['start'],
                ':end_time'=>$data['CrmVisitPlan']['end']
            ];
            $r1=Yii::$app->db->createCommand("select a.start,a.end,a.cust_id from erp.crm_visit_plan a where a.svp_staff_code = :staff and a.svp_status <> 30 and a.svp_status <> 50 and ((:start_time < a.start and :end_time > a.end) or (:start_time > a.start and :end_time < a.end) or (:start_time < a.end and :end_time > a.end))",$bindParams)->queryOne();
            if(!empty($r1)){
                $staff=HrStaff::find()->select('staff_name')->where(['staff_code'=>$data['CrmVisitPlan']['svp_staff_code']])->one();
                $r3=CrmCustomerInfo::find()->select('cust_sname')->where(['cust_id'=>$r1['cust_id']])->one();
                throw new \Exception("该拜访人".$staff['staff_name']."在".$r1['start']."~".$r1['end'].'时间段拜访'.$r3['cust_sname']."客户，请重新选择拜访时间。");
            }
            //拜访计划表
            $planModel=new CrmVisitPlan();
            if(!$planModel->load($data) || !$planModel->save()){
                throw new \Exception(json_encode($planModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //陪同人表
            if(!empty($data['accompany'])){
                foreach($data['accompany'] as $val){
                    if(!empty($val['CrmAccompany']['acc_id']) && !empty($val['CrmAccompany']['acc_mobile'])){
                        $accompanyModel=new CrmAccompany();
                        $accompanyModel->type=1;
                        $accompanyModel->pid=$planModel->svp_id;
                        if(!$accompanyModel->load($val) || !$accompanyModel->save()){
                            throw new Exception(json_encode($accompanyModel->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success('新增成功',['id'=>$planModel->svp_id,'code'=>$planModel->svp_code]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 选择拜访计划
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSelectPlan()
    {
        return CrmVisitPlanShow::find()->all();
    }

    /**
     * 通过ID获取计划信息
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGetOnePlan($id)
    {
        $planData=CrmVisitPlanShow::findOne($id);
        $accompanyData=Yii::$app->db->createCommand("select b.staff_code,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 1 and a.pid = {$planData['svp_id']}")->queryAll();
        return [
            'planData'=>$planData,
            'accompanyData'=>$accompanyData
        ];
    }

    /**
     * 更新
     */
    public function actionUpdate($id)
    {
        $data=Yii::$app->request->post();
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $planModel=CrmVisitPlan::findOne($id);
            //同一拜访人，同一时间段内只能添加一条拜访计划
            $bindParams=[
                ':svp_id'=>$id,
                ':staff'=>!empty($data['CrmVisitPlan']['svp_staff_code'])?$data['CrmVisitPlan']['svp_staff_code']:$planModel->svp_staff_code,
                ':start_time'=>!empty($data['CrmVisitPlan']['start'])?$data['CrmVisitPlan']['start']:$planModel->start,
                ':end_time'=>!empty($data['CrmVisitPlan']['end'])?$data['CrmVisitPlan']['end']:$planModel->end
            ];
            $r1=Yii::$app->db->createCommand("select a.start,a.end,a.cust_id from erp.crm_visit_plan a where a.svp_id <> :svp_id and a.svp_staff_code = :staff and a.svp_status <> 30 and a.svp_status <> 50 and ((:start_time < a.start and :end_time > a.end) or (:start_time > a.start and :end_time < a.end) or (:start_time < a.end and :end_time > a.end))",$bindParams)->queryOne();
            if(!empty($r1)){
                $staff=HrStaff::find()->select('staff_name')->where(['staff_code'=>$planModel->svp_staff_code])->one();
                $r3=CrmCustomerInfo::find()->select('cust_sname')->where(['cust_id'=>$r1['cust_id']])->one();
                throw new \Exception("该拜访人".$staff['staff_name']."在".$r1['start']."~".$r1['end'].'时间段拜访'.$r3['cust_sname']."客户，请重新选择拜访时间。");
            }
            //拜访计划表
            if(!$planModel->load($data) || !$planModel->save()){
                throw new \Exception(json_encode($planModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //陪同人表
            if($planModel->svp_status != 40){
                CrmAccompany::deleteAll(['type'=>1,'pid'=>$id]);
            }
            if(!empty($data['accompany'])){
                foreach($data['accompany'] as $val){
                    if(!empty($val['CrmAccompany']['acc_id']) && !empty($val['CrmAccompany']['acc_mobile'])){
                        $accompanyModel=new CrmAccompany();
                        $accompanyModel->type=1;
                        $accompanyModel->pid=$planModel->svp_id;
                        if(!$accompanyModel->load($val) || !$accompanyModel->save()){
                            throw new Exception(json_encode($accompanyModel->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success('修改成功',['id'=>$planModel->svp_id,'code'=>$planModel->svp_code]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 视图
     */
    public function actionView($id)
    {
        $planData=CrmVisitPlanShow::findOne($id);
        $accompanyData=Yii::$app->db->createCommand("select b.staff_code,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 1 and a.pid = {$planData['svp_id']}")->queryAll();
        return [
            'planData'=>$planData,
            'accompanyData'=>$accompanyData
        ];
    }


    /**
     * 取消拜访计划
     */
    public function actionCancel()
    {
        $post = Yii::$app->request->post();
        $idArr = explode(",", $post['id']);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($idArr as $val) {
                $model = CrmVisitPlan::find()->where(['svp_id' => $val])->one();
                if($model->svp_status != 10){
                    throw new Exception('拜访计划'.$model->svp_code.'状态不为待实施，不可执行取消计划操作');
                }else{
                    $model->svp_status=30;
                    $model->cancl_rs = $post['cause'];
                    if(!$model->save()){
                        throw new Exception('拜访计划保存失败');
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 终止拜访计划
     */
    public function actionStop()
    {
        $post = Yii::$app->request->post();
        $idArr = explode(",", $post['id']);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($idArr as $val) {
                $model = CrmVisitPlan::find()->where(['svp_id' => $val])->one();
                if($model->svp_status != 40){
                    throw new Exception('拜访计划'.$model->svp_code.'状态不为实施中，不可执行终止计划操作');
                }else{
                    $model->svp_status=50;
                    $model->cancl_rs = $post['cause'];
                    if(!$model->save()){
                        throw new Exception('拜访计划保存失败');
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 获取计划列表数据
     */
    public function actionDownList()
    {
        //获取拜访类型
        $indexData['visitType'] = BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE);
        return $indexData;
    }

    public function actionCust($id)
    {
//        $result = CrmCustomerInfoShow::find()->where(['cust_id' => $id])->one();
//        $model['customerInfo']['customerId'] = $result['cust_id'];
//        $model['customerInfo']['customerName'] = $result['cust_sname'];
//        $model['customerInfo']['customerType'] = $result['custType']['bsp_sname'];
//        $model['customerInfo']['customerManager'] = $result['manager']['staff_name'];
//        $model['customerInfo']['customerSaleArea'] = $result['saleArea'];
//        $model['customerInfo']['customerContacts'] = $result['cust_contacts'];
//        $model['customerInfo']['customerTel2'] = $result['cust_tel2'];
//        $model['customerInfo']['customerDistrict'] = $result['districts'];
//        return $model;

        if(empty($id)){
            return [];
        }
        $sql="select a.cust_id,
                     a.cust_filernumber,
                     a.cust_sname,
                     c.bsp_svalue customerType,
                     group_concat(e.staff_name) customerManager,
                     f.csarea_name,
                     a.cust_contacts,
                     a.cust_tel2,
                     concat(j.district_name, i.district_name, h.district_name, g.district_name, a.cust_adress) customerAddress
              from erp.crm_bs_customer_info a
              left join erp.crm_bs_customer_status b on b.customer_id = a.cust_id
              left join erp.bs_pubdata c on c.bsp_id = a.cust_type
              left join erp.crm_bs_customer_personinch d on d.cust_id = a.cust_id
              left join erp.hr_staff e on e.staff_id = d.ccpich_personid
              left join erp.crm_bs_salearea f on f.csarea_id = a.cust_salearea
              left join erp.bs_district g on g.district_id = a.cust_district_2
              left join erp.bs_district h on h.district_id = g.district_pid
              left join erp.bs_district i on i.district_id = h.district_pid
              left join erp.bs_district j on j.district_id = i.district_pid
              where a.cust_id = {$id}";
        $sql.=" group by a.cust_id";
        $result = Yii::$app->db->createCommand($sql)->queryOne();
        $model['customerInfo']['customerId'] = $result['cust_id'];
        $model['customerInfo']['customerName'] = $result['cust_sname'];
        $model['customerInfo']['customerType'] = $result['customerType'];
        $model['customerInfo']['customerManager'] = $result['customerManager'];
        $model['customerInfo']['customerSaleArea'] = $result['csarea_name'];
        $model['customerInfo']['customerContacts'] = $result['cust_contacts'];
        $model['customerInfo']['customerTel2'] = $result['cust_tel2'];
        $model['customerInfo']['customerDistrict'] = $result['customerAddress'];
        return $model;
    }

    //删除计划
    public function actionDeletePlan($id)
    {
        $model = CrmVisitRecordChild::find()->where(['!=', 'sil_status', CrmVisitRecordChild::STATUS_DELETE])->andWhere(['svp_plan_id' => $id])->one();
        if (!empty($model)) {
            return $this->error('计划被拜访记录已引用，不可删除！');
        }
        $model = CrmVisitPlan::findOne($id);
        $model->svp_status = CrmVisitPlan::STATUS_DELETE;
        if ($model->save()) {
            return $this->success('删除成功！', $model->svp_code);
        }
        return $this->error(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
    }
}
