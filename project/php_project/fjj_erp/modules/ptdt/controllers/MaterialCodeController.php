<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/20
 * Time: 上午 11:31
 */
namespace app\modules\ptdt\controllers;
use app\modules\common\models\BsCompany;
use Yii;
use app\controllers\BaseController;
use app\modules\ptdt\models\PdProductType;
use app\modules\ptdt\models\PdMaterialCode;
use app\modules\ptdt\models\search\PdMaterialCodeSearch;
use app\modules\ptdt\models\PdBrand;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use app\modules\system\models\SystemLog;
use yii\helpers\Url;
class MaterialCodeController extends BaseController
{
    /*新增*/
    public function actionAdd()
    {
        $planModel = new PdMaterialCode();

        if ($planModel->load(Yii::$app->request->post())) {

            //新增类别状态默认为审核中
            $planModel->m_status = PdProductType::STATUS_NORMAL;
            //文件上传
            $file = UploadedFile::getInstance($planModel, 'pro_pic');
            $path='uploads/pdm-pro_pic/';
            if ($file) {
                if(!file_exists($path)){
                    mkdir($path,0777,true);
                }
                $pathName=$path .date('Ymd').time() . '.' . $file->getExtension();
                $file->saveAs($pathName);
                $planModel->pro_pic=$pathName;
                $planModel->pro_pic_name=$file->baseName.'.'.$file->getExtension();
            }

            if ($planModel->save()) {
                $planModel->load(Yii::$app->request->post());
                $planModel->save();
                return Json::encode(['msg' => "添加成功", "flag" => 1, "url" =>Url::to(['index'])]);
            } else {
                return Json::encode(['msg' => "添加失败", "flag" => 0]);
            }
        } else {
            $res = BsCompany::find()->all();
            return $this->render("cre", [
                'planModel' => $planModel,
                'brand' =>PdBrand::find()->all(),
                'res' => $res,
                'bsCompany' => new BsCompany()
            ]);
        }

    }

    /*列表页*/
    public function actionIndex(){
        $searchModel = new PdMaterialCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new PdProductType();
        return $this->render('index', [
            'model'=>$model,
            'searchModel'=>$searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*查看页面*/
    public function actionView($id)
    {
        $model = $this->getModel($id);
        return $this->render('view', [
            'model'=>$model,
        ]);
    }

    /*修改页面*/
    public function actionEdit($id){
        $planModel = $this->getModel($id);
        if ($planModel->load($post = Yii::$app->request->post()) ){
            //文件上传
            $file = UploadedFile::getInstance($planModel, 'pro_pic');
            $path='uploads/pdm-pro_pic/';
            if ($file) {
                if(!file_exists($path)){
                    mkdir($path,0777,true);
                }
                $pathName=$path .date('Ymd').time() . '.' . $file->getExtension();
                $file->saveAs($pathName);
                $planModel->pro_pic=$pathName;
                $planModel->pro_pic_name=$file->baseName.'.'.$file->getExtension();
            }
            if ($planModel->save()){
                $mCode = $planModel->m_code;
                SystemLog::addLog('修改编号为'.$mCode.'厂商信息');
                //return $this->redirect(['view', 'id' => $model->firm_id]);
                return  Json::encode(['msg' => "修改成功", "flag" => 1, "url" => Url::to(['index'])]);
            }else{
                return  Json::encode(['msg' => "发生未知错误，修改失败", "flag" => 0]);
            }
        }else{
            $res = BsCompany::find()->all();
            $brand = PdBrand::find()->all();
            return $this->render('edit',
                [
                    'planModel'=>$planModel,
                    'brand' =>$brand,
                    'res' => $res
                ]);
        }
    }

    /*删除*/
    public function actionDelete($id){
        $model = $this->getModel($id);
        if ($model->delete()){
            SystemLog::addLog('删除了编号为'.$model->m_code.'的料号信息');
            return Json::encode(["msg"=>"刪除成功","flag"=>1]);
        }else{
            return Json::encode(["msg"=>"删除失败","flag"=>0]);
        }
    }


    /* 獲取模型*/
    public function getModel($id)
    {
        if ($model = PdMaterialCode::findOne($id)) {
            return $model;
        } else {
            throw new NotFoundHttpException("页面未找到");
        }
    }

    /* AJAX獲取商品大類  */
    public function actionGetProductType($id)
    {
        return Json::encode(PdProductType::getChildrenByParentId($id));
    }

    /*获取type_no*/
    public function actionGetTypeNo($id){
        return Json::encode(PdProductType::getProTypeNoById($id));
    }


}