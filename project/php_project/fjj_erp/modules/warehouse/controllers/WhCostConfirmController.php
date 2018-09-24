<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/12/20
 * Time: 上午 08:46
 */

namespace app\modules\warehouse\controllers;


use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;

class WhCostConfirmController extends BaseController
{
    private $_url = "warehouse/wh-cost-confirm/";

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        if (Yii::$app->request->isAjax) {
            $data = $this->findCurl()->get($url);
            $data = json_decode($data, true);
            foreach ($data['rows'] as &$val) {
                if ($val['buss_type'] == '1') {
                    $val['inout_type_name'] = '出仓操作';
                }
                if ($val['audit_status'] == '0') {
                    $val['audit_status'] = '审核中';
                }
                if ($val['audit_status'] == '1') {
                    $val['audit_status'] = '审核完成';
                }
                if ($val['audit_status'] == '2') {
                    $val['audit_status'] = '待提交';
                }
                if ($val['audit_status'] == '-1') {
                    $val['audit_status'] = '驳回';
                }
            }

            return json_encode($data);
        }
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($this->findCurl()->get($url))['rows']);
        }
        $fields = $this->getField("/warehouse/wh-cost-confirm/index");
        $fields_child = $this->getField("/warehouse/wh-price/product");
        $downlist = $this->getDownList();
        return $this->render('index', ['fields' => $fields, 'fields_child' => $fields_child, 'downList' => $downlist]);
    }

    public function actionOutWhCost($o_whpkid)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['IcInvCosth']['invh_id'] = $o_whpkid;
            $postData['IcInvCosth']['create_by'] = Yii::$app->user->identity->staff->staff_id;//创建人;
            $postData['IcInvCosth']['organization_code'] = Yii::$app->user->identity->staff->organization_code;//创建人部门code
            $postData['IcInvCosth']['create_at'] = date('Y-m-d H:i:s', time());//创建时间
            $url = $this->findApiUrl() . $this->_url . 'create1';
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $result = json_decode($curl->post($url), true);
            if ($result['status'] == 1) {
//                SystemLog::addLog('修改供应商'.$result['data']['code']);
                return json_encode([
                    'msg' => $result['msg'],
                    'flag' => 1,
                    'url' => Url::to(['index']),
                    'billId' => $result['data']['id'],
                    'billTypeId' => $result['data']['code']
                ]);
            }
            return json_encode(['msg' => $result['msg'], 'flag' => 0]);
        } else {
            $url = $this->findApiUrl() . $this->_url . 'wh-cost-confirm?o_whpkid=' . $o_whpkid;
            $model = $this->findCurl()->get($url);
            $model = Json::decode($model);
            return $this->render('out-wh-cost', ['model' => $model]);
        }
    }

    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . 'down-list';
        $downlist =Json::decode( $this->findCurl()->get($url));
        return $downlist;
    }

    //获取商品信息
    public function actionGetPdt($invh_id)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-pdt?invh_id=' . $invh_id;
        $data = json_decode($this->findCurl()->get($url));
        return json_encode($data);
    }

    //获取仓库出仓费用
    public function actionGetCost($invh_id)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-cost?invh_id=' . $invh_id;
        $data = json_decode($this->findCurl()->get($url));
        return json_encode($data);
    }

    public function actionUpdateNprice($invcl_id, $invcl_nprice)
    {
        $url = $this->findApiUrl() . $this->_url . 'update-nprice?invcl_id=' . $invcl_id . '&invcl_nprice=' . $invcl_nprice;
        $data = Json::decode($this->findCurl()->delete($url), false);
//        return  json_encode($data);
        if ($data->status == 1) {
            return Json::encode(['msg' => "操作成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作成功", "flag" => 0]);
        }
    }

}