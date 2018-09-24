<?php

namespace app\modules\ptdt\controllers;

use app\controllers\BaseController;
use yii\helpers\Json;
use yii;
use yii\helpers\Url;
/**
 * F3858995
 * 2016/10/22
 * 商品经理人控制器
 */
class PmController extends BaseController
{
    private $_url = "ptdt/pm/";
    /**
     * 列表页
     * @return string
     */
    public function actionIndex()
    {
        $url = $this->findApiUrl().$this->_url."index?".http_build_query(Yii::$app->request->queryParams);

        $dataProvider = $this->findCurl()->get($url);
        if(Yii::$app->request->isAjax){
            return $dataProvider;
        }
        $export = \Yii::$app->request->get('export');
        if (isset($export)) {
            $this->export(Json::decode($dataProvider)['rows']);
        }
        return $this->render('index',[
            "options"=>$this->getOptions("category")
        ]);

    }

    /**
     * 新增
     * @return string
     */
    public function actionAdd()
    {
        if(Yii::$app->request->isPost){
            $url = $this->findApiUrl().$this->_url."add";
            $post = Yii::$app->request->post();
            $post['PdProductManager']['create_by'] =  Yii::$app->user->identity->staff_id;
            $post['PdProductManager']['pm_level'] = 2;
            $result = $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post))->post($url);
            if( json_decode($result)->status){
                return Json::encode(['msg'=>"新增成功","flag"=>1,"url"=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg'=>"保存失败",'flag'=>0]);
            }
        }
        return $this->renderAjax('add',[
            'options'=>$this->getOptions("category,leader")
        ]);

    }

    /**
     * 修改
     * @param $id
     * @return string
     */
    public function actionEdit($id){
        if( Yii::$app->request->isPost){
            $url = $this->findApiUrl().$this->_url."edit?id=".$id;
            $post = Yii::$app->request->post();
            $post['PdProductManager']['update_by'] = Yii::$app->user->identity->staff_id;
            $result = $this->findCurl()->setOption(CURLOPT_POSTFIELDS,http_build_query($post))->put($url);

            if(json_decode($result)->status){
                return Json::encode(['msg'=>"修改成功","flag"=>1,"url"=>Url::to(['index'])]);
            }else{
                return Json::encode(['msg'=>"修改失败",'flag'=>0]);
            }
        }
        $model = $this->getModel($id);
        return $this->renderAjax('edit',[
            "model"=>$model,
            'options'=>$this->getOptions("leader,type,category")
            ]);
    }
    /**
     * AJAX删除
     * @param $id
     * @return string
     */
    public function actionDelete($id){
        $url = $this->findApiUrl().$this->_url."delete?id=".$id;
        $result = json_decode($this->findCurl()->delete($url));
        if( $result->status){
            return Json::encode(['msg'=>"删除成功","flag"=>1]);
        }else{
            return Json::encode(['msg'=>"删除失败","flag"=>1]);
        }
    }

    /**
     * 获取经理人类型
     * @return mixed
     */
    private function getLevelOption(){
        $url = $this->findApiUrl().$this->_url."get-level-option";
        return $this->findCurl()->get($url,false);
    }

    /**
     * 获取商品经理人
     * @return mixed
     */
    private function getParentOptions(){
        $url = $this->findApiUrl().$this->_url."get-parent-option";
        return $this->findCurl()->get($url,false);
    }

    private function getOptions($items=""){
        $url = $this->findApiUrl().$this->_url."get-options?items={$items}";
        $res=Json::decode($this->findCurl()->get($url));
        return $res;
    }

    private function getModel($id){
        $url = $this->findApiUrl().$this->_url."find-model?id=".$id;
        $model = $this->findCurl()->get($url);
        if(!$model){
            throw new yii\web\NotFoundHttpException();
        }
        return Json::decode($model,false);
    }


    private function export($data)
    {
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '商品经理人')
            ->setCellValue('C1', '工号')
            ->setCellValue('D1', '资位')
            ->setCellValue('E1', '商品类别')
            ->setCellValue('F1', '商品负责人')
            ->setCellValue('G1', '备注')
            ->setCellValue('H1', '最后修改时间')
            ->setCellValue('I1', '最后修改人');
        foreach ($data as $key => $val) {
            $num = $key + 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $num-1)
                ->setCellValue('B'.$num, $val['pmName'])
                ->setCellValue('C'.$num, $val['staff_code'])
                ->setCellValue('D'.$num, $val['position'])
                ->setCellValue('E'.$num, $val['typeName'])
                ->setCellValue('F'.$num, $val['parentName'])
                ->setCellValue('G'.$num, $val['pm_desc'])
                ->setCellValue('H'.$num, $val['update_at'])
                ->setCellValue('I'.$num, $val['updator']);
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



}