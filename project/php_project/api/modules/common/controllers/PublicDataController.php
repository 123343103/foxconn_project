<?php
/**
 * User: F1677929
 * Date: 2017/3/10
 */
namespace app\modules\common\controllers;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\search\BsPubdataSearch;
use app\controllers\BaseActiveController;
use Yii;
//公共数据接口控制器
class PublicDataController extends BaseActiveController
{
    public $modelClass='app\modules\common\models\BsPubdata';
    //公共数据列表
    public function actionIndex()
    {
        $model=new BsPubdataSearch();
        $dataProvider=$model->searchType(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //公共数据列表
    public function actionView()
    {
        $model=new BsPubdataSearch();
        $dataProvider=$model->searchName(Yii::$app->request->queryParams);
        return [
            'rows'=>$dataProvider->getModels(),
            'total'=>$dataProvider->totalCount
        ];
    }

    //新增
    public function actionAdd($val)
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $model=new BsPubdata();
            if($model->load($data)&&$model->save()){
                return $this->success();
            }
            $error=$model->getErrors();
            if(!empty($error['bsp_svalue'])){
                return $this->error('参数值已存在！');
            }
            return $this->error($error);
        }
        return BsPubdata::find()->where(['bsp_stype'=>$val])->one();
    }

    //修改
    public function actionEdit($id)
    {
        $model=BsPubdata::findOne($id);
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            if($model->load($data)&&$model->save()){
                return $this->success();
            }
            $error=$model->getErrors();
            if(!empty($error['bsp_svalue'])){
                return $this->error('参数值已存在！');
            }
            return $this->error($error);
        }
        return $model;
    }

    //删除
    public function actionDeleteName($id)
    {
        $model=BsPubdata::findOne($id);
        $model->bsp_status=BsPubdata::STATUS_DELETE;
        if(!$model->save(false)){
            return $this->error($model->getErrors());
        }
        return $this->success();
    }
}