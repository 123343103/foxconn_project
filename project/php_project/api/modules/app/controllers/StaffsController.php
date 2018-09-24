<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2017/2/24
 * Time: 下午 04:45
 */

namespace app\modules\app\controllers;

use app\controllers\AppBaseController;
use app\modules\hr\models\show\HrStaffShow;
use Yii;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\HrOrganization;
use app\modules\app\models\search\AppStaffSearch;
use app\modules\hr\models\HrStaffTitle;
use yii\web\NotFoundHttpException;
use app\modules\crm\models\CrmSalearea;
use yii\data\ActiveDataProvider;

class StaffsController extends AppBaseController
{
    public $modelClass = 'app\modules\hr\models\HrStaff';

    public function actionIndex()
    {
        $searchModel = new AppStaffSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->AppSearch($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionIndexTop()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AppStaffSearch::find()->select('staff_name,staff_id,staff_avatar')->where(["app_list_top" => 10])->orderBy("position")
        ]);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 新增staff
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new HrStaff();
        //Ajax验证账号是否重复
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->getRequest()->post())) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return \yii\bootstrap\ActiveForm::validate($model);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->load(Yii::$app->request->post());
            if (!$model->save()) {
                throw  new \Exception("新增失败");
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        $transaction->commit();
        return $this->success();


    }


    /**
     * 查看功能
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return HrStaffShow::findOne($id);
    }

    /**
     * 修改头像
     * @param $id
     */
    public function actionAvatarUpload($id)
    {
        $model = $this->findModel($id);
        $model->staff_avatar = "/avatar/" . $id . ".png";
        if ($model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }

    }

    public function actionDownList()
    {
        $downList['staffTitle'] = HrStaffTitle::getStaffTitleAll();
        $downList['organization'] = HrOrganization::getOrgAll();
        $downList['salearea'] = CrmSalearea::getSalearea();
        return $downList;
    }

    protected function findModel($id)
    {
        if (($model = HrStaff::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}