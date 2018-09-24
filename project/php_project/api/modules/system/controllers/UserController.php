<?php

namespace app\modules\system\controllers;

use app\controllers\BaseActiveController;
use app\models\User;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\RPwrDpt;
use app\modules\system\models\search\UserSearch;
use Yii;
use yii\web\NotFoundHttpException;


/**
 * User控制器
 */
class UserController extends BaseActiveController
{
    /**
     * index控制器
     */
    public $modelClass = true;

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }


    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetStaff()
    {
        $param = Yii::$app->request->get();
        $info = HrStaff::getStaffInfoById($param['staff_id']);
        return $info;
    }

    public function actionSave()
    {
        $para = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //根据ass_id查询有没有数据
            $rpwtdpt = RPwrDpt::find()->where(['ass_id' => $para['ass_id']])->all();

            if (!empty($rpwtdpt)) {
                // 有数据,先删除
                if (RPwrDpt::deleteAll(['ass_id' => $para['ass_id']])) {

                } else {
                    $transaction->rollBack();
                    return $this->error();
                }
            }

            //添加数据
            $ridArr = explode(",",$para['org_id']);
            foreach ($ridArr as $key => $val) {
                $model = new RPwrDpt();
                $model->type_id = $para['type_id'];
                $model->ass_id = $para['ass_id'];
                $model->org_id = $val;
                $model->opper = $para['opper'];
                $model->opp_date = $para['opp_date'];
                $model->opp_ip = $para['opp_ip'];
                if (!$model->save()) {
                    throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }

    }
}
