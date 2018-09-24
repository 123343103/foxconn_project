<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\ptdt\models\PdProductType;
use app\modules\ptdt\models\show\PdProducTypeShow;
use yii\data\ActiveDataProvider;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii;

/**
 * 料号类别维护控制器
 * User: F1676624
 * Date: 2016/11/21
 */
class ProductTypeMaintainController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PdProductType';

    /**
     * 料号类别列表页面
     * @param int $id 为type_pid 第一页默认为零
     */
    public function actionIndex($id = 0)
    {
        $params = Yii::$app->request->queryParams;
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider =
            new ActiveDataProvider([
                'query' => PdProducTypeShow::find()->where(['type_pid' => $id]),
                'pagination' => [
                    'pageSize' => $pageSize,
                ]
            ]);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 新增料号大类别
     */
    public function actionAdd()
    {
        $model = new PdProductType();
        $list['PdProductType'] = Yii::$app->request->post();

        if ($model->load($list) && $model->save()) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 新增料号大类别时获取按规则生成的元素
     * @param int $id 为 type_pid      index页'pid' => $pid得到
     */
    public function actionNewModel($id = 0)
    {

        $model = new PdProductType();
        //新增级别为父级加1
        //编码一级为输入，二级及以下为自动生成编码
        if ($id == 0) {
            $model->type_level = 1;
            $model->type_no = null;
        } else {
            $model->type_level = PdProductType::getModel($id)->type_level + 1;
            $model->type_no = $model->getNewNo($id);
        }
        $model->type_index = $model->getNewIndex($id);
        $model->type_id = $model->getNewId($id);
        $model->type_pid = $id;
        //其余默认为空
        $model->type_name = null;
        $model->type_picture = null;
        $model->is_valid = null;
        $model->status = null;
        $model->is_special = null;
        $model->create_at = null;
        $model->create_by = null;
        $model->update_at = null;
        $model->update_by = null;
        $model->type_title = null;
        $model->type_keyword = null;
        $model->type_description = null;

        return $model;

    }


    /**
     * 修改料号大类别
     * @param int $id 为type_id
     */
    public function actionEdit($id = "")
    {
        $model = PdProductType::getModel($id);
        if (Yii::$app->request->post()) {
            $list['PdProductType'] = Yii::$app->request->post();;

            if ($model->load($list) && $model->save()) {
                return $this->success();
            } else {
                return $this->error();
            }
        } else {
            $list[] = $model;

            return $list;
        }
    }

    /**
     * 查看料号大类别
     * @param int $id 为type_id
     */
    public function actionView($id)
    {
        $model = PdProducTypeShow::findOne($id);
        return $model;
    }

    /**
     * 删除料号大类别
     * @param int $id 为type_id
     */
    public function actionDelete($id = "")
    {
        $model = PdProductType::getModel($id);

//        判断是否存在正常使用的下级，若存在不能删除
        if (count(PdProductType::getChildrenByParentId($id)) == 0) {

            if ($result = $model->delete()) {

                return $this->success();
            } else {
                return $this->error();
            }
        } else {
            return $this->error('存在使用中下级,不能删除');
        }

    }

    /**
     * 获取指定类信息
     * @param int $id 为type_id
     */
    public function actionGetModel($id = '')
    {
        $model = PdProductType::getModel($id);
        return $model;
    }

    /**
     * 废弃或激活料号大类别
     * @param int $id 为type_id
     */
    public function actionChange($id)
    {
        $model = PdProductType::getModel($id);
        //根据当前状态操作
        switch ($model->status) {

            case PdProductType::STATUS_DISUSE:

                $model->status = PdProductType::STATUS_CHECKING;

                break;

            case PdProductType::STATUS_NORMAL:
                if (count(PdProductType::getChildrenByParentId($id)) == 0) {
                    $model->status = PdProductType::STATUS_DISUSE;
                } else {
                    return Json::encode(["msg" => "存在使用中下级,不能废除", "status" => 0]);
                }
                break;

            case PdProductType::STATUS_CHECKING:

                return Json::encode(["msg" => "正在审核中,不能操作", "status" => 0]);

                break;
        }

        if ($result = $model->save()) {

            SystemLog::addLog('废弃或激活了ID为' . $id . '的料号大类别');
            return Json::encode(["msg" => "操作成功", "status" => 1]);

        } else {

            return Json::encode(["msg" => "操作失敗", "status" => 0]);

        }
    }
}