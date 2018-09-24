<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
namespace app\modules\spp\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsForm;
use app\modules\common\models\BsPubdata;
use app\modules\spp\models\BsSupplier;
use app\modules\spp\models\SupplierCont;
use app\modules\spp\models\SupplierMpdt;
use app\modules\spp\models\SupplierPurpdt;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use app\classes\Trans;
/**
 * 供应商API控制器
 */
class SupplierController extends BaseActiveController
{
    public $modelClass='app\modules\spp\models\BsSupplier';

    //供应商列表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['rows'])){
            return [
                'supType'=>BsPubdata::getData(BsPubdata::FIRM_TYPE)
            ];
        }
        $sql="select a.spp_id,
                     a.type_id,
                     a.spp_code,
                     a.group_code,
                     a.spp_fname,
                     b.bsp_svalue sppType,
                     c.bsp_svalue sppSource,
                     d.bsp_svalue sourceType,
                     case a.group_spp when 'Y' then '是' when 'N' then '否' else '未知' end groupSpp,
                     e.staff_name operator,
                     substring(a.oper_time, 1, 10) oper_time,
                     case a.spp_status when 0 then '删除' when 1 then '未提交' when 2 then '审核中' when 3 then '审核完成' when 4 then '驳回' else '未知' end sppStatus,
                     a.sale_turn,
                     a.sale_profit
              from spp.bs_supplier a
              left join erp.bs_pubdata b on b.bsp_id = a.spp_type
              left join erp.bs_pubdata c on c.bsp_id = a.spp_source
              left join erp.bs_pubdata d on d.bsp_id = a.source_type
              left join erp.hr_staff e on e.staff_id = a.oper_id
              where a.spp_status <> 0
              and a.company_id in (";
        foreach(BsCompany::getIdsArr($params['companyId']) as $key=>$val){
            $sql.=$val.',';
        }
        $sql=trim($sql,',').')';
        //查询
        if(!empty($params['group_spp'])){
            $queryParams[':group_spp']=$params['group_spp'];
            $sql.=" and a.group_spp = :group_spp";
        }
        if(!empty($params['spp_type'])){
            $queryParams[':spp_type']=$params['spp_type'];
            $sql.=" and a.spp_type = :spp_type";
        }
        if(!empty($params['spp_status'])){
            $queryParams[':spp_status']=$params['spp_status'];
            $sql.=" and a.spp_status = :spp_status";
        }
        if(!empty($params['spp_fname'])){
            $trans=new Trans();//处理简体繁体
            $params['spp_fname']=str_replace(['%','_'],['\%','\_'],$params['spp_fname']);
            $queryParams[':spp_fname1']='%'.$params['spp_fname'].'%';
            $queryParams[':spp_fname2']='%'.$trans->c2t($params['spp_fname']).'%';
            $queryParams[':spp_fname3']='%'.$trans->t2c($params['spp_fname']).'%';
            $sql.=" and (a.spp_fname like :spp_fname1 or a.spp_fname like :spp_fname2 or a.spp_fname like :spp_fname3)";
        }
        if(!empty($params['oper_id'])){
            $trans=new Trans();//处理简体繁体
            $params['oper_id']=str_replace(['%','_'],['\%','\_'],$params['oper_id']);
            $queryParams[':oper_id1']='%'.$params['oper_id'].'%';
            $queryParams[':oper_id2']='%'.$trans->c2t($params['oper_id']).'%';
            $queryParams[':oper_id3']='%'.$trans->t2c($params['oper_id']).'%';
            $sql.=" and (e.staff_name like :oper_id1 or e.staff_name like :oper_id2 or e.staff_name like :oper_id3)";
        }
        if(!empty($params['start_time'])){
            $queryParams[':start_time']=date('Y-m-d H:i:s',strtotime($params['start_time']));
            $sql.=" and a.oper_time >= :start_time";
        }
        if(!empty($params['end_time'])){
            $queryParams[':end_time']=date('Y-m-d H:i:s',strtotime($params['end_time'].'+1 day'));
            $sql.=" and a.oper_time < :end_time";
        }
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $sql.=" order by a.oper_time desc, a.spp_id desc";
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
        return [
            //十八大类
            'commodify'=>Yii::$app->db->createCommand("select catg_id,catg_name from pdt.bs_category where catg_level = 1 order by catg_id asc")->queryAll(),
            //新增类型
            'addType'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'xzlx' order by bsp_id asc")->queryAll(),
            //供应商类型
            'sppType'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'cslx' order by bsp_id asc")->queryAll(),
            //供应商来源
            'sppSource'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'csly' order by bsp_id asc")->queryAll(),
            //省
            'province'=>Yii::$app->db->createCommand("select district_id,district_name from erp.bs_district where is_valid = 1 and district_level = 2")->queryAll(),
            //供应商地位
            'sppPosition'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'csdw' order by bsp_id asc")->queryAll(),
            //币别
            'currency'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'jybb' order by bsp_id asc")->queryAll(),
            //交货条件
            'deliveryCond'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'jhtj' order by bsp_id asc")->queryAll(),
            //付款条件
            'payCond'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'fktj' order by bsp_id asc")->queryAll(),
            //来源类别
            'sourceType'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'lylb' order by bsp_id asc")->queryAll(),
            //代理等级
            'agencyLevel'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'dldj' order by bsp_id asc")->queryAll(),
            //授权区域
            'authArea'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'gyssqqy' order by bsp_id asc")->queryAll(),
            //授权范围
            'authScope'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'sqqufw' order by bsp_id asc")->queryAll()
        ];
    }

    //新增供应商
    public function actionAdd()
    {
        if($data=Yii::$app->request->post()){
            $transaction=BsSupplier::getDb()->beginTransaction();
            try{
                //供应商主表
                $bsSup=new BsSupplier();
                $bsBusType=Yii::$app->db->createCommand("select a.business_type_id from erp.bs_business_type a where a.business_code = 'gysbm'")->queryOne();
                if(empty($bsBusType)){
                    throw new Exception('获取供应商单据类型失败');
                }
                $bsSup->type_id=$bsBusType['business_type_id'];
                $bsSup->apply_code=BsForm::getCode('bs_supplier',$bsSup);
                if($bsSup->load($data)){
                    if(!$bsSup->save()){
                        throw new Exception(json_encode($bsSup->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('供应商主表加载失败');
                }
                //供应商联系人
                if(!empty($data['supCont'])){
                    foreach($data['supCont'] as $key=>$val){
                        if(!empty($val['SupplierCont']['name'])){
                            $supCont=new SupplierCont();
                            $supCont->spp_id=$bsSup->spp_id;
                            if($supCont->load($val)){
                                if(!$supCont->save()){
                                    throw new Exception(json_encode($supCont->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('供应商联系人加载失败');
                            }
                        }
                    }
                }
                //供应商主营商品
                if(!empty($data['supMpdt'])){
                    foreach($data['supMpdt'] as $key=>$val){
                        if(!empty($val['SupplierMpdt']['mian_pdt'])){
                            $supMpdt=new SupplierMpdt();
                            $supMpdt->spp_id=$bsSup->spp_id;
                            if($supMpdt->load($val)){
                                if(!$supMpdt->save()){
                                    throw new Exception(json_encode($supMpdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('供应商主营商品加载失败');
                            }
                        }
                    }
                }
                //供应商拟采购商品
                if(!empty($data['supPurpdt'])){
                    foreach($data['supPurpdt'] as $key=>$val){
                        if(!empty($val['SupplierPurpdt']['prt_pkid'])){
                            $supPurpdt=new SupplierPurpdt();
                            $supPurpdt->spp_id=$bsSup->spp_id;
                            if($supPurpdt->load($val)){
                                if(!$supPurpdt->save()){
                                    throw new Exception(json_encode($supPurpdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('供应商拟采购商品加载失败');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success('新增成功',[
                    'id'=>$bsSup->spp_id,
                    'code'=>$bsSup->apply_code,
                    'typeId'=>$bsSup->type_id
                ]);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->addData();
    }

    //修改供应商
    public function actionEdit($id)
    {
        if($data=Yii::$app->request->post()){
            $transaction=BsSupplier::getDb()->beginTransaction();
            try{
                //供应商主表
                $bsSup=BsSupplier::findOne($id);
                if($bsSup->load($data)){
                    if(!$bsSup->save()){
                        throw new Exception(json_encode($bsSup->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('供应商主表加载失败');
                }
                //供应商联系人
                SupplierCont::deleteAll(['spp_id'=>$id,'status'=>1]);
                if(!empty($data['supCont'])){
                    foreach($data['supCont'] as $key=>$val){
                        if(!empty($val['SupplierCont']['name'])){
                            $supCont=new SupplierCont();
                            $supCont->spp_id=$bsSup->spp_id;
                            if($supCont->load($val)){
                                if(!$supCont->save()){
                                    throw new Exception(json_encode($supCont->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('供应商联系人加载失败');
                            }
                        }
                    }
                }
                //供应商主营商品
                SupplierMpdt::deleteAll(['spp_id'=>$id,'status'=>1]);
                if(!empty($data['supMpdt'])){
                    foreach($data['supMpdt'] as $key=>$val){
                        if(!empty($val['SupplierMpdt']['mian_pdt'])){
                            $supMpdt=new SupplierMpdt();
                            $supMpdt->spp_id=$bsSup->spp_id;
                            if($supMpdt->load($val)){
                                if(!$supMpdt->save()){
                                    throw new Exception(json_encode($supMpdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('供应商主营商品加载失败');
                            }
                        }
                    }
                }
                //供应商拟采购商品
                SupplierPurpdt::deleteAll(['spp_id'=>$id,'status'=>1]);
                if(!empty($data['supPurpdt'])){
                    foreach($data['supPurpdt'] as $key=>$val){
                        if(!empty($val['SupplierPurpdt']['prt_pkid'])){
                            $supPurpdt=new SupplierPurpdt();
                            $supPurpdt->spp_id=$bsSup->spp_id;
                            if($supPurpdt->load($val)){
                                if(!$supPurpdt->save()){
                                    throw new Exception(json_encode($supPurpdt->getErrors(),JSON_UNESCAPED_UNICODE));
                                }
                            }else{
                                throw new Exception('供应商拟采购商品加载失败');
                            }
                        }
                    }
                }
                $transaction->commit();
                return $this->success('修改成功',[
                    'id'=>$bsSup->spp_id,
                    'code'=>$bsSup->apply_code,
                    'typeId'=>$bsSup->type_id
                ]);
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $data['addData']=$this->addData();
        $data['editData']=Yii::$app->db->createCommand("select * from spp.bs_supplier where spp_id = :spp_id and (spp_status = 1 or spp_status = 4)",[':spp_id'=>$id])->queryOne();
        if(!empty($data['editData']['spp_addr_id'])){
            $data['editData']['edit_addr']=$this->getDistrict($data['editData']['spp_addr_id']);
        }
        return $data;
    }

    //查看供应商
    public function actionView($id)
    {
        $data=Yii::$app->db->createCommand("select * from spp.bs_supplier where spp_id = :spp_id and spp_status <> 0",[':spp_id'=>$id])->queryOne();
        if(!empty($data)){
            //Commodify
            $result=Yii::$app->db->createCommand("select a.catg_name from pdt.bs_category a where a.catg_id = :id",[':id'=>$data['commodify']])->queryOne();
            $data['commodify']=$result['catg_name'];
            //新增类型
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['add_type']])->queryOne();
            $data['add_type']=$result['bsp_svalue'];
            //供应商类型
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['spp_type']])->queryOne();
            $data['spp_type']=$result['bsp_svalue'];
            //供应商来源
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['spp_source']])->queryOne();
            $data['spp_source']=$result['bsp_svalue'];
            //供应商地址
            $result=Yii::$app->db->createCommand("select concat(d.district_name,c.district_name,b.district_name,a.district_name) sppAddr from erp.bs_district a left join erp.bs_district b on b.district_id = a.district_pid left join erp.bs_district c on c.district_id = b.district_pid left join erp.bs_district d on d.district_id = c.district_pid where a.district_id = :id",[':id'=>$data['spp_addr_id']])->queryOne();
            $data['sppAddr']=$result['sppAddr'].$data['spp_addr_det'];
            //供应商地位
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['spp_position']])->queryOne();
            $data['spp_position']=$result['bsp_svalue'];
            //交易币别
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['trade_cy']])->queryOne();
            $data['trade_cy']=$result['bsp_svalue'];
            //年度营业额
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['year_turn_cy']])->queryOne();
            if(!empty($data['year_turn'])){
                $data['year_turn']=$data['year_turn'].$result['bsp_svalue'];
            }
            //预计年销售额
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['sale_turn_cy']])->queryOne();
            if(!empty($data['year_turn'])){
                $data['sale_turn']=$data['sale_turn'].$result['bsp_svalue'];
            }
            //预计年销售利润
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['sale_profit_cy']])->queryOne();
            if(!empty($data['year_turn'])){
                $data['sale_profit']=$data['sale_profit'].$result['bsp_svalue'];
            }
            //交货条件
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['delivery_cond']])->queryOne();
            $data['delivery_cond']=$result['bsp_svalue'];
            //付款条件
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['pay_cond']])->queryOne();
            $data['pay_cond']=$result['bsp_svalue'];
            //来源类别
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['source_type']])->queryOne();
            $data['source_type']=$result['bsp_svalue'];
            //代理等级
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['agency_level']])->queryOne();
            $data['agency_level']=$result['bsp_svalue'];
            //授权商品类别
            $result=Yii::$app->db->createCommand("select a.catg_name from pdt.bs_category a where a.catg_id = :id",[':id'=>$data['auth_product']])->queryOne();
            $data['auth_product']=$result['catg_name'];
            //授权区域
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['auth_area']])->queryOne();
            $data['auth_area']=$result['bsp_svalue'];
            //授权范围
            $result=Yii::$app->db->createCommand("select a.bsp_svalue from erp.bs_pubdata a where a.bsp_id = :id",[':id'=>$data['auth_scope']])->queryOne();
            $data['auth_scope']=$result['bsp_svalue'];
        }
        return $data;
    }

    //删除供应商
    public function actionDeleteSupplier($id)
    {
        $success=0;
        $failed=0;
        $id=explode('-',$id);
        foreach($id as $key=>$val){
            $transaction=BsSupplier::getDb()->beginTransaction();
            try{
                //供应商
                $bsSup=BsSupplier::findOne($val);
                $bsSup->spp_status=0;
                if(!$bsSup->save()){
                    throw new Exception(json_encode($bsSup->getErrors(),JSON_UNESCAPED_UNICODE));
                }
                //供应商联系人
                $supCont=SupplierCont::findAll(['spp_id'=>$val]);
                if(!empty($supCont)){
                    foreach($supCont as $k=>$v){
                        $v->status=0;
                        if(!$v->save()){
                            throw new Exception(json_encode($v->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
                //供应商主营商品
                $supMpdt=SupplierMpdt::findAll(['spp_id'=>$val]);
                if(!empty($supMpdt)){
                    foreach($supMpdt as $k=>$v){
                        $v->status=0;
                        if(!$v->save()){
                            throw new Exception(json_encode($v->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
                //供应商拟采购商品
                $supPurpdt=SupplierPurpdt::findAll(['spp_id'=>$val]);
                if(!empty($supPurpdt)){
                    foreach($supPurpdt as $k=>$v){
                        $v->status=0;
                        if(!$v->save()){
                            throw new Exception(json_encode($v->getErrors(),JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
                $transaction->commit();
                $success++;
            }catch(\Exception $e){
                $transaction->rollBack();
                $failed++;
            }
        }
        return $this->success('删除供应商成功'.$success.'条失败'.$failed.'条');
    }

    //地址联动
    public function actionGetDistrict($id)
    {
        return BsDistrict::getChildByParentId($id);
    }

    //修改时获取地区
    public function getDistrict($id)
    {
        $districtId=[];
        $districtName=[];
        while($id>0){
            $model=BsDistrict::findOne($id);
            if(empty($model)){
                return [];
            }
            $districtId[]=$model->district_id;
            $districtName[]=BsDistrict::find()->select('district_id,district_name')->where(['is_valid'=>'1','district_pid'=>$model->district_pid])->all();
            $id=$model->district_pid;
        }
        return [
            'districtId'=>array_reverse($districtId),
            'districtName'=>array_reverse($districtName),
        ];
    }

    //获取联系人-列表、修改、详情共用
    public function actionGetContacts()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.*
              from spp.supplier_cont a
              where a.status = 1
              and a.spp_id = {$params['id']}";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A")->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
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

    //获取供应商主营商品-列表、修改、详情共用
    public function actionGetMainProduct()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.*
              from spp.supplier_mpdt a
              where a.status = 1
              and a.spp_id = {$params['id']}";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A")->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
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

    //获取拟采购商品-列表、修改、详情共用
    public function actionGetPurchaseProduct()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.prt_pkid,
                     b.part_no,
                     c.pdt_name,
                     b.tp_spec,
                     concat(g.catg_name, '>', f.catg_name, '>', e.catg_name) category,
                     d.bsp_svalue unit
              from spp.supplier_purpdt a
              left join pdt.bs_partno b on b.prt_pkid = a.prt_pkid
              left join pdt.bs_product c on c.pdt_PKID = b.pdt_PKID
              left join erp.bs_pubdata d on d.bsp_id = c.unit
              left join pdt.bs_category e on e.catg_id = c.catg_id
              left join pdt.bs_category f on f.catg_id = e.p_catg_id
              left join pdt.bs_category g on g.catg_id = f.p_catg_id
              where a.spp_id = {$params['id']}";
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A")->queryScalar();
        $provider=new SqlDataProvider([
            'sql'=>$sql,
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

    //抓取供应商数据
    public function actionGetSupplier()
    {
        ini_set('max_execution_time',0);
	    ini_set("memory_limit","512M");
        $success=0;
        $failed=0;
        $data=Yii::$app->request->post();
        $wcfUrl="http://10.151.18.208:1011/supplier.svc?wsdl";//wcf地址
        $wcfClient=new \SoapClient($wcfUrl);
        $obj=$wcfClient->getSupplier(['name'=>$data['sup_name']]);
        $str=$obj->getSupplierResult;
        $str=str_replace('<?xml version="1.0" encoding="utf-8" ?>','<xml version="1.0" encoding="utf-8" >',$str);
        $obj=simplexml_load_string($str);
        $obj=$obj->TB->ROW;
        if(!empty($obj)){
            foreach($obj as $key=>$val){
                if(!empty((string)$val->SUPPLIER_CODE)){
                    $bsSup=new BsSupplier();
                    $bsSup->data_from=2;
                    $bsSup->spp_status=3;
                    $bsSup->group_spp='Y';
                    $bsSup->spp_fname=(string)$val->SUPPLIER_CN;
                    $bsSup->spp_sname=(string)$val->SUPPLIER_CN_SHOT;
                    $bsSup->group_code=(string)$val->SUPPLIER_CODE;
                    $bsSup->spp_addr_det=(string)$val->SUPPLIER_ADDRESS;
                    $bsSup->codeType=20;
                    $bsSup->spp_code=BsForm::getCode('bs_supplier',$bsSup);
                    if($bsSup->load($data)){
                        if($bsSup->save()){
                            $success++;
                        }else{
                            $failed++;
                        }
                    }else{
                        return $this->error('加载数据失败');
                    }
                }
            }
        }
//        return $this->success('抓取成功'.$success.'条失败'.$failed.'条');
        return $this->success('抓取成功'.$success.'条');
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
            $sql.=" and a.pkmt_id not in (";
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
}
