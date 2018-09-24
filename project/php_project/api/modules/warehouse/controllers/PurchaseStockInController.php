<?php
/**
 * User: F1677929
 * Date: 2017/12/13
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\classes\Trans;
use app\modules\common\models\BsForm;
use app\modules\ptdt\models\BsMaterial;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\InWhpdt;
use app\modules\warehouse\models\InWhpdtDt;
use app\modules\warehouse\models\LInvtRe;
use app\modules\warehouse\models\RcpGoods;
use app\modules\warehouse\models\RcpNotice;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;
/**
 * 采购入库API控制器
 */
class PurchaseStockInController extends BaseActiveController
{
    public $modelClass="x";

    //列表
    public function actionList()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params)){
            //收货中心
            $receiptCenter=Yii::$app->db->createCommand("select rcp_no,rcp_name from wms.bs_receipt where 1 = 1 order by rcp_id asc")->queryAll();
            if(!empty($receiptCenter)){
                $receiptCenter=array_column($receiptCenter,"rcp_name","rcp_no");
            }
            return [
                "receiptCenter"=>$receiptCenter,
            ];
        }
        $sql="select a.invh_id,
                     a.invh_code,
                     case a.invh_status when 1 then '待提交' when 2 then '审核中' when 3 then '驳回' when 4 then '已取消' when 5 then '待上架' when 6 then '已上架' else '未知' end invh_status,
                     c.wh_name in_whname,
                     d.prch_no,
                     e.organization_name prch_depno,
                     f.rcp_name,
                     a.cdate,
                     g.staff_name update_by,
                     a.udate
              from wms.in_whpdt a
              left join wms.rcp_goods b on b.rcpg_no = a.invh_aboutno
              left join wms.bs_wh c on c.wh_code = a.wh_code
              left join wms.rcp_notice d on d.rcpnt_no = b.rcpnt_no
              left join erp.hr_organization e on e.organization_code = d.prch_depno
              left join wms.bs_receipt f on f.rcp_no = d.rcp_no
              left join erp.hr_staff g on g.staff_id = a.update_by
              where a.inout_type = 1";
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
        if(!empty($params['val6'])){
            $queryParams[':val6']=$params['val6'];
            $sql.=" and f.rcp_no = :val6";
        }
        if(!empty($params['val3'])){
            $params['val3']=str_replace(['%','_'],['\%','\_'],$params['val3']);
            $queryParams[':val3']='%'.$params['val3'].'%';
            $sql.=" and d.prch_no like :val3";
        }
        if(!empty($params['val4'])){
            $queryParams[':val4']=date('Y-m-d H:i:s',strtotime($params['val4']));
            $sql.=" and a.cdate >= :val4";
        }
        if(!empty($params['val5'])){
            $queryParams[':val5']=date('Y-m-d H:i:s',strtotime($params['val5'].'+1 day'));
            $sql.=" and a.cdate < :val5";
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

    //获取料号信息-列表、详情共用
    public function actionGetPno()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams=[":id"=>$params["id"]];
        $sql="select a.invl_id,
                     a.part_no,
                     b.pdt_name,
                     b.tp_spec,
                     b.brand,
                     b.unit,
                     e.bsp_svalue req_type,
                     d.ord_num,
                     a.real_quantity,
                     a.batch_no,
                     a.st_codes,
                     a.store_num,
                     substring(a.inout_time, 1, 10) inout_time,
                     substring(a.up_date, 1, 10) up_date,
                     f.group_code,
                     f.spp_fname
              from  wms.in_whpdt_dt a
              left join pdt.bs_material b on b.part_no = a.part_no
              left join wms.rcp_goods_dt c on c.detail_id = a.detail_id
              left join wms.rcp_notice_dt d on d.rcpdt_id = c.rcpdt_id
              left join erp.bs_pubdata e on e.bsp_id = d.req_type
              left join spp.bs_supplier f on f.group_code = d.spp_code
              where a.invh_id = :id";
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
                $whModel=BsWh::findOne(['wh_code'=>$model1->wh_code]);
                //wms.in_whpdt_dt
                if(!empty($data['arr'])){
                    foreach($data['arr'] as $key=>$val){
                        $model2=InWhpdtDt::findOne($val['InWhpdtDt']['invl_id']);
                        $model2->load($val);
                        if(!$model2->save()){
                            throw new Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                        //wms.l_invt_re
                        $stCode=explode(",",$model2->st_codes);
                        $stNum=explode(",",$model2->store_num);
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
                     c.bsp_svalue wh_attr
              from wms.in_whpdt a
              left join wms.bs_wh b on b.wh_code = a.wh_code
              left join erp.bs_pubdata c on c.bsp_id = b.wh_attr
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
                         a.real_quantity,
                         a.st_codes,
                         a.store_num,
                         substring(a.inout_time, 1, 10) inout_time
                  from wms.in_whpdt_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  where a.invh_id = :id";
            $data['arr2']=Yii::$app->db->createCommand($sql,[':id'=>$data['arr1']['invh_id']])->queryAll();
        }
        return $data;
    }

    //详情
    public function actionView($id)
    {
        $sql="select a.invh_id,
                     a.invh_code,
                     c.prch_no,
                     case a.invh_status when 1 then '待提交' when 2 then '审核中' when 3 then '驳回' when 4 then '已取消' when 5 then '待上架' when 6 then '已上架' else '未知' end invh_status,
                     d.wh_name,
                     d.wh_code,
                     e.bsp_svalue wh_attr,
                     f.organization_name prch_depno,
                     g.rcp_name,
                     a.cdate,
                     h.staff_name update_by,
                     a.udate
              from wms.in_whpdt a
              left join wms.rcp_goods b on b.rcpg_no = a.invh_aboutno
              left join wms.rcp_notice c on c.rcpnt_no = b.rcpnt_no
              left join wms.bs_wh d on d.wh_code = a.wh_code
              left join erp.bs_pubdata e on e.bsp_id = d.wh_attr
              left join erp.hr_organization f on f.organization_code = c.prch_depno
              left join wms.bs_receipt g on g.rcp_no = c.rcp_no
              left join erp.hr_staff h on h.staff_id = a.update_by
              where a.invh_id = :id";
        return Yii::$app->db->createCommand($sql,[":id"=>$id])->queryOne();
    }
}
