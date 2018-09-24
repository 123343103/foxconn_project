<?php

namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\tools\CheckUsed;
use app\modules\hr\models\HrStaff;
use Yii;
use app\modules\crm\models\CrmCreditMaintain;
use app\modules\crm\models\search\CrmCreditMaintainSearch;
use yii\db\Query;
use yii\db\QueryBuilder;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CrmCreditMaintainController implements the CRUD actions for CrmCreditMaintain model.
 */
class CrmCreditMaintainController extends BaseActiveController
{
    public $modelClass = 'app\modules\crm\models\CrmCreditMaintain';


    /**
     * Lists all CrmCreditMaintain models.
     * @return mixed
     */
    public function actionIndex(){
        $searchModel = new CrmCreditMaintainSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }



    /**
     * Creates a new CrmCreditMaintain model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CrmCreditMaintain();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->success('创建成功');
        } else {
            return $this->error($model->errors);
        }
    }

    /**
     * Updates an existing CrmCreditMaintain model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->success('修改成功');
        } else {
            return $this->error($model->errors);
        }
    }

    /**
     * Deletes an existing CrmCreditMaintain model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $checkUsed = new CheckUsed();
//        $used = $checkUsed->check($id,'id');
//        if ($used['status'] == 0) {
//            return $this->error($used['msg']);
//        }
        $arr = explode(',',$id);
        $name = '';
        foreach ($arr as $key => $val){
            $model = $this->findModel($val);
            $model->credit_status = CrmCreditMaintain::STATUS_DELETE;
            $name = $name.$model["credit_name"].',';
            $result = $model->save();
        }
        $staff_code = trim($name,',');
        if ($result) {
            $msg = array('id' => $id, 'msg' => '删除账信类型"' . $staff_code . '"');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }

    /**
     * Finds the CrmCreditMaintain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CrmCreditMaintain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CrmCreditMaintain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionModels($id){
//        $model = CrmCreditMaintain::findOne($id);
        $model = (new Query())->select([
            'ccm.id',
            'ccm.code',
            'ccm.credit_name',
            'ccm.remark',
            'ccm.credit_status',
            'ccm.create_by',
            'ccm.create_at',
            'hr.staff_name',
        ])->from(['ccm'=>'crm_credit_maintain'])
            ->leftJoin(HrStaff::tableName().' hr','hr.staff_id = ccm.create_by')
            ->one();
        return $model;
    }
}
