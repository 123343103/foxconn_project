<?php
/**
 * 招商商品類目負責人設置表
 * User: F3859386
 * Date: 2017/3/9
 * Time: 10:05
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseActiveController;
use app\modules\ptdt\models\BsCategory;
use app\modules\crm\models\CrmCustPersoninch;
use app\modules\crm\models\CrmMchpdtype;
use app\modules\crm\models\search\CrmMchpdtypeSearch;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CrmMchpdtypeController extends BaseActiveController
{

    public $modelClass = 'app\modules\crm\models\CrmMember';

    /**
     * 主页
     */
    public function actionIndex()
    {
        $searchModel = new CrmMchpdtypeSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 创建
     * @return array
     */
    public function actionCreate()
    {
        $model = new CrmMchpdtype();
        $model->load(Yii::$app->request->post());
        if ($model->save()) {
            return $this->success();
        }
        return $this->error(current($model->getFirstErrors()));
    }

    /**
     * 更新
     * @param $id
     * @return array
     */
    public function actionUpdate($id)
    {
        $model = $this->actionModel($id);
        if (!$model) {
            return $this->error();
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->success();
        }
        return $this->error(current($model->getFirstErrors()));
    }

    /*
     * 删除
     */
    public function actionDelete($id)
    {
        $data = explode(',', $id);
        $err = 0;
        $suc = 0;
        $names = '';
        $result = false;
        foreach ($data as $key => $val) {
            $model = $this->actionModel($val);
            $personinch = CrmCustPersoninch::find()->where(['ccpich_personid' => $val])->one();
            if (!empty($personinch)) {
                $err++;
                continue;
            } else {
                $suc++;
            }
            $model->mpdt_status = CrmMchpdtype::STATUS_DELETE;
            $result = $model->save() || $result;
            $names = $names . $model['id'] . ',';
            $names = trim($names, ',');
        }
        if ($result) {
            if ($err == 0 && $suc != 0) {
                $msg = '删除成功';
            }
            if ($suc == 0 && $err != 0) {
                $msg = $err . '条被引用,无法删除';
            }
            if ($suc != 0 && $err != 0) {
                $msg = '成功删除' . $suc . '条,' . $err . '条被引用,无法删除';
            }

            return $this->success($msg, '删除id为[' . $names . ']信息');
        } else {
            return $this->error("负责人被引用,无法删除");
        }

    }

    public function actionDownList()
    {
        //需求类目
        $downList['productType'] = BsCategory::getLevelOne();
        $downList['staffList'] = HrStaff::getStaffAll('staff_id,staff_name,staff_code');
        $downList['MchpdStatus'] = CrmMchpdtype::getStatus();
        return $downList;
    }


    public function actionModel($id)
    {
        return CrmMchpdtype::findOne($id);
    }
}