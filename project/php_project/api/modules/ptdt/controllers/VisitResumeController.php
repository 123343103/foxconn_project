<?php
/**
 * User: F1677929
 * Date: 2016/12/7
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\PdAccompany;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\PdNegotiation;
use app\modules\ptdt\models\PdReception;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\PdVisitResume;
use app\modules\ptdt\models\PdVisitResumeChild;
use app\modules\ptdt\models\search\PdVisitResumeChildSearch;
use app\modules\ptdt\models\search\PdVisitResumeSearch;
use app\modules\ptdt\models\show\PdAccompanyShow;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\ptdt\models\show\PdVisitResumeChildShow;
use app\modules\system\models\SysDisplayList;
use Yii;
use yii\bootstrap\Html;
//厂商拜访履历API控制器
class VisitResumeController extends BaseActiveController
{
    public $modelClass='app\modules\ptdt\models\PdVisitResume';
    //拜访履历列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            return [
                //厂商类型
                'firmType'=>BsPubdata::getData(BsPubdata::FIRM_TYPE),
                //一阶分类
                'oneCategory'=>BsCategory::getChildCategory('0'),
                //拜访状态
                'visitStatus'=>PdVisitResume::visitStatus()
            ];
        }
        $model=new PdVisitResumeSearch();
        $dataProvider=$model->searchResumeMain($params);
        $rows=$dataProvider->getModels();
        if(!empty($rows)){
            foreach($rows as &$row){
                $row['firm_category_id']=unserialize($row['firm_category_id']);
                if(!empty($row['firm_category_id'])){
                    $models=BsCategory::find()->select('category_sname')->where(['in','category_id',$row['firm_category_id']])->all();//注意此处的查询语句
                    $categoryName='';
                    foreach($models as $model){
                        $categoryName.=$model['category_sname'].',';
                    }
                    $row['firm_category_id']=rtrim($categoryName,',');
                }
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount
        ];
    }

    //加载履历子表
    public function actionLoadResume()
    {
        $model=new PdVisitResumeChildSearch();
        $dataProvider=$model->searchResumeChild(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //新增拜访履历
    public function actionAdd($visitPersonId,$firmId,$planId)
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //拜访履历主表
                $resumeMain=PdVisitResume::find()->where(['and',['firm_id'=>$data['PdVisitResume']['firm_id']],['!=','vih_status',PdVisitResume::VISIT_DELETE]])->one();
                if(empty($resumeMain)){
                    $resumeMain=new PdVisitResume();
                }else{
                    $mainCode=$resumeMain->vih_code;
                }
                $resumeMain->vih_status=PdVisitResume::VISIT_ING;
                if(!($resumeMain->load($data)&&$resumeMain->save())){
                    throw new \Exception("拜访履历主表保存失败！");
                }
                //拜访履历子表
                $resumeChild=new PdVisitResumeChild();
                $resumeChild->vih_id=$resumeMain->vih_id;
                if(!($resumeChild->load($data)&&$resumeChild->save())){
                    throw new \Exception("拜访履历子表保存失败！");
                }
                //拜访计划表
                if(!empty($data['PdVisitResumeChild']['visit_planID'])){
                    $planModel=PdVisitPlan::findOne($data['PdVisitResumeChild']['visit_planID']);
                    $planModel->plan_status=PdVisitPlan::PLAN_STATUS_ACTION;
                    if(!$planModel->save()){
                        throw new \Exception("拜访计划表保存失败！");
                    }
                }
                $negotiate = PdNegotiation::find()->where(['firm_id'=>$data['PdVisitResume']['firm_id']])->one();
                $report = PdFirmReport::find()->where(['firm_id'=>$data['PdVisitResume']['firm_id']])->one();
                if(empty($negotiate)&&empty($report)){
                    $firm = PdFirm::findOne($data['PdVisitResume']['firm_id']);
                    $firm->firm_status = PdFirm::PLAN_STATUS_ACTION;
                    $firm->save();
                }
                //接待人员表
                foreach($data['receptionArr'] as $val){
                    if(!empty($val['PdReception']['rece_sname'])){
                        $receptionPersonModel=new PdReception();
                        $receptionPersonModel->l_id=$resumeChild->vil_id;
                        $receptionPersonModel->rece_type=PdReception::RECE_TYPE_VISIT;
                        if(!($receptionPersonModel->load($val)&&$receptionPersonModel->save())){
                            throw  new \Exception("接待人员保存失败！");
                        }
                    }
                }
                //陪同人员表
                foreach($data['accompanyArr'] as $val){
                    if(!empty($val['PdAccompany']['staff_code'])){
                        $accompanyPersonModel=new PdAccompany();
                        $accompanyPersonModel->l_id=$resumeChild->vil_id;
                        $accompanyPersonModel->vacc_type=PdAccompany::RESUME_ACCOMPANY_PERSON;
                        if(!($accompanyPersonModel->load($val)&&$accompanyPersonModel->save())){
                            throw  new \Exception("陪同人员保存失败！");
                        }
                    }
                }
                //编号
                $codeArr=[
                    'mainCode'=>empty($mainCode)?$resumeMain->vih_code:'',
                    'childCode'=>$resumeChild->vil_code,
                ];
                $transaction->commit();
                return $this->success($codeArr,$resumeChild->vil_id);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return [
            'firmInfo'=>PdFirmShow::findOne($firmId),
            'visitPlan'=>PdVisitPlan::find()->where(['pvp_planID'=>$planId])->select('pvp_planID,pvp_plancode')->one(),
            'visitPerson'=>HrStaff::getStaffById($visitPersonId),
        ];
    }

    //修改拜访履历
    public function actionEdit($visitPersonId,$childId)
    {
        $resumeChild=PdVisitResumeChild::findOne($childId);
        $resumeMain=PdVisitResume::findOne($resumeChild->vih_id);
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $transaction=Yii::$app->db->beginTransaction();
            try{
                //拜访履历主表
                $resumeMain->vih_status=PdVisitResume::VISIT_ING;
                if(!($resumeMain->load($data)&&$resumeMain->save())){
                    throw new \Exception("拜访履历主表保存失败！");
                }
                //拜访履历子表
                if(!($resumeChild->load($data)&&$resumeChild->save())){
                    throw new \Exception("拜访履历子表保存失败！");
                }
                //拜访计划表
                if(!empty($data['PdVisitResumeChild']['visit_planID'])){
                    $planModel=PdVisitPlan::findOne($data['PdVisitResumeChild']['visit_planID']);
                    $planModel->plan_status=PdVisitPlan::PLAN_STATUS_ACTION;
                    if(!$planModel->save()){
                        throw new \Exception("拜访计划表保存失败！");
                    }
                }
                //接待人员表
                $receptionPersonTotal=PdReception::find()->where(['rece_type'=>PdReception::RECE_TYPE_VISIT,'l_id'=>$resumeChild->vil_id])->count();
                if(PdReception::deleteAll(['rece_type'=>PdReception::RECE_TYPE_VISIT,'l_id'=>$resumeChild->vil_id])!=$receptionPersonTotal){
                    throw  new \Exception("删除接待人员失败！");
                }
                foreach($data['receptionArr'] as $val){
                    if(!empty($val['PdReception']['rece_sname'])){
                        $receptionPersonModel=new PdReception();
                        $receptionPersonModel->l_id=$resumeChild->vil_id;
                        $receptionPersonModel->rece_type=PdReception::RECE_TYPE_VISIT;
                        if(!($receptionPersonModel->load($val)&&$receptionPersonModel->save())){
                            throw  new \Exception("接待人员保存失败！");
                        }
                    }
                }
                //陪同人员表
                $accompanyPersonTotal=PdAccompany::find()->where(['vacc_type'=>PdAccompany::RESUME_ACCOMPANY_PERSON,'l_id'=>$resumeChild->vil_id])->count();
                if(PdAccompany::deleteAll(['vacc_type'=>PdAccompany::RESUME_ACCOMPANY_PERSON,'l_id'=>$resumeChild->vil_id])!=$accompanyPersonTotal){
                    throw  new \Exception("删除陪同人员失败！");
                }
                foreach($data['accompanyArr'] as $val){
                    if(!empty($val['PdAccompany']['staff_code'])){
                        $accompanyPersonModel=new PdAccompany();
                        $accompanyPersonModel->l_id=$resumeChild->vil_id;
                        $accompanyPersonModel->vacc_type=PdAccompany::RESUME_ACCOMPANY_PERSON;
                        if(!($accompanyPersonModel->load($val)&&$accompanyPersonModel->save())){
                            throw  new \Exception("陪同人员保存失败！");
                        }
                    }
                }
                $transaction->commit();
                return $this->success($resumeChild->vil_code);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return [
            'firmInfo'=>PdFirmShow::findOne($resumeMain->firm_id),
            'resumeChild'=>$resumeChild,
            'visitPlan'=>PdVisitPlan::find()->where(['pvp_planID'=>$resumeChild->visit_planID])->select('pvp_planID,pvp_plancode')->one(),
            'receptionPerson'=>PdReception::findAll(['rece_type'=>PdReception::RECE_TYPE_VISIT,'l_id'=>$resumeChild->vil_id]),
            'visitPerson'=>HrStaff::getStaffById($visitPersonId),
            'accompanyPerson'=>PdAccompanyShow::findAll(['vacc_type'=>PdAccompany::RESUME_ACCOMPANY_PERSON,'l_id'=>$resumeChild->vil_id]),
        ];
    }

    //获取厂商
    public function actionSelectFirm()
    {
        $model=new PdVisitResumeSearch();
        $dataProvider=$model->searchFirm(Yii::$app->request->queryParams);
        $rows=$dataProvider->getModels();
        if(!empty($rows)){
            foreach($rows as &$row){
                $row['firm_issupplier']=$row['firm_issupplier']=='1'?'是':'否';
                if(!empty($row['firm_category_id'])){
                    $row['firm_category_id']=Html::decode($row['firm_category_id']);//Command.php修改导致
                    $oneCategory=unserialize($row['firm_category_id']);
                    $categoryName='';
                    foreach($oneCategory as $val){
                        $categoryModel=BsCategory::find()->select('category_sname')->where(['category_id'=>$val])->one();
                        $categoryName.=$categoryModel->category_sname.',';
                    }
                    $row['firm_category_id']=rtrim($categoryName,',');
                }
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount
        ];
    }

    //获取计划
    public function actionSelectPlan()
    {
        $model=new PdVisitResumeSearch();
        $dataProvider=$model->searchPlan(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //查看一条拜访履历
    public function actionViewResume($childId)
    {
        $resumeChild=PdVisitResumeChild::findOne($childId);
        $resumeNew=PdVisitResumeChild::find()->where(['vil_status'=>PdVisitResumeChild::STATUS_DEFAULT,'vih_id'=>$resumeChild->vih_id])->select(['vil_id','IFNULL(update_at,create_at) AS sort_at'])->orderBy(['sort_at'=>SORT_DESC])->one();
        $resumeMain=PdVisitResume::findOne($resumeChild->vih_id);
        return [
            'flag'=>$resumeChild->vil_id==$resumeNew->vil_id,
            'firmInfo'=>PdFirmShow::findOne($resumeMain->firm_id),
            'resumeChild'=>$resumeChild,
            'visitPlan'=>PdVisitPlan::find()->where(['pvp_planID'=>$resumeChild->visit_planID])->select('pvp_planID,pvp_plancode')->one(),
            'receptionPerson'=>PdReception::findAll(['rece_type'=>PdReception::RECE_TYPE_VISIT,'l_id'=>$resumeChild->vil_id]),
            'visitPerson'=>HrStaff::getStaffById($resumeChild->vih_vis_person),
            'accompanyPerson'=>PdAccompanyShow::findAll(['vacc_type'=>PdAccompany::RESUME_ACCOMPANY_PERSON,'l_id'=>$resumeChild->vil_id]),
        ];
    }

    //查看所有拜访履历
    public function actionViewResumes($mainId)
    {
        $resumeMain=PdVisitResume::findOne($mainId);
        switch($resumeMain['vih_status']){
            case PdVisitResume::VISIT_ING:
                $resumeMain['vih_status']='拜访中';
                break;
            case PdVisitResume::VISIT_COMPLETE:
                $resumeMain['vih_status']='拜访完成';
                break;
            default:
                $resumeMain['vih_status']='';
        }
        $model=new PdVisitResumeChildSearch();
        $resumeChild=$model->searchAllResume($mainId);
        return [
            'resumeMain'=>$resumeMain,
            'firmInfo'=>PdFirmShow::findOne($resumeMain['firm_id']),
            'resumeChild'=>$resumeChild,
        ];
    }

    //刪除履历
    public function actionDeleteChild($childId)
    {
        $resumeChild=PdVisitResumeChild::findOne($childId);
        $resumeNew=PdVisitResumeChild::find()->where(['vil_status'=>PdVisitResumeChild::STATUS_DEFAULT,'vih_id'=>$resumeChild->vih_id])->select(['vil_id','IFNULL(update_at,create_at) AS sort_at'])->orderBy(['sort_at'=>SORT_DESC])->one();
        if($childId!=$resumeNew->vil_id){
            return $this->error('该履历不是最新的，不可删除！');
        }
        $transaction=Yii::$app->db->beginTransaction();
        try{
            $resumeTotal=PdVisitResumeChild::find()->where(['vil_status'=>PdVisitResumeChild::STATUS_DEFAULT,'vih_id'=>$resumeChild->vih_id])->count();
            if($resumeTotal==1){
                //拜访履历主表
                $resumeMaim=PdVisitResume::findOne($resumeChild->vih_id);
                $resumeMaim->vih_status=PdVisitResume::VISIT_DELETE;
                $mainCode=$resumeMaim->vih_code;
                if(!$resumeMaim->save()){
                    throw  new \Exception("拜访履历主表删除失败！");
                }
            }
            //拜访履历子表
            $resumeChild->vil_status=PdVisitResumeChild::STATUS_DELETE;
            if(!$resumeChild->save()){
                throw  new \Exception("拜访履历子表删除失败！");
            }
            //编号
            $codeArr=[
                'mainCode'=>empty($mainCode)?'':$mainCode,
                'childCode'=>$resumeChild->vil_code,
            ];
            $transaction->commit();
            return $this->success($codeArr,$resumeTotal);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //拜访完成
    public function actionVisitComplete($mainId)
    {
        $resumeMain=PdVisitResume::findOne($mainId);
        $transaction=Yii::$app->db->beginTransaction();
        try{
            //拜访履历主表
            $resumeMain->vih_status=PdVisitResume::VISIT_COMPLETE;
            if(!$resumeMain->save()){
                throw  new \Exception("拜访履历主表保存失败！");
            }
            //拜访计划表
            $resumeChild=PdVisitResumeChild::find()->where(['vil_status'=>PdVisitResumeChild::STATUS_DEFAULT,'vih_id'=>$resumeMain->vih_id])->all();
            $planId=[];
            foreach($resumeChild as $val){
                if(!empty($val->visit_planID)){
                    $planId[]=$val->visit_planID;
                }
            }
            if(!empty($planId)){
                $planModel=PdVisitPlan::findAll(['pvp_planID'=>$planId]);
                foreach($planModel as $val){
                    $val->plan_status=PdVisitPlan::PLAN_STATUS_OVER;
                    if(!$val->save()){
                        throw  new \Exception("拜访计划表保存失败！");
                    }
                }
            }
            $negotiate = PdNegotiation::find()->where(['firm_id'=>$resumeMain['firm_id']])->one();
            $report = PdFirmReport::find()->where(['firm_id'=>$resumeMain['firm_id']])->one();
            if(empty($negotiate)&&empty($report)){
                $firm = PdFirm::findOne($resumeMain['firm_id']);
                $firm->firm_status = PdFirm::PLAN_STATUS_OVER;
                $firm->save();
            }
            $transaction->commit();
            return $this->success($resumeMain->vih_code);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
}