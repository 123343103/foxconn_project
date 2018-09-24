<?php

namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;
use app\modules\system\models\SystemLog;
use Faker\Provider\ar_JO\Company;
use Yii;
use app\modules\common\models\BsCompany;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
/**
 * CompanyController implements the CRUD actions for BsCompany model.
 */
class CompanyController extends BaseActiveController
{

    public $modelClass = 'app\modules\hr\models';
    /**
     * Lists all BsCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model=BsCompany::getCompanyTree();
        return $model;
    }

    public function actionDownList(){
        $data =BsCompany::find()->select('company_id,company_name,parent_id')->All();
        return $data;
    }
    /**
     * 创建
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BsCompany();
        $model->load(Yii::$app->request->post());
        if (!$model->save()){
            return $this->error();
        }
            return $this->success($model->company_name);
    }

    /**
     * 更新
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());
        if (!$model->save()) {
            return $this->error();
        }
        return $this->success($model->company_name);
    }

    /**
     * 删除
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->company_status = BsCompany::STATUS_DEL;
        if (!$model->save()) {
            return $this->error();
        }
        return $this->success($model->company_name);
    }
    /*所在地区一级省份*/
    public function actionDistrict()
    {
        return BsDistrict::getDisProvince();
    }

    /**
     * @return mixed
     * 获取公司属性类型等
     */
    public function actionDownListCop()
    {
        $downList['companyProperty'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_PROPERTY);  //公司属性
        $downList['managementType'] = BsPubdata::getList(BsPubdata::CRM_MANAGEMENT_TYPE);  //经营类型
        $downList['companyScale'] = BsPubdata::getList(BsPubdata::CRM_COMPANY_SCALE);//公司规模
        $downList['country'] = BsDistrict::getDisLeveOne();//所在国家
        return $downList;
    }

    /**
     * Finds the BsCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BsCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public  function actionModel($id)
    {
        if (($model = BsCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel($id)
    {
        if (($model = BsCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
