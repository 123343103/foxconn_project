<?php
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\ptdt\models\PdProductType;
use yii\data\ActiveDataProvider;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii;

/**
 * 料号类别维护控制器
 * User: F1676624
 * Date: 2016/10/20
 */
class ProductTypeMaintainController extends BaseController
{
    private $_url = "ptdt/product-type-maintain/";  //对应api控制器URL
    //静态数组，展示对应数据用
    public static $level = [1 => '一级', 2 => '二级', 3 => '三级', 4 => '四级', 5 => '五级', 6 => '六级'];
    public static $isSpecial = [1 => '属于', 0 => '不属于'];
    public static $Status = [0 => '废弃', 1 => '正常', 2 => '审核中'];

    /**
     * 料号类别列表页面
     * @param int $id 为type_pid 第一页默认为零
     */
    public function actionIndex($id = 0, $title = '分类')
    {
        $url = $this->findApiUrl() . "ptdt/product-type-maintain/index?id=" . $id;

        $queryParam = Yii::$app->request->queryParams;
        $url .= "?" . http_build_query($queryParam);

        $dataProvider = $this->findCurl()->get($url);

        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }

        if ($id != 0) {

            $new_title = $title . '>' . $this->getModel($id)['type_name'];
            //用于控制新增不超过六级
            $level = $this->getModel($id)['type_level'] + 1;

        } else {

            $new_title = $title;
            $level = 1;
        }
        return $this->render('index', [
            'level' => $level,
            'id' => $id,
            'new_title' => $new_title
        ]);
    }

    /**
     * 新增料号大类别
     * @param int $id 为 type_pid      index页'pid' => $pid得到
     */
    public function actionAdd($id = 0, $title = '')
    {
        //缺少验证
        if (Yii::$app->request->getIsPost()) {

            $postData = Yii::$app->request->post();
            $postData['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['create_at'] = date("Y-m-d H:i:s", time());
            $postData['type_pid'] = $id;
            $postData['update_by'] = "";
            $postData['update_at'] = "";
            $postData['is_valid'] = 2;
            $postData['status'] = 2;

            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->post($url));

            if ($data->status == 1) {
                SystemLog::addLog('新增了ID为' . $postData['type_id'] . '的类别');
                return Json::encode(['msg' => "添加成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，添加失败", "flag" => 0]);
            }

        } else {

            $model = $this->getNewModel($id);

            return $this->render('add', [
                'model' => $model,
                'title' => $title
                , 'level_all' => $this::$level
                , 'is_special' => $this::$isSpecial
            ]);
        }
    }


    /**
     * 修改料号大类别
     * @param int $id 为type_id
     */
    public function actionEdit($id, $title)
    {

        //缺少验证
        if (Yii::$app->request->getIsPost()) {

            $postData = Yii::$app->request->post();
            $postData['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['create_at'] = date("Y-m-d H:i:s", time());
            $postData['update_by'] = Yii::$app->user->identity->staff_id;
            $postData['update_at'] = date("Y-m-d H:i:s", time());
            $postData['type_pid'] = 0;
            $postData['is_valid'] = 2;
            $postData['status'] = 2;

            $url = $this->findApiUrl() . $this->_url . "edit?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->post($url));

            if ($data->status == 1) {
//                SystemLog::addLog('修改了ID为' . $postData['type_id'] . '的类别');
                return Json::encode(['msg' => "添加成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data->msg, "flag" => 0]);
            }

        } else {

            $model = $this->getModel($id);

            return $this->render('edit', [
                'model' => $model,
                'title' => $title
                , 'level_all' => $this::$level
                , 'is_special' => $this::$isSpecial
            ]);
        }
    }

    /**
     * 查看料号大类别
     * @param int $id 为type_id
     */
    public function actionView($id = '', $title)
    {
        $model = $this->getViewModel($id);

        return $this->render('view', [
            'model' => $model
            , 'title' => $title
            , 'level_all' => $this::$level
            , 'is_special' => $this::$isSpecial
            , 'status' => $this::$Status
        ]);
    }

    /**
     * 删除料号大类别
     * @param int $id 为type_id
     */
    public function actionDelete($id = '')
    {
        //删除返回数据
        $data = $this->deleteModel($id);

        if ($data->status == 1) {

            SystemLog::addLog('删除了ID为' . $id . '的料号大类别');
            return Json::encode(["msg" => "删除成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
        } else {
            return Json::encode(["msg" => $data->msg, "flag" => 0]);
        }

    }

    /**
     * 类别状态改变
     * @param int $id 为type_id
     */
    public function actionChange($id)
    {
        //传id过去改 接收返回结果

    }


    /**
     * @param string $type_id
     * @return array 指定类信息
     */
    public function getModel($id = "")
    {
        $url = $this->findApiUrl() . "ptdt/product-type-maintain/get-model?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $s = json_decode($dataProvider, true);
        return $s;
    }

    /**
     * @param string $type_id
     * @return array 指定类信息 含关联内容
     */

    public function getViewModel($id = "")
    {
        $url = $this->findApiUrl() . "ptdt/product-type-maintain/view?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $s = json_decode($dataProvider, true);
        return $s;
    }

    public function deleteModel($id = "")
    {
        $url = $this->findApiUrl() . "ptdt/product-type-maintain/delete?id=" . $id;
        //删除用delete方法
        $dataProvider = $this->findCurl()->delete($url);
        $data = json_decode($dataProvider);
        return $data;
    }

    /**
     * @param string $id
     * @return array 新增使用
     * 获取按规则生成的 type_level type_no type_index type_id 其他为空的model
     */
    public function getNewModel($id = "")
    {
        $url = $this->findApiUrl() . "ptdt/product-type-maintain/new-model?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $s = json_decode($dataProvider, true);
        return $s;
    }
}