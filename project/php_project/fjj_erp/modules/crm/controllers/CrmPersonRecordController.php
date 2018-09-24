<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/6/30
 * Time: 14:06
 */
namespace app\modules\crm\controllers;

use app\controllers\BaseController;
use app\models\User;
use yii;
use yii\helpers\Json;

class CrmPersonRecordController extends BaseController
{
    private $_url = "crm/crm-person-record/";  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        $isSuper = User::isSupper(Yii::$app->user->identity->user_id) == true ? '1' : '0';
        $queryParam['isSuper'] = $isSuper;
        $queryParam['user_id'] = Yii::$app->user->identity->user_id;
//        $queryParam['user_id']=
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url)); //
//        dumpE($dataProvider);
        foreach ($dataProvider['rows'] as $key => $val) {


            if ($val['p_level'] == '1') {
                $dataProvider['rows'][$key]['section'] = $val['p_oname'];
                $dataProvider['rows'][$key]['organization_name'] = $val['a_name'];
                $dataProvider['rows'][$key]['center'] = $val['b_name'];
            }
            if ($val['p_level'] == '2') {
                $dataProvider['rows'][$key]['section'] = '';
                $dataProvider['rows'][$key]['organization_name'] = $val['p_oname'];
                $dataProvider['rows'][$key]['center'] = $val['a_name'];
            }
            if ($val['p_level'] == '3') {
                $dataProvider['rows'][$key]['section'] = '';
                $dataProvider['rows'][$key]['organization_name'] = '';
                $dataProvider['rows'][$key]['center'] = $val['p_oname'];
            }
            if ($val['svp_status'] != null) {
                if ($val['svp_status'] == '10') {
                    $dataProvider['rows'][$key]['svp_status'] = '待实施';
                }
                if ($val['svp_status'] == '20') {
                    $dataProvider['rows'][$key]['svp_status'] = '已实施';
                }
                if ($val['svp_status'] == '30') {
                    $dataProvider['rows'][$key]['svp_status'] = '已取消';
                }
                if ($val['svp_status'] == '40') {
                    $dataProvider['rows'][$key]['svp_status'] = '实施中';
                }
                if ($val['svp_status'] == '50') {
                    $dataProvider['rows'][$key]['svp_status'] = '已终止';
                }
                if ($val['svp_status'] == '60') {
                    $dataProvider['rows'][$key]['svp_status'] = '已结束';
                }
            }
            //单独查出拜访记录陪同人


        }
        $dataProvider = Json::encode($dataProvider);
        $export = Yii::$app->request->get('export');
        if (isset($export)) {
            $this->exportFiled(Json::decode($dataProvider)['rows']);
        }
        $downList = $this->getDownList();
        $columns = $this->getField('/crm/crm-person-record/index');
//        dumpJ($dataProvider);
        return $this->render('index', [
                'queryParam' => $queryParam,
                'dataProvider' => $dataProvider,
                'downList' => $downList,
                'columns' => $columns
            ]
        );
    }

    public function getDownList()
    {
        $id = Yii::$app->user->identity->staff->staff_id;
//        dumpJ($id);

        $url = $this->findApiUrl() . $this->_url . "down-list?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }
}