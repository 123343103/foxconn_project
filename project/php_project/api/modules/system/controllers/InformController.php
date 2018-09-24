<?php
/**
 * @todo 审核
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2017/1/12
 * Time: 15:43
 */
namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;

use app\modules\crm\models\CrmImessage;
use app\modules\crm\models\search\CrmImessageSearch;
use app\modules\crm\models\show\CrmImessageShow;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
class InformController extends BaseActiveController
{
    public $modelClass ='app\modules\crm\models\CrmImessage';

    public function actionIndex(){
        $searchModel = new CrmImessageSearch();
        $dataProvider = $searchModel->searchInform(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    public function actionUpdate($id){
//        $post=Yii::$app->request->post();
        $model = $this->getReminderModel($id);
        $model->imesg_status = CrmImessage::STATUS_NONE;
        if ($result = $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * @param $id
     * @return array|null|yii\db\ActiveRecord
     * 提醒事项
     */
    public function actionReminderOne($id){
        return CrmImessageShow::find()->where(['imesg_id'=>$id])->one();
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * 查询提醒事项
     */
    protected function getReminderModel($id)
    {
        if (($model = CrmImessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}


