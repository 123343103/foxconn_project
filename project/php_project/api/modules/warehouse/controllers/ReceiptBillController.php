<?php
/**
 * User: F1677929
 * Date: 2017/12/13
 */
namespace app\modules\warehouse\controllers;
use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsForm;
use app\modules\warehouse\models\InWhpdt;
use app\modules\warehouse\models\InWhpdtDt;
use app\modules\warehouse\models\RcpGoods;
use app\modules\warehouse\models\RcpNotice;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use yii\helpers\Html;

/**
 * 收货单API控制器
 */
class ReceiptBillController extends BaseActiveController
{
    public $modelClass="x";

    //列表
    public function actionList()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.rcpg_id,
                     a.rcpg_no,
                     case a.rcpg_status when 1 then '待入库' when 2 then '已入库' when 3 then '已取消' else '未知' end rcpg_status,
                     case a.rcpg_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end rcpg_type,
                     c.wh_name o_whcode,
                     d.wh_name i_whcode,
                     e.organization_name prch_depno,
                     f.rcp_name,
                     substring(a.creat_date, 1, 10) creat_date,
                     b.prch_no,
                     g.staff_name operator,
                     substring(a.operate_date, 1, 10) operate_date
              from wms.rcp_goods a
              left join wms.rcp_notice b on b.rcpnt_no = a.rcpnt_no
              left join wms.bs_wh c on c.wh_code = b.o_whcode
              left join wms.bs_wh d on d.wh_code = a.in_whcode
              left join erp.hr_organization e on e.organization_code = b.prch_depno
              left join wms.bs_receipt f on f.rcp_no = b.rcp_no
              left join erp.hr_staff g on g.staff_id = a.operator
              where 1 = 1";
        //查询
        if(!empty($params['val1'])){
            $params['val1']=str_replace(['%','_'],['\%','\_'],$params['val1']);
            $queryParams[':val1']='%'.$params['val1'].'%';
            $sql.=" and a.rcpg_no like :val1";
        }
        if(!empty($params['val2'])){
            $queryParams[':val2']=$params['val2'];
            $sql.=" and a.rcpg_status = :val2";
        }
        if(!empty($params['val3'])){
            $queryParams[':val3']=$params['val3'];
            $sql.=" and a.rcpg_type = :val3";
        }
        if(!empty($params['val4'])){
            $params['val4']=str_replace(['%','_'],['\%','\_'],$params['val4']);
            $queryParams[':val4']='%'.$params['val4'].'%';
            $sql.=" and b.prch_no like :val4";
        }
        if(!empty($params['val5'])){
            $queryParams[':val5']=date('Y-m-d H:i:s',strtotime($params['val5']));
            $sql.=" and a.creat_date >= :val5";
        }
        if(!empty($params['val6'])){
            $queryParams[':val6']=date('Y-m-d H:i:s',strtotime($params['val6'].'+1 day'));
            $sql.=" and a.creat_date < :val6";
        }
        $sql.=" order by a.rcpg_id desc";
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

    //获取料号信息-列表、详情共用
    public function actionGetPno()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams=[":code"=>$params["code"]];
        $sql="select a.rcpdt_id,
                     a.part_no,
                     b.pdt_name,
                     b.tp_spec,
                     b.brand,
                     b.unit,
                     c.bsp_svalue req_type,
                     a.ord_num,
                     a.delivery_num,
                     substring(a.plan_date, 1, 10) plan_date,
                     a.ord_id,
                     a.invt_num,
                     a.before_stno,
                     a.chwh_num,
                     d.rcpg_num,
                     substring(d.rcpg_date, 1, 10) rcpg_date,
                     e.group_code,
                     e.spp_fname
              from  wms.rcp_notice_dt a
              left join pdt.bs_material b on b.part_no = a.part_no
              left join erp.bs_pubdata c on c.bsp_id = a.req_type
              left join wms.rcp_goods_dt d on d.rcpdt_id = a.rcpdt_id
              left join spp.bs_supplier e on e.group_code = a.spp_code
              where d.rcpg_no = :code";
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

    //入库
    public function actionStockIn($id,$staff_id='')
    {
        if($data=Yii::$app->request->post()){
            $transaction=RcpGoods::getDb()->beginTransaction();
            try{
                //wms.rcp_goods
                $model1=RcpGoods::findOne($id);
                $model1->rcpg_status=2;
                $model1->in_whcode=$data['RcpGoods']['in_whcode'];
                $model1->operator=$data['RcpGoods']['operator'];
                $model1->operate_date=$data['RcpGoods']['operate_date'];
                $model1->operate_ip=$data['RcpGoods']['operate_ip'];
                if(!$model1->save()){
                    throw new Exception(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //wms.in_whpdt
                $model2=new InWhpdt();
                if($model1->rcpg_type==1){
                    $model2->codeType=10;
                }
                if($model1->rcpg_type==2 || $model1->rcpg_type==3){
                    $model2->codeType=20;
                }
                $model2->invh_code=BsForm::getCode('wms.in_whpdt',$model2);
                $model2->invh_aboutno=$model1->rcpg_no;
                $model2->invh_status=5;
                $model2->inout_type=$model1->rcpg_type;
                $model2->create_by=$data['RcpGoods']['operator'];
                $model2->cdate=$data['RcpGoods']['operate_date'];
                $model2->op_ip=$data['RcpGoods']['operate_ip'];
                $model2->wh_code=$data['RcpGoods']['in_whcode'];
                if(!$model2->save()){
                    throw new Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //wms.in_whpdt_dt
                if(!empty($data['arr'])){
                    foreach($data['arr'] as $key=>$val){
                        $model3=new InWhpdtDt();
                        $model3->invh_id=$model2->invh_id;
                        $model3->detail_id=$val['InWhpdtDt']['detail_id'];
                        $model3->part_no=$val['InWhpdtDt']['part_no'];
                        $model3->real_quantity=$val['InWhpdtDt']['real_quantity'];
                        $model3->batch_no=$val['InWhpdtDt']['batch_no'];
                        $model3->st_codes=$val['InWhpdtDt']['st_codes'];
                        $model3->store_num=$val['InWhpdtDt']['store_num'];
                        $model3->inout_time=$val['InWhpdtDt']['inout_time'];
                        if(!$model3->save()){
                            throw new Exception(json_encode($model3->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
                $transaction->commit();
                return $this->success('操作成功');
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile()."--".$e->getLine()."--".$e->getMessage());
            }
        }
        $sql="select a.rcpg_id,
                     a.rcpg_no,
                     case a.rcpg_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end rcpg_type,
                     b.wh_code,
                     b.wh_name,
                     c.bsp_svalue wh_attr
              from wms.rcp_goods a
              left join wms.bs_wh b on b.wh_code = a.in_whcode and (a.rcpg_type = 2 or a.rcpg_type = 3)
              left join erp.bs_pubdata c on c.bsp_id = b.wh_attr
              where a.rcpg_id = :id 
              and a.rcpg_status = 1";
        $data['arr1']=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryOne();
        if(!empty($data['arr1'])){
            $sql="select a.staff_id,
                         a.staff_name
                  from erp.hr_staff a
                  where a.staff_id = :id";
            $staffInfo=Yii::$app->db->createCommand($sql,[':id'=>$staff_id])->queryOne();
            $data['arr1']=array_merge($data['arr1'],$staffInfo);
            $sql="select a.detail_id,
                         b.part_no,
                         b.pdt_name,
                         b.unit,
                         a.rcpg_num,
                         a.rcpg_date,
                         c.ord_id
                  from wms.rcp_goods_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  left join wms.rcp_notice_dt c on c.rcpdt_id = a.rcpdt_id
                  where a.rcpg_no = :code";
            $data['arr2']=Yii::$app->db->createCommand($sql,[':code'=>$data['arr1']['rcpg_no']])->queryAll();
            if($data['arr1']['rcpg_type']=="采购"){
                $data['arr3']=Yii::$app->db->createCommand("select a.wh_code,a.wh_name from wms.bs_wh a where a.wh_state = 'Y' order by a.wh_id desc")->queryAll();
                if(!empty($data['arr3'])){
                    $data['arr3']=array_column($data['arr3'],"wh_name","wh_code");
                }
            }
        }
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
        $r=Yii::$app->db->createCommand($sql,[":id"=>$id])->queryOne();
        $r['wh_code']=Html::decode($r['wh_code']);
        return $r;
    }

    //取消入库
    public function actionCancelStockIn($id)
    {
        $data=Yii::$app->request->post();
        $id=explode('-',$id);
        foreach($id as $key=>$val){
            //wms.rcp_goods
            $model1=RcpGoods::findOne(['rcpg_id'=>$id,'rcpg_status'=>1,'rcpg_type'=>1]);
            if(empty($model1)){
                return $this->error("wms.rcp_goods模型不可为空");
            }
            $model1->cancel_reason=$data['RcpGoods']['cancel_reason'];
            $model1->rcpg_status=3;
            $model1->load($data);
            if(!$model1->validate()){
                return $this->error(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //wms.rcp_notice
            $model2=RcpNotice::findOne(['rcpnt_no'=>$model1->rcpnt_no,'rcpnt_status'=>2,'rcpnt_type'=>1]);
            if(empty($model2)){
                return $this->error("wms.rcp_notice模型不可为空");
            }
            $model2->rcpnt_status=1;
            if(!$model2->validate()){
                return $this->error(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            $model1->save(false);
            $model2->save(false);
        }
        return $this->success('操作成功');
    }

    //详情
    public function actionView($id)
    {
        $sql="select a.rcpg_no,
                     a.rcpnt_no,
                     case a.rcpg_status when 1 then '待入库' when 2 then '已入库' when 3 then '已取消' else '未知' end rcpg_status,
                     case a.rcpg_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end rcpg_type,
                     c.organization_name prch_depno,
                     d.rcp_name,
                     a.deliverer,
                     a.deiver_tel,
                     a.consignee,
                     a.con_tel,
                     substring(a.creat_date, 1, 10) creat_date,
                     e.staff_name operator,
                     substring(a.operate_date, 1, 10) operate_date,
                     a.cancel_reason,
                     g.business_value chh_type,
                     h.organization_name depart_id,
                     i.wh_code o_wh_code,
                     i.wh_name o_wh_name,
                     j.bsp_svalue o_wh_attr,
                     k.wh_code i_wh_code,
                     k.wh_name i_wh_name,
                     l.bsp_svalue i_wh_attr,
                     f.o_status,
                     f.in_status
              from wms.rcp_goods a
              left join wms.rcp_notice b on b.rcpnt_no = a.rcpnt_no
              left join erp.hr_organization c on c.organization_code = b.prch_depno and b.rcpnt_type = 1
              left join wms.bs_receipt d on d.rcp_no = b.rcp_no and b.rcpnt_type = 1
              left join erp.hr_staff e on e.staff_id = a.operator
              left join wms.inv_changeh f on f.chh_code = b.prch_no and b.rcpnt_type = 2
              left join erp.bs_business_type g on g.business_type_id = f.chh_type
              left join erp.hr_organization h on h.organization_id = f.depart_id
              left join wms.bs_wh i on i.wh_code = b.o_whcode and (b.rcpnt_type = 2 or b.rcpnt_type = 3)
              left join erp.bs_pubdata j on j.bsp_id = i.wh_attr
              left join wms.bs_wh k on k.wh_code = b.i_whcode and (b.rcpnt_type = 2 or b.rcpnt_type = 3)
              left join erp.bs_pubdata l on l.bsp_id = k.wh_attr
              where a.rcpg_id = :id";
        return Yii::$app->db->createCommand($sql,[":id"=>$id])->queryOne();
    }

    //获取储位
    public function actionSelectLocation()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams=[":code"=>$params['code']];
        $sql="select c.st_id,
                     a.wh_name,
                     b.part_code,
                     b.part_name,
                     c.rack_code,
                     c.st_code
              from wms.bs_wh a
              left join wms.bs_part b on b.wh_code = a.wh_code
              left join wms.bs_st c on c.part_code = b.part_code
              where a.wh_code = :code
              and a.wh_state = 'Y'
              and b.YN = 1
              and c.YN = 'Y'";
        //查询
        if(!empty($params['val1'])){
            $params['val1']=str_replace(['%','_'],['\%','\_'],$params['val1']);
            $queryParams[':val1']='%'.$params['val1'].'%';
            $sql.=" and b.part_code like :val1";
        }
        if(!empty($params['val2'])){
            $params['val2']=str_replace(['%','_'],['\%','\_'],$params['val2']);
            $queryParams[':val2']='%'.$params['val2'].'%';
            $sql.=" and c.rack_code like :val2";
        }
        if(!empty($params['val3'])){
            $params['val3']=str_replace(['%','_'],['\%','\_'],$params['val3']);
            $queryParams[':val3']='%'.$params['val3'].'%';
            $sql.=" and c.st_code like :val3";
        }
        $sql.=" order by c.st_id desc";
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
}
