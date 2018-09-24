<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/17
 * Time: 15:32
 */
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDevcon;
use app\modules\common\models\BsPayCondition;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\PdNegotiation;
use app\modules\ptdt\models\PdNegotiationAnalysis;
use app\modules\ptdt\models\PdAgentsAuthorize;
use app\modules\ptdt\models\PdFirmReportChild;
use app\modules\ptdt\models\PdFirmReportProduct;
use app\modules\ptdt\models\PdReception;
use app\modules\ptdt\models\PdAccompany;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsSettlement;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\PdFirmReportCompared;
use app\modules\ptdt\models\search\PdFirmReportChildSearch;
use app\modules\ptdt\models\search\PdFirmReportProductSearch;
use app\modules\ptdt\models\search\PdFirmReportSearch;
use app\modules\ptdt\models\search\PdRequirementSearch;
use app\modules\ptdt\models\show\PdAccompanyShow;
use app\modules\ptdt\models\show\PdFirmReportChildShow;
use app\modules\ptdt\models\show\PdFirmReportShow;
use app\modules\ptdt\models\show\PdNegotiationAnalysisShow;
use app\modules\ptdt\models\show\PdAgentsAuthorizeShow;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\ptdt\models\show\PdNegotiationChildShow;
use app\modules\ptdt\models\show\PdNegotiationProductShow;
use app\modules\ptdt\models\show\PdRequirementProductShow;
use yii;
use yii\bootstrap\ActiveForm;
use yii\web\NotFoundHttpException;


/**
 * 商品开发数据控制器
 * Class FirmReportController
 * @package app\modules\ptdt\controllers
 */
class FirmReportController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PdFirmReport';

    /**
     * @param $companyId
     * @return mixed
     * 主页
     */
    public function actionIndex(){
        $searchModel = new PdFirmReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return array
     * 新增
     */
    public function actionAdd(){
        $pdFirmReport = new PdFirmReport();
        $child       = new PdFirmReportChild();
        $authorize   = new PdAgentsAuthorize();
        $analysis    = new PdNegotiationAnalysis();
        $reception   = new PdReception();
        $post = Yii::$app->request->post();
        $status = $post['PdFirmReport']['report_status'];
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $firmId = $post['PdFirmReport']['firm_id'];
            $result = PdFirmReport::find()->where(['firm_id' => $firmId])->andWhere(['<>', 'report_status', 0])->exists();
            if ($result) {
                $pdFirmReport = PdFirmReport::find()->where(['firm_id' => $firmId])->andWhere(['<>', 'report_status', 0])->one();
            }
//            谈判分析表
            $analysis->load(Yii::$app->request->post());
            $analysis->pdnc_date = $post['PdFirmReportChild']['pfrc_date'];
            $analysis->pdnc_time = $post['PdFirmReportChild']['pfrc_time'];
            $analysis->firm_id = $firmId;
            if(!$analysis->save()){
                throw  new \Exception("新增谈判分析失败");
            };
            $pdnaId = $analysis->pdna_id;

            //代理授权表

            $authorize->load(Yii::$app->request->post());
            $authorize->firm_id = $firmId;
            $authorize->pdn_date = $post['PdFirmReportChild']['pfrc_date'];
            $authorize->pdn_time = $post['PdFirmReportChild']['pfrc_time'];
            if(!$authorize->save()){
                throw  new \Exception("新增代理授权失败");
            };
            $pdaaId = $authorize->pdaa_id;

            //呈报主表
            $pdFirmReport->load(Yii::$app->request->post());
            $pdFirmReport->pdna_id = $pdnaId;
            $pdFirmReport->pdaa_id = $pdaaId;
            $pdFirmReport->report_status = $status;
            if(!$pdFirmReport->save()){
                throw  new \Exception("更新呈报主表失败");
            };
            $pfrId = $pdFirmReport->pfr_id;
            $pfrCode = $pdFirmReport->report_code;
            $firm = PdFirm::find()->where(['firm_id'=>$firmId])->one();
            $firm->firm_status = PdFirm::CHECK_PENDING;
            $firm->save();

            //呈报子表
            $child->load(Yii::$app->request->post());
            $child->pfr_id = $pfrId;
            $child->pdna_id = $pdnaId;
            $child->pdaa_id = $pdaaId;
            $child->pfrc_status = PdFirmReportChild::STATUS_DEFAULT;
            if(!$child->save()){
                throw  new \Exception("新增子表失败");
            };
            $pfrcId = $child->pfrc_id;


            //廠商主談人員表
            $reception->load(Yii::$app->request->post());
            $reception->h_id = $pfrId;
            $reception->l_id = $pfrcId;
            $reception->firm_id = $firmId;
            $reception->rece_main = PdReception::RECE_MAIN_YES;
            $reception->rece_type = PdReception::RECE_TYPE_NEGOTIATION;
            if(!$reception->save()){
                throw  new \Exception("新增厂商主谈人失败");
            };
            //陪同人員表
            $vaccArr = array_filter($post['vacc']);
            if ($vaccArr != null) {
                foreach ($vaccArr as $val) {
                    $accompany = new PdAccompany();
                    $accompany->staff_code = $val;
                    $accompany->vacc_type = PdAccompany::NEGOTIATION_ACCOMPANY_PERSON;
                    $accompany->h_id = $pfrId;
                    $accompany->l_id = $pfrcId;
                    if(!$accompany->save()){
                        throw  new \Exception("新增陪同人员失败");
                    };
                }
            }
            //談判商品
            $productInfo = isset($post['PdFirmReportProduct']) ? $post['PdFirmReportProduct'] : false;
            if ($productInfo) {
                foreach ($productInfo as $value) {
                    $product = new PdFirmReportProduct();
                    $products['PdFirmReportProduct'] = $value;
                    $product->load($products);
                    $product->pfr_id = $pfrId;
                    $product->pfrc_id = $pfrcId;
                    $product->firm_id = $firmId;
                    $product->pdh_id = '1';
                    if(!$product->save()){
                        throw  new \Exception("新增商品失败");
                    };
                }
            }

            /*谈判对比*/
            $count = PdFirmReportCompared::find()->where(['pfr_id' => $pfrId, 'pfr_code' => $pfrCode])->count();
            if (PdFirmReportCompared::deleteAll(['pfr_id' => $pfrId, 'pfr_code' => $pfrCode]) < $count) {
                throw  new \Exception();
            };
            if(!empty($post['PdFirmReportCompared'])){
                $comparedArr = count($post['PdFirmReportCompared']);
                if ($comparedArr > 1) {
                    foreach ($post['PdFirmReportCompared'] as $val) {
                        $pdFirmCompared = new PdFirmReportCompared();
                        $pdFirmCompared->firm_id = $val;
                        $pdFirmCompared->pfr_id = $pfrId;
                        $pdFirmCompared->pfr_code = $pfrCode;
                        if(!$pdFirmCompared->save()){
                            throw  new \Exception("新增厂商对比失败");
                        };
                    }
                }
            }

        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        if($post['PdFirmReport']['report_status'] == 40){
            $cid = array('id'=>$pfrId,'childId'=>$pfrcId,'message'=>'厂商呈报送审:送审['.$firm['firm_sname'].']呈报');
        }
        $cid = array('id'=>$pfrId,'childId'=>$pfrcId,'message'=>'新增['.$firm['firm_sname'].']呈报');
        return $this->success($msg=null,$cid);
    }

    /**
     * @param $id
     * @param $childId
     * @return array
     * 修改
     */
    public function actionUpdate($id,$childId){
        $transaction = Yii::$app->db->beginTransaction();
        $report = $this->getModel($id);
        $child = $this->getChildModel($childId);
        $analysis    = $child->negotiationAnalysis;
        $authorize   = $child->agentsAuthorize;
        $reception   = $child->firmReception;
        if(!$report || !$child){
            return $this->error();
        }
        $post = Yii::$app->request->post();
        $status = $post['PdFirmReport']['report_status'];
        try{
            //谈判分析表
            $analysis->load($post);
            $analysis->pdnc_date = $post['PdFirmReportChild']['pfrc_date'];
            $analysis->pdnc_time = $post['PdFirmReportChild']['pfrc_time'];
            $analysis->firm_id = $post['PdFirmReport']['firm_id'];
            if(!$analysis->save()){
                throw  new \Exception("更新谈判分析失败");
            };

            $pdnaId = $analysis->pdna_id;

            //代理授权表
            $authorize->load($post);
            $authorize->firm_id = $post['PdFirmReport']['firm_id'];
            $authorize->pdn_date = $post['PdFirmReportChild']['pfrc_date'];
            $authorize->pdn_time = $post['PdFirmReportChild']['pfrc_time'];
            if(!$authorize->save()){
                throw  new \Exception("更新代理授权失败");
            };
            $pdaaId = $authorize->pdaa_id;

            //呈报主表
            $report->load($post);
            $report->report_status = PdFirmReport::REPORT_ADD;
            $report->pdna_id = $pdnaId;
            $report->pdaa_id = $pdaaId;
            $report->report_status = $status;
            if(!$report->save()){
                throw  new \Exception("更新呈报主表失败");
            };
            $pfrId = $report->pfr_id;
            $firmId = $report->firm_id;
            $pfrCode = $report->report_code;
            $firm = PdFirm::find()->select('firm_sname')->where(['firm_id'=>$firmId])->one();
            //呈报子表
            $child->load($post);
            $child->pfr_id = $pfrId;
            $child->pdna_id = $pdnaId;
            $child->pdaa_id = $pdaaId;
            $child->pfrc_status = PdFirmReportChild::STATUS_DEFAULT;
            if(!$child->save()){
                throw  new \Exception("更新子表失败");
            };
            $pfrcId = $child->pfrc_id;
            //廠商主談人員表
            $reception->load($post);
            $reception->h_id=$pfrId;
            $reception->l_id=$pfrcId;
            $reception->firm_id=$firmId;
            $reception->rece_main=PdReception::RECE_MAIN_YES;
            $reception->rece_type=PdReception::RECE_TYPE_NEGOTIATION;
            if(!$reception->save()){
                throw  new \Exception("更新厂商主谈人失败");
            };
            //陪同人員表
            $count = PdAccompany::find()->where(['l_id' => $pfrcId,'vacc_type'=>3])->count();
            if (PdAccompany::deleteAll(['l_id' => $pfrcId,'vacc_type'=>3]) < $count) {
                throw  new \Exception("更新陪同人员失败");
            };
            $vaccArr=array_filter($post['vacc']);
            if($vaccArr != null){
                foreach ($vaccArr as $val){
                    $accompany = new PdAccompany();
                    $accompany->staff_code=$val;
                    $accompany->vacc_type=PdAccompany::NEGOTIATION_ACCOMPANY_PERSON;
                    $accompany->h_id=$pfrId;
                    $accompany->l_id=$pfrcId;
                    if(!$accompany->save()){
                        throw  new \Exception("更新陪同人员失败");
                    };
                }
            }
            //談判商品
            $count = PdFirmReportProduct::find()->where(['pfrc_id' => $pfrcId,'firm_id'=>$firmId])->count();
            if (PdFirmReportProduct::deleteAll(['pfrc_id' => $pfrcId,'firm_id'=>$firmId]) < $count) {
                throw  new \Exception("更新商品失败");
            };
            $productInfo=isset($post['PdFirmReportProduct'])?$post['PdFirmReportProduct']:false;

            if($productInfo){
                foreach ($productInfo as $value){
                    $product   = new PdFirmReportProduct();
                    $products['PdFirmReportProduct']=$value;
                    $product->load($products);
                    $product->pfr_id = $pfrId;
                    $product->pfrc_id = $pfrcId;
                    $product->firm_id=$firmId;
                    $product->pdh_id= '1';
                    if(!$product->save()){
                        throw  new \Exception("更新商品失败");
                    };
                }
            }
            /*谈判对比*/
            $count = PdFirmReportCompared::find()->where(['pfr_id'=>$pfrId,'pfr_code'=>$pfrCode])->count();
            if (PdFirmReportCompared::deleteAll(['pfr_id' => $pfrId,'pfr_code'=>$pfrCode]) < $count) {
                throw  new \Exception("更新厂商对比失败");
            };
            $comparedArr=count($post['PdFirmReportCompared']);
            if($comparedArr > 1){
                foreach ($post['PdFirmReportCompared'] as $val){
                    $pdFirmCompared = new PdFirmReportCompared();
                    $pdFirmCompared->firm_id=$val;
                    $pdFirmCompared->pfr_id = $pfrId;
                    $pdFirmCompared->pfr_code = $pfrCode;
                    if(!$pdFirmCompared->save()){
                        throw  new \Exception("更新厂商对比失败");
                    };
                }
            }
        }catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        if($post['PdFirmReport']['report_status'] == 40){
            return $this->success($msg=null,'厂商呈报:送审'.$firm['firm_sname'].'呈报');
        }else{
            return $this->success($msg=null,'厂商呈报:修改'.$firm['firm_sname'].'呈报');
        }
    }

    /**
     * @param null $id
     * @param null $childId
     * @return array
     * 删除
     */
    public function actionDelete($id=null,$childId=null){
        $model = $this->getModel($id);
        $firm = PdFirm::find()->where(['firm_id'=>$model['firm_id']])->select('firm_sname')->one();
        if($id != null && $childId == null){
            $model->report_status = PdFirmReport::REPORT_DELETE;
            if ($result = $model->save()) {
                return $this->success('','厂商呈报:删除'.$firm['firm_sname'].'呈报主表');
            } else {
                return $this->error();
            }
        }else if($id != null && $childId != null){
            $childModel = $this->getChildModel($childId);
            $childModel->pfrc_status = PdFirmReportChild::STATUS_DEL;
            if ($result = $childModel->save()) {
                return $this->success('','厂商呈报:删除'.$firm['firm_sname'].'呈报主表');
            } else {
                return $this->error();
            }
        }
        else{
            return $this->error();
        }
    }

    /**
     * @param $id
     * @param $childId
     * @return array
     * 送审
     */
    public function actionCheck($id,$childId){
        if($id != null && $childId != null){
            $model = $this->getModel($id);
            $firm = PdFirm::find()->where(['firm_id'=>$model['firm_id']])->select('firm_sname')->one();
            $model->report_status = PdFirmReport::CHECK_PENDING;
            if ($result = $model->save()) {
                return $this->success('','厂商呈报:送审'.$firm['firm_sname'].'呈报');
            } else {
                return $this->error();
            }
        }
        else{
            return $this->error();
        }
    }

    /**
     * @param null $id
     * @param null $i
     * @return mixed
     * 厂商对比
     */
    public function actionFirmCompared($id=null,$i=null){
        if($id!=null&&$i===null){
            $lists['analysis']=PdNegotiationAnalysisShow::find()->where(['firm_id'=>$id])->orderBy(['pdnc_date' => SORT_DESC,'pdnc_time' => SORT_DESC,])->one();
            $lists['firm']=PdFirmShow::find()->where(['firm_id'=>$id])->one();
            $lists['authorize']=PdAgentsAuthorizeShow::find()->where(['firm_id'=>$id])->orderBy([ 'pdn_date' => SORT_DESC,'pdn_time' => SORT_DESC])->one();
        }else{
            $lists[$i]['analysis']=PdNegotiationAnalysisShow::find()->where(['firm_id'=>$id])->orderBy(['pdnc_date' => SORT_DESC,'pdnc_time' => SORT_DESC,])->one();
            $lists[$i]['firm']=PdFirmShow::find()->where(['firm_id'=>$id])->one();
            $lists[$i]['authorize']=PdAgentsAuthorizeShow::find()->where(['firm_id'=>$id])->orderBy([ 'pdn_date' => SORT_DESC,'pdn_time' => SORT_DESC])->one();
        }
        return $lists;
    }

    /**
     * 加载呈报子表信息
     */
    public function actionLoadReport(){
        $searchModel = new PdFirmReportChildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
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

    /**
     * 加载商品信息
     * @param $id
     * @return string
     */
    public function actionDvlpInfo($id){
        $data = PdRequirementProductShow::find()->where(['requirement_id'=>$id])->all();
        return $data;
    }

    /**
     * @param $id
     * @return array|yii\db\ActiveRecord[]
     * 加载商品信息
     */
    public function actionLoadProducts(){
        $searchModel = new PdFirmReportProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @return mixed
     * 下拉菜单
     */
    public function actionDownLists(){
        //代理等级
        $downList['agentsLevel']=BsPubdata::getList(BsPubdata::PD_AGENTS_LEVEL);
        //授權區域範圍
        $downList['authorizeArea']=BsPubdata::getList(BsPubdata::PD_AUTHORIZE_AREA);
        //銷售範圍
        $downList['saleArea']=BsPubdata::getList(BsPubdata::PD_SALE_AREA);
        //物流配送
        $downList['deliveryWay']=BsPubdata::getList(BsPubdata::PD_DELIVERY_WAY);
        //售後服務
        $downList['service']=BsPubdata::getList(BsPubdata::PD_SERVICE);
        //廠商配合度
        $downList['degree'] = BsPubdata::getList(BsPubdata::PD_NEGOTIATION_COOPERATE);
        //商品定位
        $downList['productLevel']=BsPubdata::getList(BsPubdata::DP_PRODUCT_LEVEL);
        //厂商地位
        $downList['position'] = BsPubdata::getList(BsPubdata::FIRM_LEVEL);
        //厂商类型
        $downList['firmType'] = BsPubdata::getList(BsPubdata::FIRM_TYPE);
        //代理类型
        $downList['agentsType'] = BsPubdata::getList(BsPubdata::PD_AGENTS_TYPE);
        //开发类型
        $downList['developType'] = BsPubdata::getList(BsPubdata::PD_DEVELOP_TYPE);
        //紧急程度
        $downList['ungencyDegree'] = BsPubdata::getList(BsPubdata::PD_URGENCY_LEVEL);
        //一阶分类
        $downList['productTypes'] = BsCategory::getLevelOne();
        //结算方式
        $downList['settlement'] = BsSettlement::getSettlement();
        //交易单位
        $downList['tradingUnit']=BsPubdata::getList(BsPubdata::PD_TRADING_UNIT);
        //交易币别
        $downList['currency']=BsCurrency::find()->all();
        //交货条件
        $downList['devcon']=BsDevcon::find()->all();
        //付款条件
        $downList['payment']=BsPayCondition::find()->all();
        return $downList;
    }

    /**
     * @param $code
     * @return array|null|yii\db\ActiveRecord
     * 陪同人
     */
    public function actionGetVisitManager($code)
    {
        return HrStaff::getStaffByIdCode($code);
    }

    /**
     * 模型获取API
     * @param $id
     * @return array|null|static
     * 新增页查询厂商所有信息
     */
    public function actionAddModel($firmId){
        $model = PdFirmReportShow::find()->where(['firm_id'=>$firmId])->one();
        $childModel = '';
        $firmCompared = '';
        $lists = '';
        $lists['analysis'] = PdNegotiationAnalysisShow::find()->where(['firm_id' => $firmId])->orderBy(['pdnc_date' => SORT_DESC, 'pdnc_time' => SORT_DESC,])->one();
        $pdn = PdNegotiation::find()->where(['firm_id'=>$firmId])->select('pdn_id')->one();
        $lists['child'] = PdNegotiationChildShow::find()->where(['pdn_id' => $pdn['pdn_id']])->orderBy(['pdnc_date' => SORT_DESC, 'pdnc_time' => SORT_DESC,])->one();
        $lists['reception'] = PdReception::find()->where(['l_id'=>$lists['child']['pdnc_id']])->one();
        $lists['accompany'] = PdAccompanyShow::find()->where(['l_id'=>$lists['child']['pdnc_id']])->andWhere(['vacc_type'=>'3'])->all();
        $lists['firm'] = PdFirmShow::find()->where(['firm_id' => $firmId])->one();;
        $lists['authorize'] = PdAgentsAuthorizeShow::find()->where(['firm_id' => $firmId])->orderBy(['pdn_date' => SORT_DESC, 'pdn_time' => SORT_DESC])->one();
        $lists['product'] = PdNegotiationProductShow::find()->where(['firm_id'=>$firmId])->all();
        $array = [
            'report'=>$model,
            'child'=>$childModel,
            'firmCompared'=>$firmCompared,
            'lists'=>$lists,
        ];
        return $array;
    }

    /**
     * @param $id
     * @param $childId
     * @return array
     * 修改页厂商所有信息
     */
    public function actionUpdateModel($id,$childId){
        $model = PdFirmReportShow::getFirmReport($id);
        $childModel = PdFirmReportChildShow::getChildOne($childId);
        $firmCompared = $model->firmCompared;
        $i=0;
        if($firmCompared){
            foreach($firmCompared as $val){
                $lists[$i]['analysis']=PdNegotiationAnalysisShow::find()->where(['firm_id'=>$val['firm_id']])->orderBy(['pdnc_date' => SORT_DESC,'pdnc_time' => SORT_DESC,])->one();
                $lists[$i]['firm']=PdFirmShow::find()->where(['firm_id'=>$val['firm_id']])->one();
                $lists[$i]['authorize']=PdAgentsAuthorizeShow::find()->where(['firm_id'=>$val['firm_id']])->orderBy([ 'pdn_date' => SORT_DESC,'pdn_time' => SORT_DESC])->one();
                $i++;
            }
        }else{
            $lists['analysis']=PdNegotiationAnalysisShow::find()->where(['firm_id'=>$model['firm_id']])->orderBy(['pdnc_date' => SORT_DESC,'pdnc_time' => SORT_DESC,])->one();
            $lists['firm']=PdFirmShow::find()->where(['firm_id'=>$model['firm_id']])->one();;
            $lists['authorize']=PdAgentsAuthorizeShow::find()->where(['firm_id'=>$model['firm_id']])->orderBy([ 'pdn_date' => SORT_DESC,'pdn_time' => SORT_DESC])->one();
        }
        $array = [
            'report'=>$model,
            'child'=>$childModel,
            'firmCompared'=>$firmCompared,
            'lists'=>$lists
        ];
        return $array;
    }

    /**
     * @param $id
     * @return array
     * 查询厂商基本信息
     */
    public function actionFirmInfo($id){
        $model = PdFirmShow::getFirmById($id);
        $report = '';
        $child = '';
        $lists = '';
//        $reportOne = PdFirmReport::getReportOne($model['firm_id']);
//        if(!empty($reportOne)){
                $report = PdFirmReportShow::getReportOne($model['firm_id']);
                $child = PdFirmReportChildShow::getNewChildById($report['pfr_id']);
                if($report){
                    $firmCompared = $report->firmCompared;
                    if ($firmCompared) {
                        $i = 0;
                        foreach ($firmCompared as $key => $val) {
                            $lists[$i]['authorize'] = PdAgentsAuthorizeShow::getAuthorizeById($val->firm_id);
                            $lists[$i]['analysis'] = PdNegotiationAnalysisShow::getAnalysisById($val->firm_id);
                            $lists[$i]['firm'] = PdFirmShow::getFirmById($val->firm_id);
                            $i++;
                        }
                    } else {
                        $lists['authorize'] = PdAgentsAuthorizeShow::getAuthorizeById($model->firm_id);
                        $lists['analysis'] = PdNegotiationAnalysisShow::getAnalysisById($model->firm_id);
                        $lists['firm'] = PdFirmShow::getFirmById($model->firm_id);
                    }
                }
                $array = [
                    'report' => $report,
                    'child' => $child,
                    'firm' => $model,
                    'lists' => $lists
                ];
                return $array;

//        }else{
//            $array = [
//                'report'=>$report,
//                'child'=>$child,
//                'firm'=>$model,
//                'lists'=>$lists
//            ];
//            return $array;
//        }
    }

    /**
     * @return mixed
     * 呈报分析厂商查询
     */
    public function actionAnalysisComs(){
//        $searchModel = new PdFirmReportSearch();
//        $dataProvider = $searchModel->searchAnalysis(Yii::$app->request->queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list["total"] = $dataProvider->totalCount;
//        return $list;
        $result = PdFirmReportShow::find()->all();
        return $result;
    }

    /**
     * @param $id
     * @return array
     * 产品优劣势分析对比表
     */
    public function actionAnalysisReport($id){
        $a = explode(',',$id);
        $i=0;
        foreach ($a as $key => $val){
            $firm[$i] = PdFirmShow::getFirmById($val);
            $analysis[$i] = PdNegotiationAnalysisShow::getAnalysisById($val);
            $i++;
        }
        $array = [
            'firm'=>$firm,
            'analysis'=>$analysis
        ];
        return $array;
    }

    public function actionModel($id,$companyId)
    {
        $model = PdFirmReportShow::getOne($id,$companyId);
        return $model;

    }

    public function actionModels($id)
    {
        $model = PdFirmReportShow::getFirmReport($id);
        return $model;

    }

    public function actionChildModel($id){
        $childModel = PdFirmReportChildShow::getChildOne($id);
        return $childModel;
    }


    /**
     * @param $id
     * @return array|null|yii\db\ActiveRecord
     * 根据主表ID 查询呈报子表
     */
    public function actionCheckChild($id){
        $childModel = PdFirmReportChildShow::getChildById($id);
        return $childModel;
    }

    protected function getModel($id)
    {
        if (($model = PdFirmReport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getChildModel($childId)
    {
        if (($model = PdFirmReportChild::findOne($childId)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}