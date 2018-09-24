<?php

namespace app\modules\purchase\controllers;

use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\search\OrganizationSearch;
use app\modules\purchase\models\BsReq;
use app\modules\purchase\models\BsReqDt;
use app\modules\purchase\models\search\PurchaseApplySearch;
use app\modules\purchase\models\search\PurchaseNotifySearch;
use app\modules\sale\models\SaleInoutnoteh;
use app\modules\sale\models\SaleInoutnotel;
use app\modules\sale\models\SalePurchasenoteh;
use app\modules\sale\models\search\SaleCustrequireHSearch;
use app\modules\warehouse\models\SalePickingh;
use app\modules\warehouse\models\SalePickingl;
use Yii;
use  app\controllers\BaseActiveController;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use app\classes\Trans;
use app\modules\hr\models\HrOrganization;
use yii\web\NotFoundHttpException;

class PurchaseApplyController extends BaseActiveController
{
    public $modelClass = 'app\modules\purchase\models\BsReq';
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['rows'])){
            return [
                'req_dct'=>BsPubdata::getData(BsPubdata::REQ_DCT),
                'req_rqf'=>BsPubdata::getData(BsPubdata::REQ_RQF),
                'area_id'=>Yii::$app->db->createCommand("select a.factory_id,a.factory_name from erp.bs_factory a where a.factory_id IS NOT NULL ")->queryAll(),
                'req_dpt_id'=>Yii::$app->db->createCommand("select organization_id,organization_name from erp.hr_organization where organization_state = 10")->queryAll(),
                'leg_id'=>Yii::$app->db->createCommand("select company_id,company_name from erp.bs_company where company_status = 10")->queryAll(),
                'req_status'=>Yii::$app->db->createCommand("select prch_id,(CASE prch_name  when '請購未提交' THEN '未提交' when '請購審核中' then '审核中' when '請購駁回' then '驳回
' when '請購審核已取消' then '已取消' when '請購審核完成' then '审核完成' when '请购未提交' THEN '未提交' when '请购审核中' then '审核中' when '请购驳回' then '驳回
' when '请购审核已取消' then '已取消' when '请购审核完成' then '审核完成' else prch_name end)prch_name from prch.prch_status where prch_type = 0")->queryAll(),
                'com'=>Yii::$app->db->createCommand("select  i.prch_no,a.req_id from prch.bs_req_dt a  LEFT JOIN prch.r_req_prch g on g.req_dt_id=a.req_dt_id
                          LEFT JOIN prch.bs_prch_dt h on h.prch_dt_id=g.prch_dt_id
                          LEFT JOIN prch.bs_prch i on i.prch_id=h.prch_id where a.req_id is NOT NULL
                          ")->queryAll(),
            ];
        }
        $querySql="select a.req_id,
                          a.yn_can,
                          a.can_rsn,
                          a.req_no,
                          (case c.prch_name when '請購未提交' THEN '未提交' when '請購審核中' then '审核中' when '請購駁回' then '驳回
' when '請購審核已取消 ' then '已取消' when '請購審核完成' then '审核完成' when '请购未提交' THEN '未提交' when '请购审核中' then '审核中' when '请购驳回' then '驳回
' when '请购审核已取消' then '已取消' when '请购审核完成' then '审核完成' ELSE c.prch_name end)prch_name,
                          d.company_name,
                          e.organization_name,
                          m.staff_name, 
                          f.organization_name dd,
                          round(sum(b.total_amount),2) ff,
                          g.cur_code,
                          h.bsp_svalue,
                          i.bsp_svalue aa,
                          k.factory_name bb,
                          j.bsp_svalue cc,
                          LEFT (a.app_date,16)app_date,
                          a.req_status
                          from prch.bs_req a 
                          LEFT JOIN prch.bs_req_dt b ON a.req_id=b.req_id
                          LEFT JOIN prch.prch_status c ON a.req_status=c.prch_id
                          LEFT JOIN erp.bs_company d on a.leg_id=d.company_id
                          LEFT JOIN erp.hr_organization e on a.spp_dpt_id=e.organization_id
                          LEFT JOIN erp.hr_organization f on a.req_dpt_id=f.organization_id
                          LEFT JOIN erp. bs_currency g on a.cur_id=g.cur_id
                          LEFT JOIN erp.bs_pubdata h on a.req_dct=h.bsp_id
                          LEFT JOIN erp.bs_pubdata i on a.req_rqf=i.bsp_id
                          LEFT JOIN erp.bs_pubdata j on a.req_type=j.bsp_id
                          LEFT JOIN erp.bs_factory k on a.area_id=k.factory_id
                          LEFT join erp.hr_staff m on m.staff_id=a.app_id
                          WHERE a.req_id IS NOT NULL  
       
        ";
        $queryParams=[];
        if(!empty($params['req_dct'])){
            $trans=new Trans();
            $params['req_dct']=str_replace(['%','_'],['\%','\_'],$params['req_dct']);
            $queryParams[':req_dct1']='%'.$params['req_dct'].'%';
            $queryParams[':req_dct2']='%'.$trans->c2t($params['req_dct']).'%';
            $queryParams[':req_dct3']='%'.$trans->t2c($params['req_dct']).'%';
            $querySql.=" and (h.bsp_id like :req_dct1 or h.bsp_id like :req_dct2 or h.bsp_id like :req_dct3)";
        }
        if(!empty($params['leg_id'])){
            $trans=new Trans();
            $params['leg_id']=str_replace(['%','_'],['\%','\_'],$params['leg_id']);
            $queryParams[':leg_id1']='%'.$params['leg_id'].'%';
            $queryParams[':leg_id2']='%'.$trans->c2t($params['leg_id']).'%';
            $queryParams[':leg_id3']='%'.$trans->t2c($params['leg_id']).'%';
            $querySql.=" and (d.company_id like :leg_id1 or d.company_id like :leg_id2 or d.company_id like :leg_id3)";
        }
        if(!empty($params['req_rqf'])){
            $trans=new Trans();
            $params['req_rqf']=str_replace(['%','_'],['\%','\_'],$params['req_rqf']);
            $queryParams[':req_rqf1']='%'.$params['req_rqf'].'%';
            $queryParams[':req_rqf2']='%'.$trans->c2t($params['req_rqf']).'%';
            $queryParams[':req_rqf3']='%'.$trans->t2c($params['req_rqf']).'%';
            $querySql.=" and (i.bsp_id like :req_rqf1 or i.bsp_id like :req_rqf2 or i.bsp_id like :req_rqf3)";
        }
        if(!empty($params['area_id'])){
            $trans=new Trans();
            $params['area_id']=str_replace(['%','_'],['\%','\_'],$params['area_id']);
            $queryParams[':area_id1']='%'.$params['area_id'].'%';
            $queryParams[':area_id2']='%'.$trans->c2t($params['area_id']).'%';
            $queryParams[':area_id3']='%'.$trans->t2c($params['area_id']).'%';
            $querySql.=" and (k.factory_id like :area_id1 or k.factory_id like :area_id2 or k.factory_id like :area_id3)";
        }
        if(!empty($params['req_no'])){
            $trans=new Trans();
            $params['req_no']=str_replace(['%','_'],['\%','\_'],$params['req_no']);
            $queryParams[':req_no1']='%'.$params['req_no'].'%';
            $queryParams[':req_no2']='%'.$trans->c2t($params['req_no']).'%';
            $queryParams[':req_no3']='%'.$trans->t2c($params['req_no']).'%';
            $querySql.=" and (a.req_no like :req_no1 or a.req_no like :req_no2 or a.req_no like :req_no3)";
        }
        if(!empty($params['req_dpt_id'])){
            $trans=new Trans();
            $params['req_dpt_id']=str_replace(['%','_'],['\%','\_'],$params['req_dpt_id']);
            $queryParams[':req_dpt_id1']='%'.$params['req_dpt_id'].'%';
            $queryParams[':req_dpt_id2']='%'.$trans->c2t($params['req_dpt_id']).'%';
            $queryParams[':req_dpt_id3']='%'.$trans->t2c($params['req_dpt_id']).'%';
            $querySql.=" and (f.organization_id like :req_dpt_id1 or f.organization_id like :req_dpt_id2 or f.organization_id like :req_dpt_id3)";
        }
        if(!empty($params['req_status'])){
            $trans=new Trans();
            $params['req_status']=str_replace(['%','_'],['\%','\_'],$params['req_status']);
            $queryParams[':req_status1']='%'.$params['req_status'].'%';
            $queryParams[':req_status2']='%'.$trans->c2t($params['req_status']).'%';
            $queryParams[':req_status3']='%'.$trans->t2c($params['req_status']).'%';
            $querySql.=" and (c.prch_id like :req_status1 or c.prch_id like :req_status2 or c.prch_id like :req_status3)";
        }
        if(!empty($params['start_date'])){
            $queryParams[':start_date']=date('Y-m-d H:i:s',strtotime($params['start_date']));
            $querySql.=" and a.app_date >= :start_date";
        }
        if(!empty($params['end_date'])){
            $queryParams[':end_date']=date('Y-m-d H:i:s',strtotime($params['end_date'].'+1 day'));
            $querySql.=" and  a.app_date < :end_date";
        }
        $querySql.=" GROUP BY a.req_id order by a.app_date desc";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a",$queryParams)->queryScalar();
//        $date=Yii::$app->db->createCommand($querySql)->queryAll();
       $provider=new SqlDataProvider([
            'db'=>'db',
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        return  [
            'sql'=>$provider->sql,
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount,
        ];
    }
     //商品详情
    public function actionCommodity()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams = [':id' => $params['id']];
//        $attrModel = new BsQstAnsw();
//        $attrModel->answ_id = $id;
        if (empty($params['rows'])) {
            return Yii::$app->db->createCommand("select a.req_id from prch.bs_req a where a.req_id = :id", $queryParams)->queryOne();
        }
        $querySql="select a.req_dt_id,
                          b.part_no,
                          b.tp_spec,
                          a.remarks,
                          a.spp_id,
                          a.rebat_rate,
                          round(a.org_price,5)org_price,
                          a.req_date,
                          j.bsp_svalue,
                          round(a.req_price,5)req_price,
                          round(a.req_nums,2)req_nums,
                          b.pdt_name,
                          b.unit,
                          b.brand,
                          round(a.total_amount,2)total_amount,
                          a.prj_no,
                          e.req_id, 
                          k.quote_price,
                          g.prch_dt_id,
                          i.prch_no,
                          i.prch_id
                          from prch.bs_req_dt a 
                          LEFT JOIN prch.bs_req e ON a.req_id=e.req_id
                          LEFT JOIN pdt.bs_material b ON a.part_no=b.part_no
                          LEFT JOIN pdt.pdtprice_pas k ON k.part_no=a.part_no
                          LEFT JOIN wms.bs_sit_invt f ON f.part_no=b.part_no                                          
                          LEFT JOIN prch.r_req_prch g on g.req_dt_id=a.req_dt_id
                          LEFT JOIN prch.bs_prch_dt h on h.prch_dt_id=g.prch_dt_id
                          LEFT JOIN prch.bs_prch i on i.prch_id=h.prch_id
                          LEFT join erp.bs_pubdata j ON j.bsp_id=a.exp_account
                          where a.req_id={$queryParams[':id']}
                          ";
        $queryParams=[];
        $querySql.=" group by a.req_dt_id order BY a.req_dt_id";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'db'=>'db',
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>10
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
    }
    // 新增
    public function actionCreates(){
        $purApply = new applymodel();
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->oms->beginTransaction();
        try {
            $purApply->load($post);
            if (!$purApply->save()) {
                throw new \Exception('新增请购单失败！');
            }
            $transaction->commit();
            return $this->success('新增请购单成功！');
        } catch (\Exception $e) {
            $transaction->rollback();
            return $this->error('新增请购单失败！');
        }
    }

    //修改
    public function actionUpdate($id)
    {
        $data=Yii::$app->request->post();
        $model = BsReq::findOne($id);
        if($model->load($data) && $model->save()){
            return $this->success('修改成功');
        }
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }

    //取消
    public function actionCanRsn($id)
    {
        $_id=explode(',',$id);
        $_succ="操作失败";
        foreach ($_id as $vid) {
            $data = Yii::$app->request->post();
            $model = BsReq::findOne($vid);
            $model->req_status = BsReq::REQUEST_STATUS_STATUS;
            $model->yn_can = BsReq::REQUEST_STATUS_CLOSE;
            if ($model->load($data) && $model->save()) {
                $_succ= $this->success('操作成功');
            }
        }
        return $_succ;
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }

    //取消按钮
    public function actionCancels()
    {
        $post=Yii::$app->request->queryParams;
        try{
            $array = $post['id'];
            for ($i=0;$i<count($array);$i++) {
                $model = BsReq::findOne($array[$i]);
                $model->yn_can= BsReq::REQUEST_STATUS_CLOSE;
                if(!$model->save()){
                    throw  new \Exception("取消失败");
                };
            }
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }

    //选择商品
    public function actionSelectProduct()
    {
        $model = new PurchaseApplySearch();
        $dataProvider = $model->searchProducts(Yii::$app->request->queryParams);
        return [
            'rows' => $dataProvider->getModels(),
            'total' => $dataProvider->totalCount,
        ];
    }

    // 根据料号获取信息
    public function actionGetPdt($pdt_no)
    {
        $model = new SaleCustrequireHSearch();
        return $model->searchPdt($pdt_no);
    }

//    // 通知单详情
//    public function actionView($id)
//    {
//        $model = new PurchaseNotifySearch();
////        $model->searchOrderH($id);
//        $dataProviderH = current($model->searchNotifyH($id)->getModels());
//        $dataProviderL = $model->searchNotifyL($id)->getModels();
//        $data['products'] = $dataProviderL;
//        $data = array_merge($dataProviderH, $data);
//        return $data;
//    }

    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        // 单据类型 对应business中的采购订单
        $downList['billType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'puord'])->all();
        $downList['orgList'] = HrOrganization::getOrgAllLevel(0);
        // 订单类型
//        $downList['orderType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'order'])->all();
        // 交易法人
//        $downList['corporate'] = BsCompany::find()->select(['company_id', 'company_name'])->all();
        // 通知单状态
        $downList['notfy_status'] = [
            SalePurchasenoteh::STATUS_DEFAULT => '待处理',
            SalePurchasenoteh::STATUS_PURCHASING => '采购中',
            SalePurchasenoteh::STATUS_PURCHASED => '已采购',
        ];
        // 付款方式
//        $downList['payment'] = BsPayment::find()->select(['pac_id', 'pac_code', 'pac_sname'])->all();
        // 支付类型
//        $downList['payType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::PAY_TYPE])->all();
        // 付款条件
//        $downList['payCondition'] = BsPayCondition::find()->select(['pat_id', 'pat_sname'])->all();
        // 交易方式（交易模式）
//        $downList['pattern'] = BsTransaction::find()->select(['tac_id','tac_sname'])->all();
        // 订单来源
        $downList['orderFrom'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::ORDER_FROM])->all();
        // 发票类型
//        $downList['invoiceType'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::CRM_INVOICE_TYPE])->all();
        // 交易币别
//        $downList['currency'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::SUPPLIER_TRADE_CURRENCY])->all();
        // 运输方式
//        $downList['transport'] = BsTransport::find()->select(['tran_id', 'tran_code', 'tran_sname'])->all();
        // 配送方式
//        $downList['dispatching'] = BsDeliverymethod::find()->select(['bdm_id', 'bdm_code', 'bdm_sname'])->all();
        // 仓库信息
//        $downList['warehouse'] = BsWh::find()->select(['wh_id', 'wh_code', 'wh_name'])->all();
        return $downList;
    }

    // 点击主表获取子表商品信息
    public function actionGetProducts()
    {
        $params = Yii::$app->request->queryParams;
        $model = new PurchaseNotifySearch();
        $model = $model->searchOrderProducts($params);
        return $model;
    }

    // 生成采购单
    public function actionCreatePick()
    {
        $post = Yii::$app->request->post();
        // 查找信息
        $transaction = Yii::$app->oms->beginTransaction();
        try {
            $notifyH = SaleInoutnoteh::findOne($post['id']);
            if ($notifyH->notfy_status == 1) {
                $notifyH->notfy_status = $notifyH::STATUS_PICKING;
                $pickH = new SalePickingh();
                $pickH->p_bill_id = $notifyH->sonh_id;
                $pickH->bill_type = $pickH::SALE_OUT;
                $pickH->whs_id = $notifyH->whs_id;
                $pickH->create_by = $post['staff_id'];
                $pickH->update_by = $post['staff_id'];
                if (!$notifyH->save()) {
                    throw new \Exception(current($notifyH->getFirstErrors()));
                }
                if (!$pickH->save()) {
                    throw new \Exception(current($pickH->getFirstErrors()));
                }
                $notifyL = SaleInoutnotel::find()->where(['sonh_id'=>$notifyH->sonh_id])->all();
                foreach ($notifyL as $k=>$v) {
                    $pickL = new SalePickingl();
                    $pickL->soph_id = $pickH->soph_id;
                    $pickL->poh_id = $notifyH->bill_id;
                    $pickL->pol_id = $v->lbill_id;
                    $pickL->pdt_id = $v->pdt_id;
                    $pickL->p_bill_hid = $notifyH->sonh_id;
                    $pickL->p_bill_lid = $v->sonl_id;
                    if (!$pickL->save()) {
                        throw new \Exception(current($pickL->getFirstErrors()));
                    }
                }
            } else {
                throw new \Exception('只有待处理状态才能生成拣货单');
            }
//            throw new \Exception(current($notifyH->getFirstErrors()));
            $transaction->commit();
            return $this->success('生成拣货单成功！');
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    // 取消通知
    public function actionCancelNotify($id)
    {
        $post = Yii::$app->request->post();

        $notifyH = SalePurchasenoteh::findOne($id);
        if ($notifyH->notfy_status == '1') {
            $notifyH->notfy_status = $notifyH::STATUS_CANCEL;
            $notifyH->notify_descr .= '  取消原因：' . $post['reason'];
            if (!$notifyH->save()) {
                return $this->error(current($notifyH->getFirstErrors()));
            } else {
                return $this->success('取消通知成功！');
            }
        } else {
            return $this->error('只有待处理状态可以取消通知！');
        }
    }
    public function actionModels($id){
        //请购单信息
        $sql="select s.recer as restid,s.req_dpt_id as reque_id,s.t_amount_addfax,s.req_status,s.yn_can,s.can_rsn,s.req_dct as req_dct_id,s.req_no,DATE_FORMAT(s.app_date,'%Y/%m/%d')app_date,s.contact,s.agr_code,s.prj_code,s.scrce,s.rec_cont,s.remarks,yn_lead,yn_mul_dpt,yn_aff,yn_three,yn_eqp_budget,yn_low_value,
        yn_fix_cntrl, yn_req,(select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.req_rqf)req_rqf ,s.req_rqf as req_rqf_id,
        (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.req_type)req_type ,
        (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.req_dct )req_dct,
        (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.cst_type )cst_type,
        (select b.factory_name from erp.bs_factory  b where b.factory_id=s.area_id )area_id,
        (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.mtr_ass )mtr_ass,
        (select c.company_name from erp.bs_company c WHERE c.company_id=s.leg_id)leg_id,
        (select h.organization_name from erp.hr_organization h WHERE h.organization_id=s.spp_dpt_id)spp_dpt_id,
        (select h.organization_name from erp.hr_organization h WHERE h.organization_id=s.req_dpt_id)req_dpt_id,
        (select h.organization_name from erp.hr_organization h WHERE h.organization_id=s.e_dpt_id)e_dpt_id,
        (SELECT hs.staff_name from erp.hr_staff hs where hs.staff_id=s.app_id)app_id,
        (SELECT hs.staff_name from erp.hr_staff hs where hs.staff_id=s.recer)recer,
        (SELECT hs.staff_code from erp.hr_staff hs where hs.staff_id=s.recer)hrcode,
        (select b.cur_code FROM erp. bs_currency b where b.cur_id=s.cur_id)cur_id,
				(select rcp_name from wms.bs_receipt where rcp_no=s.addr)addr
        from (select * FROM prch.bs_req  WHERE req_id=:req_id)s";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':req_id', $id)->queryOne();
        //商品信息
//        $sql1="select ss.prt_pkid, ss.part_no,ss.pdt_name,ss.tp_spec, p.bsp_svalue as unit,b.brand_name_cn,ss.req_nums,ss.req_price,ss.spp_id,ss.total_amount,
//        ss.bsp_svalue as bs_req_dt,ss.org_price,ss.req_date,ss.prj_no,ss.rebat_rate,ss.remarks from(select s.prt_pkid,s.req_date,s.prj_no,s.org_price,s.rebat_rate,
//        s.bsp_svalue, s.part_no,s.remarks,s.tp_spec,s.req_nums,s.req_price ,s.spp_id,s.total_amount,p.brand_id,p.unit,p.pdt_name
//        from(SELECT o.wh_id,o.tp_spec,o.pdt_PKID,o.prt_pkid, o.part_no,b.req_nums,b.req_price,b.total_amount,b.exp_account,b.req_date,b.prj_no,b.org_price,
//        b.rebat_rate,b.remarks,p.bsp_svalue,b.spp_id FROM prch.bs_req_dt b
//        left join pdt.bs_partno o on b.prt_pkid=o.prt_pkid
//        left join erp.bs_pubdata p on p.bsp_id=b.exp_account where req_id=:req_id)s
//        left join pdt.bs_product p on  p.pdt_PKID=s.pdt_PKID)ss
//        left join erp.bs_pubdata p on p.bsp_id=ss.unit
//        left join pdt.bs_brand b on b.brand_id=ss.brand_id";
        $sql1="SELECT o.tp_spec,o.part_no,o.brand,o.pdt_name,o.unit,b.req_nums,b.req_price,b.total_amount,b.exp_account,b.req_date,b.prj_no,b.org_price,
        b.rebat_rate,b.remarks,p.bsp_svalue,b.spp_id FROM prch.bs_req_dt b 
        left join pdt.bs_material o on b.part_no=o.part_no 
        left join erp.bs_pubdata p on p.bsp_id=b.exp_account where req_id=:req_id";
        $prtinfo = Yii::$app->db->createCommand($sql1)->bindValue(':req_id', $id)->queryAll();
        $infoall=[$basicinfo,$prtinfo];
        if($infoall!==null){
            return $infoall;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }

    //获取新增页面的下拉页面
    public function actionDawnListByCreate($id)
    {
        $downList['req_dct']=BsPubdata::getList(BsPubdata::REQ_DCT); //單據類型
        $downList['req_rqf']=BsPubdata::getList(BsPubdata::REQ_RQF);  //請購形式
//        $downList['area_id']=BsPubdata::getList(BsPubdata::AREA_ID);   //采购区域
        $downList['cst_type']=BsPubdata::getList(BsPubdata::CST_TYPE);  //费用类型
        $downList['req_type']=BsPubdata::getList(BsPubdata::REQ_TYPE); //采购方式
        $downList['mtr_ass']=BsPubdata::getList(BsPubdata::MTR_ASS);  //物料归属
//        $downList['cur_id']=BsPubdata::getList(BsPubdata::SUPPLIER_TRADE_CURRENCY); //币别
        $sqlls="SELECT t.cur_id,t.cur_code FROM erp.bs_currency t";
        $downList['cur_id']=Yii::$app->db->createCommand($sqlls)->queryAll();  //币别
        $sqlarea="SELECT f.factory_id,f.factory_name
            from erp.r_user_area_dt t
            LEFT JOIN erp.bs_factory f ON t.area_pkid=f.factory_id 
            LEFT JOIN erp.user s ON t.user_id=s.user_id
            WHERE s.staff_id=".$id;
        $downList['area_id']=Yii::$app->db->createCommand($sqlarea)->queryAll();
        return $downList;
    }

    //获取采购部门
    public function actionSelectDepart()
    {
        $searchModel=new OrganizationSearch();
        $dataProvider = $searchModel->searchDepart(Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //获取配送地点
    public function actionSelectPlace()
    {
//        $searchModel = new SetWarehouseSearch();
//        $dataProvider = $searchModel->searchPlace(Yii::$app->request->queryParams);
        $params=Yii::$app->request->queryParams;
        $sql="SELECT t.rcp_no,t.rcp_name FROM wms.bs_receipt t WHERE t.rcp_status='Y'";
        $queryParams=[];
        if(!empty($params['keyWord'])){
            $trans=new Trans();
            $params['rcp_no']=str_replace(['%','_'],['\%','\_'],$params['keyWord']);
            $sql.=" and (t.rcp_no like '%".$params['keyWord']."%' ) or 
            (t.rcp_name like '%".$trans->c2t($params['keyWord'])."%') or 
            (t.rcp_name like '%".$trans->t2c($params['keyWord'])."%') or
             (t.rcp_name like '%".$params['keyWord']."%')
            ";
        }
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        $model = $provider->getModels();
        $list['rows'] = $model;
        $list['total'] = $provider->totalCount;
        return $list;
    }
    //获取商品详细信息
    public function actionGetPurchaseProduct()
    {
        $params=Yii::$app->request->queryParams;
        $arr = [];
        foreach ($params['id'] as $k => $id)
        {
            //未关联供应商
            $sql="SELECT
                    r.part_no,
                    r.pdt_name,
                    r.tp_spec,
                    r.brand,
                    r.unit
                FROM pdt.pdtprice_pas s
                LEFT JOIN pdt.bs_material r ON s.part_no=r.part_no
   --             LEFT JOIN spp.bs_supplier su ON su.group_code=s.supplier_code
                WHERE s.effective_date <= NOW() AND s.expiration_date >= NOW()
    --            AND su.spp_status=3
                AND s.status=3
                AND s.part_no='".$id."' GROUP BY r.part_no";
            $provider=new SqlDataProvider([
                'sql'=>$sql,
            ]);
            $arr[$k] = $provider->getModels();
        }
        return [
            'rows'=>$arr
        ];
    }

    //获取单笔料号数据
    public function actionGetPurchaseProducts($id)
    {
            $sql="SELECT
                    r.part_no,
                    r.pdt_name,
                    r.tp_spec,
                    r.brand,
                    r.unit
                FROM pdt.pdtprice_pas s
                LEFT JOIN pdt.bs_material r ON s.part_no=r.part_no
 --               LEFT JOIN spp.bs_supplier su ON su.group_code=s.supplier_code
                WHERE s.effective_date <= NOW() AND s.expiration_date >= NOW()
  --              AND su.spp_status=3
                and s.status=3
                AND s.part_no='".$id."' GROUP BY r.part_no";
            $provider=new SqlDataProvider([
                'sql'=>$sql,
            ]);
            $arr = $provider->getModels();
        return [
            'rows'=>$arr
        ];
    }

    //新增请购单
    public function actionCreate()
    {
        if($data=Yii::$app->request->post()){
            $transaction=BsReq::getDb()->beginTransaction();
            try{
                //供应商主表
                $bsreq=new BsReq();
                $bsreq->req_status=30;
                $bsBusType=Yii::$app->db->createCommand("select a.business_type_id from erp.bs_business_type a where a.business_code = 'reqer'")->queryOne();
                if(empty($bsBusType)){
                    throw new Exception('获取请购单据类型失败');
                }
                //$bsreq->req_dct=$bsBusType['business_type_id'];
                $bsreq->req_no=BsForm::getCode("bs_req",$bsreq);
                if($bsreq->load($data)){
                    if(!$bsreq->save()){
                        throw new Exception(json_encode($bsreq->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('请购主表加载失败');
                }
                //请购单明细表
                if(!empty($data['prod'])){
                    foreach($data['prod'] as $key=>$val){
                        if(!empty($val['BsReqDt']['part_no'])){
                            $bsreqdt=new BsReqDt();
                            $bsreqdt->req_id=$bsreq->req_id;
                            if($bsreqdt->load($val)){
                                if(!$bsreqdt->save()){
                                    throw new Exception(json_encode($bsreqdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('请购单明细表');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success('新增成功',[
                    'id'=>$bsreq->req_id,
                    'code'=>$bsreq->req_no,
                    'typeId'=>$bsBusType['business_type_id']
                ]);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    //修改
    public function actionEdit($id)
    {
        if($data=Yii::$app->request->post())
        {
            $transaction=BsReq::getDb()->beginTransaction();
            try
            {
                //请购主表
                $bsReq=BsReq::findOne($id);
                if($bsReq->load($data)){
                    if(!$bsReq->save())
                    {
                        throw new Exception(json_encode($bsReq->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('请购主表更新失败');
                }
                BsReqDt::deleteAll(['req_id'=>$id]);
                if(!empty($data['prod'])){
                    foreach($data['prod'] as $key=>$val){
                        if(!empty($val['BsReqDt']['part_no'])){
                            $reqPurpdt=new BsReqDt();
                            $reqPurpdt->req_id=$bsReq->req_id;
                            if($reqPurpdt->load($val)){
                                if(!$reqPurpdt->save()){
                                    throw new Exception(json_encode($reqPurpdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('请购子表商品加载失败');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success('修改成功',[
                    'id'=>$id,
                    'code'=>$bsReq->req_no,
                    'typeId'=>54
                ]);
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    //编辑获取model
    public function actionEditModels($id){
        //请购单信息
        $sql="select s.recer as restid,s.req_dpt_id as reque_id,s.t_amount_addfax,
              s.req_status,s.yn_can,s.can_rsn,s.req_dct as req_dct_id,s.addr,
              s.req_no,DATE_FORMAT(s.app_date,'%Y/%m/%d')app_date,s.contact,
              s.agr_code,s.prj_code,s.scrce,s.rec_cont,s.remarks,
              yn_lead,yn_mul_dpt,yn_aff,yn_three,yn_eqp_budget,yn_low_value,
              yn_fix_cntrl, yn_req,
              (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.req_rqf)req_rqf ,
              s.req_rqf as req_rqf_id,
              (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.req_type)req_type ,
              (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.req_dct )req_dct,
              (SELECT t.rcp_name FROM wms.bs_receipt t WHERE t.rcp_no=s.addr)qgaddr,
              (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.cst_type )cst_type,
              (select b.factory_name from erp.bs_factory b where b.factory_id=s.area_id )area_id,
              (select b.bsp_svalue from erp.bs_pubdata b where b.bsp_id=s.mtr_ass )mtr_ass,
              (select c.company_name from erp.bs_company c WHERE c.company_id=s.leg_id)leg_id,
              (select h.organization_name from erp.hr_organization h WHERE h.organization_id=s.spp_dpt_id)spp_dpt_id,
              (select h.organization_name from erp.hr_organization h WHERE h.organization_id=s.req_dpt_id)req_dpt_id,
              (select h.organization_name from erp.hr_organization h WHERE h.organization_id=s.e_dpt_id)e_dpt_id,
              (SELECT hs.staff_name from erp.hr_staff hs where hs.staff_id=s.app_id)app_id,
              (SELECT hs.staff_name from erp.hr_staff hs where hs.staff_id=s.recer)recer,
              (SELECT hs.staff_code from erp.hr_staff hs where hs.staff_id=s.recer)hrcode,
              (select b.cur_code FROM erp. bs_currency b where b.cur_id=s.cur_id)cur_id
        from (select * FROM prch.bs_req  WHERE req_id=:req_id)s";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':req_id', $id)->queryOne();
        //商品信息
        $sql1=" SELECT p.part_no,m.pdt_name,m.tp_spec,m.brand,m.unit,p.req_dt_id,p.req_id,
                p.req_nums,p.req_date,p.prj_no,p.remarks
                FROM prch.bs_req_dt p 
                LEFT JOIN  pdt.bs_material  m ON p.part_no = m.part_no
                WHERE p.req_id=:req_id";
        $prtinfo = Yii::$app->db->createCommand($sql1)->bindValue(':req_id', $id)->queryAll();
        $infoall=[$basicinfo,$prtinfo];
        if($infoall!==null){
            return $infoall;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }




   //导出
    public function actionExport()
    {

        $params=Yii::$app->request->queryParams;
//        return $params['req_no'];
//        return $params;
//        dump($params['req_no']);
//        $queryParams=[':id'=>$params['req_no']];
//        return $params;
//        $params['req_dct']=$params;
        $sql="select     h.bsp_id,
                          a.req_id,
                          a.yn_can,
                          a.can_rsn,
                          a.req_no,
                          (case c.prch_name when '請購未提交' THEN '未提交' when '請購審核中' then '审核中' when '請購駁回' then '驳回
' when '請購審核已取消' then '已取消' when '請購審核完成' then '审核完成' WHEN '请购未提交' THEN '未提交' when '请购审核中' then '审核中' when '请购驳回' then '驳回
' when '请购审核已取消' then '已取消' when '请购审核完成' then '审核完成' ELSE c.prch_name end)prch_name,
                          d.company_name,
                          e.organization_name,
                          m.staff_name,
                          f.organization_name dd,
--                          round(sum(b.total_amount),2) ff,
                          g.cur_code,
                          h.bsp_svalue,
                          i.bsp_svalue aa,
                          j.bsp_svalue cc,
                           k.factory_name bb,
                          LEFT (a.app_date,16)app_date,
                          a.req_status
                         
                          from prch.bs_req a 
                          LEFT JOIN prch.bs_req_dt b ON a.req_id=b.req_id
                          LEFT JOIN prch.prch_status c ON a.req_status=c.prch_id
                          LEFT JOIN erp.bs_company d on a.leg_id=d.company_id
                          LEFT JOIN erp.hr_organization e on a.spp_dpt_id=e.organization_id
                          LEFT JOIN erp.hr_organization f on a.req_dpt_id=f.organization_id
                          LEFT JOIN erp. bs_currency g on a.cur_id=g.cur_id
                          LEFT JOIN erp.bs_pubdata h on a.req_dct=h.bsp_id
                          LEFT JOIN erp.bs_pubdata i on a.req_rqf=i.bsp_id
                          LEFT JOIN erp.bs_pubdata j on a.req_type=j.bsp_id
                          LEFT JOIN erp.bs_factory k on a.area_id=k.factory_id
                          LEFT join erp.hr_staff m on m.staff_id=a.app_id
                          WHERE a.req_id IS NOT NULL 
                          ";
        $queryParams=[];
        if(!empty($params['req_no'])){
            $trans=new Trans();
            $params['req_no']=str_replace(['%','_'],['\%','\_'],$params['req_no']);
            $queryParams[':req_no1']='%'.$params['req_no'].'%';
            $queryParams[':req_no2']='%'.$trans->c2t($params['req_no']).'%';
            $queryParams[':req_no3']='%'.$trans->t2c($params['req_no']).'%';
            $sql.=" and (a.req_no like :req_no1 or a.req_no like :req_no2 or a.req_no like :req_no3)";
        }
        if(!empty($params['req_dct'])){
            $sql.=" and h.bsp_id ='".$params['req_dct']."'";
        }
        if(!empty($params['leg_id'])){
            $sql.=" and d.company_id ='".$params['leg_id']."'";
        }
        if(!empty($params['req_rqf'])){
            $sql.=" and i.bsp_id ='".$params['req_rqf']."'";
        }
        if(!empty($params['area_id'])){
            $trans=new Trans();
            $params['area_id']=str_replace(['%','_'],['\%','\_'],$params['area_id']);
            $queryParams[':area_id1']='%'.$params['area_id'].'%';
            $queryParams[':area_id2']='%'.$trans->c2t($params['area_id']).'%';
            $queryParams[':area_id3']='%'.$trans->t2c($params['area_id']).'%';
            $sql.=" and (k.factory_id like :area_id1 or k.factory_id like :area_id2 or k.factory_id like :area_id3)";
        }
        if(!empty($params['req_dpt_id'])){
            $sql.=" and f.organization_id ='".$params['req_dpt_id']."'";
        }
        if(!empty($params['req_status'])){
            $sql.=" and c.prch_id='".$params['req_status']."'";
        }
        if(!empty($params['start_date'])){
//            $queryParams[':start_date']=date('Y-m-d H:i:s',strtotime($params['start_date']));
            $sql.=" and a.app_date >='".$params['start_date']."'";
        }
        if(!empty($params['end_date'])){
            $sql.=" and a.app_date <='".$params['end_date']."'";
        }
        $sql.=" GROUP BY a.req_id order by a.app_date desc";
        $data=Yii::$app->db->createCommand($sql,$queryParams)->queryAll();
//        $index=0;
//        $data['tr']=[];
        $date['tr']=$data;
//        if (!empty($date)) {
//            foreach ($date['tr'] as $key => $val) {
//                $date['tr'][$key]['req_id'] = $index;
//                $index++;
//            }
//        }
        return $date;
    }

    //选择商品new
    public function actionProdSelect()
    {
        $params=\Yii::$app->request->queryParams;
        //请购暂不关联供应商spp.bs_supplier
        $sql="SELECT
                f.part_no,
				r.pdt_name,
				r.tp_spec,
				r.brand,
				r.unit,
				c3.catg_name
                FROM pdt.pdtprice_pas f
                LEFT JOIN pdt.bs_material r ON r.part_no=f.part_no
  --              LEFT JOIN spp.bs_supplier su ON su.group_code=f.supplier_code
                LEFT JOIN pdt.bs_category c1 ON c1.catg_no = SUBSTR(r.category_no,1,6)
                LEFT JOIN pdt.bs_category c2 ON c2.catg_id = c1.p_catg_id
                LEFT JOIN pdt.bs_category c3 ON c3.catg_id = c2.p_catg_id
                WHERE f.effective_date <= NOW() AND f.expiration_date >= NOW()
 --               AND su.spp_status=3
                AND f.status=3";
        $queryParams=[];
        if(!empty($params['kwd'])){
            $trans=new Trans();
            $params['kwd']=str_replace(['%','_'],['\%','\_'],$params['kwd']);
            $sql.=" and( (f.part_no like '%".$params['kwd']."%' ) or
            (r.pdt_name like '%".$trans->c2t($params['kwd'])."%') or
            (r.pdt_name like '%".$trans->t2c($params['kwd'])."%') or
             (r.pdt_name like '%".$params['kwd']."%'))
            ";
        }
        if(!empty($params['catg_id']))
        {
            $params['catg_id']=str_replace(['%','_'],['\%','\_'],$params['catg_id']);
            $sql.=" and ((c1.catg_id ='".$params['catg_id']."')or
                        (c2.catg_id = '".$params['catg_id']."')or
                        (c3.catg_id = '".$params['catg_id']."')) GROUP BY f.part_no";
        }
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
//        $query=BsPartnoSelectorShow::find()->joinWith("product",false)
//            ->leftJoin(BsCategory::tableName()." c1","c1.catg_id=".BsProduct::tableName().".catg_id")
//            ->leftJoin(BsCategory::tableName()." c2","c2.catg_id=c1.p_catg_id")
//            ->leftJoin(BsCategory::tableName()." c3","c3.catg_id=c2.p_catg_id");
//        $provider=new ActiveDataProvider([
//            "query"=>$query,
//            "pagination"=>[
//                "pageParam"=>"page",
//                "pageSizeParam"=>"rows"
//            ]
//        ]);
//
//        if(isset($params["filters"])){
//            $filters=explode(",",$params["filters"]);
//            $query->andFilterWhere(["not in",BsPartno::tableName().".prt_pkid",$filters]);
//        }
//
//        if(isset($params["kwd"])){
//            $query->andFilterWhere([
//                "or",
//                ["like","pdt_name",$params["kwd"]],
//                ["like","part_no",$params["kwd"]],
//            ]);
//        }
//
//        if(isset($params["catg_id"])){
//            $query->andFilterWhere([
//                "or",
//                ["c1.catg_id"=>$params["catg_id"]],
//                ["c2.catg_id"=>$params["catg_id"]],
//                ["c3.catg_id"=>$params["catg_id"]]
//            ]);
//        }
        return [
            "rows"=>$provider->models,
            "total"=>$totalCount
        ];
    }

    //获取商品类别
    public function actionGetNextLevel($id){
        if(empty($id)){
            return BsCategory::find()->select("catg_name")->where(["catg_level"=>1,"isvalid"=>1])->asArray()->indexBy("catg_id")->column();
        }
        return BsCategory::find()->select("catg_name")->where(["p_catg_id"=>$id,"isvalid"=>1])->asArray()->indexBy("catg_id")->column();
    }

    //订单商品请购
    public function actionSpecificForm($id)
    {
        $sql="SELECT
                    `pdt`.`pdt_name`,
                    `pdt`.`pdt_no`,
                     pdt.func_get_pcategory (ctg.catg_id) AS ctg_pname,
                    `bp`.`bsp_svalue` AS `unit_name`,
                    `fpprice`.`tp_spec`,
                    `fpprice`.`brand`,
                    `partno`.`part_no`,
                    `odl`.`sapl_quantity`,
	                `odl`.`request_date`
                FROM
                    `oms`.`ord_dt` `odl`
                LEFT JOIN `pdt`.`bs_partno` `partno` ON partno.prt_pkid = odl.prt_pkid
                LEFT JOIN `pdt`.`fp_price` `fpprice` ON partno.part_no = fpprice.part_no
                LEFT JOIN `pdt`.`bs_product` `pdt` ON partno.pdt_pkid = pdt.pdt_pkid
                LEFT JOIN `pdt`.`bs_category` `ctg` ON ctg.catg_id = pdt.catg_id
                LEFT JOIN `erp`.`bs_pubdata` `bp` ON bp.bsp_id = pdt.unit
                LEFT JOIN `oms`.`ord_info` `odh` ON odh.ord_id = odl.ord_id
                LEFT JOIN `pdt`.`bs_price` `price` ON odl.prt_pkid = price.prt_pkid
                AND odh.cur_id = price.currency
                WHERE 1=1
                AND ((`sapl_status` != 0) AND (`odl`.`ord_id` = :ord_id))
                GROUP BY `ord_dt_id`";
        $ret=Yii::$app->db->createCommand($sql,['ord_id' => $id])->queryAll();
        return $ret;
    }

}
