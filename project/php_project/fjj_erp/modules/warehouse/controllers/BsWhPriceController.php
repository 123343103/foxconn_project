<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/12
 * Time: 下午 03:36
 */

namespace app\modules\warehouse\controllers;


use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class BsWhPriceController extends BaseController
{
    private $_url = "warehouse/bs-wh-price/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }

        if (Yii::$app->request->isAjax) {
            $dataProvider = $this->findCurl()->get($url);
            $dataProvider = Json::decode($dataProvider);
            if (!empty($dataProvider['rows'])) {
                foreach ($dataProvider['rows'] as & $val) {
                    if ($val['stcl_status'] == 1) {
                        $val['stcl_status'] = '启用';
                    } else {
                        $val['stcl_status'] = '禁用';
                    }
                    if (empty($val['update_by'])) {
                        $val['create_by'] = $val['cname'];
                        $val['cdate'] = $val['cdate'];
                    } else {
                        $val['create_by'] = $val['uname'];
                        $val['cdate'] = $val['udate'];
                    }
                }
            }
            return Json::encode($dataProvider);
        }
        $fields = $this->getField("/warehouse/bs-wh-price/index");
        return $this->render('index', ['fields' => $fields]);
    }

    //修改
    public function actionUpdate($whpb_id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['BsWhPrice']['update_by'] = Yii::$app->user->identity->staff->staff_id;//修改人
            $postData['BsWhPrice']['udate'] = date('Y-m-d H:i:s', time());
            $url = $this->findApiUrl() . $this->_url . 'update?whpb_id=' . $whpb_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "操作失败", 'flag' => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . 'get-model?whpb_id=' . $whpb_id;
            $model = json_decode($this->findCurl()->get($url));
            return $this->renderAjax('update', ['model' => $model]);
        }
    }

    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['BsWhPrice']['create_by'] = Yii::$app->user->identity->staff->staff_id;//创建人
            $postData['BsWhPrice']['cdate'] = date('Y-m-d H:i:s', time());//创建时间
            $url = $this->findApiUrl() . $this->_url . 'create1';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
//            dumpE($data);
            if ($data->status == 1) {
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "操作失败", 'flag' => 0]);
            }
        } else {
            return $this->renderAjax('create');
        }
    }
}