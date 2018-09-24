<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/14
 * Time: 上午 10:59
 */

namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class WhPriceController extends BaseController
{
    private $_url = "warehouse/wh-price/";

    //主页
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {
            $dataProvider = $this->findCurl()->get($url);
            $dataProvider = Json::decode($dataProvider);
            return Json::encode($dataProvider);

        }
        $fields = $this->getField("/warehouse/wh-price/index");
//        $price_list = $this->getField("/warehouse/wh-price/price-list");
        //从公共数据字典中获取操作类型
        $downList = $this->getDownList();
        return $this->render('index', ['fields' => $fields, 'downList' => $downList,
        ]);
    }

    //主页子列表
    public function actionPriceList($whp_id)
    {
        $url = $this->findApiUrl() . $this->_url . 'price-list?whp_id=' . $whp_id;
        $data = $this->findCurl()->get($url);
        return $data;

    }

    //保存
    public function actionCreate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['WhPrice']['create_by'] = Yii::$app->user->identity->staff->staff_id;//创建人;
            $postData['WhPrice']['cdate'] = date('Y-m-d H:i:s', time());//创建时间
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
            $downList = $this->getDownList();
            return $this->render('create', ['downList' => $downList]);
        }
    }

    //修改
    public function actionUpdate($whp_id)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['WhPrice']['update_by'] = Yii::$app->user->identity->staff->staff_id;//创建人;
            $postData['WhPrice']['udate'] = date('Y-m-d H:i:s', time());//创建时间
            $url = $this->findApiUrl() . $this->_url . 'update?whp_id=' . $whp_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
                return Json::encode(['msg' => "操作成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "操作失败", 'flag' => 0]);
            }
        } else {
            $url = $this->findApiUrl() . $this->_url . "get-price-info?whp_id=" . $whp_id;
            $model = json_decode($this->findCurl()->get($url), true);
            $downList = $this->getDownList();
            return $this->render('update', ['downList' => $downList, 'model' => $model]);
        }
    }

    //启用禁用仓库标准价格
    public function actionOpenClose($whp_id)
    {
        $url = $this->findApiUrl() . $this->_url . "open-close?whp_id=" . $whp_id;
        $res = Json::decode($this->findCurl()->delete($url), false);
        if ($res->status == 1) {
            return Json::encode(['msg' => "操作成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作成功", "flag" => 0]);
        }
    }

    //下拉框的值
    public function getDownList()
    {
        $url_t = $this->findApiUrl() . $this->_url . "down-list";
        $downList = Json::decode($this->findCurl()->get($url_t));
        return $downList;
    }

    //仓库信息
    public function actionGetWh($wh_id)
    {
        $url_t = $this->findApiUrl() . $this->_url . "get-wh?wh_id=" . $wh_id;
        $data = Json::decode($this->findCurl()->get($url_t));
        $data = Json::encode($data);
        return $data;
    }

    //仓库标准价格费用信息
    public function actionGetBsWhPrice($value, $column)
    {
        $url_t = $this->findApiUrl() . $this->_url . "get-bs-wh-price?column=" . $column . '&value=' . $value;
        $data = Json::decode($this->findCurl()->get($url_t));
        $data = Json::encode($data);
        return $data;
    }

    //主页修改标准价格
    public function actionUpdatePrice($whpl_id, $whpb_num, $whpb_curr)
    {
        $url = $this->findApiUrl() . $this->_url . 'update-price?whpl_id=' . $whpl_id . '&whpb_num=' . $whpb_num . '&whpb_curr=' . $whpb_curr;
        $data = Json::decode($this->findCurl()->delete($url), false);
//        return  json_encode($data);
        if ($data->status == 1) {
            return Json::encode(['msg' => "操作成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作成功", "flag" => 0]);
        }
    }

    //主页删除标准价格
    public function actionDeletePrice($whpl_id)
    {
        $url = $this->findApiUrl() . $this->_url . 'delete-price?whpl_id=' . $whpl_id;
        $data = Json::decode($this->findCurl()->delete($url), false);
        if ($data->status == 1) {
            return Json::encode(['msg' => "操作成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作成功", "flag" => 0]);
        }
    }

    //检查同一个仓库只有同一个操作类型
    public function actionCheck($op_id, $wh_id)
    {
        $url = $this->findApiUrl() . $this->_url . 'check?op_id=' . $op_id . '&wh_id=' . $wh_id;
        $data = Json::decode($this->findCurl()->delete($url), false);
        return $data;
    }
}