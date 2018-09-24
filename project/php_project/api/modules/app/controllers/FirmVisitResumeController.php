<?php
namespace app\modules\app\controllers;

use yii;
use app\controllers\AppBaseController;
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
use app\modules\app\models\search\FirmVisitResumeChildSearch;
use app\modules\app\models\show\AccompanyShow;
use app\modules\app\models\show\FirmAppShow;
use app\modules\app\models\search\FirmVisitResumeSearch;

/**
 * 厂商拜访拜访履历API 
 *
 * @author F1676269 By 2017-06-21
 * V1.0
 */
class FirmVisitResumeController extends AppBaseController 
{
	public $modelClass = 'app\modules\ptdt\models\PdVisitResume';
	
	/**
	 * 获取拜访履历列表
	 */
	public function actionList($companyId)
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
        $model=new FirmVisitResumeSearch();
        $dataProvider=$model->searchResumeMain($params);
		//dumpE($dataProvider);die();
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

	/**
     * 厂商拜访履历计划详情
     */
    public function actionView($id){
    	$resumeMain=PdVisitResume::findOne($id);
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
        $model=new FirmVisitResumeChildSearch();
        $resumeChild=$model->searchAllResume($id);
        return [
            'resumeMain'=>$resumeMain,
            'firmInfo'=>FirmAppShow::findOne($resumeMain['firm_id']),
            'resumeChild'=>$resumeChild,
        ];
	}
	
	
    /**
	 * 新增拜访履历
	 */
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
				//dumpE($resumeChild->load($data)&&$resumeChild->save());
                if(!($resumeChild->load($data)&&$resumeChild->save())){
                   throw new \Exception("拜访履历子表保存失败！");
					//throw new \Exception($resumeChild->errors);
					
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
            'firmInfo'=>FirmAppShow::findOne($firmId),
            'visitPlan'=>PdVisitPlan::find()->where(['pvp_planID'=>$planId])->select('pvp_planID,pvp_plancode')->one(),
            'visitPerson'=>HrStaff::getStaffById($visitPersonId),
        ];
    }

	    //修改拜访履历
    public function actionUpdate($visitPersonId,$childId)
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
            'firmInfo'=>FirmAppShow::findOne($resumeMain->firm_id),
            'resumeChild'=>$resumeChild,
            'visitPlan'=>PdVisitPlan::find()->where(['pvp_planID'=>$resumeChild->visit_planID])->select('pvp_planID,pvp_plancode')->one(),
            'receptionPerson'=>PdReception::findAll(['rece_type'=>PdReception::RECE_TYPE_VISIT,'l_id'=>$resumeChild->vil_id]),
            'visitPerson'=>HrStaff::getStaffById($visitPersonId),
            'accompanyPerson'=>AccompanyShow::findAll(['vacc_type'=>PdAccompany::RESUME_ACCOMPANY_PERSON,'l_id'=>$resumeChild->vil_id]),
        ];
    }





}