<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\ptdt\models\FpBsCategory;
use yii\data\ActiveDataProvider;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii;

/**
 * 料号类别维护控制器
 * User: F1676624
 * Date: 2016/11/21
 */
class CategoryController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\FpBsCategory';

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
                'query' => FpBsCategory::find()->where(['p_category_id' => $id]),
                'pagination' => [
                    'pageSize' =>$pageSize,
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
        $model = new FpBsCategory();
        $list['FpBsCategory'] = Yii::$app->request->post();

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
        $model = new FpBsCategory();
        //新增级别为父级加1
        //编码一级为输入，二级及以下为自动生成编码
        if ($id == 0) {
            $model->category_level = 1;
            $model->orderby = null;
        } else {
            $model->category_level = FpBsCategory::getModel($id)->category_level + 1;
            $model->orderby = $model->getNewNo($id);
        }
        //$model->category_index = $model->getNewIndex($id);
        $model->category_id = $model->getNewId($id);

        //$model->orderby = $id;
        //其余默认为空'category_name', 'category_level', 'p_category_id', 'orderby', 'isvalid', 'center', 'min_margin'
        $model->category_name = null;
        $model->p_category_id = $id;
        $model->isvalid = null;
        $model->center = null;
        $model->min_margin = null;
        return $model;
    }


    /**
     * 修改料号大类别
     * @param int $id 为type_id
     */
    public function actionEdit($id = "")
    {
        $model = FpBsCategory::getModel($id);
        if (Yii::$app->request->post()) {
            $list['FpBsCategory'] = Yii::$app->request->post();;

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
        $model = FpBsCategoryShow::findOne($id);
        return $model;
    }

    /**
     * 删除料号大类别
     * @param int $id 为type_id
     */
    public function actionDelete($id = "")
    {
        $model = FpBsCategory::getModel($id);

//        判断是否存在正常使用的下级，若存在不能删除
        if (count(FpBsCategory::getChildrenByParentId($id)) == 0) {

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
        $model = FpBsCategory::getModel($id);
        return $model;
    }

    /**
     * 废弃或激活料号大类别
     * @param int $id 为type_id
     */
    public function actionChange($id)
    {
        $model = FpBsCategory::getModel($id);
        //根据当前状态操作
        switch ($model->status) {

            case FpBsCategory::STATUS_DISUSE:

                $model->status = FpBsCategory::STATUS_CHECKING;

                break;

            case FpBsCategory::STATUS_NORMAL:
                if (count(FpBsCategory::getChildrenByParentId($id)) == 0) {
                    $model->status = FpBsCategory::STATUS_DISUSE;
                } else {
                    return Json::encode(["msg" => "存在使用中下级,不能废除", "status" => 0]);
                }
                break;

            case FpBsCategory::STATUS_CHECKING:

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