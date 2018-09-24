<?php
/**
 * F3859386
 */
namespace app\modules\system\controllers;

use app\controllers\BaseController;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SystemLog;
use Yii;
use app\modules\common\models\BsCompany;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\modules\common\models\BsDistrict;

/**
 * CompanyController implements the CRUD actions for BsCompany model.
 */
class CompanyController extends BaseController
{

    private $_url = "system/company/";  //对应api控制器URL
    /**
     * Lists all BsCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $this->render('index', [
            'tree'  =>$dataProvider
        ]);
    }

    /**
     * 下拉表数据
     */
    private function downList(){
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }
    /**
     * 创建
     * @return mixed
     */
    public function actionCreate($pid)
    {

        if ($postData = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('公司设置新增公司:'.$data['msg']);
                return Json::encode(['msg' => "成功新增公司", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
            $this->layout = '@app/views/layouts/ajax';
            $downListCop = $this->getDownListCop();
//        dumpE($downListCop);
            $companyStatus = array(
                ''=>'请选择...',
                '10'=>'正常',
                '20'=>'废弃',
            );
            return $this->render('create', [
                'model'=>['parent_id'=>$pid],
                'downList' => $this->downList(),
                'district' => $this->getDistrict(),
                'downListCop' => $downListCop,
                'companyStatus' => $companyStatus
            ]);
    }
    /**
     * 更新
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
//        $this->layout = '@app/views/layouts/ajax';
        if ($postData = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "update?id=".$id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('公司设置修改公司:'.$data['msg']);
                return Json::encode(['msg' => "修改完成", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }
            $model = $this->findModel($id);
            $staffInfo = $this->getStaffInfo($model['comp_cman2']);
            $this->layout = '@app/views/layouts/ajax';
            $downListCop = $this->getDownListCop();
            $companyStatus = array(
                ''=>'请选择...',
                '10'=>'正常',
                '20'=>'废弃',
            );
            $districtId2 = $model['comp_disid'];
            $districtAll2 = $this->getAllDistrict($districtId2);
            return $this->render('update', [
                'model' => $model,
                'downList' => $this->downList(),
                'district' => $this->getDistrict(),
                'downListCop' => $downListCop,
                'companyStatus' => $companyStatus,
                'districtAll2' => $districtAll2,
                'staffInfo' => $staffInfo
            ]);

    }
    public function actionView($id){
        $this->layout = '@app/views/layouts/ajax';
        $model = $this->findModel($id);
        $comName = BsCompany::find()->where(['company_id'=>$model['parent_id']])->one();//上级公司地址
        $staffInfo = $this->getStaffInfo($model['comp_cman2']);//负责人工号姓名
        $comType = BsPubdata::find()->where(['bsp_id'=>$model['comp_bustype']])->one();//公司类型
        $comDis = $model['comp_disid'];
        $address = $model['comp_addrdss'];
        $disId = BsDistrict::find()->where(['district_id'=>$comDis])->one();
        $dis1 = BsDistrict::find()->where(['district_id'=>$disId['district_pid']])->one();
        $dis2 = BsDistrict::find()->where(['district_id'=>$dis1['district_pid']])->one();
        $dis3 = BsDistrict::find()->where(['district_id'=>$dis2['district_pid']])->one();
        $dis4 = BsDistrict::find()->where(['district_id'=>$dis3['district_pid']])->one();
        $disName = $dis4['district_name'].$dis3['district_name'].$dis2['district_name'].$dis1['district_name'].$disId['district_name'];
        $comAddress = $disName.$address;//公司地址
        if ($model['company_status']==10){
            $comStatus = '正常';
        }elseif ($model['company_status']==20){
            $comStatus = '废弃';
        }else{
            $comStatus = '删除';
        }
        return $this->render('view',[
            'model' => $model,
            'comName' => $comName,
            'comStatus' => $comStatus,
            'staffInfo' => $staffInfo,
            'comType' => $comType,
            'comAddress' => $comAddress
        ]);

    }
    public function getStaffInfo($staffId){
        $staffInfo = HrStaff::find()->where(['staff_id'=>$staffId])->one();
        return $staffInfo;
    }
    /*根据地址五级查出所有信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * 删除
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . "delete?id=" . $id;
        $dataProvider = Json::decode($this->findCurl()->DELETE($url));
        if ($dataProvider['status']) {
            SystemLog::addLog('公司设置删除公司:'.$dataProvider['msg']);
            return Json::encode(['msg' => "删除完成", "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
        }
    }

    /**
     * @return mixed
     * 获取地址
     */
    public function getDistrict()
    {
        $url = $this->findApiUrl() . $this->_url . "district";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /**
     * @return mixed
     * 获取公司各种属性类型等
     */
    public function getDownListCop()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list-cop";
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    protected function findModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "model?id=".$id;
        $dataProvider = Json::decode($this->findCurl()->get($url));
        if ($dataProvider !== null) {
            return $dataProvider;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
