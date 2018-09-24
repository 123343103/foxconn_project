<?php
/**
 * User: F1677929
 * Date: 2017/2/13
 */
namespace app\modules\crm\controllers;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\common\tools\CheckUsed;
use app\modules\crm\models\CrmActiveCount;
use app\modules\crm\models\CrmActiveName;
use app\modules\crm\models\CrmActiveType;
use app\modules\crm\models\search\CrmActiveNameSearch;
use app\controllers\BaseActiveController;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\db\Query;
//活动名称接口控制器
class CrmActiveNameController extends BaseActiveController
{
    public $modelClass='app\modules\crm\models\CrmActiveName';

    //活动名称列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        $model=new CrmActiveNameSearch();
        $dataProvider=$model->searchActiveName($params);
        $rows=$dataProvider->getModels();
        foreach($rows as &$row){
            if(!empty($row['actbs_start_time'])){
                $row['actbs_start_time']=date('Y-m-d H:i',strtotime($row['actbs_start_time']));
            }
            if(!empty($row['actbs_end_time'])){
                $row['actbs_end_time']=date('Y-m-d H:i',strtotime($row['actbs_end_time']));
            }
            if(!empty($row['create_at'])){
                $row['create_at']=date('Y-m-d H:i',strtotime($row['create_at']));
            }
            if(!empty($row['update_at'])){
                $row['update_at']=date('Y-m-d H:i',strtotime($row['update_at']));
            }
            //判断是否可以删除
            $checkUsed=new CheckUsed();
            $used=$checkUsed->check($row['actbs_id'],'actbs_id');
            if($used['status']==0){
                $row['del_flag']=0;
            }else{
                $row['del_flag']=1;
            }
        }
        return [
            'rows'=>$rows,
            'total'=>$dataProvider->totalCount
        ];
    }

    //获取新增修改数据
    private function getAddEditData($staffId)
    {
        return [
            //活动方式
            'activeWay'=>BsPubdata::getData(BsPubdata::CRM_ACTIVE_WAY),
            //活动月份
            'activeMonth'=>BsPubdata::getData(BsPubdata::CRM_ACTIVE_MONTH),
            //活动状态
            'activeNameStatus'=>CrmActiveName::getStatus(),
            //行业类别
            'industryType'=>BsPubdata::getData(BsPubdata::CRM_INDUSTRY_TYPE),
            //活动地点
            'country'=>BsDistrict::getChildByParentId(0),
            //参与目的
            'joinPurpose'=>BsPubdata::getData(BsPubdata::CRM_JOIN_PURPOSE),
            //负责人、维护人员
            'staff'=>HrStaff::getStaffById($staffId),
        ];
    }

    //新增活动名称
    public function actionAdd($staffId)
    {
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            $model=new CrmActiveName();
            if($model->load($data)&&$model->save()){
                return $this->success($model->actbs_code,$model->actbs_id);
            }
            $error=$model->getErrors();
            if(!empty($error['actbs_name'])){
                return $this->error('活动名称已存在！');
            }
            return $this->error($error);
        }
        $addEditData=$this->getAddEditData($staffId);
        return [
            'addEditData'=>$addEditData,
            'activeTypeName'=>CrmActiveType::getActiveType(['acttype_way'=>array_search('线上',$addEditData['activeWay'])]),
        ];
    }

    //获取活动类型
    public function actionGetActiveType($wayId)
    {
        return CrmActiveType::getActiveType(['acttype_way'=>$wayId]);
    }

    //修改活动名称
    public function actionEdit($staffId,$nameId)
    {
        $nameModel=CrmActiveName::findOne($nameId);
        if(Yii::$app->request->isPost){
            $data=Yii::$app->request->post();
            if($nameModel->load($data)&&$nameModel->save()){
                return $this->success($nameModel->actbs_code,$nameModel->actbs_id);
            }
            $error=$nameModel->getErrors();
            if(!empty($error['actbs_name'])){
                return $this->error('活动名称已存在！');
            }
            return $this->error($error);
        }
        $typeModel=CrmActiveType::findOne($nameModel->acttype_id);
        $publicData=BsPubdata::findOne($typeModel->acttype_way);
        $activeType=[
            'activeTypeName'=>CrmActiveType::getActiveType(['acttype_way'=>$typeModel->acttype_way]),
            'activeWayName'=>$publicData->bsp_svalue
        ];
        $addEditData=$this->getAddEditData($staffId);
        //判断活动统计数据，若存在不可修改活动方式
        $countModel=CrmActiveCount::find()->where(['actbs_id'=>$nameId])->andWhere(['actch_status'=>CrmActiveCount::DEFAULT_STATUS])->one();
        if(!empty($countModel)){
            foreach($addEditData['activeWay'] as $key=>$val){
                if($key!=$typeModel->acttype_way){
                    unset($addEditData['activeWay'][$key]);
                }
            }
        }
        return [
            'addEditData'=>$addEditData,
            'editData'=>$nameModel,
            'activeType'=>$activeType,
            'districtData'=>$this->getDistrict($nameModel->actbs_address_id)
        ];
    }

    //查看活动名称
    public function actionView($nameId)
    {
        $activeName=(new Query())->select([
            CrmActiveName::tableName().'.*',//活动名称表
            'bp1.bsp_svalue AS activeWay',//活动方式
            'bp5.bsp_svalue AS acttype_name',//类型名
            'bp2.bsp_svalue AS activeMonth',//活动月份
            "(CASE ".CrmActiveName::tableName().".actbs_status WHEN ".CrmActiveName::ADD_STATUS." THEN '未开始' WHEN ".CrmActiveName::ALREADY_START." THEN '进行中' WHEN ".CrmActiveName::ALREADY_END." THEN '已结束' WHEN ".CrmActiveName::ALREADY_CANCEL." THEN '已取消' ELSE '删除' END) AS activeNameStatus",//活动名称状态
            'hs1.staff_name AS activeDuty',//活动负责人
            'bp3.bsp_svalue AS industryType',//行业类别
            'CONCAT(bd4.district_name,bd3.district_name,bd2.district_name,bd1.district_name,'.CrmActiveName::tableName().'.actbs_address) AS activeAddress',//活动地址
            'bp4.bsp_svalue AS joinPurpose',//参与目的
            'hs2.staff_name AS activeMaintain',//维护人员
        ])->from(CrmActiveName::tableName())
            ->leftJoin(CrmActiveType::tableName(),CrmActiveType::tableName().'.acttype_id='.CrmActiveName::tableName().'.acttype_id')
            ->leftJoin(BsPubdata::tableName().' bp1','bp1.bsp_id='.CrmActiveType::tableName().'.acttype_way')
            ->leftJoin(BsPubdata::tableName().' bp2','bp2.bsp_id='.CrmActiveName::tableName().'.actbs_month')
            ->leftJoin(HrStaff::tableName().' hs1','hs1.staff_id='.CrmActiveName::tableName().'.actbs_duty')
            ->leftJoin(BsPubdata::tableName().' bp3','bp3.bsp_id='.CrmActiveName::tableName().'.actbs_industry')
            ->leftJoin(BsDistrict::tableName().' bd1','bd1.district_id='.CrmActiveName::tableName().'.actbs_address_id')
            ->leftJoin(BsDistrict::tableName().' bd2','bd1.district_pid=bd2.district_id')
            ->leftJoin(BsDistrict::tableName().' bd3','bd2.district_pid=bd3.district_id')
            ->leftJoin(BsDistrict::tableName().' bd4','bd3.district_pid=bd4.district_id')
            ->leftJoin(BsPubdata::tableName().' bp4','bp4.bsp_id='.CrmActiveName::tableName().'.actbs_purpose')
            ->leftJoin(HrStaff::tableName().' hs2','hs2.staff_id='.CrmActiveName::tableName().'.actbs_maintain')
            ->leftJoin(BsPubdata::tableName().' bp5','bp5.bsp_id='.CrmActiveType::tableName().'.acttype_name')
            ->where([CrmActiveName::tableName().'.actbs_id'=>$nameId])
            ->one();
        return $activeName;
    }

    //删除活动名称
    public function actionDeleteActiveName($nameId)
    {
        $checkUsed=new CheckUsed();
        $used=$checkUsed->check($nameId,'actbs_id');
        if($used['status']==0){
            return $this->error($used['msg']);
        }
        $model=CrmActiveName::findOne($nameId);
        $model->actbs_status=CrmActiveName::DELETE_STATUS;
        if($model->save()){
            return $this->success('删除成功！',$model->actbs_code);
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }

    //取消活动名称
    public function actionCancelActive($nameId,$staffId)
    {
        $model=CrmActiveName::findOne($nameId);
        $model->actbs_status=CrmActiveName::ALREADY_CANCEL;
        $model->update_by=$staffId;
        if($model->save()){
            return $this->success('取消成功！',$model->actbs_code);
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }

    //终止活动名称
    public function actionStopActive($nameId,$staffId)
    {
        $model=CrmActiveName::findOne($nameId);
        $model->actbs_status=CrmActiveName::ALREADY_STOP;
        $model->update_by=$staffId;
        if($model->save()){
            return $this->success('终止成功！',$model->actbs_code);
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }

    //地址联动
    public function actionGetDistrict($id)
    {
        return BsDistrict::getChildByParentId($id);
    }

    //修改时获取地区
    public function getDistrict($id)
    {
        if(empty($id)){
            return [];
        }
        $districtId=[];
        $districtName=[];
        while($id>0){
            $model=BsDistrict::findOne($id);
            $districtId[]=$model->district_id;
            $districtName[]=BsDistrict::find()->select('district_id,district_name')->where(['is_valid'=>'1','district_pid'=>$model->district_pid])->all();
            $id=$model->district_pid;
        }
        return [
            'districtId'=>array_reverse($districtId),
            'districtName'=>array_reverse($districtName),
        ];
    }
}