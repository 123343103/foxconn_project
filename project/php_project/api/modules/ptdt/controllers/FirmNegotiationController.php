<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2016/12/1
 * Time: 17:13
 */
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDevcon;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsSettlement;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\search\PdRequirementSearch;
use app\modules\ptdt\models\show\PdAccompanyShow;
use app\modules\ptdt\models\show\PdAgentsAuthorizeShow;
use app\modules\ptdt\models\show\PdFirmNegotiationShow;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\ptdt\models\show\PdNegotiationAnalysisShow;
use app\modules\ptdt\models\show\PdNegotiationChildShow;
use app\modules\ptdt\models\show\PdRequirementProductShow;
use app\modules\ptdt\models\show\PdVisitPlanShow;
use Yii;
use app\modules\ptdt\models\PdAccompany;
use app\modules\ptdt\models\PdReception;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\PdAgentsAuthorize;
use app\modules\ptdt\models\PdNegotiationProduct;
use app\modules\ptdt\models\search\PdNegotiationSearch;
use app\modules\ptdt\models\PdNegotiation;
use app\modules\ptdt\models\PdNegotiationChild;
use app\modules\ptdt\models\PdNegotiationAnalysis;
use app\modules\ptdt\models\Search\FirmQuery;
use yii\web\NotFoundHttpException;
use app\modules\ptdt\models\PdProductType;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * 厂商谈判履历表.
 */
class FirmNegotiationController extends BaseActiveController
{
    /**
     * 主页
     * @return mixed
     */
    public $modelClass = 'app\modules\ptdt\models\FirmNegotiation';

    public function actionIndex()
    {
        $searchModel = new PdNegotiationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
//        $list['downList']=$this->searchDownList();
        return $list;
    }

    /**
     * 加载履历列表
     * @return string
     */
    public function actionLoadInfo($id)
    {
        $child=PdNegotiationChild::find()->select(['pdnc_id','pdnc_date','pdnc_time','process_descript','negotiate_concluse','pdnc_code','visit_planID','pdnc_status'])->where(['pdn_id'=>$id])->all();
        return $child;
    }

    /**
     * 查看
     * @param integer $pid
     * @return mixed
     */
    public function actionView($pid=null,$cid=null)
    {
        if($pid != null){
            //子表信息
            $data['negotiation'] = $this->findModel($pid);
            $data['firmInfo']   = $this->actionGetFirmInfo($data['negotiation']['firm_id']);
            $data['child']=PdNegotiationChildShow::find()->where(['pdn_id'=>intval($pid)])->orderBy('pdnc_date DESC')->orderBy('pdnc_time DESC')->all();
            foreach ($data['child'] as $key=>$val){
                    $data['reception'][$key]=PdReception::find()->where(['l_id'=>$val['pdnc_id'],'rece_main'=>PdReception::RECE_MAIN_YES])->one();
                }
        }else{
            //子表信息
            $data['child']=PdNegotiationChildShow::find()->where(['pdnc_id'=>intval($cid)])->one();
            //談判分析
            $data['analysis']=PdNegotiationAnalysisShow::find()->where(['pdnc_id'=>intval($cid)])->one();
            //主表信息
            $data['negotiation']=$this->findModel($data['child']->pdn_id);

            $data['formInfo']   = $this->actionGetFirmInfo($data['negotiation']['firm_id']);
            //授权信息
            $data['authorize']=PdAgentsAuthorizeShow::find()->where(['pdnc_id'=>intval($cid)])->one();
            //陪同人员信息
            $data['accompany']=PdAccompanyShow::find()->where(['l_id'=>$cid])->all();
            //接待人员信息
            $data['reception']=PdReception::find()->where(['l_id'=>$cid,'rece_main'=>PdReception::RECE_MAIN_YES])->one();
            //商品信息
            $data['productInfo'] = $data['child']->product;
        }
            return $data;
    }

    /**
     * 创建
     * @return mixed
     */
    public function actionCreate()
    {

        $child       = new PdNegotiationChild();
        $analysis    = new PdNegotiationAnalysis();
        $negotiation = new PdNegotiation();
        $authorize   = new PdAgentsAuthorize();
        $reception   = new PdReception();

        if ($post = Yii::$app->request->post()) {
            //创建事务,发生错误时回滚数据
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //判断厂商谈判信息是否存在,存在则新增子表
                $firmId=intval($post['PdNegotiation']['firm_id']);
                $result=PdNegotiation::find()->where(['firm_id'=>$firmId])->andWhere(['<>','pdn_status',PdNegotiation::STATUS_DELETE])->exists();
                if($result){
                    $negotiation=PdNegotiation::find()->where(['firm_id'=>$firmId])->andWhere(['<>','pdn_status',PdNegotiation::STATUS_DELETE])->one();
                }
                if(!empty($post['PdNegotiationChild']['visit_planID'])){
                    $planModel=PdVisitPlan::findOne($post['PdNegotiationChild']['visit_planID']);
                    $planModel->plan_status=PdVisitPlan::PLAN_STATUS_ACTION;
                    if(!$planModel->save()){
                        throw new \Exception("拜访计划表保存失败！");
                    }
                }
                $report = PdFirmReport::find()->where(['firm_id'=>$firmId])->one();
                if(empty($report)){
                    $firm = PdFirm::findOne($firmId);
                    $firm->firm_status = PdFirm::STATUS_HALF;
                    $firm->save();
                }
                //谈判主表,pdnID判断是新增谈判类型
                $negotiation->load($post);
                $negotiation->pdn_status = PdNegotiation::STATUS_HALF;
                $negotiation->save();

                $pdnId=$negotiation->pdn_id;
                $pdnCode=$negotiation->pdn_code;

                //谈判子表
                $child->load($post);
                //文件上传
                $file = UploadedFile::getInstance($child, 'attachment');
                $path='uploads/pdn-attachment/';
                if ($file) {
                    if(!file_exists($path)){
                        mkdir($path,0777,true);
                    }
                    $pathName=$path .date('Ymd').time() . '.' . $file->getExtension();
                    $file->saveAs($pathName);
                    $child->attachment=$pathName;
                    $child->attachment_name=$file->baseName.'.'.$file->getExtension();
                }
                $child->pdn_id=$pdnId;
                $child->save();
                $childId=$child->pdnc_id;
                $childCode=$child->pdnc_code;
                $childDate=$child->pdnc_date;
                $childTime=$child->pdnc_time;

                //陪同人员表
                $vaccArr=array_filter($post['vacc']);
                if($vaccArr != null){
                    foreach ($vaccArr as $val){
                        $accompany = new PdAccompany();
                        $accompany->staff_code=$val;
                        $accompany->vacc_type=PdAccompany::NEGOTIATION_ACCOMPANY_PERSON;
                        $accompany->h_id=$pdnId;
                        $accompany->l_id=$childId;
                        $accompany->save();
                    }
                }

                //厂商主谈人员表
                $reception->load($post);
                $reception->h_id=$pdnId;
                $reception->l_id=$childId;
                $reception->firm_id=$firmId;
                $reception->rece_main=PdReception::RECE_MAIN_YES;
                $reception->rece_type=PdReception::RECE_TYPE_NEGOTIATION;
                $reception->save();


                //谈判分析表
                $analysis->load($post);
                $analysis->pdn_id   = $pdnId;
                $analysis->pdn_code = $pdnCode;
                $analysis->pdnc_id  = $childId;
                $analysis->pdnc_code= $childCode;
                $analysis->pdnc_date= $childDate;
                $analysis->pdnc_time= $childTime;
                $analysis->firm_id  = $firmId;
                $analysis->save();
                //取得授权代理
                if($post['PdNegotiationChild']['negotiate_concluse']==100019){
                    $authorize->load($post);
                    $authorize->pdn_id   = $pdnId;
                    $authorize->pdn_code = $pdnCode;
                    $authorize->pdnc_id  = $childId;
                    $authorize->pdnc_code= $childCode;
                    $authorize->pdn_date = $childDate;
                    $authorize->pdn_time = $childTime;
                    $authorize->firm_id  = $firmId;
                    $authorize->save();
                }
                //谈判商品
                $productInfo=isset($post['PdNegotiationProduct'])?$post['PdNegotiationProduct']:false;
                if($productInfo){
                    foreach ($productInfo as $value){
                        $product   = new PdNegotiationProduct();
                        $products['PdNegotiationProduct']=$value;
                        $product->load($products);
                        $product->pdn_id = $pdnId;
                        $product->pdnc_id = $childId;
                        $product->firm_id=$firmId;
                        $product->save();
                    }
                }
                $transaction->commit();
                return $this->success($negotiation->pdn_code,$child->pdnc_id);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }



    /**
     * 更新
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($cid)
    {
        $child  = PdNegotiationChild::find()->where(['pdnc_id'=>$cid])->one();
        $id     = $child->pdn_id;
        //授权信息
        $authorize  = PdAgentsAuthorize::find()->where(['pdnc_id'=>$cid,'pdaa_status'=>PdAgentsAuthorize::STATUS_DEFAULT])->one();
        if(empty($authorize)){
            $authorize = new PdAgentsAuthorize();
        }
        //谈判分析
        $analysis = PdNegotiationAnalysis::find()->where(['pdn_id'=>$id])->one();
        //主表信息
        $negotiation = PdNegotiation::find()->where(['pdn_id'=>$id])->one();
        //商品信息
        $productInfo = $child->product;
        //厂商谈判人
        $reception = PdReception::find()->where(['l_id'=>$cid])->one();
        if(empty($reception)){
            $reception = new PdReception();
        }
        //获取陪同人员列表
        $accompany=PdAccompany::find()->where(['l_id'=>$cid])->all();

        if ($post = Yii::$app->request->post()) {
            //创建事务,发生错误时回滚数据
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //谈判主表,更具pdnID判断是新增谈判类型
                $negotiation->load(Yii::$app->request->post());
                $negotiation->save();
                $pdnId=$negotiation->pdn_id;
                $pdnCode=$negotiation->pdn_code;
                $firmId=$negotiation->firm_id;

                //谈判子表
                $child->load($post);
                $child->pdn_id=$pdnId;
                $child->save();
                $childId=$child->pdnc_id;
                $childCode=$child->pdnc_code;
                $childDate=$child->pdnc_date;
                $childTime=$child->pdnc_time;

                //陪同人员表
                $vaccArr=array_filter($post['vacc']);
                PdAccompany::deleteAll('vacc_type = :vacc_type AND h_id = :h_id', [':vacc_type' => PdAccompany::NEGOTIATION_ACCOMPANY_PERSON, ':h_id' => $id]);
                if($vaccArr != null){
                    foreach ($vaccArr as $val){
                        $accompany = new PdAccompany();
                        $accompany->staff_code=$val;
                        $accompany->vacc_type=PdAccompany::NEGOTIATION_ACCOMPANY_PERSON;
                        $accompany->h_id=$pdnId;
                        $accompany->l_id=$childId;
                        $accompany->save();
                    }
                }

                //厂商主谈人员表
                $reception->load($post);
                $reception->h_id=$pdnId;
                $reception->l_id=$childId;
                $reception->firm_id=$firmId;
                $reception->rece_main=PdReception::RECE_MAIN_YES;
                $reception->rece_type=PdReception::RECE_TYPE_NEGOTIATION;
                $reception->save();

                //谈判分析表
                $analysis->load($post);
                $analysis->pdn_id   = $pdnId;
                $analysis->pdn_code = $pdnCode;
                $analysis->pdnc_id  = $childId;
                $analysis->pdnc_code= $childCode;
                $analysis->pdnc_date= $childDate;
                $analysis->pdnc_time= $childTime;
                $analysis->firm_id  = $firmId;
                $analysis->save();

                //取得授权代理
                if($post['PdNegotiationChild']['negotiate_concluse']==100019){
                    ;
                    $authorize->load($post);
                    $authorize->pdn_id   = $pdnId;
                    $authorize->pdn_code = $pdnCode;
                    $authorize->pdnc_id  = $childId;
                    $authorize->pdnc_code= $childCode;
                    $authorize->pdn_date = $childDate;
                    $authorize->pdn_time = $childTime;
                    $authorize->firm_id  = $firmId;
                    $authorize->save();
                }else{
                    $authorize->pdaa_status=PdAgentsAuthorize::STATUS_DEL;
                    $authorize->save();
                }
                //谈判商品->删除
                $productArr=array_filter(explode(",",$post['product_del']));
                if (!empty($productArr)){
                    foreach ($productArr as $val){
                        $productOne=PdNegotiationProduct::find()->where(['pdnp_id'=>$val])->one();
                        $productOne->pdnp_status=PdNegotiationProduct::STATUS_DEL;
                        $productOne->save();
                    }
                }
                //商品信息->新增
                $productInfo=isset($post['PdNegotiationProduct'])?$post['PdNegotiationProduct']:false;
                if($productInfo){
                    foreach ($productInfo as $value){
                        $product   = new PdNegotiationProduct();
                        $products['PdNegotiationProduct']=$value;
                        $product->load($products);
                        $product->pdn_id = $pdnId;
                        $product->pdnc_id = $cid;
                        $product->firm_id=$firmId;
                        $product->save();
                    }
                }
                $transaction->commit();
                return $this->success($negotiation->pdn_code);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }else{
            $data['negotiation']= $negotiation;
            $data['child']      = $child;
            $data['accompany']  = $accompany;
            $data['analysis']   = $analysis;
            $data['reception']  = $reception;
            $data['authorize']  = $authorize;
            $data['productInfo']  = $productInfo;
            return $data;
        }
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->pdn_status = PdNegotiation::STATUS_DELETE;
        if ($model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array
     * 谈判完成
     */
    public function actionFinishNegotiate($id){
        $model = $this->findModel($id);
        $child = $this->actionLoadInfo($id);
        foreach ($child as $key => $value){
            $arr[] = $value['visit_planID'];
        }
        $arr = array_filter($arr);
        if(!empty($arr)){
            $str = implode(',',array_unique($arr));
            $arr = explode(',',$str);
            foreach($arr as $key=>$val){
                $visitPlan = PdVisitPlan::find()->where(['pvp_planID'=>$val])->one();
                $visitPlan->plan_status = PdVisitPlan::PLAN_STATUS_OVER;
                $visitPlan->save();
            }
        }
        $model->pdn_status = PdNegotiation::STATUS_END;
        $model->save();
        $report = PdFirmReport::find()->where(['firm_id'=>$model['firm_id']])->one();
        if(empty($report)){
            $firm = PdFirm::findOne($model['firm_id']);
            $firm->firm_status = PdFirm::STATUS_END;
            $firm->save();
        }
        if($model){
            return $this->success();
        }else{
            return $this->error();
        }
    }

    /**
     * 谈判分析表
     */
    public function actionAnalysis($selects)
    {
        $pdnArr=explode(',',$selects);
        $i=0;
        $list=[];
        foreach ($pdnArr as $kye=>$val){
            $id=PdNegotiationChild::find()->where(['pdn_id'=>$val])->max('pdnc_id');
            $list[$kye]['analysis']=PdNegotiationAnalysisShow::find()->where(['pdnc_id'=>intval($id)])->one();
            $list[$kye]['authorize']=PdAgentsAuthorizeShow::find()->where(['pdnc_id'=>$id])->one();
            $i++;
        }
        return $list;
    }

    /**
     * 加载商品开发计划
     * @return string
     */
    public function actionLoadDvlp(){
        $searchModel = new PdRequirementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }
    public function actionGetInfo($pdnId){
        $negotiation=PdNegotiation::find()->where(['pdn_id'=>$pdnId])->andWhere(['<>','pdn_status',0])->one();
        return $negotiation;
    }

    public function actionGetFirmInfo($firmId,$select=null){
        return PdFirmShow::find()->where(['firm_id'=>$firmId])->select($select)->one();
    }

    public function actionGetVisitPlan($planId,$select=null){
        return PdVisitPlanShow::find()->where(['pvp_planId'=>$planId])->select($select)->one();
    }

    /**
     * 加载商品信息
     * @param $id
     * @return string
     */
    public function actionDvlpInfo($id){
        $data = PdRequirementProductShow::find()->where(['requirement_id'=>$id])->all();
        return $data;
    }

    public function actionSelectPlan()
    {
        $model=new PdNegotiationSearch();
        $dataProvider=$model->searchPlan(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

//    /**
//     * 一阶分类选择列表
//     */
//    protected function getProductTypeList()
//    {
//        return PdProductType::getLevelOne();
//    }

//    public function actionDownList(){
//        //厂商地位
//        $downList['firmLevel']=BsPubdata::getList(BsPubdata::FIRM_LEVEL);
//        //厂商配合度
//        $downList['firmCooperate']=BsPubdata::getList(BsPubdata::PD_NEGOTIATION_COOPERATE);
//        //商品类别
//        $downList['productType']=$this->getProductTypeList();
//        //代理等级
//        $downList['agentsLevel']=BsPubdata::getList(BsPubdata::PD_AGENTS_LEVEL);
//        //商品定位
//        $downList['productLevel']=BsPubdata::getList(BsPubdata::DP_PRODUCT_LEVEL);
//        //谈判结论
//        $downList['negotiationResult']=BsPubdata::getList(BsPubdata::PD_NEGOTIATION_RESULT);
//        //授权区域范围
//        $downList['authorizeArea']=BsPubdata::getList(BsPubdata::PD_AUTHORIZE_AREA);
//        //销售范围
//        $downList['saleArea']=BsPubdata::getList(BsPubdata::PD_SALE_AREA);
//        //物流配送
//        $downList['deliveryWay']=BsPubdata::getList(BsPubdata::PD_DELIVERY_WAY);
//        //售后服务
//        $downList['service']=BsPubdata::getList(BsPubdata::PD_SERVICE);
////        //厂商类型
////        $downList['firmType']=BsPubdata::getList(BsPubdata::FIRM_TYPE);
//        return $downList;
//    }

    public function actionSearchDownList()
    {
        //商品类别(一阶)
        $downList['productTypes']= BsCategory::getLevelOne();
        //厂商类型
        $downList['firmType']=BsPubdata::getList(BsPubdata::FIRM_TYPE);
        //状态
        $downList['pdnStatus']=['10'=>'新增','20'=>'谈判中','30'=>'谈判完成','40'=>'驳回'];
        return $downList;
    }

    public function actionFormDownList()
    {
        //厂商地位
        $downList['firmLevel']=BsPubdata::getList(BsPubdata::FIRM_LEVEL);
        //厂商配合度
        $downList['firmCooperate']=BsPubdata::getList(BsPubdata::PD_NEGOTIATION_COOPERATE);
        //商品类别(一阶)
        $downList['productType']=PdProductType::getLevelOne();
        //商品定位
        $downList['productLevel']=BsPubdata::getList(BsPubdata::DP_PRODUCT_LEVEL);
        //谈判结论
        $downList['negotiationResult']=BsPubdata::getList(BsPubdata::PD_NEGOTIATION_RESULT);
        //代理等级
        $downList['agentsLevel']=BsPubdata::getList(BsPubdata::PD_AGENTS_LEVEL);
        //授权区域范围
        $downList['authorizeArea']=BsPubdata::getList(BsPubdata::PD_AUTHORIZE_AREA);
        //销售范围
        $downList['saleArea']=BsPubdata::getList(BsPubdata::PD_SALE_AREA);
        //结算方式
        $downList['settlement']=BsSettlement::getSettlement();
        //物流配送
        $downList['deliveryWay']=BsPubdata::getList(BsPubdata::PD_DELIVERY_WAY);
        //售后服务
        $downList['service']=BsPubdata::getList(BsPubdata::PD_SERVICE);
        //交货条件
        $downList['devcon']=BsDevcon::find()->all();
        //付款条件
        $downList['payment']=BsPayCondition::find()->all();
        //交易币别
        $downList['currency']=BsCurrency::find()->all();
        //交易单位
        $downList['tradingUnit']=BsPubdata::getList(BsPubdata::PD_TRADING_UNIT);
        //商品类型
        $downList['productTypes']= BsCategory::getLevelOne();
        //商品分类
        return $downList;
    }

    /**
     * Finds the PdNegotiation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PdNegotiation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PdFirmNegotiationShow::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



}
