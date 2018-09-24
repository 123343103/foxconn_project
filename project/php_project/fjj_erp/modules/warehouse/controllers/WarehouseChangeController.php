<?php
/**
 * User: F1676624
 * Date: 2017/7/22
 * 仓库异动控制器
 */

namespace app\modules\warehouse\controllers;

use app\controllers\BaseController;
use app\modules\hr\models\HrOrganization;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;


class WarehouseChangeController extends BaseController
{
    private $_url = 'warehouse/warehouse-change/';  //对应api控制器URL

    public function actionIndex()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $queryParam = Yii::$app->request->queryParams;
        if (!empty($queryParam)) {
            $url .= "?" . http_build_query($queryParam);
        }
        $dataProvider = $this->findCurl()->get($url);
        if (Yii::$app->request->isAjax) { //如果是分页获取数据则直接返回数据
            $data = Json::decode($dataProvider);
            foreach ($data["rows"] as &$item) {
                //给报废单号加一个a标签
                $item["chh_code"] = Html::a($item["chh_code"], ['view', 'id' => $item['chh_id']], ['class' => 'viewitem']);
            }
            return Json::encode($data);
        }
        $downList = $this->getDownList();
        $whname=$this->actionWhname();//获取该用户下有权限仓库
        if (\Yii::$app->request->get('export')) {
            $this->getField(Json::decode($dataProvider)['rows']);
        }
//        dumpE($downList);
        return $this->render('index', [
            'model' => Json::decode($dataProvider),
            'downlist' => $downList,
            'get' => $queryParam,
            'whname'=>$whname
        ]);
    }

    /**
     * 根据登录用户获取仓库的权限
     */
    public function actionWhname()
    {
        $url = $this->findApiUrl() ."warehouse/other-out-stock/get-wh-jurisdiction?staff_id=".Yii::$app->user->identity->staff_id;
        return Json::decode($this->findCurl()->get($url));
    }

    /**
     * 新增报废申请
     */
    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $postData['InvChangeh']['create_at'] = date('Y-m-d');
            $postData['InvChangeh']['create_by'] = Yii::$app->user->identity->staff_id;
            $postData['InvChangeh']['chh_status'] = 10;
            $postData['InvChangeh']['comp_id'] = Yii::$app->user->identity->company_id;
            $isApply = Yii::$app->request->get('is_apply');
//            dumpE($postData);
            $url = $this->findApiUrl() . $this->_url . "create";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->post($url));
//            dumpE($data);
            if ($data['status'] == 1) {
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id'], 'is_apply' => 1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $whname=$this->actionWhname();//获取该用户下有权限仓库
            $downList = $this->getDownList();
//            dumpE($downList);
            return $this->render("create", [
                'downList' => $downList,
                'whname'=>$whname
            ]);
        }
    }

    /**
     * @param $id
     * 编辑报废申请
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->request->post()) {
            $postData = Yii::$app->request->post();
            $isApply = Yii::$app->request->get('is_apply');
            $url = $this->findApiUrl() . $this->_url . "update?id=" . $id;
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($postData));
            $data = Json::decode($curl->put($url));
            if ($data['status'] == 1) {
                if (!empty($isApply)) {
                    return Json::encode(['msg' => "保存成功，点击确定完成申请", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id'], 'is_apply' => 1])]);
                } else {
                    return Json::encode(['msg' => "保存成功", "flag" => 1, "url" => Url::to(['view', 'id' => $data['data']['id']])]);
                }
            } else {
                return Json::encode(['msg' => "发生未知错误，新增失败", "flag" => 0]);
            }
        } else {
            $invChangeInfoLH = $this->getModelH($id);//主表
            $invChangeInfoL = $this->getModelL($id);//子表
            $downList = $this->getDownList();
            $whname=$this->actionWhname();//获取该用户下有权限仓库
//            dumpE($invChangeInfoL);
            return $this->render("update", [
                'invChangeInfoLH' => $invChangeInfoLH,
                'invChangeInfoL' => $invChangeInfoL,
                'downList' => $downList,
                'whname'=>$whname
            ]);
        }
    }

    /**
     * @param $id
     * 查看
     */
    public function actionView($id)
    {
        $urlH = $this->findApiUrl() . $this->_url . "model?id=" . $id;
        $modelH = Json::decode($this->findCurl()->get($urlH));
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        $isApply = Yii::$app->request->get('is_apply');
//        dumpE($modelH);
        $verify=$this->getVerify($id,$modelH['chh_type']);//查看審核狀態
        if ($model) {
            return $this->render("view", [
                "model" => $model,
                'isApply' => $isApply,
                'modelH' => $modelH,
                'verify'=>$verify
            ]);
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    //查看审核状态
    public function getVerify($id,$type){
        $url = $this->findApiUrl() . "/system/verify-record/find-verify?id=" . $id."&type=".$type;
        $model = Json::decode($this->findCurl()->get($url));
        return $model;
    }

    /**
     * @return string
     */
    public function actionDelete()
    {

    }

    public function actionSelectProduct()
    {
        $params = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . '/warehouse/inv-changeh/select-product';
        $downList = $this->getDownList();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
//        dumpE($params);
        return $this->renderAjax('select-product', ['params' => $params, 'downList' => $downList]);
    }

    public function actionSelectStore()
    {
        $params = Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . 'select-store';
        $downList = $this->getDownList();
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        if (Yii::$app->request->isAjax) {
            return $this->findCurl()->get($url);
        }
//        dumpE($params);
        return $this->renderAjax('select-store', ['params' => $params, 'downList' => $downList]);
    }

    /**
     * 加载子表信息
     */
    public function actionGetProduct($id)
    {
        $url = $this->findApiUrl() . $this->_url . "get-product?id=" . $id;
//        dumpJ($this->findCurl()->get($url));
        return $this->findCurl()->get($url);
    }

    private function getModelH($id)
    {
        $url = $this->findApiUrl() . $this->_url . "model?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    private function getModelL($id)
    {
        $url = $this->findApiUrl() . $this->_url . "models?id=" . $id;
        $model = Json::decode($this->findCurl()->get($url));
        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('页面未找到');
        }
    }

    //列表导出
    public function actionExport()
    {
        $url = $this->findApiUrl() . $this->_url . 'index';
        $url .= '?' . http_build_query(Yii::$app->request->queryParams);
        $data = Json::decode($this->findCurl()->get($url))["rows"];
//        dumpE($data);
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '异动单号')
            ->setCellValue('C1', '异动类型')
            ->setCellValue('D1', '出仓名称')
            ->setCellValue('E1', '入仓名称')
            ->setCellValue('F1', '状态')
            ->setCellValue('G1', '操作人')
            ->setCellValue('H1', '操作日期')
            ->setCellValue('I1', '确认人')
            ->setCellValue('J1', '确认日期');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['chh_code']))
                ->setCellValue('C' . $num, Html::decode($val['chh_typeName']))
                ->setCellValue('D' . $num, Html::decode($val['wh_name']))
                ->setCellValue('E' . $num, Html::decode($val['wh_name2']))
                ->setCellValue('F' . $num, Html::decode($val['chh_status']))
                ->setCellValue('G' . $num, Html::decode($val['create_by']))
                ->setCellValue('H' . $num, Html::decode($val['create_at']))
                ->setCellValue('I' . $num, Html::decode($val['review_by']))
                ->setCellValue('J' . $num, Html::decode($val['review_at']));
            for ($i = A; $i !== K; $i++) {
                $sheet->getColumnDimension("A")->setWidth(10);
                $sheet->getColumnDimension("B")->setWidth(24);
                $sheet->getColumnDimension($i)->setWidth(20);
                $sheet->getDefaultRowDimension()->setRowHeight(18);
                $sheet->getColumnDimension($i)->setCollapsed(false);
                $sheet->getStyle($i . '1')->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
            }
        }
        $date = date("Y_m_d", time()) . rand(0, 99);
        $fileName = "_{$date}.xls";
        // 创建PHPExcel对象，注意，不能少了\
        $fileName = iconv("utf-8", "gb2312", $fileName);
        $objPHPExcel->setActiveSheetIndex(0);
        ob_end_clean(); // 清除缓冲区,避免乱码
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=" . $fileName);
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    //下拉数据
    public function getDownList()
    {
        $url = $this->findApiUrl() . $this->_url . "get-down-list";
        return json::decode($this->findCurl()->get($url));
    }

    //验证料号是否存在
    public function actionPdtValidate($id, $attr, $val, $scenario)
    {
        $val = urlencode($val);
        $url = $this->findApiUrl() . "ptdt/product-library/" . "validate";
        $url = $url . "?id={$id}&attr={$attr}&val={$val}&scenario={$scenario}";
        return $this->findCurl()->get($url);
    }

    //移仓入库通知
    public function actionInWare($id)
    {
        $url = $this->findApiUrl() . $this->_url . "in-ware?id=" . $id."&staffid=".Yii::$app->user->identity->staff_id;
        $result=json_decode($this->findCurl()->get($url),true);
//        dumpE($result);
        if ($result['status'] == 1) {
            return Json::encode(["msg" => "移仓入库通知成功", "flag" => 1]);
        } else {
            return Json::encode(["msg" => "插入失败", "flag" => 0]);
        }
    }

}
