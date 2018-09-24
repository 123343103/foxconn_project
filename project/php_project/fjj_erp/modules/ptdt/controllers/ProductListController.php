<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/6
 * Time: 上午 10:46
 */

namespace app\modules\ptdt\controllers;
use app\controllers\BaseController;
use app\modules\common\models\BsBusinessType;
use app\modules\ptdt\models\BsProduct;
use app\widgets\ueditor\Ftp;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class ProductListController extends BaseController
{
    public $_url="ptdt/product-list/";

    //权限过滤
    public function beforeAction($action)
    {
        $this->ignorelist=array_merge($this->ignorelist,[
            "/ptdt/product-list/product-selector",
        ]);
        return parent::beforeAction($action);
    }

    public function actions(){
        return array_merge(parent::actions(),[
            'upload' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'allowRatio'=>'800*800',
                'scene'=>trim(\Yii::$app->ftpPath["PDT"]["father"],"/")."/".trim(\Yii::$app->ftpPath["PDT"]["PdtImg"],"/")
            ],
            'upload3d' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'scene'=>trim(\Yii::$app->ftpPath["PDT"]["father"],"/")."/".trim(\Yii::$app->ftpPath["PDT"]["Pdt3D"],"/")
            ],
            'uploadmrk' => [
                'class' => \app\widgets\upload\UploadAction::className(),
                'scene'=>trim(\Yii::$app->ftpPath["PDT"]["father"],"/")."/".trim(\Yii::$app->ftpPath["PDT"]["PdtMrk"],"/")
            ]
        ]);
    }

    //商品列表
    public function actionIndex(){
        $curl=$this->findCurl();
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."index?".http_build_query($params);
            $data=Json::decode($curl->get($url));
            foreach($data["rows"] as &$row){
                $row["partno_link"]=Html::a($row["part_no"],Url::to(['partno-list','id'=>$row['pdt_pkid'],'status'=>$params['status']]));
                $row["pdt_name"]=Html::a($row["pdt_name"],Url::to(['view','id'=>$row['pdt_pkid'],'status'=>$params['status']]));
                if($row["pdt_img"]){
                    $row["pdt_img"]=Html::img(\Yii::$app->ftpPath["httpIP"].$row["pdt_img"],["style"=>"width:60px;height:60px;"]);
                }else{
                    $row["pdt_img"]=Html::img(Url::to('@web/nopic.jpg'),["style"=>"width:60px;height:60px;"]);
                }
                if($row["downshelf_attachment"]){
                    $father=\Yii::$app->ftpPath["PDT"]["father"];
                    $imgDir=\Yii::$app->ftpPath["PDT"]["Off"];
                    $url=\Yii::$app->ftpPath["httpIP"].$father.$imgDir."/".substr($row["downshelf_attachment"]["file_new"],2,6)."/".$row["downshelf_attachment"]["file_new"];
                    $row["downshelf_attachment"]=Html::a($row["downshelf_attachment"]["file_old"],$url);
                }else{
                    $row["downshelf_attachment"]="";
                }
            }
            return Json::encode($data);
        }

        if(\Yii::$app->request->get('export')){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."index?".http_build_query($params);
            $data=Json::decode($curl->get($url));
            return $this->exportFiled($data["rows"]);
        }


        $columns=$this->getField();
        $options=$this->getOptions();
        return $this->render("index",["options"=>$options,"columns"=>$columns]);
    }

    //商品基本信息修改
    public function actionEdit($id,$status){
        if(\Yii::$app->request->isPost){
            $data=\Yii::$app->request->post();
            $data["opp_date"]=date("Y-m-d H:i:s");
            $data["opper"]=\Yii::$app->user->identity->staff_id;
            $data["opp_ip"]=\Yii::$app->request->userIP;
            $url=$this->findApiUrl().$this->_url."edit?id={$id}";
            $res=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($data))->post($url);
            $res=Json::decode($res);
            if($res["status"]==1){
                return Json::encode(['msg' => "修改成功", "flag" => 1,'url'=>Url::to(['edit2','id'=>$id,'status'=>$status])]);
            }
            return Json::encode(['msg' =>$res["msg"], "flag" =>0]);
        }
        $url=$this->findApiUrl().$this->_url."edit?id={$id}";
        $data=$this->findCurl()->get($url);
        $model=Json::decode($data);
        $options=$this->getOptions();
        return $this->render("edit",[
            "model"=>$model,
            "options"=>$options,
            "step"=>1
        ]);
    }


    //商品料号信息维护
    public function actionEdit2($id){
        $url=$this->findApiUrl().$this->_url."edit?id={$id}";
        $data=$this->findCurl()->get($url);
        $model=Json::decode($data);
        $options=$this->getOptions();
        return $this->render("edit2",[
            "model"=>$model,
            "options"=>$options,
            "step"=>2
        ]);
    }

    //商品详情
    public function actionView($id){
        $url=$this->findApiUrl().$this->_url."edit?id={$id}";
        $data=$this->findCurl()->get($url);
        $model=Json::decode($data);
        $options=$this->getOptions();
        return $this->render("view",[
            "model"=>$model,
            "options"=>$options
        ]);
    }

    //料号列表
    public function actionPartnoList($id){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url=$this->findApiUrl().$this->_url."partno-list?".http_build_query($params);
            $data=Json::decode($this->findCurl()->get($url));
            foreach($data["rows"] as &$row){
                if($row["downshelf_attachment"]){
                    $father=\Yii::$app->ftpPath["PDT"]["father"];
                    $imgDir=\Yii::$app->ftpPath["PDT"]["PdtImg"];
                    $url=\Yii::$app->ftpPath["httpIP"].$father.$imgDir."/".substr($row["downshelf_attachment"]["file_new"],2,6)."/".$row["downshelf_attachment"]["file_new"];
                    $row["downshelf_attachment"]=Html::a($row["downshelf_attachment"]["file_old"],$url);
                }else{
                    $row["downshelf_attachment"]="";
                }
            }
            return Json::encode($data);
        }else{
            $url=$this->findApiUrl().$this->_url."edit?id={$id}";
            $data=$this->findCurl()->get($url);
            $data=Json::decode($data);
            return $this->render("partno-list",[
                "data"=>$data,
                "options"=>$this->getOptions()
            ]);
        }
    }



    //料号详情查看ajax切换
    public function actionPartnoAjaxInfo($id){
//        $params=\Yii::$app->request->queryParams;
//        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}";
//        $data=Json::decode($this->findCurl()->get($url));
////        print_r($data);die();
//        $url=$this->findApiUrl().$this->_url."edit?id=".$data["partno"]["pdt_pkid"];
//        $pdt=Json::decode($this->findCurl()->get($url));
//        $data["pdt"]=$pdt;
//        $data["wh"]=array_filter($data["wh"],function($row){
//            return $row["selected"];
//        });
    return $this->renderAjax("_partno_view");
    }


    public  function actionPartnoInfo($id,$name){
        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}&name={$name}";
        return $this->findCurl()->get($url);
    }

    //料号信息修改ajax切换
    public function actionPartnoAjaxForm($id,$status,$type){
        $params=\Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}";
        $data=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."edit?id=".$data["partno"]["pdt_pkid"];
        $pdt=Json::decode($this->findCurl()->get($url));
        $data["pdt"]=$pdt;
        $options=$this->getOptions();
        return $this->renderAjax('_partno_form',[
            "data"=>$data,
            "status"=>$status,
            "options"=>$options,
            "type"=>$type
        ]);
    }

    //商品下架
    public function actionDownShelf($id,$partno){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            if(isset($_FILES["file"])){
                $ftp=new Ftp();
                $father=\Yii::$app->ftpPath["PDT"]["father"];
                $imgDir=\Yii::$app->ftpPath["PDT"]["Off"];
                $fullDir=$father.$imgDir."/".date("ymd");
                $ftp->ftp_dir_exists($fullDir) || $ftp->mkdirs($fullDir);
                $newName=date("Ymd").rand(1000000000,9999999999).".".pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION);
                $dest=$fullDir."/".$newName;
                $tmpFile=\Yii::$app->getRuntimePath()."/"."tmpfile".time();
                $tmpFile=str_replace("\\","/",$tmpFile);
                if(move_uploaded_file($_FILES["file"]["tmp_name"],$tmpFile) && $ftp->put($dest,$tmpFile)){
                    $params["BsPartno"]["file_new"]=$newName;
                    $params["BsPartno"]["file_old"]=$_FILES["file"]["name"];
                }else{
                    throw new \Exception($dest);
                }
                @unlink($tmpFile);
            }
            $url=$this->findApiUrl().$this->_url."down-shelf?id={$id}";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                return Json::encode(['msg' => "修改成功",'l_prt_pkid'=>$data['msg']['l_prt_pkid'], "flag" => 1]);
            }
            return Json::encode(['msg' =>$data["msg"], "flag" => 0]);
        }else{
            $url=$this->findApiUrl().$this->_url."down-shelf?id={$id}";
            $data=Json::decode($this->findCurl()->get($url));
            return $this->renderAjax("down-shelf",[
                "data"=>$data,
            ]);
        }
    }


    public function actionGetNextLevel($id="",$prompt=1){
        $url=$this->findApiUrl().$this->_url."get-next-level?id={$id}";
        $data=$this->findCurl()->get($url);
        $data=Json::decode($data);
        if($prompt){
            return Html::renderSelectOptions("",$data,$options=["prompt"=>"请选择"]);
        }
        return Html::renderSelectOptions("",$data);
    }

    //编辑商品
    public function actionEditPartno($id)
    {
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $params["BsPartno"]["opp_date"]=date("Y-m-d");
            $params["BsPartno"]["opper"]=\Yii::$app->user->identity->staff_id;
            $params["BsPartno"]["opp_ip"]=\Yii::$app->request->userIP;
            $url=$this->findApiUrl().$this->_url."edit-partno?id={$id}";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                return Json::encode(['msg' => "修改成功",'l_prt_pkid'=>$data['msg']['l_prt_pkid'],"flag" => 1,'url'=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg' =>$data, "flag" => 0]);
            }
        }
        $params=\Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}";
        $data=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."edit?id=".$data["partno"]["pdt_pkid"];
        $pdt=Json::decode($this->findCurl()->get($url));
        $data["pdt"]=$pdt;
        $options=$this->getOptions();
        return $this->render('edit-partno',[
            "data"=>$data,
            "options"=>$options
        ]);
    }



    //重新上架
    public function actionRedoUpshelf($id){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."redo-upshelf?id={$id}";
            $curl=$this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params));
            $data=Json::decode($curl->post($url));
            if($data["status"]==1){
                return Json::encode(['msg' => "保存成功",'l_prt_pkid'=>$data['msg']['l_prt_pkid'],"flag" => 1,'url'=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg' =>$data, "flag" => 0]);
            }
        }else{
            $url=$this->findApiUrl().$this->_url."redo-upshelf?id={$id}";
            $data=Json::decode($this->findCurl()->get($url));
            if($data["status"]==1){
                return Json::encode(['msg' => "保存成功",'l_prt_pkid'=>$data['msg']['l_prt_pkid'],"flag" => 1,'url'=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg' =>$data, "flag" => 0]);
            }
        }
    }


    //送审
    public function actionReviewer($type, $id, $url = null)
    {
        if ($post = \Yii::$app->request->post()) {
            $post['id'] = $id;    //单据ID
            $post['type'] = $type;  //审核流类型
            $post['staff'] = \Yii::$app->user->identity->staff_id;//送审人ID
            $verifyUrl = $this->findApiUrl() . "system/verify-record/verify-record";
            $curl = $this->findCurl()->setOption(CURLOPT_POSTFIELDS, http_build_query($post));
            $data = Json::decode($curl->post($verifyUrl));
            if ($data['status']) {
                if (!empty($url)) {
                    return Json::encode(['msg' => "提交审核成功！", "flag" => 1, "url" => $url]);
                } else {
                    return Json::encode(['msg' => "提交审核成功！", "flag" => 1]);
                }
            } else {
                return Json::encode(['msg' => $data['msg'] . ' 送审失败！', "flag" => 0]);
            }
        }
        $urls = $this->findApiUrl() ."system/verify-record/reviewer?type=" . $type . '&staff_id=' . \Yii::$app->user->identity->staff_id;
        $review = Json::decode($this->findCurl()->get($urls));
        return $this->renderAjax('reviewer', [
            'review' => $review,
        ]);
    }

    public function actionCheckInfo($id){
        $params=\Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}";
        $data=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."edit?id=".$data["partno"]["pdt_pkid"];
        $pdt=Json::decode($this->findCurl()->get($url));
        $data["pdt"]=$pdt;
        $options=$this->getOptions();
        $url=$this->findApiUrl().$this->_url."get-verify-pk?id=".$data["partno"]["l_prt_pkid"];
        $verifyId=Json::decode($this->findCurl()->get($url));
        return $this->render("check-info",[
            "data"=>$data,
            "options"=>$options,
            "verifyId"=>$verifyId
        ]);
    }


    public function actionCheckInfoPopup($id){
        $params=\Yii::$app->request->queryParams;
        $url=$this->findApiUrl().$this->_url."partno-info?id={$id}";
        $data=Json::decode($this->findCurl()->get($url));
        $url=$this->findApiUrl().$this->_url."edit?id=".$data["partno"]["pdt_pkid"];
        $pdt=Json::decode($this->findCurl()->get($url));
        $data["pdt"]=$pdt;
        $options=$this->getOptions();
        $url=$this->findApiUrl().$this->_url."get-verify-pk?id={$id}";
        $verifyId=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax("_check_info",[
            "data"=>$data,
            "options"=>$options,
            "verifyId"=>$verifyId
        ]);
    }

    //查看价格
    public function actionViewPrice($id){
        $url=$this->findApiUrl().$this->_url."price?id={$id}";
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax("_view_price",["data"=>$data]);
    }


    //修改价格
    public function actionModifyPrice($id){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $url=$this->findApiUrl().$this->_url."price?id={$id}";
            $res=Json::decode($this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($params))->post($url));
            if($res["status"]==1){
                return Json::encode(['msg' => "修改成功", "flag" => 1]);
            }
            return Json::encode(['msg' => $res["msg"], "flag" => 0]);

        }
        $url=$this->findApiUrl().$this->_url."price?id={$id}";
        $data=Json::decode($this->findCurl()->get($url));
        return $this->renderAjax("_form_price",[
            "data"=>$data,
            "options"=>$this->getOptions()
        ]);
    }

    public function actionGetBusType($code=""){
        $url = $this->findApiUrl() . $this->_url . "get-bus-type?code={$code}";
        return $this->findCurl()->get($url);
    }
    //导出
    public function actionExport(){
        $params=\Yii::$app->request->queryParams;
        $url = $this->findApiUrl() . $this->_url . "index?export=1&".http_build_query($params);
        $dataProvider=$this->findCurl()->get($url);
        $data=Json::decode($dataProvider);
        \Yii::$app->controller->action->id = 'index';
        $data=array_map(function($row){
            $row["partno_link"]=$row["part_no"];
            if($row["pdt_img"]){
                $row["pdt_img"]=\Yii::$app->ftpPath["httpIP"].$row["pdt_img"];
            }else{
                $row["pdt_img"]=Url::to('@web/nopic.jpg',true);
            }
            if($row["downshelf_attachment"]){
                $row["downshelf_attachment"]=$row["downshelf_attachment"]["file_old"];
            }else{
                $row["downshelf_attachment"]="";
            }
            return $row;
        },$data["rows"]);

        $filed = '';
        $filedVal = '';
        $filedTitle = 'A';
        $fieldIndex = 1;
        $fieldArr = [];
        $objPHPExcel = new \PHPExcel();
        $columns = $this->getField(null, true, true);
        $number = [['field_field' => true, 'field_title' => '序号']];
        $columns = array_merge($number, $columns);
        switch ($params["status"]){
            case "selling":
                $columns=array_merge($columns,[
                    ['field_field' =>'upshelf_person', 'field_title' => '上架人员'],
                    ['field_field' =>'upshelf_date', 'field_title' => '上架时间'],
                ]);
                break;
            case "notupshelf":
                $columns=array_merge($columns,[
                    ['field_field' =>'upshelf_person', 'field_title' => '上架人员'],
                    ['field_field' =>'create_date', 'field_title' => '创建时间'],
                ]);
                break;
            case "checking":
                $columns=array_merge($columns,[
                    ['field_field' =>'upshelf_person', 'field_title' => '上架人员'],
                    ['field_field' =>'check_date', 'field_title' => '提交时间'],
                ]);
                break;
            case "downshelf":
                $columns=array_merge($columns,[
                    ['field_field' =>'downshelf_reason', 'field_title' => '下架原因'],
                    ['field_field' =>'downshelf_date', 'field_title' => '下架时间'],
                    ['field_field' =>'downshelf_attachment', 'field_title' => '附件']
                ]);
                break;
        }


        $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(50);
        $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
        $excelIndex = $objPHPExcel->setActiveSheetIndex(0);
        //获取列
        foreach ($columns as $key => $value) {
            if ($fieldIndex > 24) {
                $fieldIndex = 1;
            }
            //宽度
            //标题垂直居中
            $objPHPExcel->getActiveSheet()->getStyle($filedTitle)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excelIndex->setCellValue($filedTitle . $fieldIndex,$value['field_title']);
            $filedTitle++;
            $fieldArr[$key] = $value['field_field'];
        }
        $filedTitle = 'A';
        foreach ($data as $key => $val) {
            $num = $key + 2;
            foreach ($fieldArr as $v) {
                $field_val = htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode(htmlspecialchars_decode($val[$v]))));
                if ($v === true) {
                    $field_val = $key + 1;
                }
                if($v==="pdt_img"){
                    $img=new \PHPExcel_Worksheet_MemoryDrawing();
                    $newImage=imagecreatetruecolor(50,50);
                    $image=imagecreatefromstring(file_get_contents($val["pdt_img"]));
                    imagecopyresampled($newImage,$image,0,0,0,0,50,50,imagesx($image),imagesy($image));
                    $img->setImageResource($newImage);
                    $img->setWidthAndHeight(50,50);
                    $img->setOffsetX(80);
                    $img->setOffsetY(10);
                    $img->setRenderingFunction(\PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT);//渲染方法
                    $img->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                    $img->setCoordinates($filedTitle . $num);
                    $img->setWorksheet($excelIndex);
                }else{
                    $excelIndex->setCellValue($filedTitle . $num,$field_val);
                }
                $objPHPExcel->getActiveSheet()->getStyle($filedTitle . $num)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER)->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $filedTitle++;
            }
            $filedTitle = 'A';
            Html::decode($filedVal);
            $filedVal = '';
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


    public function actionProductSelector(){
        if(\Yii::$app->request->isAjax){
            $params=\Yii::$app->request->queryParams;
            $url = $this->findApiUrl() . $this->_url . "product-selector?".http_build_query($params);
            return $this->findCurl()->get($url);
        }else{
            return $this->renderAjax("product-selector");
        }
    }

    private function getOptions(){
        $url=$this->findApiUrl().$this->_url."options";
        $curl=$this->findCurl();
        return Json::decode($curl->get($url));
    }
}