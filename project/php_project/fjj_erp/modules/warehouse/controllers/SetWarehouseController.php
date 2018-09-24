<?php
/**
 * Created by PhpStorm.
 * User: F3860959
 * Date: 2017/6/12
 * Time: 上午 08:06
 */

namespace app\modules\warehouse\controllers;

use app\classes\Menu;
use app\controllers\BaseController;
use yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\warehouse\models\BsWh;

/*
 * 仓库设置控制器
 */

class SetWarehouseController extends BaseController
{
    private $_url = "warehouse/set-warehouse/";  //对应api控制器URL

    //    首页
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (\Yii::$app->request->get('export')) {
            $this->getExcelData(Json::decode($dataProvider)['rows']);
        }
        if (Yii::$app->request->isAjax) { //如果是分页获取数据则直接返回数据
            $data = Json::decode($dataProvider);
//            dumpE($data);
            if (Menu::isAction('/purchase/warehouse-apply/view')) {
                //给请购单号添加单击事件
                $dataProvider = Json::decode($dataProvider);
                if (!empty($dataProvider['rows'])) {
                    foreach ($dataProvider['rows'] as &$val) {
                        $val['wh_code'] = "<a class='wcode' onclick='window.location.href=\"" . Url::to(['view', 'id' => $val['wh_id']]) . "\";event.stopPropagation();'>" . $val['wh_code'] . "</a>";
                    }
                }
                return Json::encode($dataProvider);
            }
//            foreach ($data["rows"] as &$item) {
//                $item["awh_code"] = Html::a($item["wh_code"], ['view', 'id' => $item['wh_id']]);
////                $item["awh_code"] = Html::a($item["wh_code"], null, ['class' => 'viewitem']);
//                if ($item['wh_statew'] == '无效') {
//                    $item['wh_statew'] = '<span style="color:red;">' . $item['wh_statew'] . '</span>';
//                }
//            }
            return Json::encode($data);
        }
        $downList=$this->getDownLists();
        $fields = $this->getField("/warehouse/set-warehouse/index");
        $data['table']=$this->getField('/warehouse/set-warehouse/index');
        return $this->render('index', [
            'downlist' => $downList,
            'queryParam' => $queryParam,
            'fields'=>$fields,
            'data' => $data,
        ]);
    }

    //获取子表中数据
    public function actionLoadInfor()
    {
        $queryParam=Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "load-infor";
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {
            return Json::encode($dataProvider);
        }
        return $this->redirect('index');
    }

    public function actionSetCharacter($id)
    {
        //$this->layout = '@app/views/layouts/ajax';
        $model = $this->getModel($id);
        return $this->renderAjax("set-character", [
            'model' => $model
        ]);
    }
//    public function actionSetCharacter()
//    {
//        if (Yii::$app->request->getIsPost()) {
//            $url = $this->findApiUrl() . $this->_url . "set-character";
//            $postData = Yii::$app->request->post();
//            $postData['BsWh']['OPPER'] = Yii::$app->user->identity->staff->staff_code;//操作人
//            $postData['BsWh']['OPP_DATE'] = date('Y-m-d H:i:s', time());//操作时间
//            $postData['BsWh']['NWER'] = Yii::$app->user->identity->staff->staff_code;//创建人
//            $postData['BsWh']['NW_DATE'] = date('Y-m-d H:i:s', time());//创建时间
//            $postData['BsWh']['opp_ip'] = Yii::$app->request->getUserIP();//'//获取ip地址
//            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
//            $data = Json::decode($curl->post($url));
//            if ($data['status']) {
//                return Json::encode(['msg' => "设置状态成功", "flag" => 1, "url" => Url::to(['index'])]);
//            } else {
//                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
//            }
//        }
//        $this->layout = '@app/views/layouts/ajax';
//        $get = Yii::$app->request->get();
//        //获取地址联动信息
//        $firmDis = $this->getDistrictLevelOne();
//        $firmDisName = '';
//        foreach ($firmDis as $k => $v) {
//            $firmDisName[$v['district_id']] = $v['district_name'];
//        }
//        return $this->render('set-character', [
//                'downList' => $this->getDownList(),
//                'firmDisName' => $firmDisName]
//        );
//    }
    /**
     * 新增仓库物流
     **/
    public function actionCreateWarehouse()
    {
        if (Yii::$app->request->getIsPost()) {
            $url = $this->findApiUrl() . $this->_url . "create-warehouse";
            $postData = Yii::$app->request->post();
            $postData['BsWh']['opper'] = Yii::$app->user->identity->staff->staff_name;//操作人
            $postData['BsWh']['opp_date'] = date('Y-m-d H:i:s', time());//操作时间
            $postData['BsWh']['nwer'] = Yii::$app->user->identity->staff->staff_name;//创建人
            $postData['BsWh']['nw_date'] = date('Y-m-d H:i:s', time());//创建时间
            $postData['BsWh']['opp_ip'] = Yii::$app->request->getUserIP();//'//获取ip地址
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status']) {
                return Json::encode(['msg' => "新增仓库存储信息成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
//        $this->layout = '@app/views/layouts/ajax';
        $get = Yii::$app->request->get();
        //获取地址联动信息
        $firmDis = $this->getDistrictLevelOne();
        $firmDisName = '';
        foreach ($firmDis as $k => $v) {
            $firmDisName[$v['district_id']] = $v['district_name'];
        }
        $downList=$this->getDownLists();
        return $this->render('create-warehouse', [
                'downList' => $downList,
                'firmDisName' => $firmDisName
            ]);
    }

    //禁启用状态
    public function actionUpdateWhState($id)
    {
        $url = $this->findApiUrl() . $this->_url . "update-wh-state?id=" . $id;
        $data = $this->findCurl()->delete($url);
        if (json_decode($data)->status == 1) {
            return Json::encode(['msg' => json_decode($data)->msg, "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(['msg' => json_decode($data)->msg . "失败!", 'flag' => 0]);
        }
    }

    //批量启用禁用仓库ss
    public function actionOpenss()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "openss";
        $url .= "?" . http_build_query($queryParam);
        $res = Json::decode($this->findCurl()->delete($url), false);
//        dumpE($res);
        if ($res->status == 1) {
            return Json::encode(['msg' => "已启用", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作失败", "flag" => 0]);
        }
    }
    //批量禁用仓库
    public function actionClosess()
    {
        $queryParam = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "closess";
        $url .= "?" . http_build_query($queryParam);
        $res = Json::decode($this->findCurl()->delete($url), false);
//        dumpE($res);
        if ($res->status == 1) {
            return Json::encode(['msg' => "已禁用", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "操作失败", "flag" => 0]);
        }
    }



    /*
     * 修改仓库信息
     */
    public function actionUpdateWarehouse($id)
    {
//        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->isPost) {
            $url = $this->findApiUrl() . $this->_url . "update-warehouse?id=".$id;
            $postData = Yii::$app->request->post();
            $postData['BsWh']['opper'] = Yii::$app->user->identity->staff->staff_name;//操作人
            $postData['BsWh']['opp_date'] = date('Y-m-d H:i:s', time());//操作时间
            $postData['BsWh']['opp_ip'] = Yii::$app->request->getUserIP();//'//获取ip地址
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                return Json::encode(['msg' => "修改仓库存储信息成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $firmDis = $this->getDistrictLevelOne();
            $firmDisName = '';
            foreach ($firmDis as $k => $v) {
                $firmDisName[$v['district_id']] = $v['district_name'];
            }
            $downList=$this->getDownLists();
            $model = $this->getModel($id);
//            dumpE($model);
            $districtId2 = $model[0]['district_id'];
            $districtAll2 = $this->getAllDistrict($districtId2);
//            dumpE($districtAll2);
            return $this->render("update-warehouse", [
                'downList'=>$downList,
                'firmDisName' => $firmDisName,
                'districtAll2' => $districtAll2,
                'model' => $model,
                'id'=>$id
            ]);
        }
    }

    /*
     * 仓库详情
     */
    public function actionView($id)
    {
        $model = $this->getModel($id);
//        dumpE($model);
        return $this->render("view", [
            'model' => $model,
            'id'=>$id
        ]);
    }

    //筛选项下拉列表数据
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        $res = $this->findCurl()->get($url);
        return Json::decode($res, true);
    }

    /**
     * 获取一级地址
     * @return array|yii\db\ActiveRecord[]
     */
    public function getDistrictLevelOne()
    {
        $url = $this->findApiUrl() . $this->_url . "district-level-one";
        return Json::decode($this->findCurl()->get($url));
    }

    /*根据地址五级获取全部信息*/
    public function getAllDistrict($id)
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-all-district?id=" . $id;
        $result = Json::decode($this->findCurl()->get($url));
        return $result;
    }

    /*获取所在地区一级地址*/
    public function getDistrict()
    {
        $url = $this->findApiUrl() . "/crm/crm-customer-info/get-district";
        $result = $this->findCurl()->get($url);
        return $result;
    }

    /*
     * 根据仓库代码获取仓库信息
     */
    private function getModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException('页面未找到');
        }
    }

    /*
     * 根据用户输入的仓库代码判断仓库是否存在
     */
    /*WarehouseInfo*/
    public function actionGetWarehouseInfo($id)
    {
        $url = $this->findApiUrl() . 'warehouse/set-warehouse/get-warehouse-info?id=' . $id;
        $info = $this->findCurl()->get($url);
        if (!empty($info)) {
            //如果不为空，则表示用户输入的仓库代码已经存在
            return true;
        }
        return false;
    }

    //导出数据
    private function getExcelData($data)
    {
        // Create new PHPExcel object
        $objPHPExcel = new \PHPExcel();
        $date = date('Y-m-d-H-i-s', time()) . '_' . rand(0, 99);//加随机数，防止重名
        $fileName = '仓库列表导出信息' . "_{$date}.xls";
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);//设置J栏位（电话栏）宽度为15//
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '仓库代码')
            ->setCellValue('B1', '仓库名称')
            ->setCellValue('C1', '仓库地址')
            ->setCellValue('D1', '仓库性质')
            ->setCellValue('E1', '仓库属性')
            ->setCellValue('F1', '仓库类别')
            ->setCellValue('G1', '仓库级别')
            ->setCellValue('H1', '仓库管理员')
            ->setCellValue('I1', '邮箱')
            ->setCellValue('J1', '电话')
            ->setCellValue('K1', '状态')
            ->setCellValue('L1', '创建人')
            ->setCellValue('M1', '创建时间');
        $num = 2;
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $val['wh_code'])
                ->setCellValue('B' . $num, $val['wh_name'])
                ->setCellValue('C' . $num, $val['address'])
                ->setCellValue('D' . $num, $val['wh_naturew'])
                ->setCellValue('E' . $num, $val['wh_attrw'])
                ->setCellValue('F' . $num, $val['wh_typew'])
                ->setCellValue('G' . $num, $val['wh_levw'])
                ->setCellValue('H' . $num, $val['OPPER'])
                ->setCellValue('I' . $num, $val['hrstaff_email'])
                ->setCellValue('J' . $num, $val['hrstaff_mobile'])
                ->setCellValue('K' . $num, $val['wh_statew'])
                ->setCellValue('L' . $num, $val['NWER'])
                ->setCellValue('M' . $num, $val['NW_DATE']);
            $num++;
        }
        $fileName = iconv("utf-8", "gb2312", $fileName);
        // 重命名表
        // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    /**
     * 获取数据改为 datagrid 格式
     * @param $url
     * @return string
     */
//    public function getField($url = null, $formatter = [])
//    {
//        if ($url == null) {
//            $url = '/' . $this->getRoute();
//        }
//        $url = $this->findApiUrl() . "system/display-list/get-url-field?url=" . $url . "&user=" . Yii::$app->user->identity->user_id;
//        $childModel = Json::decode($this->findCurl()->get($url));
//        if (empty($childModel)) {
//            //返回空防止前端报错
//            return '';
//        }
//        $columns = '';
//        foreach ($childModel as $val) {
//            $columns .= "{field:'" . $val['field_field'] . "',title:'" . $val['field_title'] . "',width:" . $val['field_width'] . "},";
//        }
//        return $columns;
//    }

    public function actionWarehouseInfo()
    {
        $url = $this->findApiUrl() . $this->_url . "warehouse-info";
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
//        dumpE($dataProvider);
        if (Yii::$app->request->isAjax) {
            return Json::encode($dataProvider);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('warehouse-info',[
            'whcode'=>$queryParam
        ]);
    }

    //新增仓库获取基础数据
    function getDownLists()
    {
        $_url=$this->findApiUrl().$this->_url."down-list-wh";
        return Json::decode($this->findCurl()->get($_url));
    }

}