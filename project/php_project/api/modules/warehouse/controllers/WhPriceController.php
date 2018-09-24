<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/14
 * Time: 上午 10:57
 */

namespace app\modules\warehouse\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsCurrency;
use app\modules\common\models\BsPubdata;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\BsWhPrice;
use app\modules\warehouse\models\search\WhPriceSearch;
use app\modules\warehouse\models\WhPrice;
use app\modules\warehouse\models\WhPricel;
use Yii;
use yii\db\Exception;

class WhPriceController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\WhPrice';

    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        $searchModel = new WhPriceSearch();
        $dataProvoder = $searchModel->search($param);
        $model = $dataProvoder->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvoder->totalCount;
        return $list;
    }

    //新增
    public function actionCreate1()
    {
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $WhPriceModel = new WhPrice();
            $WhPriceModel->load($post);
            if (!$WhPriceModel->save()) {
                throw new \Exception(json_encode($WhPriceModel->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            $whp_id = $WhPriceModel->attributes['whp_id'];
            if (!empty($post['WhPricel'][0]['whpb_id'])) {
                foreach ($post['WhPricel'] as $val) {
                    $WhPricelModel = new WhPricel();
                    $WhPricelModel->whp_id = $whp_id;
                    $WhPricelModel->whpb_id = $val['whpb_id'];
                    $WhPricelModel->whpb_num = $val['whpb_num'];
                    $WhPricelModel->whpb_curr = $val['whpb_curr'];
                    if (!$WhPricelModel->save()) {
                        throw new Exception(json_encode($WhPricelModel->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //修改
    public function actionUpdate($whp_id)
    {
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            $post = Yii::$app->request->post();
            $WhPriceModel = WhPrice::findOne($whp_id);
            $WhPriceModel->load($post);
            if (!$WhPriceModel->save()) {
                throw new \Exception(json_encode($WhPriceModel->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            //有没有费用标准
            $WhPricelModel = WhPricel::find()->where(['whp_id' => $whp_id])->asArray()->all();
            if (!empty($post['WhPricel']) && !empty($WhPricelModel)) {
                //循环
                $update = array();//取交集
                $add = array();//取新添加的(新增)
                $delete = array();//取被删除的数据
                foreach ($post['WhPricel'] as $val) {
                    $tf = true;
                    //判断是否是新曾的
                    if (!empty($val['whpl_id'])) {
                        $whpl_id = $val['whpl_id'];
                        foreach ($WhPricelModel as $val1) {
                            if ($whpl_id == $val1['whpl_id']) {
                                $tf = false;
                                $update[] = $val;
                            }
                        }
                    }
                    if ($tf) {
                        $add[] = $val;
                    }
                }
                //循环取出被删除的数据
                foreach ($WhPricelModel as $val1) {
                    $tf = true;
                    foreach ($update as $val) {
                        if ($val1['whpl_id'] == $val['whpl_id']) {
                            $tf = false;
                        }
                    }
                    if ($tf) {
                        $delete[] = $val1;
                    }
                }
                //修改
                foreach ($update as $val) {
                    $wpl = WhPricel::findOne($val['whpl_id']);
                    $wpl->whpb_id = $val['whpb_id'];
                    $wpl->whpb_num = $val['whpb_num'];
                    $wpl->whpb_curr = $val['whpb_curr'];
                    if (!$wpl->save()) {
                        throw new Exception(json_encode($wpl->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                //新增
                foreach ($add as $val) {
                    $wpl = new WhPricel();
                    $wpl->whp_id = $whp_id;
                    $wpl->whpb_id = $val['whpb_id'];
                    $wpl->whpb_num = $val['whpb_num'];
                    $wpl->whpb_curr = $val['whpb_curr'];
                    if (!$wpl->save()) {
                        throw new Exception(json_encode($wpl->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                //删除
                foreach ($delete as $val) {
                    WhPricel::deleteAll(['whpl_id' => $val['whpl_id']]);
                }

            } elseif (empty($WhPricelModel)) {
                //直接添加
                foreach ($post['WhPricel'] as $val) {
                    $WhPricelModel = new WhPricel();
                    $WhPricelModel->whp_id = $whp_id;
                    $WhPricelModel->whpb_id = $val['whpb_id'];
                    $WhPricelModel->whpb_num = $val['whpb_num'];
                    $WhPricelModel->whpb_curr = $val['whpb_curr'];
                    if (!$WhPricelModel->save()) {
                        throw new Exception(json_encode($WhPricelModel->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            } else {
                //删除现有的
                WhPricel::deleteAll(['whp_id' => $whp_id]);
            }
            $transaction->commit();
            return $this->success();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //下拉框的值
    public function actionDownList()
    {
        $downList['type'] = BsPubdata::getList(BsPubdata::WH_STANDARD_PRICE);  //仓库标准价格
        $downList['BsWh'] = BsWh::getList();  //仓库列表
        $downList['BsWhPrice'] = BsWhPrice::getList();//仓库费用种类
        $downList['BsCurrency'] = BsCurrency::getList();//币别
        return $downList;
    }

    //获取仓库信息(选中仓库名称带出仓库地址和仓库代码)
    public function actionGetWh($wh_id)
    {
        $searchModel = new WhPriceSearch();
        $date = $searchModel->getBsWhList($wh_id);
        $model = $date->getModels();
        return $model;

    }

    //主页获取仓库费用名称
    public function actionPriceList($whp_id)
    {
        $model = new WhPriceSearch();
        $WhPricelModel = $model->getWhPrice($whp_id)->getModels();
//        $WhPricel = WhPricelShow::find()->where(['whp_id' => $whp_id])->all();
        return $WhPricelModel;
    }

    //查询仓库标准费用信息
    public function actionGetBsWhPrice($value, $column)
    {
        $BsWhPricel = BsWhPrice::find()->where([$column => $value])->all();
        return $BsWhPricel;
    }

    //要修改的数据
    public function actionGetPriceInfo($whp_id)
    {
        $model = new WhPriceSearch();
        //查询要修改的仓库标准价格信息(主表)
        $WhPriceModel['WhPrice'] = WhPrice::find()->where(['whp_id' => $whp_id])->asArray()->all();
        //查询要修改的仓库标准价格信息(子表)
        $WhPricelModel['WhPricel'] = $model->getWhPrice($whp_id)->getModels();
        //查询仓库代码和地址信息
        $BsWhModel['BsWh'] = $model->getBsWhList($WhPriceModel['WhPrice'][0]['wh_id'])->getModels();

        $WhPricelModel = array_merge($WhPriceModel, $WhPricelModel, $BsWhModel);
//        dumpE($WhPricelModel );
        return $WhPricelModel;
    }

    //启用禁用
    public function actionOpenClose($whp_id)
    {

        try {

            $whp_idArrry = explode(',', $whp_id);
            foreach ($whp_idArrry as $val) {
                $WhPrice = WhPrice::findOne($val);
                if ($WhPrice->whp_status == 0) {
                    $WhPrice->whp_status = 1;
                    if (!$WhPrice->save()) {
                        throw new \Exception(json_encode($WhPrice->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                } else {
                    $WhPrice->whp_status = 0;
                    if (!$WhPrice->save()) {
                        throw new \Exception(json_encode($WhPrice->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //主页修改标准价格
    public function actionUpdatePrice($whpl_id, $whpb_num, $whpb_curr)
    {

        try {
            $model = WhPricel::findOne($whpl_id);
            $model->whpb_num = $whpb_num;
            $model->whpb_curr = $whpb_curr;
            if (!$model->save()) {
                throw new Exception(json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

    }

    //主页删除标准价格
    public function actionDeletePrice($whpl_id)
    {
        try {
            WhPricel::deleteAll(['whpl_id' => $whpl_id]);
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function actionCheck($op_id, $wh_id)
    {
        $count = WhPrice::find()->where(['op_id' => $op_id, 'wh_id' => $wh_id])->count();
        return $count;
    }
}