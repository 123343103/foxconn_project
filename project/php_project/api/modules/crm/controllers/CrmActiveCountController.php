<?php
/**
 * User: F1677929
 * Date: 2017/6/1
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveCount;
use app\modules\crm\models\CrmActiveCountChild;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\CrmMediaType;
use app\modules\crm\models\search\CrmActiveCountChildSearch;
use app\modules\crm\models\search\CrmActiveCountSearch;
use Yii;
use yii\db\Exception;

/**
 * 活动统计控制器
 */
class CrmActiveCountController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmActiveCount';

    /**
     * 活动统计列表
     */
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            return [
                'activeMonth'=>BsPubdata::getData(BsPubdata::CRM_ACTIVE_MONTH),
                'activeType'=>CrmActiveType::getActiveType(),
                'activeWay'=>BsPubdata::getData(BsPubdata::CRM_ACTIVE_WAY),
                'activeStatus'=>CrmActiveName::getStatus()
            ];
        }
        $model=new CrmActiveCountSearch();
        $dataProvider=$model->searchCountMain($params);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    /**
     * 加载活动统计信息
     */
    public function actionLoadCount()
    {
        $model=new CrmActiveCountChildSearch();
        $dataProvider=$model->searchCountChild(Yii::$app->request->queryParams);
        $rows=$dataProvider->getModels();
        $footer=[];
        if(!empty($rows)){
            $arr=[];
            foreach($rows as $key=>$row){
                foreach($row as $k=>$v){
                    $arr[$k][$key]=$v;
                }
            }
//            $arr1=[
//                'actc_datetime'=>'平均数',
//                'actc_extent'=>'/',
//                'actc_peopleqty'=>'/',
//                'actc_dwqty'=>'/',
//                'actc_sjkqty'=>'/',
//                'actc_boxqty'=>'/',
//                'actc_ldqty'=>'/',
//                'actc_travelqty'=>'/',
//                'actc_countqty'=>'/',
//                'actc_memqty'=>'/',
//                'actc_cpa'=>(round((array_sum($arr['actc_cpa']))/count($arr['actc_cpa']),2)),
//                'actc_artqty'=>'/',
//                'actc_watqyt'=>'/',
//                'actc_bUV'=>'/',
//                'actc_UV'=>'/',
//                'actc_UVadd'=>(round((array_sum($arr['actc_UVadd']))/count($arr['actc_UVadd']),2))
//            ];
//            $footer[]=$arr1;
            $arr2=[
                'actc_datetime'=>'总数',
                'actc_extent'=>array_sum($arr['actc_extent']),
                'actc_peopleqty'=>array_sum($arr['actc_peopleqty']),
                'actc_dwqty'=>array_sum($arr['actc_dwqty']),
                'actc_sjkqty'=>array_sum($arr['actc_sjkqty']),
                'actc_boxqty'=>array_sum($arr['actc_boxqty']),
                'actc_ldqty'=>array_sum($arr['actc_ldqty']),
                'actc_travelqty'=>array_sum($arr['actc_travelqty']),
                'actc_countqty'=>array_sum($arr['actc_countqty']),
                'actc_memqty'=>array_sum($arr['actc_memqty']),
                'actc_cpa'=>'/',
                'actc_artqty'=>array_sum($arr['actc_artqty']),
                'actc_watqyt'=>array_sum($arr['actc_watqyt']),
                'actc_bUV'=>array_sum($arr['actc_bUV']),
                'actc_UV'=>array_sum($arr['actc_UV']),
                'actc_UVadd'=>'/'
            ];
            $footer[]=$arr2;
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount,
            'footer'=>$footer
        ];
    }

    /**
     * 新增活动统计
     */
    public function actionAdd($nameId='')
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $trans=Yii::$app->db->beginTransaction();
            try{
                //活动统计主表
                $countMainModel=CrmActiveCount::findOne(['actbs_id'=>$data['CrmActiveCount']['actbs_id'],'actch_status'=>CrmActiveCount::DEFAULT_STATUS]);
                if(empty($countMainModel)){
                    $countMainModel=new CrmActiveCount();
                    if(!$countMainModel->load($data)||!$countMainModel->save()){
                        throw new Exception(json_encode($countMainModel->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
                //活动统计子表
                $countChildModel=new CrmActiveCountChild();
                $countChildModel->actch_id=$countMainModel->actch_id;
                if(!$countChildModel->load($data)||!$countChildModel->save()){
                    throw new Exception(json_encode($countChildModel->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                $trans->commit();
                return $this->success($countChildModel->actch_code,$countChildModel->actc_iid);
            }catch(\Exception $e){
                $trans->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->getAddEditData($nameId);
    }

    /**
     * 获取新增修改的数据
     */
    private function getAddEditData($nameId)
    {
        if(!empty($nameId)){
            $queryParams=[
                ':nameId'=>$nameId,
                ':add_status'=>CrmActiveName::ADD_STATUS,
                ':already_start'=>CrmActiveName::ALREADY_START,
                ':already_end'=>CrmActiveName::ALREADY_END,
                ':already_cancel'=>CrmActiveName::ALREADY_CANCEL
            ];
            $querySql="select a.*,
                              m.bsp_svalue acttype_name,
                              c.bsp_svalue activeWay,
                              d.bsp_svalue industryType,
                              concat(h.district_name,g.district_name,f.district_name,e.district_name,a.actbs_address) activeAddress,
                              i.bsp_svalue joinPurpose,
                              j.staff_name maintainPerson,
                              k.bsp_svalue activeMonth,
                              (case a.actbs_status when :add_status then '未开始' when :already_start then '进行中' when :already_end then '已结束' when :already_cancel then '已取消' else '删除' end) activeStatus,
                              l.staff_name activeDutyPerson
                       from erp.crm_bs_act a
                       left join erp.crm_bs_acttype b on b.acttype_id = a.acttype_id
                       left join erp.bs_pubdata c on c.bsp_id = b.acttype_way
                       left join erp.bs_pubdata d on d.bsp_id = a.actbs_industry
                       left join erp.bs_district e on e.district_id = a.actbs_address_id
                       left join erp.bs_district f on f.district_id = e.district_pid
                       left join erp.bs_district g on g.district_id = f.district_pid
                       left join erp.bs_district h on h.district_id = g.district_pid
                       left join erp.bs_pubdata i on i.bsp_id = a.actbs_purpose
                       left join erp.hr_staff j on j.staff_id = a.actbs_maintain
                       left join erp.bs_pubdata k on k.bsp_id = a.actbs_month
                       left join erp.hr_staff l on l.staff_id = a.actbs_duty
                       left join erp.bs_pubdata m on m.bsp_id = b.acttype_name
                       where a.actbs_id = :nameId";
            $activeData=Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
        }
        return [
            'mediaType'=>CrmMediaType::getMediaType(),
            'activeData'=>empty($activeData)?'':$activeData
        ];
    }

    /**
     * 修改活动统计
     */
    public function actionEdit($childId)
    {
        $countChildModel=CrmActiveCountChild::find()->where(['actc_iid'=>$childId])->andWhere(['actch_status'=>CrmActiveCountChild::DEFAULT_STATUS])->one();
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            if($countChildModel->load($data)&&$countChildModel->save()){
                return $this->success($countChildModel->actch_code,$countChildModel->actc_iid);
            }
            return $this->error($countChildModel->getErrors());
        }
        $countMainModel=CrmActiveCount::findOne($countChildModel->actch_id);
        return [
            'addEditData'=>$this->getAddEditData($countMainModel->actbs_id),
            'countMain'=>$countMainModel,
            'editData'=>$countChildModel
        ];
    }

    /**
     * 选择活动
     */
    public function actionSelectActive()
    {
        $model=new CrmActiveCountSearch();
        $dataProvider=$model->searchActive(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    /**
     * 查看活动统计
     */
//    public function actionView($mainId)
//    {
//        $mainModel=CrmActiveCount::findOne(['actch_id'=>$mainId,'actch_status'=>CrmActiveCount::DEFAULT_STATUS]);
//        if(!empty($mainModel)){
//            $queryParams=[
//                ':nameId'=>$mainModel->actbs_id,
//                ':add_status'=>CrmActiveName::ADD_STATUS,
//                ':already_start'=>CrmActiveName::ALREADY_START,
//                ':already_end'=>CrmActiveName::ALREADY_END,
//                ':already_cancel'=>CrmActiveName::ALREADY_CANCEL
//            ];
//            $querySql="select a.*,
//                              m.bsp_svalue acttype_name,
//                              c.bsp_svalue activeWay,
//                              d.bsp_svalue industryType,
//                              concat(h.district_name,g.district_name,f.district_name,e.district_name,a.actbs_address) activeAddress,
//                              i.bsp_svalue joinPurpose,
//                              j.staff_name maintainPerson,
//                              k.bsp_svalue activeMonth,
//                              (case a.actbs_status when :add_status then '未开始' when :already_start then '进行中' when :already_end then '已结束' when :already_cancel then '已取消' else '删除' end) activeStatus,
//                              l.staff_name activeDutyPerson
//                       from erp.crm_bs_act a
//                       left join erp.crm_bs_acttype b on b.acttype_id = a.acttype_id
//                       left join erp.bs_pubdata c on c.bsp_id = b.acttype_way
//                       left join erp.bs_pubdata d on d.bsp_id = a.actbs_industry
//                       left join erp.bs_district e on e.district_id = a.actbs_address_id
//                       left join erp.bs_district f on f.district_id = e.district_pid
//                       left join erp.bs_district g on g.district_id = f.district_pid
//                       left join erp.bs_district h on h.district_id = g.district_pid
//                       left join erp.bs_pubdata i on i.bsp_id = a.actbs_purpose
//                       left join erp.hr_staff j on j.staff_id = a.actbs_maintain
//                       left join erp.bs_pubdata k on k.bsp_id = a.actbs_month
//                       left join erp.hr_staff l on l.staff_id = a.actbs_duty
//                       left join erp.bs_pubdata m on m.bsp_id = b.acttype_name
//                       where a.actbs_id = :nameId";
//            $activeData=Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
//            return [
//                'countMain'=>$mainModel,
//                'activeData'=>$activeData
//            ];
//        }
//        return false;
//    }

    /**
     * 通过活动统计主表获取查看时的活动信息
     */
    private function getViewActiveInfo($id)
    {
        $queryParams=[
            ':id'=>$id,
            ':default_status'=>CrmActiveCount::DEFAULT_STATUS,
            ':add_status'=>CrmActiveName::ADD_STATUS,
            ':already_start'=>CrmActiveName::ALREADY_START,
            ':already_end'=>CrmActiveName::ALREADY_END,
            ':already_cancel'=>CrmActiveName::ALREADY_CANCEL
        ];
        $querySql="select a.actch_id,
                          a.actch_code,
                          b.*,
                          d.bsp_svalue activeType,
                          e.bsp_svalue activeWay,
                          f.bsp_svalue industryType,
                          concat(j.district_name, i.district_name, h.district_name, g.district_name, b.actbs_address) activeAddress,
                          k.bsp_svalue joinPurpose,
                          l.staff_name maintainPerson,
                          m.bsp_svalue activeMonth,
                          (case b.actbs_status when :add_status then '未开始' when :already_start then '进行中' when :already_end then '已结束' when :already_cancel then '已取消' else '删除' end) activeStatus,
                          n.staff_name activeDutyPerson
                   from erp.crm_act_count a 
                   left join erp.crm_bs_act b on b.actbs_id = a.actbs_id
                   left join erp.crm_bs_acttype c on c.acttype_id = b.acttype_id
                   left join erp.bs_pubdata d on d.bsp_id = c.acttype_name
                   left join erp.bs_pubdata e on e.bsp_id = c.acttype_way
                   left join erp.bs_pubdata f on f.bsp_id = b.actbs_industry
                   left join erp.bs_district g on g.district_id = b.actbs_address_id
                   left join erp.bs_district h on h.district_id = g.district_pid
                   left join erp.bs_district i on i.district_id = h.district_pid
                   left join erp.bs_district j on j.district_id = i.district_pid
                   left join erp.bs_pubdata k on k.bsp_id = b.actbs_purpose
                   left join erp.hr_staff l on l.staff_id = b.actbs_maintain
                   left join erp.bs_pubdata m on m.bsp_id = b.actbs_month
                   left join erp.hr_staff n on n.staff_id = b.actbs_duty
                   where a.actch_id = :id
                   and a.actch_status = :default_status";
        return Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
    }

    /**
     * 查看一条活动统计
     */
    public function actionViewCount($childId)
    {
        $queryParams=[
            ':id'=>$childId,
            ':default_status'=>CrmActiveCountChild::DEFAULT_STATUS
        ];
        $querySql="select a.*,
                          b.cmt_type
                   from erp.crm_act_count_child a
                   left join erp.crm_bs_media_type b on b.cmt_id = a.cc_id
                   where a.actc_iid = :id
                   and a.actch_status = :default_status";
        $countInfo=Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
        if(!empty($countInfo['actch_id'])){
            $activeInfo=$this->getViewActiveInfo($countInfo['actch_id']);
        }
        return [
            'activeInfo'=>empty($activeInfo)?'':$activeInfo,
            'countInfo'=>$countInfo
        ];
    }

    /**
     * 查看所有活动统计
     */
    public function actionViewCounts($mainId)
    {
        $activeInfo=$this->getViewActiveInfo($mainId);
        if(!empty($activeInfo['actch_id'])){
            $queryParams=[
                ':id'=>$activeInfo['actch_id'],
                ':default_status'=>CrmActiveCountChild::DEFAULT_STATUS
            ];
            $querySql="select a.*,
                              b.cmt_type
                       from erp.crm_act_count_child a
                       left join erp.crm_bs_media_type b on b.cmt_id = a.cc_id
                       where a.actch_id = :id
                       and a.actch_status = :default_status";
            $countInfo=Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
        }
        return [
            'activeInfo'=>$activeInfo,
            'countInfo'=>empty($countInfo)?'':$countInfo
        ];
    }

    /**
     * 删除活动统计
     */
    public function actionDeleteChild($childId,$isSupper='')
    {
        $trans=Yii::$app->db->beginTransaction();
        try{
            $childModel=CrmActiveCountChild::findOne($childId);
            //删除主表
            $last=0;
//            if($isSupper==1){
//                $total=CrmActiveCountChild::find()->where(['actch_id'=>$childModel->actch_id])->andWhere(['actch_status'=>CrmActiveCountChild::DEFAULT_STATUS])->count();
//                if($total==1){
//                    $mainModel=CrmActiveCount::findOne($childModel->actch_id);
//                    $mainModel->actch_status=CrmActiveCount::DELETE_STATUS;
//                    if(!$mainModel->save()){
//                        throw new Exception(json_encode($mainModel->getErrors(),JSON_UNESCAPED_UNICODE));
//                    }
//                    $last=1;
//                }
//            }
            //删除子表
            $childModel->actch_status=CrmActiveCountChild::DELETE_STATUS;
            if(!$childModel->save()){
                throw new Exception(json_encode($childModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            $trans->commit();
            return $this->success($childModel->actch_code,$last);
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }

    /**
     * 删除所有活动统计
     */
    public function actionDeleteMain($mainId)
    {
        $mainModel=CrmActiveCount::findOne($mainId);
        $trans=Yii::$app->db->beginTransaction();
        try{
            //删除主表
            $mainModel->actch_status=CrmActiveCount::DELETE_STATUS;
            if(!$mainModel->save()){
                throw new Exception(json_encode($mainModel->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //删除子表
            $childModel=CrmActiveCountChild::findAll(['actch_id'=>$mainId,'actch_status'=>CrmActiveCountChild::DEFAULT_STATUS]);
            if(!empty($childModel)){
                foreach($childModel as $val){
                    $val->actch_status=CrmActiveCountChild::DELETE_STATUS;
                    if(!$val->save()){
                        throw new Exception(json_encode($val->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $trans->commit();
            return $this->success($mainModel->actch_code);
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }
}