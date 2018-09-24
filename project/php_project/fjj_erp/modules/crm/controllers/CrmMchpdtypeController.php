<?php
/**
 * 招商商品類目負責人設置表
 * User: F3859386
 * Date: 2017/3/9
 * Time: 10:05
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class CrmMchpdtypeController extends BaseController
{

    private $_url = "crm/crm-mchpdtype/";//对应api控制器

    /**
     * 主页
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index?companyId=" . Yii::$app->user->identity->company_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {                //如果是分页获取数据则直接返回数据
            $dataProvider = $this->findCurl()->get($url);
            return $dataProvider;
        }
        $downList = $this->getDownList();
//        dumpE($downList);
        return $this->render('index', [
            'downList' => $downList,
            'search' => $queryParam['CrmMchpdtypeSearch']
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $url = $this->findApiUrl() . $this->_url . "create";
            $post = Yii::$app->request->post();
//            dumpE($post);
            $post['CrmMchpdtype']['create_by'] = Yii::$app->user->identity->staff_id;
            $result = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post))->post($url);
            $result = json_decode($result, true);
            if ($result['status']) {
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $downList = $this->getDownList();
        $categorys = [];
        foreach ($downList["productType"] as $key => $val) {
            $categorys[$val[catg_id]] = $val[catg_name];
        }
        return $this->render('create', [
            'downList' => $downList,
            'categorys' => $categorys
        ]);
    }

    public function actionUpdate()
    {
        $id = Yii::$app->request->get()['id'];
        if ($post = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $post['CrmMchpdtype']['id'];
            $post['CrmMchpdtype']['update_by'] = Yii::$app->user->identity->staff_id;
            $result = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post))->post($url);
            $result = json_decode($result, true);
            if ($result['status']) {
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->model($id);
        $downList = $this->getDownList();
        $categorys = [];
        foreach ($downList["productType"] as $key => $val) {
            $categorys[$val[catg_id]] = $val[catg_name];
        }
        return $this->render('update', [
            'model' => $model,
            'downList' => $downList,
            'categorys' => $categorys
        ]);
    }

    public function actionView($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        return $this->render('view', [
            'model' => $this->model($id)
        ]);
    }

    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $result = Json::decode($this->findCurl()->delete($url));
        if ($result['status']) {
            SystemLog::addLog('招商类目负责人删除' . $result['data']);
            return Json::encode(["msg" => $result['msg'], "flag" => 1]);
        } else {
            return Json::encode(["msg" => $result['msg'], "flag" => 0]);
        }
    }

    private function getDownList()
    {
        $url = $this->findApiUrl() . "crm/crm-mchpdtype/down-list";
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }

    private function model($id)
    {
        $url = $this->findApiUrl() . "crm/crm-mchpdtype/model?id=" . $id;
        $data = Json::decode($this->findCurl()->get($url));
        return $data;
    }


}