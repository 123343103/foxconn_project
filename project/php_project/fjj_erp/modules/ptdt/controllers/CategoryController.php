<?php
/**
 * Created by PhpStorm.
 * User: F1677180
 * Date: 2016/12/12
 * Time: 上午 09:58
 */
namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use app\modules\ptdt\models\FpBsCategory;
use yii\data\ActiveDataProvider;
use app\modules\system\models\SystemLog;
use yii\helpers\Json;
use yii;


class CategoryController extends BaseController
{
    private $_url = "ptdt/category/";  //对应api控制器URL
    //静态数组，展示对应数据用
    public static $level = [1 => '一级', 2 => '二级', 3 => '三级', 4 => '四级',5=>'五级',6=>'六级'];
    public static $isValid = [1 => '有效', 0 => '无效'];
    public static $Status = [0 => '废弃', 1 => '正常', 2 => '审核中'];


    public function actionIndex($id = 0, $title = '分类')
    {
        $url = $this->findApiUrl() .$this->_url. "index?id=". $id;

        $queryParam = Yii::$app->request->queryParams;
        $url .= "&" . http_build_query($queryParam);

        $dataProvider = $this->findCurl()->get($url);

        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            return $dataProvider;
        }

        if ($id != '0') {

            $new_title = $title . '>' . $this->getModel($id)['category_name'];
            //用于控制新增不超过六级
            $level = $this->getModel($id)['category_level'] + 1;

        } else {
            $new_title = $title;
            $level = 1;
        }
//                dumpE($new_title);die();
        return $this->render('index', [
            'level' => $level,
            'id' => $id,
            'new_title' => $new_title
        ]);
    }


    public function actionAdd($id = 0, $title = '')
    {
        //缺少验证
        if (Yii::$app->request->getIsPost()) {

            $postData = Yii::$app->request->post();
            $postData['category_id'] = Yii::$app->user->identity->stuff_id;
            $postData['category_name'] = "";

            $postData['p_category_id'] = $id;
            $postData['orderby'] = 1;
            $postData['isvalid'] = 1;
            $postData['center'] = 0;
            $postData['min_margin'] = 0;

            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->post($url));

            if ($data->status == 1) {
                SystemLog::addLog('新增了ID为' . $postData['category_id'] . '的类别');
                return Json::encode(['msg' => "添加成功", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，添加失败", "flag" => 0]);
            }

        } else {

            $model = $this->getNewModel($id);
            return $this->render('add', [
                'model' => $model,
                'title' => $title,
                'level_all' => self::$level,
                'isvalid' => self::$isValid
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
            $postData['category_id'] = Yii::$app->user->identity->stuff_id;
            $postData['category_name'] = "";
            $postData['p_category_id'] = Yii::$app->user->identity->stuff_id;
            $postData['orderby'] = 1;
            $postData['isvalid'] = 1;
            $postData['center'] = 0;
            $postData['min_margin'] = 0;

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
                , 'isvalid' => $this::$isValid
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
           , 'isvalid' => $this::$isValid

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
        $url = $this->findApiUrl() . "ptdt/category/get-model?id=" . $id;
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
        $url = $this->findApiUrl() . "ptdt/category/view?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $s = json_decode($dataProvider, true);
        return $s;
    }

    public function deleteModel($id = "")
    {
        $url = $this->findApiUrl() . "ptdt/category/delete?id=" . $id;
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
        $url = $this->findApiUrl() . "ptdt/category/new-model?id=" . $id;
        $dataProvider = $this->findCurl()->get($url);
        $s = json_decode($dataProvider, true);
        return $s;
    }
}