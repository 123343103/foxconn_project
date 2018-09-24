<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 03:35
 */

namespace app\modules\warehouse\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsForm;
use app\modules\warehouse\models\BsWhPrice;
use app\modules\warehouse\models\search\BsWhPriceSearch;
use Yii;

class BsWhPriceController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsWhPrice';

    //列表页
    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new BsWhPriceSearch();
        $dataProvoder = $searchModel->search($param);
        $model = $dataProvoder->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvoder->totalCount;
        return $list;

    }

    //修改
    public function actionUpdate($whpb_id)
    {
        try {
            $post = Yii::$app->request->post();
            $model = BsWhPrice::findOne($whpb_id);
            $model->load($post);
            if (!$model->save()) {
                throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

    //添加
    public function actionCreate1()
    {
        try {
            $post = Yii::$app->request->post();
            $model =new BsWhPrice();
            $model->load($post);
            //系统生成费用编码
            $model->whpb_code = BsForm::getCode("bs_wh_price", $model);
            if (!$model->save()) {
                throw new \Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            return $this->success();
//            return $model;
        } catch (\Exception $e) {
        }
    }

    public function actionGetModel($whpb_id)
    {
        $model = BsWhPrice::find()->where(['whpb_id' => $whpb_id])->one()->toArray();
        return $model;
    }
}