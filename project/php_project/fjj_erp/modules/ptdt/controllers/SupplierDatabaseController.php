<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTradConditions;
use app\modules\common\models\BsTransaction;
use app\modules\ptdt\models\BsVendorMainlist;
use app\modules\ptdt\models\PdMaterialCode;
use app\modules\ptdt\models\PdProductType;
use app\modules\ptdt\models\PdNegotiationChild;
use app\modules\ptdt\models\PdProductManager;
use app\modules\ptdt\models\PdSupplier;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\PdVendorconetionPersion;
use app\modules\ptdt\models\Search\PdFirmQuery;
use app\modules\ptdt\models\search\PdSupplierSearch;
use app\modules\ptdt\controllers;
use yii\helpers\Json;
use yii\helpers\Url;
use yii;
/**
 * 供应商控制器
 * F3858995
 * 2016/10/25
 */
class SupplierDatabaseController extends BaseController{

    /**
     * 首页
     */
    public function actionIndex(){
        $searchModel = new PdSupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render("index",[
            'searchModel'=>$searchModel,
            'dataProvider' => $dataProvider,
            'downList' =>$this->getDownList()
        ]);
    }

    /**
     * 加载供应商资料库
     * @return string
     */
    public function actionLoadInfo()
    {
        $id = Yii::$app->request->post('id');
        $product=BsVendorMainlist::find()->where(['vendor_id'=>$id])->andWhere(['vmanil_status'=>BsVendorMainlist::STATUS_DEFAULT])->all();
        $contacts=PdVendorconetionPersion::find()->where(['vendor_id'=>$id])->andWhere(['vcper_status'=>PdVendorconetionPersion::STATUS_DEFAULT])->all();
        return $this->renderAjax('load-info', [
            'product'=>$product,
            'contacts'=>$contacts
        ]);
    }

    protected function getDownList()
    {
        //厂商地位
        $downList['firmLevel'] = BsPubdata::getList(BsPubdata::FIRM_LEVEL);
        //厂商来源
        $downList['firmSource'] = BsPubdata::getList(BsPubdata::FIRM_SOURCE);
        //厂商类型
        $downList['firmType'] = BsPubdata::getList(BsPubdata::FIRM_TYPE);
        //付款条件
        $downList['payCondition'] = BsPayCondition::find()->all();
        //商品类型
        $downList['productType'] = PdProductType::getLevelOne();
        //代理等级
        $downList['agentsLevel'] = BsPubdata::getList(BsPubdata::PD_AGENTS_LEVEL);
        //授权区域范围
        $downList['authorizeArea'] = BsPubdata::getList(BsPubdata::PD_AUTHORIZE_AREA);
        //供应商管理_新增类型
        $downList['supplierComptype'] = BsPubdata::getList(BsPubdata::PD_SUPPLIER_COMPTYPE);
        return $downList;

    }


}