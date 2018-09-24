<?php
/**
 * User: F1677929
 * Date: 2017/12/13
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsForm;
use app\modules\hr\models\HrStaff;
use app\modules\purchase\models\BsPrch;
use app\modules\purchase\models\BsPrchDt;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\RcpGoods;
use app\modules\warehouse\models\RcpGoodsDt;
use app\modules\warehouse\models\RcpNotice;
use Yii;
use yii\data\SqlDataProvider;
use yii\db\Exception;

/**
 * 收货通知API控制器
 */
class ReceiptNoticeController extends BaseActiveController
{
    public $modelClass="x";

    //列表
    public function actionList()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.rcpnt_id,
                     a.rcpnt_no,
                     case a.rcpnt_status when 1 then '待收货' when 2 then '已收货' when 3 then '已取消' else '未知' end rcpnt_status,
                     case a.rcpnt_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end rcpnt_type,
                     b.wh_name,
                     c.organization_name prch_depno,
                     d.rcp_name,
                     a.prch_no,
                     e.staff_name creator,
                     substring(a.creat_date, 1, 10) creat_date,
                     f.staff_name operator,
                     substring(a.operate_date, 1, 10) operate_date
              from wms.rcp_notice a
              left join wms.bs_wh b on b.wh_code = a.o_whcode
              left join erp.hr_organization c on c.organization_code = a.prch_depno and c.organization_state = 10 and a.rcpnt_type = 1
              left join wms.bs_receipt d on d.rcp_no = a.rcp_no and a.rcpnt_type = 1
              left join erp.hr_staff e on e.staff_id = a.creator
              left join erp.hr_staff f on f.staff_id = a.operator
              where 1 = 1";
        //查询
        if(!empty($params['val1'])){
            $params['val1']=str_replace(['%','_'],['\%','\_'],$params['val1']);
            $queryParams[':val1']='%'.$params['val1'].'%';
            $sql.=" and a.rcpnt_no like :val1";
        }
        if(!empty($params['val2'])){
            $queryParams[':val2']=$params['val2'];
            $sql.=" and a.rcpnt_status = :val2";
        }
        if(!empty($params['val3'])){
            $queryParams[':val3']=$params['val3'];
            $sql.=" and a.rcpnt_type = :val3";
        }
        if(!empty($params['val4'])){
            $params['val4']=str_replace(['%','_'],['\%','\_'],$params['val4']);
            $queryParams[':val4']='%'.$params['val4'].'%';
            $sql.=" and a.prch_no like :val4";
        }
        if(!empty($params['val5'])){
            $queryParams[':val5']=date('Y-m-d H:i:s',strtotime($params['val5']));
            $sql.=" and a.creat_date >= :val5";
        }
        if(!empty($params['val6'])){
            $queryParams[':val6']=date('Y-m-d H:i:s',strtotime($params['val6'].'+1 day'));
            $sql.=" and a.creat_date < :val6";
        }
        $sql.=" order by a.rcpnt_id desc";
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
                     a.ord_num,
                     a.delivery_num,
                     substring(a.plan_date, 1, 10) plan_date,
                     a.ord_id,
                     a.invt_num,
                     a.before_stno,
                     a.chwh_num,
                     a.remarks,
                     c.group_code,
                     c.spp_fname
              from  wms.rcp_notice_dt a
              left join pdt.bs_material b on b.part_no = a.part_no
              left join spp.bs_supplier c on c.group_code = a.spp_code
              where a.rcpnt_no = :code";
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

    //生成收货单
    public function actionGenerateReceiptBill($id,$staff_id='')
    {
        if($data=Yii::$app->request->post()){
            $transaction=RcpNotice::getDb()->beginTransaction();
            try{
                //wms.rcp_notice
                $model1=RcpNotice::findOne($id);
                $model1->rcpnt_status=2;
                $model1->operator=$data['RcpNotice']['operator'];
                $model1->operate_date=$data['RcpNotice']['operate_date'];
                $model1->operate_ip=$data['RcpNotice']['operate_ip'];
                if(!$model1->save()){
                    throw new Exception(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //wms.rcp_goods
                $model2=new RcpGoods();
                $model2->rcpg_no=BsForm::getCode('wms.rcp_goods',$model2);
                $model2->rcpnt_no=$model1->rcpnt_no;
                $model2->rcpg_status=1;
                $model2->in_whcode=$model1->i_whcode;
                $model2->rcpg_type=$model1->rcpnt_type;
                $model2->deliverer=$data['RcpGoods']['deliverer'];
                $model2->deiver_tel=$data['RcpGoods']['deiver_tel'];
                $model2->consignee=$data['RcpGoods']['consignee'];
                $model2->con_tel=$data['RcpGoods']['con_tel'];
                $model2->creator=$data['RcpNotice']['operator'];
                $model2->creat_date=$data['RcpNotice']['operate_date'];
                $model2->operate_ip=$data['RcpNotice']['operate_ip'];
                if(!$model2->save()){
                    throw new Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //wms.rcp_goods_dt
                if(!empty($data['arr'])){
                    foreach($data['arr'] as $key=>$val){
                        $model3=new RcpGoodsDt();
                        $model3->rcpg_no=$model2->rcpg_no;
                        $model3->rcpdt_id=$val['RcpGoodsDt']['rcpdt_id'];
                        $model3->part_no=$val['RcpGoodsDt']['part_no'];
                        $model3->rcpg_num=$val['RcpGoodsDt']['rcpg_num'];
                        $model3->rcpg_date=$val['RcpGoodsDt']['rcpg_date'];
                        $model3->operator=$data['RcpNotice']['operator'];
                        $model3->operate_date=$data['RcpNotice']['operate_date'];
                        $model3->operate_ip=$data['RcpNotice']['operate_ip'];
                        if(!$model3->save()){
                            throw new Exception(json_encode($model3->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
                $transaction->commit();
                if($model1->rcpnt_type==1){//采购
                    //prch.bs_prch
                    if(empty($model1->prch_no)){
                        throw new Exception("wms.rcp_notice.prch_no不可为空");
                    }
                    $arr=explode(',',$model1->prch_no);
                    foreach($arr as $k=>$v){
                        $model4=BsPrch::findOne(['prch_no'=>$v]);
                        if(empty($model4)){
                            throw new Exception("prch.bs_prch模型不可为空");
                        }
                        //prch.bs_prch_dt
                        $arrModel=BsPrchDt::findAll(['prch_id'=>$model4->prch_id]);
                        $flag=true;
                        foreach($arrModel as $key=>$val){
                            $sql="select sum(c.rcpg_num) rcpg_num 
                                  from wms.rcp_notice a
                                  left join wms.rcp_notice_dt b on b.rcpnt_no = a.rcpnt_no
                                  left join wms.rcp_goods_dt c on c.rcpdt_id = b.rcpdt_id
                                  where a.rcpnt_type = 1
                                  and a.rcpnt_status = 2
                                  and b.prch_dt_id = :prch_dt_id
                                  group by b.prch_dt_id";
                            $result=Yii::$app->db->createCommand($sql,[':prch_dt_id'=>$val['prch_dt_id']])->queryOne();
                            if($result['rcpg_num']<$val['prch_num']){
                                $flag=false;
                                break;
                            }
                        }
                        if($flag){
                            $model4->prch_status=48;
                            $model4->save(false);
                        }
                    }
                }
                return $this->success('操作成功');
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile()."--".$e->getLine()."--".$e->getMessage());
            }
        }
        $sql="select a.rcpnt_id,
                     a.rcpnt_no,
                     b.organization_name,
                     c.rcp_name,
                     a.rcpnt_type,
                     a.creat_date,
                     a.prch_date
              from wms.rcp_notice a
              left join erp.hr_organization b on b.organization_code = a.prch_depno and b.organization_state = 10
              left join wms.bs_receipt c on c.rcp_no = a.rcp_no
              where a.rcpnt_id = :id 
              and a.rcpnt_status = 1";
        $data['arr1']=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryOne();
        if(!empty($data['arr1'])){
            $sql="select a.staff_id,
                         a.staff_name
                  from erp.hr_staff a
                  where a.staff_id = :id";
            $staffInfo=Yii::$app->db->createCommand($sql,[':id'=>$staff_id])->queryOne();
            $data['arr1']=array_merge($data['arr1'],$staffInfo);
            $sql="select a.rcpdt_id,
                         a.part_no,
                         b.pdt_name,
                         a.delivery_num,
                         b.unit,
                         a.ord_num,
                         a.chwh_num,
                         c.spp_fname
                  from wms.rcp_notice_dt a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  left join spp.bs_supplier c on c.group_code = a.spp_code
                  where a.rcpnt_no = :code";
            $data['arr2']=Yii::$app->db->createCommand($sql,[':code'=>$data['arr1']['rcpnt_no']])->queryAll();
        }
        return $data;
    }

    //取消收货
    public function actionCancelReceipt($id)
    {
        $arr1=[];
        $data=Yii::$app->request->post();
        $id=explode('-',$id);
        foreach($id as $key=>$val){
            //wms.rcp_notice
            $model1=RcpNotice::findOne(['rcpnt_id'=>$id,'rcpnt_status'=>1,'rcpnt_type'=>1]);
            if(empty($model1)){
                return $this->error("wms.rcp_notice模型不可为空");
            }
            $model1->cancel_reason=$data['RcpNotice']['cancel_reason'];
            $model1->rcpnt_status=3;
            if(!$model1->validate()){
                return $this->error(json_encode($model1->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            //采购
            if($model1->rcpnt_type==1){
                //prch.bs_prch
                if(empty($model1->prch_no)){
                    return $this->error("wms.rcp_notice.prch_no不可为空");
                }
                $arr=explode(',',$model1->prch_no);
                foreach($arr as $k=>$v){
                    $model2=BsPrch::findOne(['prch_no'=>$v]);
                    if(empty($model2)){
                        return $this->error("prch.bs_prch模型不可为空");
                    }
                    $model2->prch_status=49;
                    if(!$model2->save()){
                        return $this->error(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                    //增加邮件功能
                    if($model2->apper){
                        if(empty($arr1[$model2->apper])){
                            $arr1[$model2->apper]=$v;
                        }else{
                            $arr1[$model2->apper]=$arr1[$model2->apper].','.$v;
                        }
                    }
                }
            }
            $model1->save(false);
            foreach($arr1 as $key1=>$val1){
                $model3=HrStaff::findOne($key1);
                if($model3->staff_email){
                    $client = new \SoapClient('http://imes.foxconn.com/mailintoface.asmx?WSDL');
                    $client->send([
                        'from' => 'service@foxconnmall.com',
                        'toLst' => array($model3->staff_email),
                        'subject' => '采购单取消通知',
                        'body' => '您的采购单'.$val1.'在入库通知中有做取消操作。取消原因：'.$data['RcpNotice']['cancel_reason'],
                        'MessageType' => '邮件',
                        'isHtml' => 'true',
                        'strEncoding' => 'utf-8',
                    ]);
                }
            }
        }
        return $this->success('操作成功');
    }

    //详情
    public function actionView($id)
    {
        $sql="select a.rcpnt_id,
                     a.rcpnt_no,
                     case a.rcpnt_status when 1 then '待收货' when 2 then '已收货' when 3 then '已取消' else '未知' end rcpnt_status,
                     case a.rcpnt_type when 1 then '采购' when 2 then '调拨' when 3 then '移仓' else '未知' end rcpnt_type,
                     b.wh_name,
                     c.organization_name prch_depno,
                     d.rcp_name,
                     a.prch_no,
                     e.staff_name creator,
                     substring(a.creat_date, 1, 10) creat_date,
                     f.staff_name operator,
                     substring(a.operate_date, 1, 10) operate_date,
                     a.cancel_reason,
                     h.business_value chh_type,
                     i.wh_code o_wh_code,
                     i.wh_name o_wh_name,
                     j.bsp_svalue o_wh_attr,
                     k.wh_code i_wh_code,
                     k.wh_name i_wh_name,
                     l.bsp_svalue i_wh_attr,
                     g.o_status,
                     g.in_status,
                     n.organization_name depart_id
              from wms.rcp_notice a
              left join wms.bs_wh b on b.wh_code = a.o_whcode
              left join erp.hr_organization c on c.organization_code = a.prch_depno and c.organization_state = 10 and a.rcpnt_type = 1
              left join wms.bs_receipt d on d.rcp_no = a.rcp_no and a.rcpnt_type = 1
              left join erp.hr_staff e on e.staff_id = a.creator
              left join erp.hr_staff f on f.staff_id = a.operator
              left join wms.inv_changeh g on g.chh_code = a.prch_no and a.rcpnt_type = 2
              left join erp.bs_business_type h on h.business_type_id = g.chh_type
              left join wms.bs_wh i on i.wh_code = a.o_whcode and (a.rcpnt_type = 2 or a.rcpnt_type = 3)
              left join erp.bs_pubdata j on j.bsp_id = i.wh_attr
              left join wms.bs_wh k on k.wh_code = a.i_whcode and (a.rcpnt_type = 2 or a.rcpnt_type = 3)
              left join erp.bs_pubdata l on l.bsp_id = k.wh_attr
              left join erp.hr_organization n on n.organization_id = g.depart_id and n.organization_state = 10
              where a.rcpnt_id = :id";
        return Yii::$app->db->createCommand($sql,[":id"=>$id])->queryOne();
    }
}
