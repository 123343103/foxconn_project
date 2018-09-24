<?php
/**
 * User: F1677929
 * Date: 2017/6/1
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmMediaCount;
use app\modules\crm\models\CrmMediaType;
use app\modules\crm\models\search\CrmMediaTypeSearch;
use Yii;
/**
 * 媒体类型控制器
 */
class CrmMediaTypeController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmMediaType';

    /**
     * 媒体类型列表
     */
    public function actionIndex()
    {
        $model=new CrmMediaTypeSearch();
        $dataProvider=$model->searchMediaType(Yii::$app->request->queryParams);
        $rows=$dataProvider->getModels();
        foreach($rows as &$row){
            if(!empty($row['create_at'])){
                $row['create_at']=date('Y-m-d H:i',strtotime($row['create_at']));
            }
            if(!empty($row['update_at'])){
                $row['update_at']=date('Y-m-d H:i',strtotime($row['update_at']));
            }
            //判断是否可以删除
            $mediaCount=Yii::$app->db->createCommand("select * from erp.crm_media_count where cmt_status = 1 and cmt_id = {$row['cmt_id']}")->queryOne();
            $activeCount=Yii::$app->db->createCommand("select * from erp.crm_act_count_child where actch_status = 10 and cc_id = {$row['cmt_id']}")->queryOne();
            if(empty($mediaCount) && empty($activeCount)){
                $row['del_flag']=1;
            }else{
                $row['del_flag']=0;
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount
        ];
    }

    /**
     * 新增媒体类型
     */
    public function actionAdd()
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $model=new CrmMediaType();
            if($model->load($data)&&$model->save()){
                return $this->success('新增成功！',$model->cmt_code);
            }
            return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        return CrmMediaType::getStatus();
    }

    /**
     * 修改媒体类型
     */
    public function actionEdit($id)
    {
        $model=CrmMediaType::find()->where(['cmt_id'=>$id])->andWhere(['or',['cmt_status'=>CrmMediaType::ENABLED_STATUS],['cmt_status'=>CrmMediaType::DISABLED_STATUS]])->one();
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            if($model->load($data)&&$model->save()){
                return $this->success('修改成功！',$model->cmt_code);
            }
            return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        return [
            'mediaStatus'=>CrmMediaType::getStatus(),
            'editData'=>$model
        ];
    }

    /**
     * 删除媒体类型
     */
    public function actionDeleteMedia($id)
    {
        $mediaCount=Yii::$app->db->createCommand("select * from erp.crm_media_count where cmt_status = 1 and cmt_id = :id",[':id'=>$id])->queryOne();
        if(!empty($mediaCount)){
            return $this->error('媒体统计已引用，不可删除！');
        }
        $activeCount=Yii::$app->db->createCommand("select * from erp.crm_act_count_child where actch_status = 10 and cc_id = :id",[':id'=>$id])->queryOne();
        if(!empty($activeCount)){
            return $this->error('活动统计已引用，不可删除！');
        }
        $model=CrmMediaType::findOne($id);
        $model->cmt_status=CrmMediaType::DELETE_STATUS;
        if($model->save()){
            return $this->success('删除成功！',$model->cmt_code);
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }
}