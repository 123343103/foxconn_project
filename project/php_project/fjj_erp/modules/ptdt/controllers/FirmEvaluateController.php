<?php
/**
 * User: F1677929
 * Date: 2016/11/11
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
/**
 * 厂商评鉴控制器
 */
class FirmEvaluateController extends BaseController
{
    private $_url = "ptdt/firm-evaluate/";

    /**
     * 厂商评鉴列表
     */
    public function actionIndex()
    {
        $dataProvider = $this->getData('index','companyId='.Yii::$app->user->identity->company_id);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        //获取列表页数据
        $indexData = json::decode($this->getData('index-data'));
        return $this->render('index', ['indexData' => $indexData]);
    }

    /**
     * 获取评鉴信息
     */
    public function actionLoadEvaluate()
    {
        return $this->getData('load-evaluate');
    }

    /**
     * 新增厂商评鉴
     */
    public function actionAdd()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['PdFirmEvaluate']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdFirmEvaluate']['company_id'] = Yii::$app->user->identity->company_id;
            $data = json_decode($this->sendData('add',$postData));
            if ($data->status == 1) {
                return Json::encode(['msg'=>'新增需求成功！','flag'=>1,'url'=>Url::to(['index'])]);
            } else {
                return Json::encode(['msg'=>'发生未知错误，新增失败！','flag'=>0]);
            }
        } else {
            //新增页面数据
            $addData = json::decode($this->getData('add-data','evaluatePersonId='.Yii::$app->user->identity->staff_id));
            if (empty($addData)) {
                throw new NotFoundHttpException('页面未找到！');
            }
            return $this->render('add', ['addData' => $addData]);
        }
    }

    /**
     * 选择评鉴通过厂商
     */
    public function actionSelectFirm()
    {
        $dataProvider = $this->getData('select-firm');
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        return $this->renderAjax('select-firm');
    }

    /**
     * 判断评鉴不通过厂商
     */
    public function actionEvaluateNoPassFirm()
    {
        return $this->getData('evaluate-no-pass-firm');
    }

    /**
     * 采购评鉴判断
     */
    public function actionPurchaseEvaluateJudge()
    {
        return $this->getData('purchase-evaluate-judge');
    }

    /**
     * 工管评鉴判断
     */
    public function actionManageEvaluateJudge()
    {
        return $this->getData('manage-evaluate-judge');
    }

    /**
     * 采购评鉴
     */
    public function actionPurchaseEvaluate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['PdFirmEvaluateResult']['create_by'] = Yii::$app->user->identity->staff_id;
            $data = json_decode($this->sendData('purchase-evaluate',$postData));
            if ($data->status == 1) {
                return Json::encode(['msg' => "评鉴成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，评鉴失败", "flag" => 0]);
            }
        } else {
            //获取评鉴数据
            $evaluateData = json::decode($this->getData('evaluate-data'));
            if (empty($evaluateData)) {
                throw new NotFoundHttpException('页面未找到！');
            }
            return $this->render('purchase-evaluate', [
                'evaluateData' => $evaluateData,
            ]);
        }
    }

    /**
     * 工管评鉴
     */
    public function actionManageEvaluate()
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $postData['PdFirmEvaluateResult']['create_by'] = Yii::$app->user->identity->staff_id;
            $data = json_decode($this->sendData('manage-evaluate',$postData));
            if ($data->status == 1) {
                return Json::encode(['msg' => "评鉴成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，评鉴失败", "flag" => 0]);
            }
        } else {
            //获取评鉴数据
            $evaluateData = json::decode($this->getData('evaluate-data'));
            if (empty($evaluateData)) {
                throw new NotFoundHttpException('页面未找到！');
            }
            return $this->render('manage-evaluate', [
                'evaluateData' => $evaluateData,
            ]);
        }
    }

    /**
     * 修改判断
     */
    public function actionEditJudge()
    {
        return $this->getData('edit-judge');
    }

    /**
     * 查看厂商评鉴
     */
    public function actionView()
    {
        //获取评鉴数据
        $evaluateData = Json::decode($this->getData('evaluate-data'));
        if (empty($evaluateData)) {
            throw new NotFoundHttpException('页面未找到！');
        }
        //获取评鉴申请数据
        $evaluateApplyData = Json::decode($this->getData('evaluate-apply-data'));
        return $this->render('view', [
            'evaluateData' => $evaluateData,
            'evaluateApplyData' => $evaluateApplyData,
        ]);
    }

    /**
     * 删除主表判断
     */
    public function actionDeleteMainJudge()
    {
        return $this->getData('delete-main-judge');
    }

    /**
     * 删除主表数据
     */
    public function actionDeleteMain()
    {
        $data = json_decode($this->getData('delete-main'));
        if ($data->status == 1) {
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    /**
     * 删除子表判断
     */
    public function actionDeleteChildJudge()
    {
        return $this->getData('delete-child-judge');
    }

    /**
     * 删除子表数据
     */
    public function actionDeleteChild()
    {
        $data = json_decode($this->getData('delete-child'));
        if ($data->status == 1) {
            return Json::encode(["msg" => "刪除成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "刪除失败", "flag" => 0]);
        }
    }

    /**
     * 通过url获取数据
     */
    protected function getData($action,$str=null)
    {
        $url = $this->findApiUrl() . $this->_url . $action;
        $queryParam = Yii::$app->request->queryParams;
        if (empty($str) && !empty($queryParam))
            $url .= '?' . http_build_query($queryParam);
        if (!empty($str) && empty($queryParam))
            $url .= '?' . $str;
        if (!empty($str) && !empty($queryParam)) {
            $url .= '?' . $str;
            $url .= '&' . http_build_query($queryParam);
        }
        return $this->findCurl()->get($url);
    }

    /**
     * 通过url发送数据
     */
    protected function sendData($action,$data)
    {
        $url = $this->findApiUrl() . $this->_url . $action;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= '?' . http_build_query($queryParam);
        }
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        return $curl->post($url);
    }
}