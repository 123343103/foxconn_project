<?php

namespace app\modules\warehouse\controllers;

use app\modules\common\models\BsBusinessType;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\HrStaff;
use app\modules\purchase\models\BsReq;
use app\modules\warehouse\models\PdtInventory;
use app\modules\purchase\models\BsReqDt;
use app\modules\purchase\models\search\PurchaseNotifySearch;
use app\modules\sale\models\SalePurchasenoteh;
use app\modules\warehouse\models\PdtInventoryDt;
use Yii;
use  app\controllers\BaseActiveController;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use app\classes\Trans;
use app\modules\hr\models\HrOrganization;

class CheckListController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\PdtInventory';
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['rows'])){
            return [
                'legal_code'=>Yii::$app->db->createCommand("select company_id,company_name from erp.bs_company where company_status = 10")->queryAll(),
                'wh_name'=>Yii::$app->db->createCommand("select wh_code,wh_name,wh_state from wms.bs_wh where wh_state ='Y' ")->queryAll(),
            ];
        }
        $querySql="select a.ivt_id,
                          a.ivt_code,
                          c.company_name,
                          (
                            CASE a.stage
                            WHEN '1' THEN
                                '第一期'
                            WHEN '2' THEN
                                '第二期'
                            WHEN '3' THEN
                                '第三期'
                            WHEN '4' THEN
                                '第四期'
                            ELSE
                                a.stage
                            END
                        ) stage,
                          b.wh_name,
                          a.wh_code,
                          LEFT (a.expiry_date,16)expiry_date,
                          d.staff_name first_ivtor,
                          LEFT (a.first_date,10)first_date,
                          e.staff_name re_ivtor,
                          LEFT (a.re_date,10)re_date,
                         (
                            CASE a.ivt_status
                            WHEN '0' THEN
                                '审核中'
                            WHEN '1' THEN
                                '待提交'
                            WHEN '2' THEN
                                '信息完善中'
                            WHEN '3' THEN
                                '审核完成'
                            WHEN '4' THEN
                                '驳回'
                            WHEN '5' THEN
                                '单据已取消'
                            ELSE
                                '未知'
                            END
                        ) ivt_status
                   from wms.pdt_inventory a
                   LEFT JOIN wms.bs_wh b on a.wh_code=b.wh_code
                   LEFT JOIN erp.bs_company c on a.legal_code=c.company_id
                   LEFT JOIN erp.hr_staff d ON d.staff_id=a.first_ivtor
                   LEFT JOIN erp.hr_staff e ON e.staff_id=a.re_ivtor
                   where a.ivt_id is NOT NULL             
        ";
        $queryParams=[];
        if(!empty($params['ivt_code'])){
            $trans=new Trans();
            $params['ivt_code']=str_replace(['%','_'],['\%','\_'],$params['ivt_code']);
            $queryParams[':ivt_code1']='%'.$params['ivt_code'].'%';
            $queryParams[':ivt_code2']='%'.$trans->c2t($params['ivt_code']).'%';
            $queryParams[':ivt_code3']='%'.$trans->t2c($params['ivt_code']).'%';
            $querySql.=" and(a.ivt_code like :ivt_code1 or a.ivt_code like :ivt_code2 or a.ivt_code like :ivt_code3)";
        }
        if(isset($params['ivt_status']) && $params['ivt_status'] != ''){
            $querySql.=" and a.ivt_status={$params['ivt_status']}";
        }
        if(!empty($params['stage'])){
            $querySql.=" and a.stage={$params['stage']}";
        }
        if(!empty($params['legal_code'])){
            $querySql.=" and c.company_id={$params['legal_code']}";
        }
        if(!empty($params['wh_name'])){
            $querySql.=" and a.wh_code='{$params['wh_name']}'";
        }
        if(!empty($params['wh_code'])){
            $trans=new Trans();
            $params['wh_code']=str_replace(['%','_'],['\%','\_'],$params['wh_code']);
            $queryParams[':wh_code1']='%'.$params['wh_code'].'%';
            $queryParams[':wh_code2']='%'.$trans->c2t($params['wh_code']).'%';
            $queryParams[':wh_code3']='%'.$trans->t2c($params['wh_code']).'%';
            $querySql.=" and(a.wh_code like :wh_code1 or a.wh_code like :wh_code2 or a.wh_code like :wh_code3)";
        }
        if(!empty($params['start_date'])){
            $queryParams[':start_date']=date('Y-m-d H:i:s',strtotime($params['start_date']));
            $querySql.=" and a.first_date >= :start_date";
        }
        if(!empty($params['end_date'])){
            $queryParams[':end_date']=date('Y-m-d H:i:s',strtotime($params['end_date'].'+1 day'));
            $querySql.=" and  a.first_date < :end_date";
        }
        $querySql.=" group by a.ivt_code order by a.ivt_id desc";
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
    //盘点商品信息
    public function actionCommodity()
    {
        $params=Yii::$app->request->queryParams;
        $queryParams = [':id' => $params['id']];
        if (empty($params['rows'])) {
            return Yii::$app->db->createCommand("select a.ivt_id from wms.pdt_inventory a where a.ivt_id = :id", $queryParams)->queryOne();
        }
        $querySql="select a.ivtdt_id,
                          a.ivt_code,
                          a.part_no,
                          b.pdt_name,
                          b.tp_spec,
                          b.unit,
                          d.invt_num,
                          a.first_num,
                          a.re_num,
                          lose_num,
                          a.lose_price,
                          a.remarks,
                          a.remarks1
                   from wms.pdt_inventory_dt a
                   LEFT JOIN pdt.bs_material b ON a.part_no=b.part_no
                   LEFT JOIN pdt.bs_product c ON a.pdt_id=c.pdt_pkid
                   LEFT JOIN wms.pdt_inventory e ON e.ivt_code=a.ivt_code
                   LEFT JOIN wms.bs_wh_invt d ON b.part_no=d.part_no AND e.wh_code=d.wh_code
                   WHERE a.ivt_code='{$queryParams[':id']}'
                          ";
        $queryParams=[];
        $querySql.=" group by a.ivtdt_id order BY a.ivtdt_id desc";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ({$querySql}) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'db'=>'db',
            'sql'=>$querySql,
            'params'=>$queryParams,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount
        ];
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
            $model =PdtInventory::findOne($vid);
            $model->ivt_status = PdtInventory::REQUEST_STATUS_STATUS;
            if ($model->save()) {
                $_succ= $this->success('操作成功');
            }
        }
        return $_succ;
    }

    //盘点单明细
    public function actionDetail()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['rows'])){
            return [
                'legal_code'=>Yii::$app->db->createCommand("select company_id,company_name from erp.bs_company where company_status = 10")->queryAll(),
                'wh_name'=>Yii::$app->db->createCommand("select wh_code,wh_name,wh_state from wms.bs_wh where wh_state ='Y' ")->queryAll(),
            ];
        }
        $querySql="SELECT
                        a.ivt_id,
                        a.ivt_code,
                        c.company_name,
                        (
                            CASE a.stage
                            WHEN '1' THEN
                                '第一期'
                            WHEN '2' THEN
                                '第二期'
                            WHEN '3' THEN
                                '第三期'
                            WHEN '4' THEN
                                '第四期'
                            ELSE
                                a.stage
                            END
                        ) stage,
                        b.wh_name,
                        a.wh_code,
                        LEFT (a.expiry_date, 16) expiry_date,
                        d.part_no,
                        e.pdt_name,
                        e.tp_spec,
                        e.unit,
                        k.invt_num,
                        i.staff_name first_ivtor,
                        d.first_num,
                        LEFT (a.first_date, 10) first_date,
                        j.staff_name re_ivtor,
                        d.re_num,
                        LEFT (a.re_date, 10) re_date,
                        d.lose_num,
                        d.lose_price,
                        d.remarks,
                        d.remarks1,
                        -- LEFT (a.expiry_date, 10) expiry_date,
                        (
                            CASE a.ivt_status
                            WHEN '0' THEN
                                '审核中'
                            WHEN '1' THEN
                                '待提交'
                            WHEN '2' THEN
                                '信息完善中'
                            WHEN '3' THEN
                                '审核完成'
                            WHEN '4' THEN
                                '驳回'
                            WHEN '5' THEN
                                '单据已取消'
                            ELSE
                                '未知'
                            END
                        ) ivt_status
                    FROM
                        wms.pdt_inventory a
                    LEFT JOIN wms.bs_wh b ON a.wh_code = b.wh_code
                    LEFT JOIN erp.bs_company c ON a.legal_code = c.company_id
                    LEFT JOIN wms.pdt_inventory_dt d ON d.ivt_code = a.ivt_code
                    LEFT JOIN pdt.bs_material e ON d.part_no = e.part_no
                    LEFT JOIN pdt.bs_product f ON d.pdt_id = f.pdt_pkid
                    LEFT JOIN wms.bs_wh h ON h.wh_code = a.wh_code
                    LEFT JOIN wms.bs_wh_invt k on k.wh_code=a.wh_code AND k.part_no=d.part_no
                    LEFT JOIN erp.hr_staff i ON i.staff_id=a.first_ivtor
                    LEFT JOIN erp.hr_staff j ON j.staff_id=a.re_ivtor
                    WHERE
                        a.ivt_id IS NOT NULL      
        ";
        $queryParams=[];
        if(!empty($params['ivt_code'])){
            $trans=new Trans();
            $params['ivt_code']=str_replace(['%','_'],['\%','\_'],$params['ivt_code']);
            $queryParams[':ivt_code1']='%'.$params['ivt_code'].'%';
            $queryParams[':ivt_code2']='%'.$trans->c2t($params['ivt_code']).'%';
            $queryParams[':ivt_code3']='%'.$trans->t2c($params['ivt_code']).'%';
            $querySql.=" and(a.ivt_code like :ivt_code1 or a.ivt_code like :ivt_code2 or a.ivt_code like :ivt_code3)";
        }
        if(!empty($params['stage'])){
            $querySql.=" and a.stage={$params['stage']}";
        }
        if(!empty($params['legal_code'])){
            $querySql.=" and c.company_id={$params['legal_code']}";
        }
        if (isset($params['stage'])&& $params['stage']!=""){
         return 111;
        }
        if(!empty($params['wh_name'])){
            $querySql.=" and a.wh_code='{$params['wh_name']}'";
        }
        if(!empty($params['wh_code'])){
            $trans=new Trans();
            $params['wh_code']=str_replace(['%','_'],['\%','\_'],$params['wh_code']);
            $queryParams[':wh_code1']='%'.$params['wh_code'].'%';
            $queryParams[':wh_code2']='%'.$trans->c2t($params['wh_code']).'%';
            $queryParams[':wh_code3']='%'.$trans->t2c($params['wh_code']).'%';
            $querySql.=" and(a.wh_code like :wh_code1 or a.wh_code like :wh_code2 or a.wh_code like :wh_code3)";
        }
        if(!empty($params['part_no'])){
            $trans=new Trans();
            $params['part_no']=str_replace(['%','_'],['\%','\_'],$params['part_no']);
            $queryParams[':part_no1']='%'.$params['part_no'].'%';
            $queryParams[':part_no2']='%'.$trans->c2t($params['part_no']).'%';
            $queryParams[':part_no3']='%'.$trans->t2c($params['part_no']).'%';
            $querySql.=" and(d.part_no like :part_no1 or d.part_no like :part_no2 or d.part_no like :part_no3)";
        }
        if(!empty($params['pdt_name'])){
            $trans=new Trans();
            $params['pdt_name']=str_replace(['%','_'],['\%','\_'],$params['pdt_name']);
            $queryParams[':pdt_name1']='%'.$params['pdt_name'].'%';
            $queryParams[':pdt_name2']='%'.$trans->c2t($params['pdt_name']).'%';
            $queryParams[':pdt_name3']='%'.$trans->t2c($params['pdt_name']).'%';
            $querySql.=" and(e.pdt_name like :pdt_name1 or e.pdt_name like :pdt_name2 or e.pdt_name like :pdt_name3)";
        }
        $querySql.=" order by a.ivt_id desc";
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
        return  [
            'rows'=>$provider->getModels(),
            'total'=>$provider->totalCount,
        ];
    }
    // 通知单详情
    public function actionView($id)
    {
        $model = new PurchaseNotifySearch();
//        $model->searchOrderH($id);
        $dataProviderH = current($model->searchNotifyH($id)->getModels());
        $dataProviderL = $model->searchNotifyL($id)->getModels();
        $data['products'] = $dataProviderL;
        $data = array_merge($dataProviderH, $data);
        return $data;
    }

    // 下拉列表
    public function actionGetDownList()
    {
        $downList = [];
        $downList['billType'] = BsBusinessType::find()->select(['business_type_id', 'business_value'])->where(['business_code' => 'puord'])->all();
        $downList['orgList'] = HrOrganization::getOrgAllLevel(0);

        $downList['notify_status'] = [
            SalePurchasenoteh::STATUS_DEFAULT => '待处理',
            SalePurchasenoteh::STATUS_PURCHASING => '采购中',
            SalePurchasenoteh::STATUS_PURCHASED => '已采购',
        ];
        $downList['orderFrom'] = BsPubdata::find()->select(['bsp_id', 'bsp_svalue'])->where(['bsp_stype' => BsPubdata::ORDER_FROM])->all();
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

    public function actionModels($id,$code){
        //盘点单信息
        $sql="select      a.ivt_id,
                          a.ivt_code,
                          a.legal_code,
                          c.company_name,
                          a.stage st,
                         (CASE a.stage when '1' THEN '第一期' when '2' THEN '第二期' WHEN '3' THEN '第三期'
                          WHEN '4' THEN '第四期' ELSE a.stage END )stage,
                          b.wh_name,
                          a.curency_id,                     
                          d.cur_code,
                          a.wh_code,
                          LEFT (a.expiry_date,16)expiry_date,
                          e.staff_name first_ivtor,
                          LEFT (a.first_date,10)first_date,
                          f.staff_name re_ivtor,
                          LEFT (a.re_date,10)re_date,
                          a.re_ivtor re,
                          a.ivt_status state,
                          (CASE a.ivt_status WHEN '0' then '审核中' when '1' THEN '待提交' WHEN '2' then '信息完善中'
                          WHEN '3' THEN '审核完成' WHEN '4' THEN '驳回' WHEN '5' THEN '单据已取消' ELSE '未知' END )ivt_status
                   from wms.pdt_inventory a
                   LEFT JOIN wms.bs_wh b on a.wh_code=b.wh_code
                   LEFT JOIN erp.bs_currency d ON d.cur_id=a.curency_id
                   LEFT JOIN erp.bs_company c on a.legal_code=c.company_id
                   LEFT JOIN erp.hr_staff e ON e.staff_id=a.first_ivtor
                   LEFT JOIN erp.hr_staff f ON f.staff_id=a.re_ivtor
                   WHERE a.ivt_id=:ivt_id";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':ivt_id', $id)->queryOne();
        //商品信息
        $sql1="SELECT
                    a.ivtdt_id,
                    a.ivt_code,
                    a.part_no,
                    b.pdt_name,
                    b.tp_spec,
                    b.unit,
                    g.invt_num,
                    a.first_num,
                    a.re_num,
                    lose_num,
                    a.lose_price,
                    a.remarks,
                    a.remarks1
                FROM
                    wms.pdt_inventory_dt a
                LEFT JOIN pdt.bs_material b ON a.part_no = b.part_no
                LEFT JOIN pdt.bs_product c ON a.pdt_id = c.pdt_pkid
                LEFT JOIN wms.pdt_inventory e ON e.ivt_code = a.ivt_code
                LEFT JOIN wms.bs_wh f ON f.wh_code = e.wh_code
                LEFT JOIN wms.bs_wh_invt g ON g.wh_code =e.wh_code
                AND g.part_no = a.part_no
                WHERE
                    a.ivt_code = :ivt_code
                GROUP BY
                    a.ivtdt_id";
        $prtinfo = Yii::$app->db->createCommand($sql1)->bindValue(':ivt_code', $code)->queryAll();
        $infoall=[$basicinfo,$prtinfo];
        if($infoall!==null){
            return $infoall;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }
    public function actionCode($code){
        $sql="
        select a.ivt_code
        from wms.pdt_inventory a 
        WHERE a.ivt_id=$code
        ";
        $db = Yii::$app->db->createCommand($sql)->queryAll();
        return $db[0]['ivt_code'];
    }
    //获取商品详细信息
    public function actionGetPurchaseProduct()
    {
        $params=Yii::$app->request->queryParams;
        $arr = [];
        foreach ($params['id'] as $k => $id)
        {
            $sql="SELECT 
                    r.part_no,
					r.pdt_name,
					r.tp_spec,
					r.brand,
					r.unit,
					sum(a.invt_num)invt_num
                FROM pdt.bs_material r
                LEFT JOIN wms.bs_wh_invt a ON r.part_no=a.part_no
                LEFT JOIN wms.bs_wh b ON a.wh_code=b.wh_code
                    WHERE r.part_no='".$id."'
                    and b.wh_code='".$params['wh']."'
                    ";
            $sql.=" group by r.part_no";
            $provider=new SqlDataProvider([
                'sql'=>$sql,
            ]);
            $arr[$k] = $provider->getModels();
        }
        return [
            'rows'=>$arr
        ];
    }
    //新增请购单
    public function actionCreate()
    {
        if($data=Yii::$app->request->post()){
            $transaction=PdtInventory::getDb()->beginTransaction();
            try{
                //盘点主表
                $PdtInventory=new PdtInventory();
                $PdtInventory->ivt_status=2;
                $PdtInventory->ivt_code=BsForm::getCode("pdt_inventory",$PdtInventory);
                if($PdtInventory->load($data)){
                    if(!$PdtInventory->save()){
                        throw new Exception(json_encode($PdtInventory->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('盘点列表加载失败');
                }
                //请购单明细表
                if(!empty($data['prod'])){
                        foreach ($data['prod'] as $key => $val) {
                            if(!empty($val['PdtInventoryDt']['part_no'])) {
                                $PdtInventoryDt = new PdtInventoryDt();
                                $PdtInventoryDt->ivt_code = $PdtInventory->ivt_code;
                                if ($PdtInventoryDt->load($val)) {
                                    if (!$PdtInventoryDt->save()) {
                                        throw new Exception(json_encode($PdtInventoryDt->getErrors(), JSON_UNESCAPED_UNICODE));
                                    }
                                } else {
                                    throw new Exception('盘点明细加载失败');
                                }
                            }
                        }
                }
                $transaction->commit();
                return $this->success('新增成功',[
                    'id'=>$PdtInventory->ivt_id,
                    'code'=>$PdtInventory->ivt_code,
//                    'typeId'=>$bsBusType['business_type_id']
                ]);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }
    //选择商品new
    public function actionProdSelect()
    {
        $params=\Yii::$app->request->queryParams;
        $sql="SELECT
                r.part_no,
				r.pdt_name,
				r.tp_spec,
				r.brand,
				r.unit,
				sum(a.invt_num)invt_num,
				c3.catg_name
                FROM pdt.bs_material r
                LEFT JOIN pdt.bs_category c1 ON c1.catg_no = r.category_no
                LEFT JOIN pdt.bs_category c2 ON c2.catg_id = c1.p_catg_id
                LEFT JOIN pdt.bs_category c3 ON c3.catg_id = c2.p_catg_id
                LEFT JOIN wms.bs_wh_invt a ON a.part_no=r.part_no
                LEFT JOIN wms.bs_wh b ON b.wh_code=a.wh_code
                WHERE b.wh_code='{$params["wh"]}'";
        $queryParams=[];
        if(!empty($params['kwd'])){
            $trans=new Trans();
            $params['kwd']=str_replace(['%','_'],['\%','\_'],$params['kwd']);
            $sql.=" and ( (r.part_no like '%".$params['kwd']."%' ) or
            (r.pdt_name like '%".$trans->c2t($params['kwd'])."%') or
            (r.pdt_name like '%".$trans->t2c($params['kwd'])."%') or
             (r.pdt_name like '%".$params['kwd']."%') )
            ";
        }
        if(!empty($params['catg_id']))
        {
            $params['catg_id']=str_replace(['%','_'],['\%','\_'],$params['catg_id']);
            $sql.=" and ((c1.catg_id ='".$params['catg_id']."')or
                        (c2.catg_id = '".$params['catg_id']."')or
                        (c3.catg_id = '".$params['catg_id']."'))";
        }
        $sql.=" group by r.pkmt_id";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a",$queryParams)->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
            'totalCount'=>$totalCount,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
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
    //获取新增页面的下拉页面
    public function actionDawnListByCreate($id)
    {
       $downList['legal_code']=Yii::$app->db->createCommand("select company_id,company_name from erp.bs_company where company_status = 10")->queryAll();
       $downList['wh_name']=Yii::$app->db->createCommand("select wh_code,wh_name,wh_state from wms.bs_wh where wh_state ='Y' ")->queryAll();
       $downList['cur_id']=Yii::$app->db->createCommand("SELECT t.cur_id,t.cur_code FROM erp.bs_currency t")->queryAll();
       $downList['business']=Yii::$app->db->createCommand("select a.business_type_id from erp.bs_business_type a WHERE a.business_code='checklist' ")->queryAll();
//      return $downList['business'][0]['business_type_id'];
//        $id=Yii::$app->user->identity->staff_id;
//        return $id;
//        $downList['staff']=Yii::$app->db->createCommand("select a.staff_id,b.staff_code from erp.user a LEFT JOIN erp.hr_staff b ON a.staff_id=b.staff_id WHERE a.staff_id=$id")->queryAll();
        $downList['staff'] = HrStaff::find()->select(['staff_code'])->where(['staff_id'=>$id])->one();
//        return $downList['staff'][0]['staff_code'];
        return $downList;
    }
    //修改
    public function actionEdit($id,$code)
    {
        if($data=Yii::$app->request->post())
        {
            $transaction=PdtInventory::getDb()->beginTransaction();
            try
            {
                //请购主表
                $bsReq=PdtInventory::findOne($id);
                $bsReq->ivt_status=1;
//                $bsReq=PdtInventoryDt::findOne($id);
//                $bsReq->ivt_code=$code;
                if($bsReq->load($data)){
                    if(!$bsReq->save())
                    {
                        throw new Exception(json_encode($bsReq->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('盘点主表更新失败');
                }
                PdtInventoryDt::deleteAll(['ivt_code'=>$code]);
                if(!empty($data['prod'])){
                    foreach($data['prod'] as $key=>$val){
                        $reqPurpdt=new PdtInventoryDt();
                            $reqPurpdt->ivt_code=$bsReq->ivt_code;
                            if($reqPurpdt->load($val)){
                                if(!$reqPurpdt->save()){
                                    throw new Exception(json_encode($reqPurpdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('盘点商品加载失败');
                            }

                    }
                }
                $transaction->commit();
                return $this->success('修改成功',[
                    'id'=>$id,
                    'code'=>$code,
//                    'typeId'=>54
                ]);
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }
    //盘点明细单导出
    public function actionExportDetail()
    {
        $params=Yii::$app->request->queryParams;
        $sql="SELECT
	a.ivt_id,
	a.ivt_code,
	c.company_name,
	(
		CASE a.stage
		WHEN '1' THEN
			'第一期'
		WHEN '2' THEN
			'第二期'
		WHEN '3' THEN
			'第三期'
		WHEN '4' THEN
			'第四期'
		ELSE
			a.stage
		END
	) stage,
	b.wh_name,
	a.wh_code,
	LEFT (a.expiry_date, 16) expiry_date,
	d.part_no,
	e.pdt_name,
	e.tp_spec,
	e.unit,	
	k.invt_num,
	i.staff_name first_ivtor,
	d.first_num,
	LEFT (a.first_date, 10) first_date,
	j.staff_name re_ivtor,
	d.re_num,
	LEFT (a.re_date, 10) re_date,
	d.lose_num,
	d.lose_price,
	d.remarks,
	d.remarks1,
	-- LEFT (a.expiry_date, 10) expiry_date,
	(
		CASE a.ivt_status
		WHEN '0' THEN
			'审核中'
		WHEN '1' THEN
			'待提交'
		WHEN '2' THEN
			'信息完善中'
		WHEN '3' THEN
			'审核完成'
		WHEN '4' THEN
			'驳回'
		WHEN '5' THEN
			'单据已取消'
		ELSE
			'未知'
		END
	) ivt_status
FROM
	wms.pdt_inventory a
LEFT JOIN wms.bs_wh b ON a.wh_code = b.wh_code
LEFT JOIN erp.bs_company c ON a.legal_code = c.company_id
LEFT JOIN wms.pdt_inventory_dt d ON d.ivt_code = a.ivt_code
LEFT JOIN pdt.bs_material e ON d.part_no = e.part_no
LEFT JOIN pdt.bs_product f ON d.pdt_id = f.pdt_pkid
LEFT JOIN wms.bs_wh h ON h.wh_code = a.wh_code
LEFT JOIN wms.bs_wh_invt k on k.wh_code=a.wh_code AND k.part_no=d.part_no
LEFT JOIN erp.hr_staff i ON i.staff_id=a.first_ivtor
LEFT JOIN erp.hr_staff j ON j.staff_id=a.re_ivtor
WHERE
	a.ivt_id IS NOT NULL";
        $queryParams=[];
        if(!empty($params['ivt_code'])){
            $trans=new Trans();
            $params['ivt_code']=str_replace(['%','_'],['\%','\_'],$params['ivt_code']);
            $queryParams[':ivt_code1']='%'.$params['ivt_code'].'%';
            $queryParams[':ivt_code2']='%'.$trans->c2t($params['ivt_code']).'%';
            $queryParams[':ivt_code3']='%'.$trans->t2c($params['ivt_code']).'%';
            $sql.=" and(a.ivt_code like :ivt_code1 or a.ivt_code like :ivt_code2 or a.ivt_code like :ivt_code3)";
        }
        if(!empty($params['stage'])){
            $sql.=" and a.stage={$params['stage']}";
        }
        if(!empty($params['legal_code'])){
            $sql.=" and c.company_id={$params['legal_code']}";
        }
        if(!empty($params['wh_name'])){
            $sql.=" and a.wh_code='{$params['wh_name']}'";
        }
        if(!empty($params['wh_code'])){
            $trans=new Trans();
            $params['wh_code']=str_replace(['%','_'],['\%','\_'],$params['wh_code']);
            $queryParams[':wh_code1']='%'.$params['wh_code'].'%';
            $queryParams[':wh_code2']='%'.$trans->c2t($params['wh_code']).'%';
            $queryParams[':wh_code3']='%'.$trans->t2c($params['wh_code']).'%';
            $sql.=" and(a.wh_code like :wh_code1 or a.wh_code like :wh_code2 or a.wh_code like :wh_code3)";
        }
        if(!empty($params['part_no'])){
            $trans=new Trans();
            $params['part_no']=str_replace(['%','_'],['\%','\_'],$params['part_no']);
            $queryParams[':part_no1']='%'.$params['part_no'].'%';
            $queryParams[':part_no2']='%'.$trans->c2t($params['part_no']).'%';
            $queryParams[':part_no3']='%'.$trans->t2c($params['part_no']).'%';
            $sql.=" and(d.part_no like :part_no1 or d.part_no like :part_no2 or d.part_no like :part_no3)";
        }
        if(!empty($params['pdt_name'])){
            $trans=new Trans();
            $params['pdt_name']=str_replace(['%','_'],['\%','\_'],$params['pdt_name']);
            $queryParams[':pdt_name1']='%'.$params['pdt_name'].'%';
            $queryParams[':pdt_name2']='%'.$trans->c2t($params['pdt_name']).'%';
            $queryParams[':pdt_name3']='%'.$trans->t2c($params['pdt_name']).'%';
            $sql.=" and(e.pdt_name like :pdt_name1 or e.pdt_name like :pdt_name2 or e.pdt_name like :pdt_name3)";
        }
        $sql.=" order BY a.ivt_id desc";
        $data=Yii::$app->db->createCommand($sql,$queryParams)->queryAll();
        $date['tr']=$data;
        return $date;
    }

    //导出
    public function actionExport()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select      a.ivt_id,
                          a.ivt_code,
                          c.company_name,
                          (CASE a.stage when '1' THEN '第一期' when '2' THEN '第二期' WHEN '3' THEN '第三期'
                          WHEN '4' THEN '第四期' ELSE a.stage END )stage,
                          b.wh_name,
                          a.wh_code,
                          LEFT (a.expiry_date,16)expiry_date,
                          d.staff_name first_ivtor,
                          LEFT (a.first_date,10)first_date,
                          e.staff_name re_ivtor,
                          LEFT (a.re_date,10)re_date,
                          (CASE a.ivt_status WHEN '0' then '审核中' when '1' THEN '待提交' WHEN '2' then '信息完善中'
                          WHEN '3' THEN '审核完成' WHEN '4' THEN '驳回' WHEN '5' THEN '单据已取消' ELSE '未知' END )ivt_status
                   from wms.pdt_inventory a
                   LEFT JOIN wms.bs_wh b on a.wh_code=b.wh_code
                   LEFT JOIN erp.bs_company c on a.legal_code=c.company_id
                   LEFT JOIN erp.hr_staff d ON d.staff_id=a.first_ivtor
                   LEFT JOIN erp.hr_staff e ON e.staff_id=a.re_ivtor
                   where a.ivt_id is NOT NULL
                          ";
        $queryParams=[];
        if(!empty($params['ivt_code'])){
            $trans=new Trans();
            $params['ivt_code']=str_replace(['%','_'],['\%','\_'],$params['ivt_code']);
            $queryParams[':ivt_code1']='%'.$params['ivt_code'].'%';
            $queryParams[':ivt_code2']='%'.$trans->c2t($params['ivt_code']).'%';
            $queryParams[':ivt_code3']='%'.$trans->t2c($params['ivt_code']).'%';
            $sql.=" and(a.ivt_code like :ivt_code1 or a.ivt_code like :ivt_code2 or a.ivt_code like :ivt_code3)";
        }
        if(isset($params['ivt_status']) && $params['ivt_status'] != ''){
            $sql.=" and a.ivt_status={$params['ivt_status']}";
        }
        if(!empty($params['stage'])){
            $sql.=" and a.stage={$params['stage']}";
        }
        if(!empty($params['legal_code'])){
            $sql.=" and c.company_id={$params['legal_code']}";
        }
        if(!empty($params['wh_name'])){
            $sql.=" and a.wh_code='{$params['wh_name']}'";
        }
        if(!empty($params['wh_code'])){
            $trans=new Trans();
            $params['wh_code']=str_replace(['%','_'],['\%','\_'],$params['wh_code']);
            $queryParams[':wh_code1']='%'.$params['wh_code'].'%';
            $queryParams[':wh_code2']='%'.$trans->c2t($params['wh_code']).'%';
            $queryParams[':wh_code3']='%'.$trans->t2c($params['wh_code']).'%';
            $sql.=" and(a.wh_code like :wh_code1 or a.wh_code like :wh_code2 or a.wh_code like :wh_code3)";
        }
        if(!empty($params['start_date'])){
            $queryParams[':start_date']=date('Y-m-d H:i:s',strtotime($params['start_date']));
            $sql.=" and a.first_date >= :start_date";
        }
        if(!empty($params['end_date'])){
            $queryParams[':end_date']=date('Y-m-d H:i:s',strtotime($params['end_date'].'+1 day'));
            $sql.=" and  a.first_date < :end_date";
        }
        $sql.=" order BY a.ivt_id desc";
        $data=Yii::$app->db->createCommand($sql,$queryParams)->queryAll();
        $date['tr']=$data;
        return $date;
    }
}
