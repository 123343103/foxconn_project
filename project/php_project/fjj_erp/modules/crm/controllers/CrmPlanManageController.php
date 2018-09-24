<?php
/**
 * 拜访计划视图
 * User: F3859386
 * Date: 2016/12/13
 * Time: 11:19
 */

namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\system\models\SystemLog;
use Yii;
use app\modules\crm\models\CrmVisitPlan;
use app\modules\crm\models\search\CrmVisitPlanSearch;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
class CrmPlanManageController extends BaseController
{

    private $_url = "crm/crm-plan-manage/";

    public function actionIndex()
    {
        return $this->render('index');
    }


    /**
     * 添加拜访(计划,记录)
     **/
    public function actionCreateVisit()
    {
        $this->layout='@app/views/layouts/ajax';
        $get=Yii::$app->request->get();
        $date['start']=str_replace('@',' ',$get['start']);
        $date['end']=str_replace('@',' ',$get['end']);
        $time= strtotime(date("Y-m-d H:i"));
        if($time > strtotime($date['start'])){
            $date['time']=0;
        }else{
            $date['time']=1;
        }
        $supper=User::isSupper(Yii::$app->user->identity->user_id);
        return $this->render('create-visit',[
            'dateTime'=>$date,
            'downList'=>$this->downList(),
            'from'=>$get['from'],
            'staff'=>$this->getStaff(),
            'supper'=>$supper
        ]);
    }

//    /**
//     * 创建拜访记录
//     * @return string
//     */
//    public function actionCreateRecord(){
//        $url = $this->findApiUrl() . 'crm/crm-visit-record/add';
//        if (Yii::$app->request->isPost) {
//            $data = Yii::$app->request->post();
//            $data['CrmVisitRecord']['create_by'] = Yii::$app->user->identity->staff_id;
//            $data['CrmVisitRecord']['company_id'] = Yii::$app->user->identity->company_id;
//            $data['CrmVisitRecordChild']['create_by'] = Yii::$app->user->identity->staff_id;
//            $data['CrmVisitRecordChild']['company_id'] = Yii::$app->user->identity->company_id;
//            $data['CrmVisitRecordChild']['sil_time'] = serialize([$data['day'], $data['hour'], $data['minute']]);
//            $data['CrmVisitRecordChild']['title'] = '拜访' . $data['cust_name'];
//            $data['CrmVisitRecordChild']['color'] = '#7facef';
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
//            $result = Json::decode($curl->post($url));
//            if ($result['status']) {
//                if (empty($result['msg']['mainCode'])) {
//                    SystemLog::addLog('客户拜访记录子表新增,编号：' . $result['msg']['childCode']);
//                } else {
//                    SystemLog::addLog('客户拜访记录主表新增,编号：' . $result['msg']['mainCode'] . ';客户拜访记录子表新增,编号：' . $result['msg']['childCode']);
//                }
//                return Json::encode(['msg' => '新增成功！', 'flag' => 1, 'url' => Url::to(['view-record', 'childId' => $result['data']])]);
//            }
//            return Json::encode(['msg' => $result['msg'], 'flag' => 0]);
//        }
//            $post=Yii::$app->request->post();
//            $post['CrmVisitInfoChild']['sil_time'] = serialize([$post['day-1'], $post['hours-1'], $post['minutes-1']]);
//            $post['CrmVisitInfoChild']['title'] = '拜访'.$post['cust_name'];
//            $post['CrmVisitInfoChild']['start'] =  $post['arriveDate'];
//            $post['CrmVisitInfoChild']['end']   =  $post['leaveDate'];
////            $post['CrmVisitInfoChild']['color'] = '#FEE188';
//            $post['CrmVisitInfo']['create_by'] = $post['CrmVisitInfoChild']['create_by'] =Yii::$app->user->identity->staff_id;
//            $post['CrmVisitInfo']['company_id'] = $post['CrmVisitInfoChild']['company_id'] =Yii::$app->user->identity->company_id;
//            $url = $this->findApiUrl() . $this->_url . "create-info";
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
//            $data = Json::decode($curl->post($url));
//            if ($data['status']) {
//                if ($post['CrmVisitInfo']['from']=='home') {
//                    return Json::encode(['msg' => "新增拜访记录成功", "flag" => 1, "url" => Url::to(['/index/index'])]);
//                }
//                return Json::encode(['msg' => "新增拜访记录成功", "flag" => 1, "url" => Url::to(['/index'])]);
//            } else {
//                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
//            }
//    }

//    /**
//     * 新增拜访计划
//     * @return string
//     */
//    public function actionCreatePlan()
//    {
//            $post=Yii::$app->request->post();
//            $post['CrmVisitPlan']['spend_time'] = serialize([$post['day'], $post['hours'], $post['minutes']]);
//            $post['CrmVisitPlan']['title'] = '拜访'.$post['cust_name'];
//            $post['CrmVisitPlan']['start'] =  $post['startDate'];
//            $post['CrmVisitPlan']['end']   =  $post['endDate'];
////            $post['CrmVisitPlan']['color'] = '#FEE188';
//            $post['CrmVisitPlan']['create_by'] = Yii::$app->user->identity->staff_id;
//            $post['CrmVisitPlan']['company_id'] = Yii::$app->user->identity->company_id;
//            $url = $this->findApiUrl() . $this->_url . "create-plan";
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
//            $data = Json::decode($curl->post($url));
//            if ($data['status']) {
//                if ($post['CrmVisitPlan']['from']=='home') {
//                    return Json::encode(['msg' => "添加拜访计划成功", "flag" => 1, "url" => Url::to(['/index/index'])]);
//                }
//                return Json::encode(['msg' => "添加拜访计划成功", "flag" => 1, "url" => Url::to(['index'])]);
//            } else {
//                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
//            }
//    }

    /**
     * 数据来源
     * @return mixed
     */
    public function actionPlanData(){
        $url = $this->findApiUrl().$this->_url."plan-data?staff=".Yii::$app->user->identity->staff_id."&user=".Yii::$app->user->identity->user_account;
        $dataProvider = $this->findCurl()->get($url);
        return Html::decode($dataProvider);
    }
    /**
     * 首页统计日程数据源
     * @return string
     */
    public function actionPlanCount(){
        $param = Yii::$app->request->get(); // 获取fullcalendar自动传递的start和end参数以及ajax传递的staffCode
        $param['isSupper'] = !empty(Yii::$app->user->identity->is_supper) ? 1 : 0; // 可能为null 0 1
        $url = $this->findApiUrl().$this->_url."plan-count";
        $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($param));
        $dataProvider = json_decode($curl->post($url),true);
        $data = [];
        // 判断每个事件的class
        foreach ($dataProvider as $k=>$v) {
            if (date('Y-m-d',strtotime($v['start']))==date('Y-m-d')) {
                $v['className'] = 'today';
            } elseif (date('Y-m-d',strtotime($v['start']))<date('Y-m-d')) {
                $v['className'] = 'past';
            } else {
                $v['className'] = 'future';
            }
            $v['title'] = $v['title']>9 ? '9+' : $v['title'];
            $data[$k] = $v;
            $data[$k]['url'] = Url::to(['/crm/sale-visit-calendar/index']);
        }
        return json_encode($data);
    }

    /*
     *选择客户
     */
    public function actionSelectCustomer()
    {
        $params = Yii::$app->request->queryParams;
        if (Yii::$app->request->isAjax) {
            $url = $this->findApiUrl() . 'crm/crm-visit-record/select-customer';
            $url .= '?companyId=' . Yii::$app->user->identity->company_id;
            if(empty(Yii::$app->user->identity->is_supper)){
                $url .= '&staffId=' . Yii::$app->user->identity->staff_id;
            }
            $url .= '&' . http_build_query($params);
            return $this->findCurl()->get($url);
        }
        return $this->renderAjax('select-customer', ['params' => $params]);
    }

    //
    public function actionCreateCustomer()
    {
        $managerDefault = '';
        $code = '';
        if (Yii::$app->request->getIsPost()) {
            $post = Yii::$app->request->post();
            $post['CrmCustomerInfo']['company_id'] = Yii::$app->user->identity->company_id;
            $post['CrmCustomerInfo']['create_by'] = Yii::$app->user->identity->staff_id;
            $post['CrmCustomerStatus']['sale_status'] = 10;
            $post['code'] = Yii::$app->user->identity->staff->staff_code;
//            dumpE($post);
//            $url = $this->findApiUrl() ."/crm/crm-member-develop/create";
            $url = $this->findApiUrl() ."/crm/crm-customer-info/create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
                return Json::encode(['msg' => "新增客户成功", "flag" => 1]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $this->layout = '@app/views/layouts/ajax';
            $downList = $this->getDownList();
//            dumpE($downList);
            return $this->render('create-customer',[
                'downList'=>$downList,
            ]);
        }
    }

    private function getDownList(){
        $url = $this->findApiUrl() . $this->_url . "create-down-list?code=".Yii::$app->user->identity->staff->staff_code;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 登陆人信息
     * @return mixed
     */
    private function getStaff(){
        $url = $this->findApiUrl() . "/hr/staff/return-info?id=".Yii::$app->user->identity->staff_id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 客户类型等下拉菜单
     * @return mixed
     */
    private function downList(){
        $url = $this->findApiUrl().$this->_url."down-list";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}
