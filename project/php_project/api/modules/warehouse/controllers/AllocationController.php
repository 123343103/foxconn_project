<?php
/**
 * Created by PhpStorm.
 * User: F3860961
 * Date: 2017/7/25
 * Time: 下午 02:39
 */
namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\VerifyrecordChild;
use app\modules\warehouse\models\BsSt;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\IcInvh;
use app\modules\warehouse\models\InvChangeh;
use app\modules\warehouse\models\InvChangel;
use app\modules\warehouse\models\RcpNotice;
use app\modules\warehouse\models\RcpNoticeDt;
use app\modules\warehouse\models\search\InvChangehSearch;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class AllocationController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\InvChangeh';

    public function actionIndex()
    {
        $searsh = new InvChangehSearch();
        $post = \Yii::$app->request->queryParams;
        $dataProvider = $searsh->searchs($post);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }

    //关联商品信息
    public function actionRelationCommodity($id='')
    {
        $searsh = new InvChangehSearch();
        $dataProvider = $searsh->searchRelationCommodity($id);
//        return $dataProvider ;
        return [
            'rows' => $dataProvider->getModels()
        ];
    }

    //删除
    public function actionDeletes($id, $staff_id='')
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model = InvChangeh::findOne($id);
        $hr_staff = new HrStaff();
        if ($model) {
            $username = $hr_staff->getStaffById($staff_id);//获取用户名
            $model->chh_status = (string)InvChangeh::STATUS_DELETE;
            if ($result = $model->save()) {
                return $this->success();
            } else {
//                throw new \Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
                return $this->error();
            }
        } else {
            return $this->error();
        }

    }

    //详情
//    public function actionViews($id)
//    {
//        //调拨单信息
//        $queryParams = [
//            ':id' => $id,
//        ];
//        $searsh = new InvChangehSearch();
//        $dataProvider = $searsh->searchbyid($id);
//        $model = $dataProvider->getModels();
////        return $model;
//
//
////        return $AppropriationInfo;
//        //关联商品信息
//        $sql2 = "SELECT
//	t.part_no,
//	t.pdt_name,
//	t.brand,
//	t.tp_spec,
//	(
//		SELECT
//			bi.L_invt_num
//		FROM
//			wms.l_bs_invt_list bi
//		WHERE
//			bi.part_no = t.part_no
//		AND bi.L_invt_bach=i.chl_bach
//	) invt_num,
//	i.chl_num,
//	i.chl_bach,
//	i.st_id,
//	(
//		SELECT
//			bs.st_code
//		FROM
//			wms.bs_st bs
//		WHERE
//			bs.st_id = i.st_id
//	) Ost_code,
//	t.unit
//FROM
//	pdt.bs_material t,
//	wms.inv_changel i
//WHERE
//	t.part_no = i.pdt_no
//AND i.chh_id =:id";
//        $RelationCommodity = \Yii::$app->db->createCommand($sql2, $queryParams)->queryAll();
//
//        $sql3="select t.wh_id,t.wh_id2 from wms.inv_changeh t where t.chh_id=:id";
//        $stockInInfo = \Yii::$app->db->createCommand($sql3, $queryParams)->queryOne();
//
//
//        if(!empty($stockInInfo['wh_id'])){
//            $queryParams=[
//                ':warehouse_id'=>$stockInInfo['wh_id']
//            ];
//            $querySql="select a.st_id,a.st_code
//                       from wms.bs_st a
//                       left join wms.bs_part b on b.part_code = a.part_code
//                       left join wms.bs_wh c on c.wh_code = b.wh_code
//                       where c.wh_id = :warehouse_id
//                       and a.YN = 'Y'";
//            $locationInfo=\Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
//        }
//        else
//        {
//            $locationInfo=null;
//        }
//        if(!empty($stockInInfo['wh_id2'])){
//            $queryParams=[
//                ':warehouse_id'=>$stockInInfo['wh_id2']
//            ];
//            $querySql="select a.st_id,a.st_code
//                       from wms.bs_st a
//                       left join wms.bs_part b on b.part_code = a.part_code
//                       left join wms.bs_wh c on c.wh_code = b.wh_code
//                       where c.wh_id = :warehouse_id
//                       and a.YN = 'Y'";
//            $locationInfos=\Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
//        }
//        else
//        {
//            $locationInfos=null;
//        }
//
//        //审核信息
//        if($model[0]['chh_status']=='审核中' || $model[0]['chh_status']=='审核完成' || $model[0]['chh_status']=='驳回'){
//            $queryParams=[
//                ':but_code'=>$RelationCommodity['inout_type'],
//                ':vco_busid'=>$RelationCommodity['chh_id']
//            ];
//            $querySql="select a.vco_id
//                   from erp.system_verifyrecord a
//                   where a.but_code = 'wms03'
//                   and a.vco_busid = :vco_busid";
//            $verifyRecord=\Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
//            if(!empty($verifyRecord)){
//                $queryParams=[
//                    ':vco_id'=>$verifyRecord['vco_id'],
//                    ':status_default'=>VerifyrecordChild::STATUS_DEFAULT,
//                    ':status_checking'=>VerifyrecordChild::STATUS_CHECKIND,
//                    ':status_pass'=>VerifyrecordChild::STATUS_PASS,
//                    ':status_reject'=>VerifyrecordChild::STATUS_REJECT
//                ];
//                $querySql="select c.organization_code,
//                                  c.staff_name,
//                                  a.vcoc_datetime,
//                                  (case a.vcoc_status when :status_default then '待审' when :status_checking then '待审' when :status_pass then '通过' when :status_reject then '驳回' else '未知' end) checkStatus,
//                                  a.vcoc_remark,
//                                  a.vcoc_computeip
//                   from erp.system_verifyrecord_child a
//                   left join erp.user b on b.user_id = a.ver_acc_id
//                   left join erp.hr_staff c on c.staff_id = b.staff_id
//                   where a.vco_id = :vco_id";
//                $checkInfo=\Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
//            }
//        }
//        return [
//            'AppropriationInfo'=>$model,
//            'RelationCommodity'=>empty($RelationCommodity)?'':$RelationCommodity,
//            'stockInInfo'=>$stockInInfo,
//            'locationInfo'=>$locationInfo,
//            'locationInfos'=>$locationInfos,
//            'checkInfo'=>empty($checkInfo)?'':$checkInfo
//        ];
//    }

    //根据料号查询商品
    public function actionProductData(){
        $params=\Yii::$app->request->queryParams;
        $where=" where wms.bs_wh.wh_state='Y' AND pdt.bs_material.part_no <> '' ";
//        $params["kwd"]='口';
        $bindParams=[];
        if(!empty($params["kwd"])){
            $bindParams[":pdt_no"]="%{$params["kwd"]}%";
            $bindParams[":pdt_name"]="%{$params["kwd"]}%";
            $bindParams[":tp_spec"]="%{$params["kwd"]}%";
            $bindParams[":brand_name"]="%{$params["kwd"]}%";
            $bindParams[":st_code"]="%{$params["kwd"]}%";
            $where.=" and (pdt.bs_material.part_no like :pdt_no or pdt.bs_material.pdt_name like :pdt_name or pdt.bs_material.tp_spec like :tp_spec or pdt.bs_material.brand like :brand_name or wms.bs_st.st_code like :st_code) ";
        }
        if(!empty($params['pdt_no']))
        {
            $bindParams[":pdt_no"]=$params['pdt_no'];
            $where.=" and wms.bs_sit_invt.part_no = :pdt_no";
        }
        if(!empty($params['wh_id']))
        {
            $ret=BsWh::getBsWhcn($params['wh_id']);
            $bindParams[":wh_id"]=$ret['wh_code'];
            $where.=" and wms.bs_sit_invt.wh_code = :wh_id";
        }
        $sql="SELECT
    	    pdt.bs_material.part_no,
        	pdt.bs_material.pdt_name,
        	pdt.bs_material.category_no,
        	pdt.bs_material.unit,
        	pdt.bs_material.tp_spec,
        	pdt.bs_material.brand,
        	wms.bs_wh.wh_id,
        	wms.bs_sit_invt.wh_name,
        	wms.bs_sit_invt.wh_code,
        	wms.bs_sit_invt.invt_num,
        	wms.bs_sit_invt.batch_no,
        	wms.bs_st.st_code,
        	wms.bs_st.st_id
        FROM
        	wms.bs_sit_invt
        LEFT JOIN pdt.bs_material ON pdt.bs_material.part_no = wms.bs_sit_invt.part_no
        LEFT JOIN wms.bs_wh ON wms.bs_sit_invt.wh_code = wms.bs_wh.wh_code
        LEFT JOIN wms.bs_st ON wms.bs_st.st_code = wms.bs_sit_invt.st_code
        {$where} GROUP BY  wms.bs_sit_invt.part_no,wms.bs_sit_invt.wh_code,wms.bs_sit_invt.wh_name,wms.bs_sit_invt.st_code,wms.bs_sit_invt.unit_name";
        $count=\Yii::$app->db->createCommand("select count(*) from wms.bs_sit_invt
        LEFT JOIN pdt.bs_material ON pdt.bs_material.part_no = wms.bs_sit_invt.part_no
        LEFT JOIN wms.bs_wh ON wms.bs_sit_invt.wh_code = wms.bs_wh.wh_code
        LEFT JOIN wms.bs_st ON wms.bs_st.st_code = wms.bs_sit_invt.st_code
        {$where}  GROUP BY  wms.bs_sit_invt.part_no,wms.bs_sit_invt.wh_code,wms.bs_sit_invt.wh_name,wms.bs_sit_invt.st_code,wms.bs_sit_invt.unit_name",$bindParams)->query()->count();
        $provider=new SqlDataProvider([
            "sql"=>$sql,
            "totalCount"=>$count,
            "params"=>$bindParams,
            "pagination"=>[
                "page"=>isset($params["page"])?$params["page"]-1:0,
                "pageSize"=>isset($params["rows"])?$params["rows"]:10,
            ]
        ]);
//        return $sql;
        return [
            "rows"=>$provider->models,
            "total"=>$provider->totalCount
        ];
    }

    //获取仓库信息
    public function actionGetWarehouseInfo($Oid='',$Iid='',$code='')
    {
        $queryParams=[];
        $querySql="select a.wh_id,
                          a.wh_name,
                          a.wh_code,
                          (case a.wh_attr when 'Y' then '自有' when 'N' then '外租' else '未知' end) warehouseAttr 
                   from wms.bs_wh a
                   where a.wh_state = 'Y'";
        if(!empty($Oid)){
            $queryParams[':id']=$Oid;
            $querySql.=" and a.wh_id = :id";
        }
        if(!empty($Iid)){
            $queryParams[':id']=$Iid;
            $querySql.=" and a.wh_id = :id";
        }
        if(!empty($code)){
            $queryParams[':code']=$code;
            $querySql.=" and a.wh_code = :code";
        }
        $warehouseInfo=\Yii::$app->db->createCommand($querySql,$queryParams)->queryOne();
        if(!empty($warehouseInfo['wh_id'])){
            $queryParams=[
                ':warehouse_id'=>$warehouseInfo['wh_id']
            ];
            $querySql="select a.st_id,a.st_code 
                       from wms.bs_st a
                       left join wms.bs_part b on b.part_code = a.part_code
                       left join wms.bs_wh c on c.wh_code = b.wh_code
                       where c.wh_id = :warehouse_id
                       and a.YN = 'Y'";
            $warehouseInfo['locationInfo']=\Yii::$app->db->createCommand($querySql,$queryParams)->queryAll();
        }
        return $warehouseInfo;
    }

    //修改
    public function actionUpdate($id)
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
//        $model=new InvChangeh();
        $transaction = \Yii::$app->db->beginTransaction();
        $post = \Yii::$app->request->post();
        try{
            $model=InvChangeh::findOne($id);
            if((!$model->load($post))||(!$model->save()))
            {
                $transaction->rollBack();
                throw new \Exception("修改失败");
            }
            InvChangel::deleteAll(['chh_id'=>$id]);
            if(!empty($post['product'])){
                foreach($post['product'] as $val){
                    if(!empty($val['InvChangel']['pdt_no'])){
                        $childModel=new InvChangel();
                        $childModel->chh_id=$model->chh_id;
//                        $childModel['st_id']=$val['InvChangel']['Ost_id'];
//                        $childModel['st_id2']=$val['InvChangel']['Ist_id'];
                        if($childModel->load($val)){
                            if(!$childModel->save()){
                                throw new Exception(json_encode($childModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }else{
                            throw new Exception('调拨子表商品加载失败');
                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success('修改成功！',['id'=>$model->chh_id,'chh_type'=>$model->chh_type]);
        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //新增
    public function actionAdd()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model=new InvChangeh();
        $transaction = \Yii::$app->db->beginTransaction();
        $post = \Yii::$app->request->post();
        try{
            $model->load($post);
            $model->codeType=InvChangeh::CODE_TYPE_DB;
            $model['chh_status']=InvChangeh::STATUS_ADD;
            if(!$model->save())
            {
                $transaction->rollBack();
                throw new \Exception("新增失败");
            }
            if(!empty($post['product'])){
                foreach($post['product'] as $val){
                    if(!empty($val['InvChangel']['pdt_no'])){
                        $childModel=new InvChangel();
                        $childModel->chh_id=$model->chh_id;
//                        $childModel['st_id']=$val['InvChangel']['Ost_id'];
//                        $childModel['st_id2']=$val['InvChangel']['Ist_id'];
                        if($childModel->load($val)){
                            if(!$childModel->save()){
                                throw new Exception(json_encode($childModel->getErrors(),JSON_UNESCAPED_UNICODE));
                            }
                        }else{
                            throw new Exception('调拨子表商品加载失败');
                        }
                    }
                }
            }
            $transaction->commit();
            return $this->success('新增成功！',['id'=>$model->chh_id,'chh_type'=>$model->chh_type]);
        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
//        $msg = array('新增成功！！');
//        return $this->success('',$msg);
    }

    public function actionOptions(){
        return [
            "invh_status"=>IcInvh::getStatus(),    //状态
            "inout_type"=>IcInvh::getInOutType(),                      //单据类型
            "organization"=>IcInvh::getOrganization(),                                  //申请部门
            "warehouse"=>IcInvh::getWareHouse(),                                       //出仓仓库,
            "trans_type"=>IcInvh::getTransType(),                                     //物流方式,
            "delivery_type"=>IcInvh::getDelveryType(),                                 //配送方式
            "storage_position"=>IcInvh::getSt(),
            "product_property"=>["1"=>"样品","0"=>"非样品"]

        ];
    }

    //仓库名称
    public function actionGetWhName()
    {
        return BsWh::getWarehouseAll();
    }

    //调拨单位
    public function actionOrganization()
    {
        return HrOrganization::getOrgAll();
    }

    //仓储码
    public function actionStCode()
    {
        return BsSt::getSetCode();
    }

    //调拨类型
    public function actionBusinessTypeName()
    {
        return BsBusinessType::getBusinessType('business_code="wm03"');
    }

    //获取调拨单位
    public function actionGetOrgniozation($_id)
    {
        $sqlls="SELECT o.organization_id,o.organization_name from erp.r_user_dpt_dt dpt 
                LEFT JOIN erp.hr_organization o ON dpt.dpt_pkid=o.organization_id 
                LEFT JOIN erp.`user` u ON dpt.user_id=u.user_id
                LEFT JOIN erp.hr_staff s ON u.staff_id=s.staff_id
                WHERE s.staff_id=:id";
        $ret=\Yii::$app->db->createCommand($sqlls,[':id'=>$_id])->queryAll();
        return $ret;
    }

    //获取用户的登录权限
    public function actionGetUserType($_id)
    {
        $sqlls="SELECT u.is_supper from erp.`user` u 
                LEFT JOIN erp.hr_staff s ON s.staff_id=u.staff_id 
                WHERE s.staff_id=:id";
        $_uu=\Yii::$app->db->createCommand($sqlls,[':id'=>$_id])->queryScalar();
        return $_uu;
    }

    //生成入库通知单
    public  function actionInWare($id,$staff=null)
    {
            $sql01="SELECT *,
                    (SELECT b.wh_code FROM wms.bs_wh b WHERE b.wh_id=h.wh_id) Owhid,
                    (SELECT b.wh_code FROM wms.bs_wh b WHERE b.wh_id=h.wh_id2) Iwhid,
                    (SELECT c.st_code FROM wms.bs_st c WHERE c.st_id=l.st_id) stcode
                    from  wms.inv_changeh h 
                    LEFT JOIN wms.inv_changel l ON h.chh_id=l.chh_id
                    WHERE h.chh_id=:chh_id";
            $ret1=\Yii::$app->db->createCommand($sql01,['chh_id'=>$id])->queryAll();
//            return $ret1;
        $transaction=RcpNotice::getDb()->beginTransaction();
        try{
            $model=new RcpNotice();
//            $model->rcpnt_no=date('YmdHis');
            $model->rcpnt_status=1;
            $model->rcpnt_type=2;
            $model->i_whcode=$ret1[0]['Iwhid'];
            $model->o_whcode=$ret1[0]['Owhid'];
            $model->prch_no=$ret1[0]['chh_code'];
            $model->leg_id=$ret1[0]['comp_id'];
            $model->app_depno=$ret1[0]['depart_id'];
            $model->creator=$staff;
            $model->creat_date=date('Y-m-d H:i:s');
            if(!$model->save()){
                throw new Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            foreach ($ret1 as $key=>$val){
                $model2=new RcpNoticeDt();
                $model2->rcpnt_no=$model->rcpnt_no;
                $model2->ord_id=$val['chl_bach'];
                $model2->part_no=$val['pdt_no'];
                $model2->delivery_num=$val['chl_num'];
                $model2->before_stno=$val['stcode'];
                //$model2->operator='1103';
                $model2->operate_date=date('Y-m-d H:i:s');
                if(!$model2->save()){
                    throw new Exception(json_encode($model2->getErrors(),JSON_UNESCAPED_UNICODE));
                }
            }
            //回写InvChangeh改变状态
            $model3=InvChangeh::findOne($id);
            $model3->o_status=InvChangeh::STATUS_OUTACTION;
            $model3->in_status=InvChangeh::STATUS_INWAIT;
            if(!$model3->save()){
                throw new Exception(json_encode($model3->getErrors(),JSON_UNESCAPED_UNICODE));
            }
            $transaction->commit();
            return $this->success('新增成功',[
                'id'=>$model->rcpnt_id
            ]);
        }catch(\Exception $e){
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //获取models
    public function actionModels($id){
        //请购单信息
        $sql="SELECT h.chh_code,
                      h.chh_type AS  chhtype,
                    h.wh_id AS  whid,
                    l.st_id AS  stid,
                    co.company_name AS company_id,
                    t.business_type_desc,
                    wh.wh_name AS wh_id,
                    wh2.wh_name AS wh_id2,
                    ( CASE h.chh_status
                        WHEN '10' THEN '待提交'
                        WHEN '20' THEN '审核中'
                        WHEN '30' THEN '审核完成'
                        WHEN '40' THEN '驳回'
                        WHEN '1' THEN '待出库'
                        WHEN '2' THEN '待入库'
                        WHEN '3' THEN '驳回'
                        WHEN '4' THEN '已出库'
                    END )chh_status,
                ( CASE h.o_status
                        WHEN '1' THEN '待出库'
                        WHEN '2' THEN '已出库'
                    END )o_status,
                ( CASE h.in_status
                        WHEN '3' THEN '待入库'
                        WHEN '4' THEN '已入库'
                    END )in_status,
                    hr.staff_name AS create_by,
                    h.create_at,
                    hr2.staff_name AS review_by,
                    h.review_at,
                    o.organization_name AS depart_id,
                    ma.part_no,ma.brand,ma.pdt_name,ma.tp_spec,ma.unit,l.chl_num,l.chl_bach,l.before_num1,
                    st.st_code AS st_id
                FROM
                    wms.inv_changeh h
                LEFT JOIN wms.inv_changel l ON h.chh_id = l.chh_id
                LEFT JOIN erp.bs_business_type t ON t.business_type_id = h.chh_type
                LEFT JOIN wms.bs_wh wh ON wh.wh_id = h.wh_id
                LEFT JOIN wms.bs_wh wh2 ON wh2.wh_id = h.wh_id2
                LEFT JOIN erp.bs_company co ON co.company_id=h.comp_id
                LEFT JOIN erp.hr_staff hr ON hr.staff_id=h.create_by
                LEFT JOIN erp.hr_staff hr2 ON hr2.staff_id=h.review_by
                LEFT JOIN erp.hr_organization o ON o.organization_id=h.depart_id
                LEFT JOIN pdt.bs_material ma ON ma.part_no=l.pdt_no 
                LEFT JOIN wms.bs_st st ON st.st_id=l.st_id
                WHERE
                    h.chh_id = :chh_id";
        $basicinfo = \Yii::$app->db->createCommand($sql)->bindValue(':chh_id', $id)->queryAll();
        if($basicinfo!==null){
            return $basicinfo;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }

}