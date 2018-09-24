<?php
/**
 * User: F1677929
 * Date: 2016/11/1
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseController;
use app\modules\ptdt\models\PdProductType;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;
/**
 * 厂商评鉴申请控制器
 */
class FirmEvaluateApplyController extends BaseController
{
    private $_url = "ptdt/firm-evaluate-apply/";

    /**
     * 厂商评鉴申请列表
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
     * 新增厂商评鉴申请
     */
    public function actionAdd($id=null)
    {
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            dumpE($postData);
            $postData['PdFirmEvaluate']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['PdFirmEvaluate']['company_id'] = Yii::$app->user->identity->company_id;
            $data = json_decode($this->sendData('add',$postData));
            if ($data->status == 1) {
                return Json::encode(['msg'=>'新增需求成功！','flag'=>1,'url'=>Url::to(['index'])]);
            } else {
                return Json::encode(['msg'=>'发生未知错误，新增失败！','flag'=>0]);
            }
        } else {
            //获取评鉴申请数据
            $evaluateApplyData = json::decode($this->getData('evaluate-apply-data','applicantId='.Yii::$app->user->identity->staff_id));
            //获取供应商数据
            $supplierData = json::decode($this->getData('supplier-data'));
            $result = '';
            if(!empty($id)){
                $result = $this->getFirm($id);
            }
            return $this->render('add', [
                'evaluateApplyData' => $evaluateApplyData,
                'supplierData' => $supplierData,
                'result' => $result
            ]);
        }
    }

    /**
     * 选择呈报通过的厂商
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
     * 添加商品
     */
    public function actionAddProduct()
    {
        $dataProvider = $this->getData('add-product');
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
        //获取商品数据
        $productData = json::decode($this->getData('product-data'));
        return $this->renderAjax('add-product',['productData'=>$productData]);
    }

    /**
     * @param $id
     * 获取厂商信息
     */
    public function getFirm($id){
        $url = $this->findApiUrl().$this->_url."find-firm?id=".$id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
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