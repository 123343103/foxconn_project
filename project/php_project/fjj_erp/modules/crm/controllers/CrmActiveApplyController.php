<?php
/**
 * User: F1677929
 * Date: 2017/2/10
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseController;
use app\models\UploadForm;
use app\modules\common\tools\ExcelToArr;
use app\modules\common\tools\SendMail;
use app\modules\system\models\SystemLog;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use Yii;
//活动报名控制器
class CrmActiveApplyController extends BaseController
{
    //属性
    public $_url="crm/crm-active-apply/";

    //所有操作执行之前执行
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/crm/crm-active-apply/get-district-by-base",
            "/crm/crm-active-apply/import",
            "/crm/crm-active-apply/send-message",
            "/crm/crm-active-apply/validate",
        ]);
        return parent::beforeAction($action);
    }

    //活动报名列表页
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'crm/crm-active-apply/index?userId='.Yii::$app->user->identity->user_id;
        if(Yii::$app->request->isAjax){
            $url.='&companyId='.Yii::$app->user->identity->company_id;
            $url.='&'.http_build_query($params);
            $dataProvider=Json::decode($this->findCurl()->get($url));
            if(!empty($dataProvider['rows'])){
                foreach($dataProvider['rows'] as &$val){
                    $val['acth_code']="<a onclick='window.location.href=\"".Url::to(['view','id'=>$val['acth_id']])."\";event.stopPropagation();'>".$val['acth_code']."</a>";
                }
            }
            return Json::encode($dataProvider);
        }
        $data=Json::decode($this->findCurl()->get($url));
        $data['applyTable']=$this->getField('/crm/crm-active-apply/index');
        return $this->render('index',['params'=>$params,'data'=>$data]);
    }

    //加载签到信息
    public function actionCheckInInfo()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'crm/crm-active-apply/check-in-info';
        $url.='?'.http_build_query($params);
        return $this->findCurl()->get($url);
    }

    //加载缴费信息
    public function actionPayInfo()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'crm/crm-active-apply/pay-info';
        $url.='?'.http_build_query($params);
        return $this->findCurl()->get($url);
    }

    //加载通讯记录
    public function actionMessageInfo()
    {
        $params=Yii::$app->request->queryParams;
        $url=$this->findApiUrl().'crm/crm-active-apply/message-info';
        $url.='?'.http_build_query($params);
        return $this->findCurl()->get($url);
    }

    //新增活动报名
    public function actionAdd()
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/add';
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            if(empty($data['CrmCustomerInfo']['cust_id'])){
                $data['CrmCustomerInfo']['create_by']=Yii::$app->user->identity->staff_id;
                $data['CrmCustomerInfo']['company_id']=Yii::$app->user->identity->company_id;
            }else{
                $data['CrmCustomerInfo']['update_by']=Yii::$app->user->identity->staff_id;
            }
            $data['CrmActiveApply']['create_by']=Yii::$app->user->identity->staff_id;
            $data['CrmActiveApply']['company_id']=Yii::$app->user->identity->company_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增活动报名'.$result['msg']);
                return Json::encode(['msg'=>'新增成功！','flag'=>1,'url'=>Url::to(['view','id'=>$result['data']])]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->render('add',['data'=>$data]);
    }

    //获取活动名称
    public function actionGetActiveName($typeId)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/get-active-name?typeId='.$typeId;
        return $this->findCurl()->get($url);
    }

    //获取活动时间
    public function actionGetActiveTime($nameId)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/get-active-time?nameId='.$nameId;
        return $this->findCurl()->get($url);
    }

    //获取客户信息
    public function actionGetCustomerInfo($name)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/get-customer-info?name='.$name;
        return $this->findCurl()->get($url);
    }

    //修改活动报名
    public function actionEdit($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/edit?id='.$id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmCustomerInfo']['update_by']=Yii::$app->user->identity->staff_id;
            $data['CrmActiveApply']['update_by']=Yii::$app->user->identity->staff_id;
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('修改活动报名'.$result['msg']);
                return Json::encode(['msg'=>'修改成功！','flag'=>1,'url'=>Url::to(['view','id'=>$id])]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data['editData'])){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('edit',['data'=>$data]);
    }

    //查看活动报名
    public function actionView($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/view?id='.$id;
        $data=Json::decode($this->findCurl()->get($url));
        if(empty($data)){
            throw new NotFoundHttpException('页面未找到！');
        }
        return $this->render('view',['data'=>$data]);
    }

    //删除活动报名
    public function actionDeleteApply($arrId)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/delete-apply?arrId='.$arrId;
        $result=Json::decode($this->findCurl()->get($url));
        if($result['status']==1){
            SystemLog::addLog('删除活动报名');
            return Json::encode(['msg'=>'删除成功！','flag'=>1]);
        }
        return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
    }

    //签到
    public function actionCheckIn($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/check-in?id='.$id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                if(!empty($result['msg'])){
                    SystemLog::addLog('新增签到'.$result['msg']);
                }
                return Json::encode(['msg'=>'签到成功！','flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('check-in',['data'=>$data]);
    }

    //缴费
    public function actionPay($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/pay?id='.$id;
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data));
            $result=Json::decode($curl->post($url));
            if($result['status']==1){
                SystemLog::addLog('新增缴费'.$result['msg']);
                return Json::encode(['msg'=>'缴费成功！','flag'=>1]);
            }
            return Json::encode(['msg'=>$result['msg'],'flag'=>0]);
        }
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax('pay',['data'=>$data]);
    }

    //导出
    public function actionExport()
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/export?companyId='.Yii::$app->user->identity->company_id.'&'.http_build_query(Yii::$app->request->queryParams);
        $data=Json::decode($this->findCurl()->get($url));
        $objPHPExcel = new \PHPExcel();
        $sheet = $objPHPExcel->getActiveSheet();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '序号')
            ->setCellValue('B1', '开始时间')
            ->setCellValue('C1', '结束时间')
            ->setCellValue('D1', '活动类型')
            ->setCellValue('E1', '活动名称')
            ->setCellValue('F1', '公司名称')
            ->setCellValue('G1', '报名人姓名')
            ->setCellValue('H1', '报名日期')
            ->setCellValue('I1', '职位')
            ->setCellValue('J1', '手机号码')
            ->setCellValue('K1', '邮箱')
            ->setCellValue('L1', '参会身份')
            ->setCellValue('M1', '用餐信息')
            ->setCellValue('N1', '是否要缴费')
            ->setCellValue('O1', '是否已缴费')
            ->setCellValue('P1', '是否开票')
            ->setCellValue('Q1', '签到信息');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $num, $num - 1)
                ->setCellValue('B' . $num, Html::decode($val['actbs_start_time']))
                ->setCellValue('C' . $num, Html::decode($val['actbs_end_time']))
                ->setCellValue('D' . $num, Html::decode($val['acttype_name']))
                ->setCellValue('E' . $num, Html::decode($val['actbs_name']))
                ->setCellValue('F' . $num, Html::decode($val['cust_sname']))
                ->setCellValue('G' . $num, Html::decode($val['acth_name']))
                ->setCellValue('H' . $num, Html::decode($val['acth_date']))
                ->setCellValue('I' . $num, Html::decode($val['acth_position']))
                ->setCellValue('J' . $num, Html::decode($val['acth_phone']))
                ->setCellValue('K' . $num, Html::decode($val['acth_email']))
                ->setCellValue('L' . $num, Html::decode($val['joinIdentity']))
                ->setCellValue('M' . $num, Html::decode($val['isEat']))
                ->setCellValue('N' . $num, Html::decode($val['isPay']))
                ->setCellValue('O' . $num, Html::decode($val['isYetPay']))
                ->setCellValue('P' . $num, Html::decode($val['isBill']))
                ->setCellValue('Q' . $num, Html::decode($val['isCheckIn']));
            for ($i = A; $i !== R; $i++) {
                $sheet->getColumnDimension($i)->setWidth(25);
                $sheet->getDefaultRowDimension()->setRowHeight(18);
                $sheet->getColumnDimension($i)->setCollapsed(false);
                $sheet->getStyle($i . '1')->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . $num)->getAlignment()->setHorizontal("center");
                $sheet->getStyle($i . '1')->getFont()->setName('黑体')->setSize(14);
            }
        }
        $sheet->getColumnDimension("A")->setWidth(10);
        $sheet->getColumnDimension("F")->setWidth(50);
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

    //地址联动
    public function actionGetDistrict($id)
    {
        $url=$this->findApiUrl().'crm/crm-active-apply/get-district';
        $url.='?id='.$id;
        return $this->findCurl()->get($url);
    }
}