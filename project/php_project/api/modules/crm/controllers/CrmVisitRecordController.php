<?php
/**
 * User: F1677929
 * Date: 2017/3/29
 */
namespace app\modules\crm\controllers;
use app\classes\Trans;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\Crm;
use app\modules\crm\models\CrmAccompany;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmReception;
use app\modules\crm\models\CrmSalearea;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\CrmVisitRecord;
use app\modules\crm\models\CrmVisitRecordChild;
use app\modules\crm\models\search\CrmActiveTypeSearch;
use app\controllers\BaseActiveController;
use app\modules\crm\models\search\CrmVisitRecordChildSearch;
use app\modules\crm\models\search\CrmVisitRecordSearch;
use app\modules\crm\models\show\CrmVisitRecordChildShow;
use app\modules\crm\models\show\CrmVisitRecordShow;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SysDisplayList;
use Yii;
use yii\bootstrap\Html;
use yii\data\SqlDataProvider;
use yii\db\Exception;
use yii\db\Query;
//客户拜访记录API控制器
class CrmVisitRecordController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmVisitRecord';
    //客户拜访记录列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            return [
                'customerType'=>BsPubdata::getData(BsPubdata::CRM_CUSTOMER_TYPE),
                'saleArea'=>CrmSalearea::find()->select('csarea_id,csarea_name')->where(['csarea_status'=>[CrmSalearea::STATUS_STOP,CrmSalearea::STATUS_DEFAULT]])->all()
            ];
        }
//        $queryParams=[];
//        $sql="select a.sih_id,
//                     a.sih_code,
//                     b.cust_id,
//                     b.cust_filernumber,
//                     b.cust_sname,
//                     d.bsp_svalue customerType,
//                     group_concat(f.staff_name) customerManager,
//                     g.csarea_name,
//                     b.cust_contacts,
//                     b.cust_tel2,
//                     concat(k.district_name, j.district_name, i.district_name, h.district_name, b.cust_adress) customerAddress,
//                     ifnull(a.update_at, a.create_at) sort_at
//              from erp.crm_visit_info a
//              left join erp.crm_bs_customer_info b on b.cust_id = a.cust_id
//              left join erp.crm_bs_customer_status c on c.customer_id = b.cust_id
//              left join erp.bs_pubdata d on d.bsp_id = b.cust_type
//              left join erp.crm_bs_customer_personinch e on e.cust_id = b.cust_id
//              left join erp.hr_staff f on f.staff_id = e.ccpich_personid
//              left join erp.crm_bs_salearea g on g.csarea_id = b.cust_salearea
//              left join erp.bs_district h on h.district_id = b.cust_district_2
//              left join erp.bs_district i on i.district_id = h.district_pid
//              left join erp.bs_district j on j.district_id = i.district_pid
//              left join erp.bs_district k on k.district_id = j.district_pid
//              where c.sale_status = 10
//              and e.ccpich_status = 10
//              and e.ccpich_stype = 1
//              and a.company_id in (";
//        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
//            $sql.=$val.',';
//        }
//        $sql=trim($sql,',').')';
//        //计划拜访、临时拜访
//        $sql1="select sih_id from erp.crm_visit_info_child where (type = 20 or type = 30)";
//        if(!empty($params['staffId'])){//管理员看到所有人拜访记录
//            $staffInfo=Yii::$app->db->createCommand("select staff_code from erp.hr_staff where staff_id = {$params['staffId']}")->queryOne();
//            $sql1.=" and sil_staff_code = '{$staffInfo['staff_code']}'";
//        }
//        $result=Yii::$app->db->createCommand($sql1)->queryAll();
//        if(!empty($result)){
//            $mainId=[];
//            foreach($result as $key=>$val){
//                $mainId[]=(int)$val['sih_id'];
//            }
//            $mainId=array_unique($mainId);
//            $sql.=" and a.sih_id in (";
//            foreach($mainId as $key=>$val){
//                $sql.=$val.',';
//            }
//            $sql=trim($sql,',').')';
//        }
//        //搜索
//        if(!empty($params['cust_sname'])){
//            $trans=new Trans();//处理简体繁体
//            $params['cust_sname']=str_replace(['%','_'],['\%','\_'],$params['cust_sname']);
//            $queryParams[':cust_sname1']='%'.$params['cust_sname'].'%';
//            $queryParams[':cust_sname2']='%'.$trans->c2t($params['cust_sname']).'%';
//            $queryParams[':cust_sname3']='%'.$trans->t2c($params['cust_sname']).'%';
//            $sql.=" and (b.cust_sname like :cust_sname1 or b.cust_sname like :cust_sname2 or b.cust_sname like :cust_sname3)";
//        }
//        if(!empty($params['cust_type'])){
//            $queryParams[':cust_type']=$params['cust_type'];
//            $sql.=" and b.cust_type = :cust_type";
//        }
//        if(!empty($params['cust_salearea'])){
//            $queryParams[':cust_salearea']=$params['cust_salearea'];
//            $sql.=" and b.cust_salearea = :cust_salearea";
//        }
//        $sql.=" group by b.cust_id order by sort_at desc";
//        $totalCount=Yii::$app->db->createCommand("select count(*) from ( {$sql} ) a",$queryParams)->queryScalar();
//        $provider=new SqlDataProvider([
//            'sql'=>$sql,
//            'params'=>$queryParams,
//            'totalCount'=>$totalCount,
//            'pagination'=>[
//                'pageSize'=>isset($params['rows'])?$params['rows']:false
//            ]
//        ]);
//        return [
//            'rows'=>$provider->getModels(),
//            'total'=>$provider->totalCount
//        ];
        $sql="select a.sih_id,
                     a.sih_code,
                     b.cust_id,
                     b.cust_filernumber,
                     b.cust_sname,
                     d.bsp_svalue customerType,
                     group_concat(f.staff_name) customerManager,
                     g.csarea_name,
                     b.cust_contacts,
                     b.cust_tel2,
                     concat(k.district_name, j.district_name, i.district_name, h.district_name, b.cust_adress) customerAddress,
                     ifnull(a.update_at, a.create_at) sort_at
              from erp.crm_visit_info a
              left join erp.crm_bs_customer_info b on b.cust_id = a.cust_id
              left join erp.crm_bs_customer_status c on c.customer_id = b.cust_id
              left join erp.bs_pubdata d on d.bsp_id = b.cust_type
              left join erp.crm_bs_customer_personinch e on e.cust_id = b.cust_id
              left join erp.hr_staff f on f.staff_id = e.ccpich_personid
              left join erp.crm_bs_salearea g on g.csarea_id = b.cust_salearea
              left join erp.bs_district h on h.district_id = b.cust_district_2
              left join erp.bs_district i on i.district_id = h.district_pid
              left join erp.bs_district j on j.district_id = i.district_pid
              left join erp.bs_district k on k.district_id = j.district_pid
              where c.sale_status = 10
              and e.ccpich_status = 10
              and e.ccpich_stype = 1
              and a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $sql.=$val.',';
        }
        $sql=trim($sql,',').')';
        if(!empty($params['uid'])){
            $sql.=" and role_auth({$params['uid']}, b.cust_id) IN ('1', '2')";
        }
        if(!empty($params['cust_sname'])){
            $trans=new Trans();//处理简体繁体
            $params['cust_sname']=str_replace(['%','_'],['\%','\_'],$params['cust_sname']);
            $queryParams[':cust_sname1']='%'.$params['cust_sname'].'%';
            $queryParams[':cust_sname2']='%'.$trans->c2t($params['cust_sname']).'%';
            $queryParams[':cust_sname3']='%'.$trans->t2c($params['cust_sname']).'%';
            $sql.=" and (b.cust_sname like :cust_sname1 or b.cust_sname like :cust_sname2 or b.cust_sname like :cust_sname3)";
        }
        if(!empty($params['cust_type'])){
            $queryParams[':cust_type']=$params['cust_type'];
            $sql.=" and b.cust_type = :cust_type";
        }
        if(!empty($params['cust_salearea'])){
            $queryParams[':cust_salearea']=$params['cust_salearea'];
            $sql.=" and b.cust_salearea = :cust_salearea";
        }
        $sql.=" group by b.cust_id order by sort_at desc";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
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

    //加载客户拜访记录
    public function actionLoadRecord()
    {
        $params=Yii::$app->request->queryParams;
        $model=new CrmVisitRecordChildSearch();
        $dataProvider=$model->searchVisitRecord($params);
        $rows=$dataProvider->getModels();
        if(!empty($params['staffId'])){
            $staffModel=HrStaff::findOne($params['staffId']);
            $newModel=CrmVisitRecordChild::find()->where(['sih_id'=>$params['mainId']])->andWhere(['sil_staff_code'=>$staffModel->staff_code])->andWhere(['!=','sil_status',0])->orderBy(['sil_id'=>SORT_DESC])->one();
        }
        if(!empty($rows)){
            foreach($rows as &$row){
//                $row['sil_time']=Html::decode($row['sil_time']);
//                $arr=unserialize($row['sil_time']);
//                $row['sil_time']=$arr[0].'天'.$arr[1].'时'.$arr[2].'分';
                //判断是否可以删除
                $row['del_edit_flag']=0;
                if($row['type']==20 || $row['type']==30){
                    if(empty($params['staffId']) || (!empty($newModel) && $newModel->sil_id==$row['sil_id'])){
                        $row['del_edit_flag']=1;
                    }
                }
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount,
        ];
    }

    //获取客户信息
    private function getCustomerInfo($customerId)
    {
//        $customerInfo=(new Query())->select([
//            CrmCustomerInfo::tableName().'.cust_id',//客户id
//            CrmCustomerInfo::tableName().'.cust_filernumber',//客户编号
//            CrmCustomerInfo::tableName().'.cust_sname',//客户名称
//            'bp1.bsp_svalue customerType',//客户类型
//            'hs1.staff_name customerManager',//客户经理人
//            'hs1.staff_code customerManagerCode',//客户经理人
//            CrmSalearea::tableName().'.csarea_name',//营销区域
//            CrmCustomerInfo::tableName().'.cust_contacts',//联系人
//            CrmCustomerInfo::tableName().'.cust_tel2',//联系电话
//            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.CrmCustomerInfo::tableName().'.cust_adress) AS customerAddress',//客户地址
//        ])->from(CrmCustomerInfo::tableName())
//            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmCustomerInfo::tableName().'.cust_type')
//            ->leftJoin(CrmCustPersoninch::tableName(),CrmCustPersoninch::tableName().'.cust_id='.CrmCustomerInfo::tableName().'.cust_id')
//            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmCustPersoninch::tableName().'.ccpich_personid')
//            ->leftJoin(CrmSalearea::tableName(),CrmSalearea::tableName().'.csarea_id='.CrmCustomerInfo::tableName().'.cust_salearea')
//            ->leftJoin(BsDistrict::tableName().' bd1','bd1.district_id='.CrmCustomerInfo::tableName().'.cust_district_2')
//            ->leftJoin(BsDistrict::tableName().' bd2','bd1.district_pid=bd2.district_id')
//            ->leftJoin(BsDistrict::tableName().' bd3','bd2.district_pid=bd3.district_id')
//            ->leftJoin(BsDistrict::tableName().' bd4','bd3.district_pid=bd4.district_id')
//            ->where([CrmCustomerInfo::tableName().'.cust_id'=>$customerId])
//            ->one();
//        return $customerInfo;
        if(empty($customerId)){
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
              where a.cust_id = {$customerId}";
        $sql.=" group by a.cust_id";
        return Yii::$app->db->createCommand($sql)->queryOne();
    }

    //新增拜访记录
    public function actionAdd($visitPersonId,$customerId,$planId)
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //同一拜访人，同一时间段内只能添加一条拜访记录
                $bind=[
                    ':staff'=>$data['CrmVisitRecordChild']['sil_staff_code'],
                    ':start_time'=>$data['CrmVisitRecordChild']['start'],
                    ':end_time'=>$data['CrmVisitRecordChild']['end']
                ];
                $r1=Yii::$app->db->createCommand("select a.sih_id,a.start,a.end from erp.crm_visit_info_child a where a.sil_staff_code = :staff and ((a.start > :start_time and a.start < :end_time) or (a.start < :start_time and a.end > :end_time) or (a.end > :start_time and a.end < :end_time))",$bind)->queryOne();
                if(!empty($r1)){
                    $staff=HrStaff::findOne(['staff_code'=>$data['CrmVisitRecordChild']['sil_staff_code']]);
                    $r2=CrmVisitRecord::findOne($r1['sih_id']);
                    $r3=CrmCustomerInfo::findOne($r2['cust_id']);
                    throw new \Exception("该拜访人".$staff['staff_name']."在".$r1['start']."~".$r1['end'].'时间段拜访'.$r3['cust_sname']."客户，请重新选择拜访时间。");
                }
                //拜访记录主表
                $recordMain=CrmVisitRecord::find()->where(['and',['cust_id'=>$data['CrmVisitRecord']['cust_id']],['!=','sih_status',CrmVisitRecord::STATUS_DELETE]])->one();
                if(empty($recordMain)){
                    $recordMain=new CrmVisitRecord();
                }
                $recordMain->sih_status=CrmVisitRecord::STATUS_DEFAULT;
                if(!($recordMain->load($data)&&$recordMain->save())){
                    throw new Exception(json_encode($recordMain->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //拜访记录子表
                $recordChild=new CrmVisitRecordChild();
                $recordChild->sih_id=$recordMain->sih_id;
                $recordChild->sil_status=CrmVisitRecordChild::STATUS_DEFAULT;
                if(!($recordChild->load($data)&&$recordChild->save())){
                    throw new Exception(json_encode($recordChild->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //拜访计划表
                if(!empty($data['CrmVisitRecordChild']['svp_plan_id'])){
                    $planModel=CrmVisitPlan::findOne($data['CrmVisitRecordChild']['svp_plan_id']);
                    $planModel->svp_status=CrmVisitPlan::VISIT_PLAN_COMPLETE;
                    if(!$planModel->save()){
                        throw new Exception(json_encode($planModel->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
                //接待人表
                if(!empty($data['reception'])){
                    foreach($data['reception'] as $val){
                        if(!empty($val['CrmReception']['rece_sname'])){
                            $receptionModel=new CrmReception();
                            $receptionModel->cust_id=$data['CrmVisitRecord']['cust_id'];
                            $receptionModel->h_id=$recordMain->sih_id;
                            $receptionModel->l_id=$recordChild->sil_id;
                            if(!$receptionModel->load($val) || !$receptionModel->save()){
                                throw new Exception(json_encode($receptionModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                //陪同人表
                if(!empty($data['accompany'])){
                    foreach($data['accompany'] as $val){
                        if(!empty($val['CrmAccompany']['acc_id']) && !empty($val['CrmAccompany']['acc_mobile'])){
                            $accompanyModel=new CrmAccompany();
                            $accompanyModel->type=2;
                            $accompanyModel->pid=$recordChild->sil_id;
                            if(!$accompanyModel->load($val) || !$accompanyModel->save()){
                                throw new Exception(json_encode($accompanyModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                //处理客户表-客户列表-是否回访
                $customerInfo=CrmCustomerInfo::findOne($recordMain->cust_id);
                if(empty($customerInfo->member_visitflag)){
                    $customerInfo->member_visitflag=1;
                    if(!$customerInfo->save()){
                        throw new Exception(json_encode($customerInfo->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
                return $this->success($recordChild->sil_code,$recordChild->sil_id);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        if(!empty($planId)){
            $visitPlan=Yii::$app->db->createCommand("select a.svp_id,a.svp_code,a.start,a.end from erp.crm_visit_plan a where a.svp_id = {$planId} and (a.svp_status = 40 or a.svp_status = 10)")->queryOne();
            if(!empty($visitPlan)){
                $visitPlan['accompanyData']=Yii::$app->db->createCommand("select b.staff_code,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 1 and a.pid = {$visitPlan['svp_id']}")->queryAll();
            }
        }
        return [
            'customerInfo'=>$this->getCustomerInfo($customerId),
            'visitPerson'=>HrStaff::getStaffById($visitPersonId),
            'visitType'=>BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE),
            'visitPlan'=>empty($visitPlan)?[]:$visitPlan,
        ];
    }

    //判断拜访记录是否是当前用户下最新的记录
    public function actionRecordNewJudge($childId,$staffId)
    {
        $recordChild=CrmVisitRecordChild::findOne($childId);
        $staffModel=HrStaff::findOne($staffId);
        $recordChildNew=CrmVisitRecordChild::find()
            ->where(['sil_staff_code'=>$staffModel['staff_code']])
            ->andWhere(['sih_id'=>$recordChild['sih_id']])
            ->andWhere(['!=','sil_status',CrmVisitRecordChild::STATUS_DELETE])
            ->orderBy(['create_at'=>SORT_DESC])
            ->one();
        if($recordChild['sil_id']!=$recordChildNew['sil_id']){
            return $this->error('该记录不是自己的或不是最新的，不可修改！');
        }
        return $this->success();
    }

    //修改拜访记录
    public function actionEdit($childId)
    {
        $recordChild=CrmVisitRecordChild::findOne($childId);
        $recordMain=CrmVisitRecord::findOne($recordChild->sih_id);
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //同一拜访人，同一时间段内只能添加一条拜访记录
                $bind=[
                    ':staff'=>!empty($recordChild['sil_staff_code'])?$recordChild['sil_staff_code']:$recordChild->sil_staff_code,
                    ':start_time'=>!empty($data['CrmVisitRecordChild']['start'])?$data['CrmVisitRecordChild']['start']:$recordChild->start,
                    ':end_time'=>!empty($data['CrmVisitRecordChild']['end'])?$data['CrmVisitRecordChild']['end']:$recordChild->end,
                    ':sil_id'=>$recordChild['sil_id']
                ];
                $r1=Yii::$app->db->createCommand("select a.sih_id,a.start,a.end from erp.crm_visit_info_child a where a.sil_id <> :sil_id and a.sil_staff_code = :staff and ((a.start > :start_time and a.start < :end_time) or (a.start < :start_time and a.end > :end_time) or (a.end > :start_time and a.end < :end_time))",$bind)->queryOne();
                if(!empty($r1)){
                    $staff=HrStaff::findOne(['staff_code'=>$recordChild['sil_staff_code']]);
                    $r2=CrmVisitRecord::findOne($r1['sih_id']);
                    $r3=CrmCustomerInfo::findOne($r2['cust_id']);
                    throw new \Exception("该拜访人".$staff['staff_name']."在".$r1['start']."~".$r1['end'].'时间段拜访'.$r3['cust_sname']."客户，请重新选择拜访时间。");
                }
                //拜访记录主表
                if(!($recordMain->load($data)&&$recordMain->save())){
                    throw new Exception(json_encode($recordMain->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //拜访记录子表
                if(!($recordChild->load($data)&&$recordChild->save())){
                    throw new Exception(json_encode($recordChild->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //接待人表
                CrmReception::deleteAll(['l_id'=>$recordChild->sil_id]);
                if(!empty($data['reception'])){
                    foreach($data['reception'] as $val){
                        if(!empty($val['CrmReception']['rece_sname'])){
                            $receptionModel=new CrmReception();
                            $receptionModel->cust_id=$recordMain->cust_id;
                            $receptionModel->h_id=$recordMain->sih_id;
                            $receptionModel->l_id=$recordChild->sil_id;
                            if(!$receptionModel->load($val) || !$receptionModel->save()){
                                throw new Exception(json_encode($receptionModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                //陪同人表
                CrmAccompany::deleteAll(['type'=>2,'pid'=>$childId]);
                if(!empty($data['accompany'])){
                    foreach($data['accompany'] as $val){
                        if(!empty($val['CrmAccompany']['acc_id']) && !empty($val['CrmAccompany']['acc_mobile'])){
                            $accompanyModel=new CrmAccompany();
                            $accompanyModel->type=2;
                            $accompanyModel->pid=$recordChild->sil_id;
                            if(!$accompanyModel->load($val) || !$accompanyModel->save()){
                                throw new Exception(json_encode($accompanyModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success($recordChild->sil_code);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $visitPlan=CrmVisitPlan::find()->select('svp_id,svp_code,start,end')->where(['svp_id'=>$recordChild->svp_plan_id])->one();
        return [
            'customerInfo'=>$this->getCustomerInfo($recordMain->cust_id),
            'visitPerson'=>HrStaff::find()->select(['staff_code','staff_name'])->where(['staff_code'=>$recordChild->sil_staff_code])->one(),
            'visitType'=>BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE),
            'recordChild'=>$recordChild,
            'visitPlan'=>$visitPlan,
            'receptionData'=>CrmReception::find()->where(['l_id'=>$childId])->all(),
            'accompanyData'=>$this->getAccompany($recordChild->sil_id)
        ];
    }

    /**
     * User:F3859386
     * 新增拜访记录
     * @return array
     */
    public function actionCreate()
    {
        $data=Yii::$app->request->post();
        $transaction=Yii::$app->db->beginTransaction();
        try{
            //拜访记录主表
            $recordMain=CrmVisitRecord::find()->where(['and',['cust_id'=>$data['CrmVisitRecord']['cust_id']],['!=','sih_status',CrmVisitRecord::STATUS_DELETE]])->one();
            if(empty($recordMain)){
                $recordMain=new CrmVisitRecord();
                $recordMain->sih_status=CrmVisitRecord::STATUS_DEFAULT;
                if(!($recordMain->load($data)&&$recordMain->save())){
                    throw new \Exception("拜访记录主表保存失败！");
                }
            }
            //拜访记录子表
            $recordChild=new CrmVisitRecordChild();
            $recordChild->sih_id=$recordMain->sih_id;
            $recordChild->sil_status=CrmVisitRecordChild::STATUS_DEFAULT;
            if(!($recordChild->load($data)&&$recordChild->save())){
                throw new \Exception("拜访记录子表保存失败！");
            }
            $transaction->commit();
            return $this->success();
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * User:F3859386
     * 新增临时拜访
     * @return array
     */
    public function actionCreateTemp()
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //拜访记录主表
                $recordMain=CrmVisitRecord::find()->where(['and',['cust_id'=>$data['CrmVisitRecord']['cust_id']],['!=','sih_status',CrmVisitRecord::STATUS_DELETE]])->one();
                if(empty($recordMain)){
                    $recordMain=new CrmVisitRecord();
                    $recordMain->sih_status=CrmVisitRecord::STATUS_DEFAULT;
                    if(!($recordMain->load($data)&&$recordMain->save())){
                        throw new Exception(json_encode($recordMain->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
                //拜访记录子表
                $recordChild=new CrmVisitRecordChild();
                $recordChild->sih_id=$recordMain->sih_id;
                $recordChild->sil_status=CrmVisitRecordChild::STATUS_DEFAULT;
                if(!($recordChild->load($data)&&$recordChild->save())){
                    throw new Exception(json_encode($recordChild->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //接待人表
                if(!empty($data['reception'])){
                    foreach($data['reception'] as $val){
                        if(!empty($val['CrmReception']['rece_sname'])){
                            $receptionModel=new CrmReception();
                            $receptionModel->cust_id=$recordMain->cust_id;
                            $receptionModel->h_id=$recordMain->sih_id;
                            $receptionModel->l_id=$recordChild->sil_id;
                            if(!$receptionModel->load($val) || !$receptionModel->save()){
                                throw new Exception(json_encode($receptionModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success();
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return [
            'visitType'=>BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE),
        ];
    }

    //查看拜访记录
    public function actionViewRecord($childId,$staffId='')
    {
        $recordChild=(new Query())->select([
            CrmVisitRecordChild::tableName().'.*',
            CrmVisitRecord::tableName().'.cust_id',
            CrmVisitPlan::tableName().'.svp_id',
            CrmVisitPlan::tableName().'.svp_code',
            CrmVisitPlan::tableName().'.start plan_start_time',
            CrmVisitPlan::tableName().'.end plan_end_time',
            'hs1.staff_name visitPersonName',
            'hs1.staff_code visitPersonCode',
            'bp1.bsp_svalue visitType',
        ])->from(CrmVisitRecordChild::tableName())
            ->leftJoin(CrmVisitRecord::tableName(),CrmVisitRecord::tableName().'.sih_id='.CrmVisitRecordChild::tableName().'.sih_id')
            ->leftJoin(CrmVisitPlan::tableName(),CrmVisitPlan::tableName().'.svp_id='.CrmVisitRecordChild::tableName().'.svp_plan_id')
            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_code='.CrmVisitRecordChild::tableName().'.sil_staff_code')
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmVisitRecordChild::tableName().'.sil_type')
            ->where([CrmVisitRecordChild::tableName().'.sil_id'=>$childId])
            ->one();
        //判断是否可以修改删除
        if(!empty($staffId)){
            $staffModel=HrStaff::findOne($staffId);
            $newModel=CrmVisitRecordChild::find()
                ->where(['sih_id'=>$recordChild['sih_id']])
                ->andWhere(['sil_staff_code'=>$staffModel->staff_code])
                ->andWhere(['!=','sil_status',0])
                ->orderBy(['sil_id'=>SORT_DESC])
                ->one();
        }
        $del_edit_flag=0;
        if($recordChild['type']==20 || $recordChild['type']==30){
            if(empty($staffId) || (!empty($newModel) && $newModel->sil_id==$recordChild['sil_id'])){
                $del_edit_flag=1;
            }
        }
        return [
            'flag'=>$del_edit_flag,
            'customerInfo'=>$this->getCustomerInfo($recordChild['cust_id']),
            'recordChild'=>$recordChild,
            'receptionData'=>CrmReception::find()->where(['l_id'=>$childId])->all(),
            'accompanyData'=>$this->getAccompany($recordChild['sil_id'])
        ];
    }

    //查看所有拜访记录
    public function actionViewRecords($mainId,$staffId='')
    {
        $recordMain=CrmVisitRecord::findOne($mainId);
        $model=new CrmVisitRecordChildSearch();
        $allRecord=$model->searchAllRecord($mainId);
        //判断是否可以修改删除
        if(!empty($staffId)){
            $staffModel=HrStaff::findOne($staffId);
            $newModel=CrmVisitRecordChild::find()->where(['sih_id'=>$mainId])->andWhere(['sil_staff_code'=>$staffModel->staff_code])->andWhere(['!=','sil_status',0])->orderBy(['sil_id'=>SORT_DESC])->one();
        }
        if(!empty($allRecord)){
            foreach($allRecord as &$val){
                $val['receptionData']=CrmReception::find()->where(['l_id'=>$val['sil_id']])->all();
                $val['accompanyData']=$this->getAccompany($val['sil_id']);
                $val['del_edit_flag']=0;
                if($val['type']==20 || $val['type']==30){
                    if(empty($staffId) || (!empty($newModel) && $newModel->sil_id==$val['sil_id'])){
                        $val['del_edit_flag']=1;
                    }
                }
            }
        }
        return [
            'customerInfo'=>$this->getCustomerInfo($recordMain->cust_id),
            'allRecord'=>$allRecord,
            'mainCode'=>$recordMain->sih_code
        ];
    }

    //删除子表
    public function actionDeleteChild($childId,$staffId,$super)
    {
        $recordChild=CrmVisitRecordChild::findOne($childId);
        if(empty($super)){//判断超级管理员
            $staffModel=HrStaff::findOne($staffId);
            $recordChildNew=CrmVisitRecordChild::find()
                ->where(['sil_staff_code'=>$staffModel->staff_code])
                ->andWhere(['sih_id'=>$recordChild->sih_id])
                ->andWhere(['!=','sil_status',CrmVisitRecordChild::STATUS_DELETE])
                ->orderBy(['create_at'=>SORT_DESC])
                ->one();
            if($recordChild['sil_id']!=$recordChildNew['sil_id']){
                return $this->error('该记录不是自己的或不是最新的，不可删除！');
            }
        }
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $recordTotal=CrmVisitRecordChild::find()
                ->where(['!=','sil_status',CrmVisitRecordChild::STATUS_DEFAULT])
                ->andWhere(['sih_id'=>$recordChild->sih_id])
                ->count();
            if($recordTotal==1){
                //拜访记录主表
                $recordMain=CrmVisitRecord::findOne($recordChild->sih_id);
                $recordMain->sih_status=CrmVisitRecord::STATUS_DELETE;
                if(!$recordMain->save()){
                    throw  new \Exception("拜访记录主表删除失败！");
                }
            }
            //拜访记录子表
            $recordChild->sil_status=CrmVisitRecordChild::STATUS_DELETE;
            if(!$recordChild->save()){
                throw  new \Exception("拜访记录子表删除失败！");
            }
            $transaction->commit();
            return $this->success($recordChild->sil_code,$recordTotal);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //选择客户信息
    public function actionSelectCustomer()
    {
//        $model=new CrmVisitRecordSearch();
//        $dataProvider=$model->searchCustomerInfo(Yii::$app->request->queryParams);
//        return [
//            'rows'=>$dataProvider->getModels(),
//            'total'=>$dataProvider->totalCount,
//        ];
        $queryParams=[];
        $params=Yii::$app->request->queryParams;
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
              where b.sale_status = 10
              and a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $sql.=$val.',';
        }
        $sql=trim($sql,',').')';
        //管理员看到所有，非管理员看到客户经理人是包含自己和没有客户经理人的客户
        if(!empty($params['staffId'])){
            $sql1="select a.cust_id 
                   from erp.crm_bs_customer_personinch a
                   where a.ccpich_personid = {$params['staffId']}";
            $result1=Yii::$app->db->createCommand($sql1)->queryAll();
            if(!empty($result1)){
                $sql.=" and ((d.cust_id is null and a.personinch_status = 0) or (d.ccpich_status = 10 and d.ccpich_stype = 1 and a.cust_id in (";
                foreach($result1 as $val){
                    $sql.=$val['cust_id'].',';
                }
                $sql=trim($sql,',').')))';
            }
        }
        $sql.=" group by a.cust_id order by a.cust_id desc";
        //搜索
        if(!empty($params['keyword'])){
            $trans=new Trans();//处理简体繁体
            $params['keyword']=str_replace(['%','_'],['\%','\_'],$params['keyword']);
            $queryParams[':keyword1']='%'.$params['keyword'].'%';
            $queryParams[':keyword2']='%'.$trans->c2t($params['keyword']).'%';
            $queryParams[':keyword3']='%'.$trans->t2c($params['keyword']).'%';
            $sql="select * from ({$sql}) a1 
                  where a1.cust_filernumber like :keyword1 
                  or a1.cust_sname like :keyword1 
                  or a1.cust_sname like :keyword2 
                  or a1.cust_sname like :keyword3 
                  or a1.customerManager like :keyword1 
                  or a1.customerManager like :keyword2 
                  or a1.customerManager like :keyword3";
        }
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) a2",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //认领客户
    public function actionClaimCustomer($customerId,$staffId)
    {
        $staffModel=HrStaff::find()->select('staff_code')->where(['staff_id'=>$staffId])->one();
        $employeeModel=CrmEmployee::find()->where(['staff_code'=>$staffModel->staff_code])->andWhere(['isrule'=>CrmEmployee::SALE_MANAGER_Y])->andWhere(['sale_status'=>CrmEmployee::SALE_STATUS_DEFAULT])->one();
        if(empty($employeeModel)){
            return $this->error('登陆用户非销售人员，不可认领客户！');
        }
        $transaction=Yii::$app->db->beginTransaction();
        try{
            //客户表
            $customerModel=CrmCustomerInfo::findOne($customerId);
            foreach ($customerModel as $k=>$v) {
                $customerModel[$k] = html_entity_decode($v);
            }
            $customerModel->personinch_status=CrmCustomerInfo::ASSIGN_STATUS_YES;
            if(!$customerModel->save()){
                throw new \Exception(json_encode($customerModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //认领表
            $claimModel=CrmCustPersoninch::findOne(['cust_id'=>$customerId]);
            if(empty($claimModel)){
                $claimModel=new CrmCustPersoninch();
                $claimModel->cust_id=$customerId;
            }
            $claimModel->ccpich_status=CrmCustPersoninch::STATUS_DEFAULT;
            $claimModel->ccpich_stype=CrmCustPersoninch::PERSONINCH_SALES;
            $claimModel->ccpich_personid=$staffId;
            $claimModel->ccpich_date=date('Y-m-d');
            if(!$claimModel->save()){
                throw new \Exception(json_encode($claimModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            $transaction->commit();
            return $this->success($customerModel->cust_filernumber);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //选择拜访计划
    public function actionSelectPlan()
    {
        $model=new CrmVisitRecordSearch();
        $dataProvider=$model->searchVisitPlan(Yii::$app->request->queryParams);
        $rows=$dataProvider->getModels();
        if(!empty($rows)){
            foreach($rows as &$row){
                $row['accompanyData']=Yii::$app->db->createCommand("select b.staff_id,b.staff_code,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 1 and a.pid = {$row['svp_id']}")->queryAll();
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount,
        ];
    }

    //列表导出
    public function actionExport()
    {
        $model=new CrmVisitRecordSearch();
        $dataProvider=$model->searchVisitCustomer(Yii::$app->request->queryParams);
        return $dataProvider->getModels();
    }

    //明细表
    public function actionList()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            return [
                'customerType'=>BsPubdata::getData(BsPubdata::CRM_CUSTOMER_TYPE),
                'visitType'=>BsPubdata::getData(BsPubdata::CRM_VISIT_TYPE)
            ];
        }
//        $model=new CrmVisitRecordChildSearch();
//        $dataProvider=$model->searchList($params);
//        $rows=$dataProvider->getModels();
//        foreach($rows as &$row){
//            if(!empty($row['start'])){
//                $row['start']=date('Y-m-d H:i',strtotime($row['start']));
//            }
//            if(!empty($row['end'])){
//                $row['end']=date('Y-m-d H:i',strtotime($row['end']));
//            }
//        }
//        return [
//            'rows'=>$rows,
//            'total'=>$dataProvider->totalCount,
//        ];
        $queryParams=[];
        $sql="select a.sil_id,
                     a.sil_code,
                     c.cust_sname,
                     e.bsp_svalue customerType,
                     f.customerManager,
                     h.csarea_name,
                     c.cust_contacts,
                     c.cust_tel2,
                     concat(l.district_name, k.district_name, j.district_name, i.district_name, c.cust_adress) customerAddress,
                     m.staff_name visitPerson,
                     n.bsp_svalue visitType,
                     substring(a.start, 1, 16) start,
                     substring(a.end, 1, 16) end,
                     a.sil_interview_conclus,
                     o.svp_id,
                     o.svp_code,
                     p.staff_name createPerson,
                     a.create_at
              from erp.crm_visit_info_child a 
              left join erp.crm_visit_info b on b.sih_id = a.sih_id
              left join erp.crm_bs_customer_info c on c.cust_id = b.cust_id
              left join erp.crm_bs_customer_status d on d.customer_id = c.cust_id
              left join erp.bs_pubdata e on e.bsp_id = c.cust_type
              left join (select a1.cust_id,
                                group_concat(b1.staff_name) customerManager
                         from erp.crm_bs_customer_personinch a1 
                         left join erp.hr_staff b1 on b1.staff_id = a1.ccpich_personid 
                         where a1.ccpich_status = 10 
                         and a1.ccpich_stype = 1 
                         group by a1.cust_id
                        ) f on f.cust_id = c.cust_id
              left join erp.crm_bs_salearea h on h.csarea_id = c.cust_salearea
              left join erp.bs_district i on i.district_id = c.cust_district_2
              left join erp.bs_district j on j.district_id = i.district_pid
              left join erp.bs_district k on k.district_id = j.district_pid
              left join erp.bs_district l on l.district_id = k.district_pid
              left join erp.hr_staff m on m.staff_code = a.sil_staff_code
              left join erp.bs_pubdata n on n.bsp_id = a.sil_type
              left join erp.crm_visit_plan o on o.svp_id = a.svp_plan_id
              left join erp.hr_staff p on p.staff_id = a.create_by
              where d.sale_status = 10
              and b.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $sql.=$val.',';
        }
        $sql=trim($sql,',').')';
        //计划拜访、临时拜访
        $sql1="select sih_id from erp.crm_visit_info_child where (type = 20 or type = 30)";
        if(!empty($params['staffId'])){//管理员看到所有人拜访记录
            $staffInfo=Yii::$app->db->createCommand("select staff_code from erp.hr_staff where staff_id = {$params['staffId']}")->queryOne();
            $sql1.=" and sil_staff_code = '{$staffInfo['staff_code']}'";
        }
        $result=Yii::$app->db->createCommand($sql1)->queryAll();
        if(!empty($result)){
            $mainId=[];
            foreach($result as $key=>$val){
                $mainId[]=(int)$val['sih_id'];
            }
            $mainId=array_unique($mainId);
            $sql.=" and b.sih_id in (";
            foreach($mainId as $key=>$val){
                $sql.=$val.',';
            }
            $sql=trim($sql,',').')';
        }
        //搜索
        if(!empty($params['cust_sname'])){
            $trans=new Trans();//处理简体繁体
            $params['cust_sname']=str_replace(['%','_'],['\%','\_'],$params['cust_sname']);
            $queryParams[':cust_sname1']='%'.$params['cust_sname'].'%';
            $queryParams[':cust_sname2']='%'.$trans->c2t($params['cust_sname']).'%';
            $queryParams[':cust_sname3']='%'.$trans->t2c($params['cust_sname']).'%';
            $sql.=" and (c.cust_sname like :cust_sname1 or c.cust_sname like :cust_sname2 or c.cust_sname like :cust_sname3)";
        }
        if(!empty($params['cust_type'])){
            $queryParams[':cust_type']=$params['cust_type'];
            $sql.=" and c.cust_type = :cust_type";
        }
        if(!empty($params['sil_type'])){
            $queryParams[':sil_type']=$params['sil_type'];
            $sql.=" and a.sil_type = :sil_type";
        }
        $sql.=" order by a.sil_id desc";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ( {$sql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>isset($params['rows'])?$params['rows']:false
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //明细表导出
    public function actionListExport()
    {
        $params=Yii::$app->request->queryParams;
        $model=new CrmVisitRecordChildSearch();
        $dataProvider=$model->searchList($params);
        return $dataProvider->getModels();
    }

    public function actionGetRecordOne($id){
        return CrmVisitRecordChildShow::find()->where(['sil_id'=>$id])->one();
    }

    //获取陪同人
    private function getAccompany($id)
    {
        return Yii::$app->db->createCommand("select b.staff_code,b.staff_name,c.title_name,a.acc_mobile from erp.crm_accompany a left join erp.hr_staff b on b.staff_id = a.acc_id left join erp.hr_staff_title c on c.title_id = b.position where a.type = 2 and a.pid = {$id}")->queryAll();
    }

    //获取客户经理人
    public function actionGetCustomerManager($id)
    {
        return Yii::$app->db->createCommand("select b.staff_code,b.staff_name from erp.crm_bs_customer_personinch a left join erp.hr_staff b on b.staff_id = a.ccpich_personid where a.cust_id = :id and a.ccpich_status = 10 and a.ccpich_stype = 1",[':id'=>$id])->queryAll();
    }

    //获取计划标识
    public function actionGetPlanFlag($id,$code)
    {
        $result=Yii::$app->db->createCommand("select * from erp.crm_visit_plan a where a.cust_id = :id and a.svp_staff_code = :staff_code and (a.svp_status = 40 or a.svp_status = 10)",[':id'=>$id,':staff_code'=>$code])->queryAll();
        if(!empty($result)){
            return 1;
        }
        return 0;
    }
}