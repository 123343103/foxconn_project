<?php
/**
 * User: F1677929
 * Date: 2017/12/13
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsForm;
use app\modules\ptdt\models\BsMaterial;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\InWhpdt;
use app\modules\warehouse\models\InWhpdtDt;
use app\modules\warehouse\models\LInvtRe;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;

/**
 * 其他入库API控制器
 */
class OtherStockInController extends BaseActiveController
{
    public $modelClass="x";

    //列表
    public function actionList()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params)){
            //单据类型
            $billType=Yii::$app->db->createCommand("select business_type_id,business_value from erp.bs_business_type where business_code = 'wm01' order by business_type_id asc")->queryAll();
            if(!empty($billType)){
                $billType=array_column($billType,"business_value","business_type_id");
            }
            //入仓仓库
            $warehouse=Yii::$app->db->createCommand("select wh_code,wh_name from wms.bs_wh order by wh_id asc")->queryAll();
            if(!empty($warehouse)){
                $warehouse=array_column($warehouse,"wh_name","wh_code");
            }
            return [
                "billType"=>array_merge(["2"=>"调拨","3"=>"移仓"],$billType),
                "warehouse"=>$warehouse,
            ];
        }
        $sql="select a.invh_id,
                     a.invh_code,
                     case a.invh_status when 1 then '待提交' when 2 then '审核中' when 3 then '驳回' when 4 then '已取消' when 5 then '待上架' when 6 then '已上架' else '未知' end invh_status,
                     a.invh_aboutno,
                     ifnull(b.business_value,(case a.inout_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end)) inout_flag_val,
                     a.inout_flag,
                     c.wh_name,
                     a.invh_sendperson,
                     d.staff_name invh_reperson,
                     a.recive_date,
                     e.staff_name create_by,
                     a.cdate,
                     a.inout_type
              from wms.in_whpdt a
              left join erp.bs_business_type b on b.business_type_id = a.inout_flag
              left join wms.bs_wh c on c.wh_code = a.wh_code
              left join erp.hr_staff d on d.staff_id = a.invh_reperson
              left join erp.hr_staff e on e.staff_id = a.create_by
              where 1 = 1
              and (a.inout_type is null or a.inout_type = 2 or a.inout_type = 3)";
        //查询
        if(!empty($params['val1'])){
            $params['val1']=str_replace(['%','_'],['\%','\_'],$params['val1']);
            $queryParams[':val1']='%'.$params['val1'].'%';
            $sql.=" and a.invh_code like :val1";
        }
        if(!empty($params['val2'])){
            $queryParams[':val2']=$params['val2'];
            $sql.=" and a.invh_status = :val2";
        }
        if(!empty($params['val3'])){
            $queryParams[':val3']=$params['val3'];
            $sql.=" and (a.inout_type = :val3 or a.inout_flag = :val3)";
        }
        if(!empty($params['val4'])){
            $queryParams[':val4']=$params['val4'];
            $sql.=" and a.wh_code = :val4";
        }
        if(!empty($params['val5'])){
            $params['val5']=str_replace(['%','_'],['\%','\_'],$params['val5']);
            $queryParams[':val5']='%'.$params['val5'].'%';
            $sql.=" and a.invh_reperson like :val5";
        }
        if(!empty($params['val6'])){
            $params['val6']=str_replace(['%','_'],['\%','\_'],$params['val6']);
            $queryParams[':val6']='%'.$params['val6'].'%';
            $sql.=" and d.staff_name like :val6";
        }
        if(!empty($params['val7'])){
            $params['val7']=str_replace(['%','_'],['\%','\_'],$params['val7']);
            $queryParams[':val7']='%'.$params['val7'].'%';
            $sql.=" and e.staff_name like :val7";
        }
        $sql.=" order by a.invh_id desc";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>empty($queryParams)?[]:$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //获取料号信息-列表、修改、详情共用
    public function actionGetProducts()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams=[":id"=>$params['id']];
        if(!empty($params['inout_type']) && $params['inout_type']==2){//调拨
            $sql="select a.invl_id,
                         b.part_no,
                         b.pdt_name,
                         b.brand,
                         b.tp_spec,
                         d.ord_id,
                         d.invt_num,
                         d.delivery_num,
                         d.before_stno,
                         b.unit,
                         a.real_quantity,
                         a.st_codes,
                         substring(a.inout_time, 1, 10) inout_time
                  from wms.in_whpdt_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  left join wms.rcp_goods_dt c on c.detail_id = a.detail_id
                  left join wms.rcp_notice_dt d on d.rcpdt_id = c.rcpdt_id 
                  where 1 = 1
                  and a.invh_id = :id";
        }elseif(!empty($params['inout_type']) && $params['inout_type']==3){//移仓
            $sql="select a.invl_id,
                         b.part_no,
                         b.pdt_name,
                         b.brand,
                         b.tp_spec,
                         d.ord_id,
                         d.before_stno,
                         d.chwh_num,
                         b.unit,
                         a.real_quantity,
                         a.st_codes,
                         substring(a.inout_time, 1, 10) inout_time
                  from wms.in_whpdt_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  left join wms.rcp_goods_dt c on c.detail_id = a.detail_id
                  left join wms.rcp_notice_dt d on d.rcpdt_id = c.rcpdt_id 
                  where 1 = 1
                  and a.invh_id = :id";
        }else{//新增
            $sql="select a.invl_id,
                         b.part_no,
                         b.pdt_name,
                         b.brand,
                         b.tp_spec,
                         a.batch_no,
                         a.in_quantity,
                         a.real_quantity,
                         b.unit,
                         c.bsp_svalue pack_type,
                         c.bsp_id pack_type_id,
                         a.pack_num,
                         a.st_codes
                  from wms.in_whpdt_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  left join erp.bs_pubdata c on c.bsp_id = a.pack_type
                  where 1 = 1
                  and a.invh_id = :id";
        }
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>empty($queryParams)?[]:$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //新增时数据
    private function addData()
    {
        //单据类型
        $billType=Yii::$app->db->createCommand("select business_type_id,business_value from erp.bs_business_type where business_code = 'wm01' order by business_type_id asc")->queryAll();
        if(!empty($billType)){
            $billType=array_column($billType,"business_value","business_type_id");
        }
        //入仓仓库
        $warehouse=Yii::$app->db->createCommand("select wh_code,wh_name from wms.bs_wh order by wh_id asc")->queryAll();
        if(!empty($warehouse)){
            $warehouse=array_column($warehouse,"wh_name","wh_code");
        }
        //包装方式
        $packType=Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_stype = 'qtrkbzfs' order by bsp_id asc")->queryAll();
        if(!empty($packType)){
            $packType=array_column($packType,"bsp_svalue","bsp_id");
        }
        return [
            "billType"=>$billType,
            "warehouse"=>$warehouse,
            "packType"=>$packType,
        ];
    }

    //新增
    public function actionAdd($staff_id="")
    {
        if($data=Yii::$app->request->post()){
            $transaction=InWhpdt::getDb()->beginTransaction();
            try{
                //主表
                $model1=new InWhpdt();
                $model1->invh_status=1;
                $model1->codeType=20;
                $model1->invh_code=BsForm::getCode('wms.in_whpdt',$model1);
                if($model1->load($data)){
                    if(!$model1->save()){
                        throw new \Exception(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new \Exception('主表加载失败');
                }
                //子表
                if(!empty($data['arr'])){
                    foreach($data['arr'] as $key=>$val){
                        if(!empty($val['InWhpdtDt']['part_no'])){
                            $model2=new InWhpdtDt();
                            $model2->invh_id=$model1->invh_id;
                            if($model2->load($val)){
                                if(!$model2->save()){
                                    throw new \Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new \Exception('子表加载失败');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success('新增成功',[
                    'id'=>$model1->invh_id,
                    'code'=>$model1->invh_code,
                    'typeId'=>$model1->inout_flag
                ]);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile()."--".$e->getLine()."--".$e->getMessage());
            }
        }
        $addData=$this->addData();
        $addData['staffInfo']=Yii::$app->db->createCommand("select staff_id,staff_name,staff_code from erp.hr_staff where staff_id = {$staff_id}")->queryOne();
        return $addData;
    }

    //修改
    public function actionEdit($id)
    {
        if($data=Yii::$app->request->post()){
            $transaction=InWhpdt::getDb()->beginTransaction();
            try{
                //主表
                $model1=InWhpdt::findOne($id);
                if($model1->load($data)){
                    if(!$model1->save()){
                        throw new \Exception(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new \Exception('主表加载失败');
                }
                //子表
                InWhpdtDt::deleteAll(['invh_id'=>$id]);
                if(!empty($data['arr'])){
                    foreach($data['arr'] as $key=>$val){
                        if(!empty($val['InWhpdtDt']['part_no'])){
                            $model2=new InWhpdtDt();
                            $model2->invh_id=$model1->invh_id;
                            if($model2->load($val)){
                                if(!$model2->save()){
                                    throw new \Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new \Exception('子表加载失败');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success('修改成功',[
                    'id'=>$model1->invh_id,
                    'code'=>$model1->invh_code,
                    'typeId'=>$model1->inout_flag
                ]);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile()."--".$e->getLine()."--".$e->getMessage());
            }
        }
        $data['addData']=$this->addData();
        $sql="select a.invh_id,
                     a.invh_code,
                     b.business_value,
                     a.invh_aboutno,
                     c.wh_name,
                     c.wh_code,
                     e.bsp_svalue wh_attr,
                     a.invh_sendperson,
                     a.invh_sendaddress,
                     a.recive_date,
                     a.invh_reperson,
                     concat(d.staff_code,'--',d.staff_name) staff_info,
                     a.invh_repaddress,
                     a.invh_remark
              from wms.in_whpdt a
              left join erp.bs_business_type b on b.business_type_id = a.inout_flag
              left join wms.bs_wh c on c.wh_code = a.wh_code
              left join erp.hr_staff d on d.staff_id = a.invh_reperson
              left join erp.bs_pubdata e on e.bsp_id = c.wh_attr
              where a.invh_id = :id
              and (a.invh_status = 1 or a.invh_status = 3)";
        $data['editData']=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryOne();
        return $data;
    }

    //获取仓库信息
    public function actionGetWarehouseInfo($id)
    {
        $sql="select a.wh_id,
                     a.wh_code,
                     a.wh_name,
                     b.bsp_svalue wh_attr
              from wms.bs_wh a
              left join erp.bs_pubdata b on b.bsp_id = a.wh_attr
              where a.wh_code = :id";
        return Yii::$app->db->createCommand($sql,[":id"=>$id])->queryOne();
    }

    //选择料号
    public function actionSelectPno()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.pkmt_id,
                     a.part_no,
                     a.pdt_name,
                     a.tp_spec,
                     a.brand,
                     a.unit
              from pdt.bs_material a
              left join pdt.bs_category b on b.catg_no = a.category_no
              left join pdt.bs_category c on c.catg_id = b.p_catg_id
              left join pdt.bs_category d on d.catg_id = c.p_catg_id
              where 1 = 1";
        //过滤
        if(!empty($params['filters'])){
            $arr=explode(',',$params['filters']);
            $sql.=" and a.part_no not in (";
            foreach($arr as $key=>$val){
                $sql.="'".$val."',";
            }
            $sql=trim($sql,',').')';
        }
        //查询
        if(!empty($params['catg_id'])){
            $queryParams[':catg_id']=$params['catg_id'];
            $sql.=" and d.catg_id = :catg_id";
        }
        if(!empty($params['kwd'])){
            $params['kwd']=str_replace(['%','_'],['\%','\_'],$params['kwd']);
            $queryParams[':kwd']='%'.$params['kwd'].'%';
            $sql.=" and (a.part_no like :kwd or a.pdt_name like :kwd)";
        }
        $sql.=" order by a.pkmt_id desc";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>empty($queryParams)?[]:$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //获取料号
    public function actionGetPno()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.part_no,a.pdt_name,a.tp_spec,a.brand,a.unit from pdt.bs_material a where a.part_no = :pno";
        return Yii::$app->db->createCommand($sql,[":pno"=>$params['code']])->queryOne();
    }

    //详情
    public function actionView()
    {
        $params=Yii::$app->request->queryParams;
        if(!empty($params['inout_type']) && $params['inout_type']==2){//调拨
            $sql="select a.invh_id,
                         a.inout_type,
                         a.invh_code,
                         a.invh_aboutno,
                         case a.invh_status when 1 then '待提交' when 2 then '审核中' when 3 then '驳回' when 4 then '已取消' when 5 then '待上架' when 6 then '已上架' else '未知' end invh_status,
                         e.business_value chh_type,
                         f.organization_name depart_name,
                         g.wh_name o_wh_name,
                         g.wh_code o_wh_code,
                         h.wh_name i_wh_name,
                         h.wh_code i_wh_code,
                         d.o_status,
                         d.in_status
                  from wms.in_whpdt a
                  left join wms.rcp_goods b on b.rcpg_no = a.invh_aboutno
                  left join wms.rcp_notice c on c.rcpnt_no = b.rcpnt_no
                  left join wms.inv_changeh d on d.chh_code = c.prch_no
                  left join erp.bs_business_type e on e.business_type_id = d.chh_type
                  left join erp.hr_organization f on f.organization_id = d.depart_id
                  left join wms.bs_wh g on g.wh_code = c.o_whcode
                  left join wms.bs_wh h on h.wh_code = c.i_whcode
                  where a.invh_id = :id";
        }elseif(!empty($params['inout_type']) && $params['inout_type']==3){//移仓
            $sql="select a.invh_id,
                         a.inout_type,
                         a.invh_code,
                         a.invh_aboutno,
                         case a.invh_status when 1 then '待提交' when 2 then '审核中' when 3 then '驳回' when 4 then '已取消' when 5 then '待上架' when 6 then '已上架' else '未知' end invh_status,
                         d.wh_name o_wh_name,
                         d.wh_code o_wh_code,
                         e.bsp_svalue o_wh_attr,
                         f.wh_name i_wh_name,
                         f.wh_code i_wh_code,
                         g.bsp_svalue i_wh_attr
                  from wms.in_whpdt a
                  left join wms.rcp_goods b on b.rcpg_no = a.invh_aboutno
                  left join wms.rcp_notice c on c.rcpnt_no = b.rcpnt_no
                  left join wms.bs_wh d on d.wh_code = c.o_whcode
                  left join erp.bs_pubdata e on e.bsp_id = d.wh_attr
                  left join wms.bs_wh f on f.wh_code = b.in_whcode
                  left join erp.bs_pubdata g on g.bsp_id = f.wh_attr
                  where a.invh_id = :id";
        }else{//新增
            $sql="select a.invh_id,
                         a.inout_type,
                         a.invh_code,
                         b.business_value inout_flag_val,
                         a.inout_flag,
                         a.invh_aboutno,
                         c.wh_name,
                         c.wh_code,
                         d.bsp_svalue wh_attr,
                         a.invh_sendperson,
                         a.invh_sendaddress,
                         a.recive_date,
                         e.staff_name invh_reperson,
                         a.invh_repaddress,
                         a.invh_remark,
                         a.can_reason,
                         case a.invh_status when 1 then '待提交' when 2 then '审核中' when 3 then '驳回' when 4 then '已取消' when 5 then '待上架' when 6 then '已上架' else '未知' end invh_status
                  from wms.in_whpdt a
                  left join erp.bs_business_type b on b.business_type_id = a.inout_flag
                  left join wms.bs_wh c on c.wh_code = a.wh_code
                  left join erp.bs_pubdata d on d.bsp_id = c.wh_attr
                  left join erp.hr_staff e on e.staff_id = a.invh_reperson
                  where a.invh_id = :id";
        }
        return Yii::$app->db->createCommand($sql,[":id"=>$params['id']])->queryOne();
    }

    //获取签核记录
    public function actionGetCheckRecord()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams=[
            'billId'=>$params['billId'],
            'billTypeId'=>$params['billTypeId']
        ];
        $sql="select d.organization_code,
                     d.staff_name,
                     a.vcoc_datetime,
                     case a.vcoc_status when 10 then '待审' when 20 then '待审' when 30 then '通过' when 40 then '驳回' else '未知' end checkStatus,
                     a.vcoc_remark,
                     a.vcoc_computeip
              from erp.system_verifyrecord_child a
              left join erp.system_verifyrecord b on b.vco_id = a.vco_id
              left join erp.user c on c.user_id = a.ver_acc_id
              left join erp.hr_staff d on d.staff_id = c.staff_id
              where b.vco_busid = :billId
              and b.but_code = :billTypeId
              and a.vcoc_status <> 50";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>empty($params['rows'])?false:$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }

    //取消新增
    public function actionCancelAdd($id)
    {
        $data=Yii::$app->request->post();
        $id=explode('-',$id);
        foreach($id as $key=>$val){
            //wms.in_whpdt
            $model1=InWhpdt::findOne(['invh_id'=>$id,'invh_status'=>[1,3]]);
            $model1->invh_status=4;
            $model1->can_reason=$data['InWhpdt']['can_reason'];
            if(!$model1->save()){
                return $this->error(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
            }
        }
        return $this->success('操作成功');
    }

    //上架
    public function actionPutAway($id,$staff_id='')
    {
        if($data=Yii::$app->request->post()){
            $transaction=InWhpdt::getDb()->beginTransaction();
            try{
                //wms.in_whpdt
                $model1=InWhpdt::findOne($id);
                $model1->invh_status=6;
                $model1->update_by=$data['InWhpdt']['update_by'];
                $model1->udate=$data['InWhpdt']['udate'];
                $model1->op_ip=$data['InWhpdt']['op_ip'];
                if(!$model1->save()){
                    throw new Exception(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //wms.in_whpdt_dt
                if(!empty($data['arr'])){
                    foreach($data['arr'] as $key=>$val){
                        $model2=InWhpdtDt::findOne($val['InWhpdtDt']['invl_id']);
                        $model2->st_codes=$val['InWhpdtDt']['st_codes'];
                        $model2->store_num=$val['InWhpdtDt']['store_num'];
                        $model2->inout_time=$val['InWhpdtDt']['inout_time'];
                        if(!$model2->save()){
                            throw new Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                        //wms.l_invt_re
                        $stCode=explode(",",$model2->st_codes);
                        $stNum=explode(",",$model2->store_num);
                        $whModel=BsWh::findOne(['wh_code'=>$model1->wh_code]);
                        $pnoModel=BsMaterial::findOne(['part_no'=>$model2->part_no]);
                        foreach($stCode as $k=>$v){
                            $model3=new LInvtRe();
                            $model3->l_types=1;
                            $model3->wh_code=$model1->wh_code;
                            $model3->wh_name=$whModel->wh_name;
                            $model3->st_code=$v;
                            $model3->l_r_no=$model1->invh_code;
                            $model3->batch_no=$model2->batch_no;
                            $model3->part_no=$model2->part_no;
                            $model3->pdt_name=$pnoModel->pdt_name;
                            $model3->unit_name=$pnoModel->unit;
                            $model3->lock_nums=0;
                            $model3->invt_nums=$stNum[$k];
                            $model3->opp_date=$data['InWhpdt']['udate'];
                            $model3->opper=$data['InWhpdt']['update_by'];
                            $model3->yn=0;
                            if(!$model3->save()){
                                throw new Exception(json_encode($model3->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                //wms.inv_changeh
                $sql="select d.chh_id 
                      from wms.in_whpdt a 
                      left join wms.rcp_goods b on b.rcpg_no = a.invh_aboutno
                      left join wms.rcp_notice c on c.rcpnt_no = b.rcpnt_no
                      left join wms.inv_changeh d on d.chh_code = c.prch_no
                      where a.invh_status = 5
                      and a.inout_type = 2
                      and a.invh_id = :id";
                $result=Yii::$app->db->createCommand($sql,[":id"=>$id])->queryOne();
                if(!empty($result)){
                    $model4=InvChangeh::findOne($result['chh_id']);
                    $model4->in_status=4;
                    $model4->save(false);
                }
                $transaction->commit();
                return $this->success('操作成功');
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile()."--".$e->getLine()."--".$e->getMessage());
            }
        }
        $sql="select a.invh_id,
                     b.wh_code,
                     b.wh_name,
                     c.bsp_svalue wh_attr,
                     ifnull(d.business_value,(case a.inout_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end)) inout_flag_val
              from wms.in_whpdt a
              left join wms.bs_wh b on b.wh_code = a.wh_code
              left join erp.bs_pubdata c on c.bsp_id = b.wh_attr
              left join erp.bs_business_type d on d.business_type_id = a.inout_flag
              where a.invh_id = :id 
              and a.invh_status = 5";
        $data['arr1']=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryOne();
        if(!empty($data['arr1'])){
            $sql="select a.staff_id,
                         a.staff_name
                  from erp.hr_staff a
                  where a.staff_id = :id";
            $staffInfo=Yii::$app->db->createCommand($sql,[':id'=>$staff_id])->queryOne();
            $data['arr1']=array_merge($data['arr1'],$staffInfo);
            $sql="select a.invl_id,
                         b.part_no,
                         b.pdt_name,
                         b.unit,
                         a.in_quantity,
                         a.real_quantity,
                         a.st_codes,
                         a.store_num,
                         a.inout_time
                  from wms.in_whpdt_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  where a.invh_id = :id";
            $data['arr2']=Yii::$app->db->createCommand($sql,[':id'=>$data['arr1']['invh_id']])->queryAll();
        }
        return $data;
    }
}
