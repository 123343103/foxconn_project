<?php
namespace app\modules\system\controllers;
use Yii;
use \app\controllers\BaseController;
use app\modules\crm\models\search\CrmImessageSearch;
use yii\helpers\Json;

/**
 * 操作日志控制器
 * F3858995
 * 2016/10/20
 */
class InformController extends BaseController
{
    private $_url = "system/inform/";  //对应api控制器URL
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/ajax';
        $url = $this->findApiUrl().$this->_url."index?id=".Yii::$app->user->identity->staff_id;
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "&" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) {
            return $dataProvider;
        }
//        dumpJ($dataProvider);
        return $this->render('index');
    }


    public function actionUpdate($mid){
//        return 1;
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url."update?id=" . $mid;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
//            $data = $curl->post($url);
            return $data['status'];
        }
    }

    public function actionView($id,$mid){
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->getCustomer($id);
        $reminder = $this->getReminderOne($mid);
        return $this->render('view', [
            'model' => $model,
            'reminder' => $reminder,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * 客户信息
     */
    public function getCustomer($id){
        $url = $this->findApiUrl() . "/crm/crm-member-develop/models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /*获取提醒事项*/
    public function getReminderOne($id){
        $url = $this->findApiUrl() .$this->_url."reminder-one?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}