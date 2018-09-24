<?php
/**
 * User: F1677929
 * Date: 2017/6/1
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveCountChild;
use app\modules\crm\models\CrmCarrier;
use app\modules\crm\models\CrmCommunity;
use app\modules\crm\models\CrmMediaType;
use app\modules\crm\models\search\CrmCarrierSearch;
use app\modules\crm\models\search\CrmMediaTypeSearch;
use Yii;
/**
 * 载体控制器
 */
class CrmCarrierController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmCarrier';

    /**
     * 载体列表
     */
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $model=new CrmCarrierSearch();
        $dataProvider=$model->searchCarrier($params);
        $rows=$dataProvider->getModels();
        foreach($rows as &$row){
            if(!empty($row['create_at'])){
                $row['create_at']=date('Y-m-d H:i',strtotime($row['create_at']));
            }
            if(!empty($row['update_at'])){
                $row['update_at']=date('Y-m-d H:i',strtotime($row['update_at']));
            }
            //判断是否可以删除
            $communitySale=Yii::$app->db->createCommand("select * from erp.crm_community where cmt_id = {$row['cc_id']}")->queryOne();
            if(empty($communitySale)){
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
     * 新增载体
     */
    public function actionAdd()
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmCarrier']['cc_carrier']=implode(',',$data['CrmCarrier']['cc_carrier']);
            $data['CrmCarrier']['cc_carrier']=trim($data['CrmCarrier']['cc_carrier'],',');
            $data['CrmCarrier']['cc_sale_way']=implode(',',$data['CrmCarrier']['cc_sale_way']);
            $data['CrmCarrier']['cc_sale_way']=trim($data['CrmCarrier']['cc_sale_way'],',');
            $model=new CrmCarrier();
            if($model->load($data)&&$model->save()){
                return $this->success('新增成功！',$model->cc_code);
            }
            return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        return [
            'carrierType'=>BsPubdata::getData(BsPubdata::CRM_CARRIER_TYPE),
            'saleWay'=>BsPubdata::getData(BsPubdata::CRM_SALE_WAY),
            'carrierStatus'=>CrmCarrier::getStatus()
        ];
    }

    /**
     * 修改载体
     */
    public function actionEdit($id)
    {
        $model=CrmCarrier::find()->where(['cc_id'=>$id])->andWhere(['or',['cc_status'=>CrmCarrier::ENABLED_STATUS],['cc_status'=>CrmCarrier::DISABLED_STATUS]])->one();
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $data['CrmCarrier']['cc_carrier']=implode(',',$data['CrmCarrier']['cc_carrier']);
            $data['CrmCarrier']['cc_carrier']=trim($data['CrmCarrier']['cc_carrier'],',');
            $data['CrmCarrier']['cc_sale_way']=implode(',',$data['CrmCarrier']['cc_sale_way']);
            $data['CrmCarrier']['cc_sale_way']=trim($data['CrmCarrier']['cc_sale_way'],',');
            if($model->load($data)&&$model->save()){
                return $this->success('修改成功！',$model->cc_code);
            }
            return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        return [
            'carrierType'=>BsPubdata::getData(BsPubdata::CRM_CARRIER_TYPE),
            'saleWay'=>BsPubdata::getData(BsPubdata::CRM_SALE_WAY),
            'carrierStatus'=>CrmCarrier::getStatus(),
            'editData'=>$model
        ];
    }

    /**
     * 删除载体
     */
    public function actionDeleteCarrier($id)
    {
        $communitySale=Yii::$app->db->createCommand("select * from erp.crm_community where cmt_id = :id",[':id'=>$id])->queryOne();
        if(!empty($communitySale)){
            return $this->error('社群营销已引用，不可删除！');
        }
        $model=CrmCarrier::findOne($id);
        $model->cc_status=CrmCarrier::DELETE_STATUS;
        if($model->save()){
            return $this->success('删除成功！',$model->cc_code);
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }
}