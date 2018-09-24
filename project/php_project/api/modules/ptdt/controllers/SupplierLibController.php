<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsDevcon;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPayment;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\BsVendorMainlist;
use app\modules\ptdt\models\PdMaterialCode;
use app\modules\ptdt\models\search\SupplierInfoSearch;
use app\modules\ptdt\models\show\PdMaterialCodeShow;
use app\modules\ptdt\models\PdProductType;
use app\modules\ptdt\models\PdVendorconetionPersion;
use app\modules\ptdt\models\search\PdMaterialCodeSearch;
use app\modules\ptdt\models\PdSupplier;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\Search\PdFirmQuery;
use app\modules\ptdt\models\show\PdSupplierShow;
use app\modules\ptdt\models\SupplierInfo;
use yii\helpers\Json;
use yii;

class SupplierLibController extends BaseActiveController{


    public $modelClass = 'app\modules\ptdt\models\PdSupplier';

    /**
     * 首页
     * @return string
     */
    public function actionIndex(){
        $searchModel = new SupplierInfoSearch();
        $dataProvider =  $searchModel->searchSupplierLib(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
//        $list['downList']=$this->actionDownList();
        return $list;
    }

    /**
     * 创建
     * @return string
     */
    public function actionCreate(){
        $model = new PdSupplier();
        if( $post = Yii::$app->request->post()){
        $transaction = Yii::$app->db->beginTransaction();
            try {
                //供应商信息
                $model->load($post);
                $model->save();
                $pid=$model->supplier_id;

                //主营商品
                $mainList=isset($post['BsVendorMainlist'])?$post['BsVendorMainlist']:false;
                if($mainList){
                    foreach ($mainList as $value){
                        $mainModel = new BsVendorMainlist();
                        $mainModels['BsVendorMainlist']=$value;
                        $mainModel->load($mainModels);
                        $mainModel->vendor_id=$pid;
                        $mainModel->save();
                    }
                }
                //联系人信息
                $persion=isset($post['PdVendorconetionPersion'])?$post['PdVendorconetionPersion']:false;
                if($persion){
                    foreach ($persion as $value){
                        $persionModel = new PdVendorconetionPersion();
                        $persionModels['PdVendorconetionPersion']=$value;
                        $persionModel->load($persionModels);
                        $persionModel->vendor_id=$pid;
                        $persionModel->save();
                    }
                }
//                SystemLog::addLog('申请供应商：'.$model->supplier_shortname);
                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * 查看详情
     */
    public function actionView($id)
    {
        $viewModel = new SupplierInfoSearch();
        $model['model'] = current($viewModel->searchSupplierView($id)->getModels());
//        return $model['model'];
        //主营商品
        $model['mainList']= BsVendorMainlist::find()->where(['vendor_id'=>$id])->andWhere(['vmanil_status'=>BsVendorMainlist::STATUS_DEFAULT])->all();
        $model['persionList']= PdVendorconetionPersion::find()->where(['vendor_id'=>$id])->andWhere(['vcper_status'=>PdVendorconetionPersion::STATUS_DEFAULT])->all();
        return $model;
    }

    //更新
    public function actionEdit($id)
    {
        $model  = PdSupplier::find()->where(['supplier_id'=>$id])->one();
            $post=Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //拟采购商品
                $materials = isset($post['material']) ? serialize(array_unique(array_filter($post['material']))) : null;
                $model->load($post);
                $model->material_id = $materials;
                $model->save();
                $pid = $model->supplier_id;


                //删除商品
                if ($delMain = $post['delMain']) {
                    $delMainArray = explode(",", $delMain);
                    foreach ($delMainArray as $val) {
                        $model = BsVendorMainlist::find()->where(['vmainl_id' => $val])->one();
                        $model->vmanil_status = BsVendorMainlist::STATUS_DELETE;
                        $model->save();
                    }
                }

                //增加主营商品
                $mainLists = isset($post['BsVendorMainlist']) ? $post['BsVendorMainlist'] : false;
                if ($mainLists) {   
                    foreach ($mainLists as $item) {
                        if (isset($item['vmainl_id'])) {
                            continue;
                        }
                        $mainModel = new BsVendorMainlist();
                        $mainModels['BsVendorMainlist'] = $item;
                        $mainModel->load($mainModels);
                        $mainModel->vendor_id = $pid;
                        $mainModel->save();
                    }
                }
                //删除联系人
                if ($delPersion = $post['delPersion']) {
                    $delPersionArray = explode(",", $delPersion);
                    foreach ($delPersionArray as $val) {
                        $model = PdVendorconetionPersion::find()->where(['vcper_id' => $val])->one();
                        $model->vcper_status = PdVendorconetionPersion::STATUS_DELETE;
                        $model->save();
                    }
                }
                //增加联系人
                $persionList = isset($post['PdVendorconetionPersion']) ? $post['PdVendorconetionPersion'] : false;
                if ($persionList) {
                    foreach ($persionList as $item) {
//                        if (isset($item['vcper_id'])) {
//                            continue;
//                        }
                        $persionModel = new PdVendorconetionPersion();
                        $persionModels['PdVendorconetionPersion'] = $item;
                        $persionModel->load($persionModels);
                        $persionModel->vendor_id = $pid;
                        $persionModel->save();
                    }
                }
                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
        }
    }
    //获取所有数据
    public function actionInfoAll($id)
    {
            $model  = $data['model'] = PdSupplier::find()->where(['supplier_id'=>$id])->one();
            //拟采购商品
            $data['materialList']='';
            if($material=unserialize($model->material_id)){
                foreach($material as $val){
                    $data['materialList'][] = PdMaterialCode::find()->where(['m_id'=>$val])->one();
                }
            }
            //联系人信息
            $data['persionList']= PdVendorconetionPersion::find()->where(['vendor_id'=>$id])->andWhere(['vcper_status'=>PdVendorconetionPersion::STATUS_DEFAULT])->all();
            //主营商品
            $data['mainList']= BsVendorMainlist::find()->where(['vendor_id'=>$id])->andWhere(['vmanil_status'=>BsVendorMainlist::STATUS_DEFAULT])->all();
            $data['downList']=$this->actionDownList();
            return $data;
    }

    /**
     * 删除
     */
    public function actionDelete($id)
    {
        $model=PdSupplier::find()->where(['supplier_id'=>$id])->one();
        $model->supplier_status = PdSupplier::STATUS_DELETE;
        if ($model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 选择厂商信息
     */
    public function actionSelectCom()
    {
        $searchModel = new PdFirmQuery();
        $dataProvider = $searchModel->searchQuery(Yii::$app->request->queryParams);
        return $dataProvider;
    }

    /**
     * 选择商品信息
     */
    public function actionSelectMaterial()
    {
        $searchModel = new PdMaterialCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $dataProvider;
    }

    /**
     * 加载供应商资料
     * @return string
     */
    public function actionLoadInfo($id)
    {
        $list['info'] = PdSupplierShow::getSupplierInfo($id);
        $list['material']= PdMaterialCodeShow::getMaterialList(unserialize($list['info']['material_id']));
        return $list;

    }

    /**
     * 加载供应商资料库信息
     * @return string
     */
    public function actionLoadData()
    {
        $id = Yii::$app->request->post('id');
        $model=PdSupplier::find()->select(['firm_id','supplier_id','supplier_code','create_at','supplier_pre_annual_sales','supplier_pre_annual_profit','supplier_source','material_id'])->where(['supplier_id'=>$id])->one();
        $MaterialCode='';
        $MaterialArr=unserialize($model->material_id);
        foreach ($MaterialArr as $val){
            $materialModel=PdMaterialCode::find()->where(['m_id'=>$val])->one();
            $MaterialCode[]=$materialModel;
        }

        return $this->renderAjax('load-data', [
            'model'=>$model,
            'materialCode'=>$MaterialCode
        ]);
    }

    //厂商信息
    public function actionFirmInfo($id){
        $firmData = PdFirm::find()->where(['firm_id'=>$id])->one();
        $categoryType = $firmData->CategoryName;
        return yii\helpers\Json::encode([$firmData,$categoryType]);
    }

    // 料号信息
    public function actionMaterialInfo($id){
        $data = PdMaterialCode::find()->where(['m_id'=>$id])->one();
        return yii\helpers\Json::encode([$data]);
    }
    // 下拉列表选项
    public function actionDownList(){
        //供应商地位
        $downList['supplierLevel']=BsPubdata::getList(BsPubdata::FIRM_LEVEL);
        //供应商来源
        $downList['supplierSource']=BsPubdata::getList(BsPubdata::FIRM_SOURCE);
        //供应商类型
        $downList['supplierType']=BsPubdata::getList(BsPubdata::FIRM_TYPE);
        //付款条件
        $downList['payCondition']=BsPayCondition::find()->all();
        //交货条件
        $downList['devcon']=BsDevcon::find()->all();
        //付款条件
        $downList['payment']=BsPayment::find()->all();
        //交易币别
        $downList['currency']=BsCurrency::find()->all();
        //商品类型
        $downList['productType']= PdProductType::getLevelOne();
        //代理等级
        $downList['agentsLevel']=BsPubdata::getList(BsPubdata::PD_AGENTS_LEVEL);
        //销售范围 从军区表/销售区域表取
        //$downList['saleArea']=BsPubdata::getList(BsPubdata::PD_SALE_AREA);
        //授权区域范围 从军区表/销售区域表取
        //$downList['authorizeArea']=BsPubdata::getList(BsPubdata::PD_AUTHORIZE_AREA);
        //供应商管理_新增类型
        $downList['supplierComptype']=BsPubdata::getList(BsPubdata::PD_SUPPLIER_COMPTYPE);

        $downList['sourceType']=BsPubdata::getList(BsPubdata::PD_SOURCE_TYPE);

//        //商品定位
//        $downList['productLevel']=BsPubdata::getList(BsPubdata::DP_PRODUCT_LEVEL);
//        //供应商类型
//        $downList['firmType']=BsPubdata::getList(BsPubdata::FIRM_TYPE);
//        //销售范围
//        $downList['saleArea']=BsPubdata::getList(BsPubdata::PD_SALE_AREA);
//        //交易方式
//        $downList['transaction']=BsTransaction::find()->all();
//        //交易条件
//        $downList['tradConditions']=BsTradConditions::find()->all();


        return $downList;
    }

    // 首页下拉列表选项
    public function actionIndexDownList(){
        // 供应商来源
        $downList['supplierSource']=BsPubdata::getList(BsPubdata::FIRM_SOURCE);
        // 供应商类型
        $downList['supplierType']=BsPubdata::getList(BsPubdata::FIRM_TYPE);
        // 供应商状态
        $downList['status'] = [
            SupplierInfo::STATUS_SEAL => '封存',
            SupplierInfo::STATUS_NORMAL => '正常',
        ];
        return $downList;
    }
}