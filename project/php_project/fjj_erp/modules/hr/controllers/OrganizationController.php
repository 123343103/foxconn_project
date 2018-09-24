<?php
/**
 * 组织机构控制器
 * User: F3859386
 * Date: 2016/9/12
 * Time: 上午 11:38
 */
namespace app\modules\hr\controllers;

use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


class OrganizationController extends BaseController
{

    private $_url = "hr/organization/";  //对应api控制器URL

    /**
     * Lists all Organization models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . "index";
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $this->render('index', [
            'tree' => $dataProvider
        ]);
    }


    /**
     * 創建.
     */
    public function actionCreate()
    {
        if ($postData = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('组织列表新增:' . $data['msg']);
                return Json::encode(['msg' => "新增成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => $data['msg'], "flag" => 0]);
            }
        }
        $this->layout = '@app/views/layouts/ajax';
        $id = Yii::$app->request->get('pid');
        //上级信息
        $models = $this->findModel($id);
        $model['organization_pid'] = $models['organization_id'];
        if ($models['organization_pid'] !== 0) {
            $model['organization_level'] = $models['organization_level'] - 1;
        } else {
            $model['organization_level'] = 4;
        }
        $model['is_abandoned'] = 0;
        $downList = $this->getDownList();
        return $this->render('create', [
            'downList' => $downList,
            'model' => $model,
        ]);
    }

    /**
     * 更新.
     */
    public function actionUpdate($id)
    {
        $this->layout = '@app/views/layouts/ajax';
        if ($postData = Yii::$app->request->post()) {
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
            if ($data['status']) {
                SystemLog::addLog('组织列表修改:' . $data['msg']);
                return Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        }
        $model = $this->findModel($id);
        $downList = $this->getDownList();
        return $this->render('update', [
            'downList' => $downList,
            'model' => $model,
        ]);
    }

    /**
     * 刪除.
     */
    public function actionDelete($id)
    {
        $url = $this->findApiUrl() . $this->_url . 'delete?id=' . $id;
        $data = Json::decode($this->findCurl()->DELETE($url));
        if ($data['status']) {
            SystemLog::addLog('组织列表删除:' . $data['msg']);
            return Json::encode(['msg' => "删除成功", "flag" => 1]);
        } else {
            return Json::encode(['msg' => "删除失败", "flag" => 0]);
        }
    }

    /**
     * 导出会员模板
     */
    public function actionDownTemplate()
    {
        $objPHPExcel = new \PHPExcel();
        $field = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($field as $key => $value) {
            //宽度
            $objPHPExcel->getActiveSheet()->getColumnDimension($value)->setWidth(15);
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($value)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($value)->getFont()->setName('宋体');
        }
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '工号')
            ->setCellValue('C1', '姓名')
            ->setCellValue('D1', '部门')
            ->setCellValue('E1', '资位')
            ->setCellValue('F1', '管理职')
            ->setCellValue('G1', '入厂日期')
            ->setCellValue('H1', '手机');
//        foreach ($data as $key => $val) {
//            $num = $key + 2;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', '1')
            ->setCellValue('B2', 'XXX')
            ->setCellValue('C2', 'XXX')
            ->setCellValue('D2', 'XXX')
            ->setCellValue('E2', 'XX')
            ->setCellValue('F2', 'XXX')
            ->setCellValue('G2', '2017-10-10')
            ->setCellValue('H2', '13699999999');
//        }
        $fileName = "sale_customer.xlsx";
        // 创建PHPExcel对象，注意，不能少了\
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        ob_clean();
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    protected function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "down-list";
        $dataProvider = Json::decode($this->findCurl()->get($url));
        return $dataProvider;
    }

    protected function findModel($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    //更具部门名称查询部门id
    public function actionGetIdByName($name)
    {
        $url = $this->findApiUrl() . $this->_url . 'get-id-by-name?name=' . $name;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model['organization_id'];
        } else {
            return 0;
        }
    }
}
