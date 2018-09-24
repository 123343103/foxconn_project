<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\controllers;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmActiveApply;
use app\modules\crm\models\CrmActiveCount;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\search\CrmActiveTypeSearch;
use app\controllers\BaseActiveController;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SysDisplayList;
use Yii;
use yii\db\Query;

//活动类型接口控制器
class CrmActiveTypeController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmActiveType';

    //活动类型列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['companyId'])){
            $num1=BsPubdata::find()->select('bsp_id')->where(['bsp_stype'=>BsPubdata::CRM_ACTIVE_TYPE])->andWhere(['bsp_status'=>BsPubdata::STATUS_DEFAULT])->count();
            $num2=CrmActiveType::find()->select('acttype_id')->where(['!=','acttype_status',CrmActiveType::DELETE_STATUS])->count();
            return $num1 > $num2;
        }
        $model=new CrmActiveTypeSearch();
        $dataProvider=$model->searchActiveType($params);
        $rows=$dataProvider->getModels();
        foreach($rows as &$row){
            if(!empty($row['create_at'])){
                $row['create_at']=date('Y-m-d H:i',strtotime($row['create_at']));
            }
            if(!empty($row['update_at'])){
                $row['update_at']=date('Y-m-d H:i',strtotime($row['update_at']));
            }
            //判断是否可以删除
            $activeNameModel=CrmActiveName::findOne(['acttype_id'=>$row['acttype_id'],'actbs_status'=>10]);
            if(empty($activeNameModel)){
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

    //新增修改数据
    private function getAddEditData()
    {
        return [
            'activeWay'=>BsPubdata::getData(BsPubdata::CRM_ACTIVE_WAY),
            'activeTypeStatus'=>CrmActiveType::getStatus(),
        ];
    }

    //新增活动类型
    public function actionAdd()
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $model=new CrmActiveType();
            if($model->load($data)&&$model->save()){
                return $this->success('新增成功！',$model->acttype_code);
            }
            return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        return $this->getAddEditData();
    }

    //修改活动类型
    public function actionEdit($typeId)
    {
        $model=CrmActiveType::findOne($typeId);
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            if($model->load($data)&&$model->save()){
                return $this->success('修改成功！',$model->acttype_code);
            }
            return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
        }
        $addEditData=$this->getAddEditData();
        //判断活动统计数据，若存在不可修改活动方式
        $activeName=CrmActiveName::find()->select('actbs_id')->where(['acttype_id'=>$typeId])->andWhere(['or',['actbs_status'=>CrmActiveName::ADD_STATUS],['actbs_status'=>CrmActiveName::ALREADY_START],['actbs_status'=>CrmActiveName::ALREADY_END]])->all();
        if(!empty($activeName)){
            $nameId=[];
            foreach($activeName as $val){
                $nameId[]=$val;
            }
            $countModel=CrmActiveCount::find()->where(['in','actbs_id',$nameId])->andWhere(['actch_status'=>CrmActiveCount::DEFAULT_STATUS])->one();
            if(!empty($countModel)){
                foreach($addEditData['activeWay'] as $key=>$val){
                    if($key!=$model->acttype_way){
                        unset($addEditData['activeWay'][$key]);
                    }
                }
            }
        }
        return [
            'addEditData'=>$addEditData,
            'editData'=>$model
        ];
    }

    //查看活动类型
    public function actionView($typeId)
    {
        $data=(new Query())->select([
            CrmActiveType::tableName().'.*',//活动类型表
            'bp1.bsp_svalue AS activeWay',//活动方式
            "(CASE ".CrmActiveType::tableName().".acttype_status WHEN ".CrmActiveType::VALID_STATUS." THEN '启用' WHEN ".CrmActiveType::INVALID_STATUS." THEN '禁用' ELSE '删除' END) AS activeTypeStatus",//活动类型状态
            'hs1.staff_name AS createBy',//创建人
            'hs2.staff_name AS updateBy',//修改人
            'bp2.bsp_svalue AS acttype_name',//活动类型
        ])->from(CrmActiveType::tableName())
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmActiveType::tableName().'.acttype_way')
            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmActiveType::tableName().'.create_by')
            ->leftJoin(HrStaff::tableName().' hs2','hs2.staff_id='.CrmActiveType::tableName().'.update_by')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.CrmActiveType::tableName().'.acttype_name')
            ->where([CrmActiveType::tableName().'.acttype_id'=>$typeId])
            ->one();
        return $data;
    }

    //删除活动类型
    public function actionDeleteActiveType($typeId)
    {
        $model=CrmActiveName::findOne(['acttype_id'=>$typeId,'actbs_status'=>10]);
        if(!empty($model)){
            return $this->error('该活动名称已引用，不可删除！');
        }
        $model=CrmActiveType::findOne($typeId);
        $model->acttype_status=CrmActiveType::DELETE_STATUS;
        if($model->save()){
            return $this->success('删除成功！',$model->acttype_code);
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }

    //获取活动类型
    public function actionGetActiveType($companyId,$id)
    {
        //查询参数
        $queryParams=[
            ':delete_status'=>CrmActiveType::DELETE_STATUS
        ];
        //查询sql
        $querySql="select a.acttype_name from erp.crm_bs_acttype a where a.acttype_status != :delete_status and a.company_id in (";
        //遍历公司id,绑定参数
        foreach(BsCompany::getIdsArr($companyId) as $key=>$val){
            $queryParams[':company_id_'.$key]=$val;
            $querySql.=':company_id_'.$key.',';
        }
        $querySql=trim($querySql,',').')';
        $activeTypeModel=Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
        if(!empty($activeTypeModel)){
            foreach($activeTypeModel as $key=>$val){
                if($val['acttype_name']==$id){
                    continue;
                }
                $existId[$key]=$val['acttype_name'];
            }
        }

        //查询参数
        $queryParams1=[
            ':hdlx'=>BsPubdata::CRM_ACTIVE_TYPE,
            ':status_delete'=>BsPubdata::STATUS_DELETE
        ];
        $querySql1="select a.bsp_id,a.bsp_svalue from erp.bs_pubdata a where a.bsp_status != :status_delete and a.bsp_stype = :hdlx";
        if(!empty($existId)){
            $querySql1.=" and a.bsp_id not in (";
            foreach($existId as $key=>$val){
                $queryParams1[':bsp_id_'.$key]=$val;
                $querySql1.=":bsp_id_".$key.',';
            }
            $querySql1=trim($querySql1,',').')';
        }
        return Yii::$app->db->createCommand($querySql1,$queryParams1)->queryAll();
    }
}