<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/6/9
 * Time: 上午 09:32
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\ptdt;
use yii;
use yii\helpers\Json;
use app\modules\system\models\SystemLog;
use yii\helpers\Url;
use yii\helpers\Html;

class StorageController extends BaseController
{
    private $_url = 'warehouse/storage/';

    //主页
    public function actionIndex()
    {
        $url = $this->findApiUrl() . "/warehouse/storage/index";
        if (Yii::$app->request->get('export')) {
            $dataProvider = Json::decode($this->findCurl()->get($url));
            $this->export($dataProvider['rows']);
        }
        $queryParam = Yii::$app->request->queryParams;
//        print_r($queryParam);
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = Json::decode($this->findCurl()->get($url));
        $get = Yii::$app->request->get();
        if (!isset($get['PartSearch'])) {
            $get['PartSearch'] = null;
        }
//        print_r($dataProvider['rows']);
        if (Yii::$app->request->isAjax) {
            foreach ($dataProvider['rows'] as $key => $val) {
                $dataProvider['rows'][$key]['st_code'] = '<a class="details">' . $dataProvider['rows'][$key]['st_code'] . '</a>';
                if ($dataProvider['rows'][$key]['YN'] == '禁用') {
                    $dataProvider['rows'][$key]['YNs'] = '<span>' . $dataProvider['rows'][$key]['YN'] . '</span>';
                }
                else
                {
                    $dataProvider['rows'][$key]['YNs'] =$dataProvider['rows'][$key]['YN'];
                }
            }
            return Json::encode($dataProvider);
        }

        return $this->render('index');
    }

    //添加
    public function actionAdd()
    {
        $this->layout = '@app/views/layouts/ajax';
        $id=Yii::$app->user->identity->staff_id;
        if (Yii::$app->request->getIsPost()) {
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "add";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status'] == 1) {
//                SystemLog::addLog($data['data']['msg']);
                return Json::encode(['msg' => "新增成功!", "flag" => 1, "url" => yii\helpers\Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        } else {
            $whname = $this->actionWhname();
            $name=$this->actionName($id);
            return $this->render('add', [
                'whname' => $whname,
                'name'=>$name
            ]);
        }
    }
     public function actionName($id){
         $url = $this->findApiUrl() . $this->_url . "name?id=".$id;
         return Json::decode($this->findCurl()->get($url));
     }
    //设置状态
    public function actionSetCharacter($st_id)
    {
        $views = $this->actionViewsbyid($st_id);
        if ($views['YN'] == 'Y') {
            $views['YN'] = '启用';
        } else {
            $views['YN'] = '禁用';
        }
        return $this->renderAjax('set-character', ['model' => $views]);
    }

    //禁启用状态
    public function actionUpdateStid($st_id,$staff_id)
    {
//        $views = $this->actionViewsbyid($st_id);
        $url = $this->findApiUrl() . $this->_url . "updateyn?st_id=" . $st_id."&staff_id=".$staff_id;
        $data = $this->findCurl()->delete($url);
//        print_r($data);
        if (json_decode($data)->status == 1) {
//                SystemLog::addLog('修改ID为' . $st_id . '的信息');
            return Json::encode(['msg' =>json_decode($data)->msg, "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(['msg' =>"失败!", 'flag' => 0]);
        }
    }

    //批量设置状态
    public function actionBulkEnable($id,$yn,$opper){
        $url = $this->findApiUrl() . $this->_url . "bulk-enable?id=" . $id."&yn=".$yn."&opper=".$opper;
        $data = $this->findCurl()->delete($url);
        if (json_decode($data)->status == 1) {
            return Json::encode(['msg' =>json_decode($data)->msg, "flag" => 1, "url" => Url::to(['index'])]);
        } else {
            return Json::encode(['msg' =>"失败!", 'flag' => 0]);
        }
    }

    //获取仓库信息-$id和$code不会同时有值(入)
    public function actionGetWarehouseInfo($id='',$code='')
    {
        $url=$this->findApiUrl().$this->_url.'get-warehouse-info';
        if(!empty($id)){
            $url.='?id='.$id;
        }
        if(!empty($code)){
            $url.='?code='.$code;
        }
        return $this->findCurl()->get($url);
    }

    //详情
    public function actionViews($st_id)
    {
        $this->layout = '@app/views/layouts/ajax';
        $views = $this->actionViewsbyid($st_id);
        if ($views['YN'] == 'Y') {
            $views['YN'] = '启用';
        } else {
            $views['YN'] = '禁用';
        }
        return $this->render('views', ['model' => $views]);
    }

    //修改
    public function actionUpdate($st_id)
    {
        $this->layout = '@app/views/layouts/ajax';
        if (Yii::$app->request->getIsPost()) {
            $views = $this->actionViewsbyid($st_id);
            $postData = Yii::$app->request->post();
            $url = $this->findApiUrl() . $this->_url . "update?part_code=" . $views['part_code'] . "&st_id=" . $st_id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = json_decode($curl->put($url));
            if ($data->status == 1) {
//                SystemLog::addLog('修改ID为' . $st_id . '的信息');
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "修改失败", 'flag' => 0]);
            }
        } else {
            $views = $this->actionViewsbyid($st_id);
            $whname = $this->actionWhname();
            if ($views['YN'] == 'Y') {
                $views['YN'] = '启用';
            } else {
                $views['YN'] = '禁用';
            }
//            print_r($views);
            return $this->render('update', ['model' => $views, 'whname' => $whname]);
        }
    }

    //获取仓库名称
    public function actionWhname()
    {
        $url = $this->findApiUrl() . $this->_url . "whname";
        return Json::decode($this->findCurl()->get($url));
    }

    //根据st_id获取数据
    public function actionViewsbyid($st_id)
    {
        $url = $this->findApiUrl() . $this->_url . "viewsbyid?st_id=" . $st_id;
        return Json::decode($this->findCurl()->get($url));
    }

    //获取bs_st表中数据总数量
    public function actionCounts()
    {
        $url = $this->findApiUrl() . $this->_url . "counts";
        return Json::decode($this->findCurl()->get($url));
    }

    //导出
    private function export($data)
    {
//        dumpE($data);
        $objPHPExcel = new \PHPExcel();
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '仓库名称')
            ->setCellValue('B1', '分区码')
            ->setCellValue('C1', '区位名称')
            ->setCellValue('D1', '货架码')
            ->setCellValue('E1', '儲位码')
            ->setCellValue('F1', '备注')
            ->setCellValue('G1', '状态');
        $num = 2;
        foreach ($data as $key => $val) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $val['wh_name'])
                ->setCellValue('B' . $num, $val['part_code'])
                ->setCellValue('C' . $num, $val['part_name'])
                ->setCellValue('D' . $num, $val['rack_code'])
                ->setCellValue('E' . $num, $val['st_code'])
                ->setCellValue('F' . $num, $val['remarks'])
                ->setCellValue('G' . $num, $val['YN']);
            $num++;
        }

//        foreach ($data as $key => $val) {
//            $num = $key + 2;
//            $objPHPExcel->setActiveSheetIndex(0)
//                ->setCellValue('A' . $num, $num - 1)
//                ->setCellValue('B' . $num, Html::decode($val['wh_name']))
//                ->setCellValue('C' . $num, Html::decode($val['part_code']))
//                ->setCellValue('D' . $num, Html::decode($val['part_name']))
//                ->setCellValue('E' . $num, Html::decode($val['rack_code']))
//                ->setCellValue('F' . $num, Html::decode($val['st_code']))
//                ->setCellValue('G' . $num, Html::decode($val['remarks']))
//                ->setCellValue('H' . $num, Html::decode($val['YN']));
//        }
//        dumpE($num);
        // 创建PHPExcel对象，注意，不能少了\
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();

//        $date = date("Y_m_d", time()) . rand(0, 99);
//        $fileName = "_{$date}.xls";
//        // 创建PHPExcel对象，注意，不能少了\
//        $fileName = iconv("utf-8", "gb2312", $fileName);
//        $objPHPExcel->setActiveSheetIndex(0);
//        ob_end_clean(); // 清除缓冲区,避免乱码
//        header('Content-Type: application/vnd.ms-excel');
//        header("Content-Disposition: attachment;filename=" . $fileName);
//        header('Cache-Control: max-age=0');
//
//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//        $objWriter->save('php://output'); // 文件通过浏览器下载
//        exit();
    }
}