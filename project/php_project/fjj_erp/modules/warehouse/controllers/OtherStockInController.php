<?php
/**
 * User: F1677929
 * Date: 2017/12/13
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseController;
use app\modules\system\models\SystemLog;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * 其他入库控制器
 */
class OtherStockInController extends BaseController
{
    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist = array_merge($this->ignorelist, [
            "/warehouse/other-stock-in/view",
        ]);
        return parent::beforeAction($action);
    }

    //列表
    public function actionList()
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/list';
        if(Yii::$app->request->isAjax){
            $url.='?'.http_build_query(Yii::$app->request->queryParams);
            return $this->findCurl()->get($url);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('list',['data'=>$data]);
    }

    //获取料号信息-列表、修改、详情共用
    public function actionGetProducts()
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/get-products';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //新增
    public function actionAdd()
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/add';
        if($data=Yii::$app->request->post()){
            $data['InWhpdt']['comp_id']=Yii::$app->user->identity->company_id;
            $data['InWhpdt']['create_by']=Yii::$app->user->identity->staff_id;
            $data['InWhpdt']['cdate']=date('Y-m-d');
            $data['InWhpdt']['op_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('新增其他入库单'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id']]),
                    'billId'=>$result['data']['id'],
                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $url.="?staff_id=".Yii::$app->user->identity->staff_id;
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('add',['data'=>$data]);
    }

    //修改
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/edit';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post()){
            $data['InWhpdt']['update_by']=Yii::$app->user->identity->staff_id;
            $data['InWhpdt']['udate']=date('Y-m-d');
            $data['InWhpdt']['op_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('修改其他入库单'.$result['data']['code']);
                return json_encode([
                    'msg'=>$result['msg'],
                    'flag'=>1,
                    'url'=>Url::to(['view','id'=>$result['data']['id']]),
                    'billId'=>$result['data']['id'],
                    'billTypeId'=>$result['data']['typeId']
                ]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    //获取仓库信息
    public function actionGetWarehouseInfo($id)
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/get-warehouse-info';
        $url.='?id='.$id;
        return $this->findCurl()->get($url);
    }

    //选择料号
    public function actionSelectPno()
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/select-pno';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //获取料号
    public function actionGetPno()
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/get-pno';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        return $this->findCurl()->get($url);
    }

    //详情
    public function actionView()
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/view';
        $url.='?'.http_build_query(Yii::$app->request->queryParams);
        $data=json_decode($this->findCurl()->get($url),true);
        if(empty($data)){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('view',['viewData'=>$data]);
    }

    //获取签核记录
    public function actionGetCheckRecord($billId,$billTypeId)
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/get-check-record';
        $url.='?billId='.$billId;
        $url.='&billTypeId='.$billTypeId;
        return $this->findCurl()->get($url);
    }

    //取消新增
    public function actionCancelAdd($id)
    {
        if($data=Yii::$app->request->post()){
            $url=$this->findApiUrl().'warehouse/other-stock-in/cancel-add';
            $url.='?id='.$id;
            $data['InWhpdt']['update_by']=Yii::$app->user->identity->staff_id;
            $data['InWhpdt']['udate']=date('Y-m-d');
            $data['InWhpdt']['op_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('取消上架');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $this->layout="@app/views/layouts/ajax.php";
        return $this->render('cancel-add');
    }

    //上架
    public function actionPutAway($id)
    {
        $url=$this->findApiUrl().'warehouse/other-stock-in/put-away';
        $url.='?id='.$id;
        if($data=Yii::$app->request->post()){
            $data['InWhpdt']['update_by']=Yii::$app->user->identity->staff_id;
            $data['InWhpdt']['udate']=date('Y-m-d');
            $data['InWhpdt']['op_ip']=Yii::$app->request->getUserIP();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=json_decode($curl->post($url),true);
            if($result['status']==1){
                SystemLog::addLog('其他入库上架');
                return json_encode(['msg'=>$result['msg'],'flag'=>1]);
            }
            return json_encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $this->layout="@app/views/layouts/ajax.php";
        $url.="&staff_id=".Yii::$app->user->identity->staff_id;
        $data=json_decode($this->findCurl()->get($url),true);
        return $this->render('put-away',['data'=>$data]);
    }

    //导出
    public function actionExport()
    {
        $headArr[] = [
            "序号"=>7,
            "入库单号"=>25,
            "入库单状态"=>15,
            "关联单号"=>25,
            "单据类型"=>15,
            "入仓仓库"=>15,
            "送货人"=>15,
            "收货人"=>15,
            "收货日期"=>15,
            "制单人"=>15,
            "制单时间"=>15,
        ];
        $url=$this->findApiUrl().'warehouse/other-stock-in/list';
        $url .= '?' . http_build_query(Yii::$app->request->queryParams);
        $data = json_decode($this->findCurl()->get($url), true);
        $rows = array_merge($headArr, $data['rows']);
        $objPHPExcel = new \PHPExcel();
        $column = "A";
        foreach ($rows as $key => $val) {
            foreach ($val as $k => $v) {
                if ($key == 0) {
                    $objPHPExcel->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($v);
                    $v=$k;
                }
                if($key>0 && $k==key($val)){
                    $v=$key;
                }
                if($k!="inout_flag" && $k!="inout_type"){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . ($key+1), $v);
                    $column++;
                }
            }
            $column = "A";
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="other_stock_in.xlsx"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}