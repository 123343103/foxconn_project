<?php
/**
 * User: F1677929
 * Date: 2017/9/25
 */
namespace app\modules\ptdt\controllers;
use app\controllers\BaseActiveController;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\PdtpricePas;
use app\modules\ptdt\models\RPrtSpp;
use app\modules\ptdt\models\RPrtSppDt;
use app\modules\spp\models\BsSupplier;
use Yii;
use yii\base\Exception;
use yii\data\SqlDataProvider;
use app\classes\Trans;
/**
 * 料号关联供应商API控制器
 */
class PnoBindSppController extends BaseActiveController
{
    public $modelClass='x';

    //料号关联供应商表
    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if($params['flag']=="d"){
            $sql="select a.pk_id,
                         a.part_no,
                         b.pdt_name,
                         a.supplier_code,
                         a.supplier_name,
                         a.payment_terms,
                         a.trading_terms,
                         a.currency,
                         a.num_area,
                         a.buy_price,
                         substring(a.effective_date,1,10) effective_date,
                         substring(a.expiration_date,1,10) expiration_date,
                         a.remarks,
                         case a.status when 1 then '待提交' when 2 then '审核中' when 3 then '审核完成' when 4 then '驳回' when 5 then '终止' else '' end status 
                  from pdt.fp_pas a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  where 1 = 1";
            //查询
            if(!empty($params['part_no'])){
                $params['part_no']=str_replace(['%','_'],['\%','\_'],$params['part_no']);
                $queryParams[':part_no']='%'.$params['part_no'].'%';
                $sql.=" and a.part_no like :part_no";
            }
            if(!empty($params['pdt_name'])){
                $trans=new Trans();//处理简体繁体
                $params['pdt_name']=str_replace(['%','_'],['\%','\_'],$params['pdt_name']);
                $queryParams[':pdt_name1']='%'.$params['pdt_name'].'%';
                $queryParams[':pdt_name2']='%'.$trans->c2t($params['pdt_name']).'%';
                $queryParams[':pdt_name3']='%'.$trans->t2c($params['pdt_name']).'%';
                $sql.=" and (b.pdt_name like :pdt_name1 or b.pdt_name like :pdt_name2 or b.pdt_name like :pdt_name3)";
            }
            if(!empty($params['spp_code'])){
                $params['spp_code']=str_replace(['%','_'],['\%','\_'],$params['spp_code']);
                $queryParams[':spp_code']='%'.$params['spp_code'].'%';
                $sql.=" and a.supplier_code like :spp_code";
            }
            if(!empty($params['spp_fname'])){
                $trans=new Trans();//处理简体繁体
                $params['spp_fname']=str_replace(['%','_'],['\%','\_'],$params['spp_fname']);
                $queryParams[':spp_fname1']='%'.$params['spp_fname'].'%';
                $queryParams[':spp_fname2']='%'.$trans->c2t($params['spp_fname']).'%';
                $queryParams[':spp_fname3']='%'.$trans->t2c($params['spp_fname']).'%';
                $sql.=" and (a.supplier_name like :spp_fname1 or a.supplier_name like :spp_fname2 or a.supplier_name like :spp_fname3)";
            }
            $sql.=" order by a.pk_id desc";
        }else{
            $sql="select a.pk_id,
                         a.part_no,
                         b.pdt_name,
                         a.supplier_code,
                         a.supplier_name,
                         a.payment_terms,
                         a.trading_terms,
                         a.currency,
                         ifnull(concat(a.min_num,'~',ifnull(a.max_num,'以上')),'无') num_area,
                         a.buy_price,
                         substring(a.effective_date,1,10) effective_date,
                         substring(a.expiration_date,1,10) expiration_date,
                         a.remarks,
                         case a.status when 1 then '待提交' when 2 then '审核中' when 3 then '审核完成' when 4 then '驳回' when 5 then '终止' else '' end status 
                  from pdt.pdtprice_pas a
                  left join pdt.bs_material b on b.part_no = a.part_no
                  where 1 = 1";
            if($params['flag']=="a"){//已审核核价资料
                $sql.=" and a.status = 3";
            }
            if($params['flag']=="b"){//审核中核价资料
                $sql.=" and a.status = 2";
            }
            if($params['flag']=="c"){//未审核核价资料
                $sql.=" and (a.status = 1 or a.status = 4)";
            }
            //查询
            if(!empty($params['part_no'])){
                $params['part_no']=str_replace(['%','_'],['\%','\_'],$params['part_no']);
                $queryParams[':part_no']='%'.$params['part_no'].'%';
                $sql.=" and a.part_no like :part_no";
            }
            if(!empty($params['pdt_name'])){
                $trans=new Trans();//处理简体繁体
                $params['pdt_name']=str_replace(['%','_'],['\%','\_'],$params['pdt_name']);
                $queryParams[':pdt_name1']='%'.$params['pdt_name'].'%';
                $queryParams[':pdt_name2']='%'.$trans->c2t($params['pdt_name']).'%';
                $queryParams[':pdt_name3']='%'.$trans->t2c($params['pdt_name']).'%';
                $sql.=" and (b.pdt_name like :pdt_name1 or b.pdt_name like :pdt_name2 or b.pdt_name like :pdt_name3)";
            }
            if(!empty($params['spp_code'])){
                $params['spp_code']=str_replace(['%','_'],['\%','\_'],$params['spp_code']);
                $queryParams[':spp_code']='%'.$params['spp_code'].'%';
                $sql.=" and a.supplier_code like :spp_code";
            }
            if(!empty($params['spp_fname'])){
                $trans=new Trans();//处理简体繁体
                $params['spp_fname']=str_replace(['%','_'],['\%','\_'],$params['spp_fname']);
                $queryParams[':spp_fname1']='%'.$params['spp_fname'].'%';
                $queryParams[':spp_fname2']='%'.$trans->c2t($params['spp_fname']).'%';
                $queryParams[':spp_fname3']='%'.$trans->t2c($params['spp_fname']).'%';
                $sql.=" and (a.supplier_name like :spp_fname1 or a.supplier_name like :spp_fname2 or a.supplier_name like :spp_fname3)";
            }
            $sql.=" order by a.pk_id asc";
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
        return [
            //付款條件
            'payment_terms'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'hjfktj' order by bsp_id asc")->queryAll(),
            //交易條件
            'trading_terms'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'hjjytj' order by bsp_id asc")->queryAll(),
            //币别
            'currency'=>Yii::$app->db->createCommand("select bsp_id,bsp_svalue from erp.bs_pubdata where bsp_status = 10 and bsp_stype = 'jybb' order by bsp_id asc")->queryAll(),
        ];
    }

    //新增料号关联供应商
    public function actionAdd()
    {
        if($data=Yii::$app->request->post()){
            $transaction=PdtpricePas::getDb()->beginTransaction();
            try{
                PdtpricePas::deleteAll(['part_no'=>$data['PdtpricePas']['part_no'],'status'=>[1,4]]);
                $oneLevel=[];
                $twoLevel=[];
                foreach($data['arr'] as $key=>$val){
                    $model=new PdtpricePas();
                    $model->part_no=$data['PdtpricePas']['part_no'];
                    $model->material=$data['PdtpricePas']['material'];
                    $model->status=1;
                    $model->pas_date=date('Y-m-d H:i:s');
                    if(count($val['PdtpricePas'])==12){
                        if(!empty($oneLevel) && $oneLevel['PdtpricePas']['supplier_code']!=$val['PdtpricePas']['supplier_code']){
                            $twoLevel=[];
                        }
                        $oneLevel=$val;
                    }
                    if(count($val['PdtpricePas'])==11){
                        $twoLevel=$val;
                    }
                    $model->load($oneLevel);
                    $model->load($twoLevel);
                    $model->load($val);
                    if(!$model->save()){
                        throw new Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
                return $this->success('新增成功');
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile().'--'.$e->getLine().'--'.$e->getMessage());
            }
        }
        return $this->addData();
    }

    //修改料号关联供应商
    public function actionEdit($id)
    {
        if($data=Yii::$app->request->post()){
            $transaction=PdtpricePas::getDb()->beginTransaction();
            try{
                PdtpricePas::deleteAll(['part_no'=>$data['PdtpricePas']['part_no'],'status'=>[1,4]]);
                $oneLevel=[];
                $twoLevel=[];
                foreach($data['arr'] as $key=>$val){
                    $model=new PdtpricePas();
                    $model->part_no=$data['PdtpricePas']['part_no'];
                    $model->material=$data['PdtpricePas']['material'];
                    $model->status=1;
                    if(count($val['PdtpricePas'])==12){
                        if(!empty($oneLevel) && $oneLevel['PdtpricePas']['supplier_code']!=$val['PdtpricePas']['supplier_code']){
                            $twoLevel=[];
                        }
                        $oneLevel=$val;
                    }
                    if(count($val['PdtpricePas'])==11){
                        $twoLevel=$val;
                    }
                    $model->load($oneLevel);
                    $model->load($twoLevel);
                    $model->load($val);
                    if(!$model->save()){
                        throw new Exception(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
                return $this->success('修改成功');
            }catch(\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getFile().'--'.$e->getLine().'--'.$e->getMessage());
            }
        }
        $data['addData']=$this->addData();
        $sql="select b.part_no,
                     b.pdt_name,
                     b.brand,
                     b.tp_spec,
                     b.unit,
                     a.material,
                     concat(e.catg_name,'>',d.catg_name,'>',c.catg_name) category
              from pdt.pdtprice_pas a
              left join pdt.bs_material b on b.part_no = a.part_no
              left join pdt.bs_category c on c.catg_no = b.category_no
              left join pdt.bs_category d on d.catg_id = c.p_catg_id
              left join pdt.bs_category e on e.catg_id = d.p_catg_id
              where a.pk_id = :id";
        $data['editData']=Yii::$app->db->createCommand($sql,[':id'=>$id])->queryOne();
        return $data;
    }

    //选择料号
    public function actionSelectPno()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['rows'])){
            return [
                'pdtCategory'=>Yii::$app->db->createCommand("select catg_id,catg_name from pdt.bs_category where catg_level = 1 order by catg_id asc")->queryAll()
            ];
        }
        $sql="select a.pkmt_id,
                     a.part_no,
                     a.pdt_name,
                     a.brand,
                     a.tp_spec,
                     a.unit,
                     concat(d.catg_name,'>',c.catg_name,'>',b.catg_name) category
              from pdt.bs_material a
              left join pdt.bs_category b on b.catg_no = a.category_no
              left join pdt.bs_category c on c.catg_id = b.p_catg_id
              left join pdt.bs_category d on d.catg_id = c.p_catg_id
              where 1 = 1";
        //查询
        if(!empty($params['pdt_category'])){
            $queryParams[':pdt_category']=$params['pdt_category'];
            $sql.=" and d.catg_id = :pdt_category";
        }
        if(!empty($params['pdt_info'])){
            $trans=new Trans();//处理简体繁体
            $params['pdt_info']=str_replace(['%','_'],['\%','\_'],$params['pdt_info']);
            $queryParams[':pdt_info1']='%'.$params['pdt_info'].'%';
            $queryParams[':pdt_info2']='%'.$trans->c2t($params['pdt_info']).'%';
            $queryParams[':pdt_info3']='%'.$trans->t2c($params['pdt_info']).'%';
            $sql.=" and (a.part_no like :pdt_info1 or a.pdt_name like :pdt_info1 or a.pdt_name like :pdt_info2 or a.pdt_name like :pdt_info3)";
        }
        $totalCount=Yii::$app->db->createCommand("select count(*) from ({$sql}) A",empty($queryParams)?[]:$queryParams)->queryScalar();
        $sql.=" order by a.pkmt_id desc";
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

    //根据料号获取料号信息
    public function actionGetPnoInfo()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.pkmt_id,
                     a.part_no,
                     a.pdt_name,
                     a.brand,
                     a.tp_spec,
                     a.unit,
                     concat(d.catg_name,'>',c.catg_name,'>',b.catg_name) category
              from pdt.bs_material a
              left join pdt.bs_category b on b.catg_no = a.category_no
              left join pdt.bs_category c on c.catg_id = b.p_catg_id
              left join pdt.bs_category d on d.catg_id = c.p_catg_id
              where a.part_no = :pno";
        return Yii::$app->db->createCommand($sql,[':pno'=>$params['code']])->queryOne();
    }

    //根据料号获取对应供应商
    public function actionGetSupplierByPno()
    {
        $params=Yii::$app->request->queryParams;
        $sql="select a.supplier_code,
                     a.supplier_name,
                     a.payment_terms,
                     a.trading_terms,
                     a.currency,
                     substring(a.effective_date, 1, 10) effective_date,
                     substring(a.expiration_date, 1, 10) expiration_date,
                     a.flag,
                     a.min_num,
                     a.max_num,
                     a.buy_price,
                     a.remarks,
                     a.material
              from pdt.pdtprice_pas a
              where a.part_no = :pno";
        return Yii::$app->db->createCommand($sql,[':pno'=>$params['pno']])->queryAll();
    }

    //导入客户
    public function actionImport($companyId,$createBy){
        $post = \Yii::$app->request->post();
        static $succ = 0;
        static $err = 0;
        $log=[];
        foreach ($post as $k => $v) {
            if ($k >= 0) {
                $trans=RPrtSpp::getDb()->beginTransaction();
                try{
                    //判断导入数据是否存在
                    if(empty($v['A'])){
                        throw new \Exception("料号不能为空");
                    }
                    if(empty($v['C'])){
                        throw new \Exception("供应商代码不能为空");
                    }
                    if(empty($v['E'])){
                        throw new \Exception("采购价不能为空");
                    }
                    if(empty(preg_match("/^[1-9]([0-9]{1,11})?([.][0-9]{1,6})?$/",$v['E']))){
                        throw new \Exception("采购价字符错误");
                    }
//                    if(empty($v['F'])){
//                        throw new \Exception("有效期不能为空");
//                    }elseif(empty(preg_match("/^[1-9]\d{3}\/(0?[1-9]|1[0-2])\/(0?[1-9]|[1-2][0-9]|3[0-1])$/",$v['F']))){
//                        throw new \Exception($v['F']."日期格式错误");
//                    }elseif(strtotime($v['F']) <= strtotime("today")){
//                        throw new \Exception("有效期".$v['F']."要大于当前日期".date("Y-m-d"));
//                    }
                    if(empty($v['F'])){
                        throw new \Exception("有效期不能为空");
                    }
                    if((int)$v['F'] <= strtotime("today")){
                        throw new \Exception("有效期要大于当前日期");
                    }
                    $v['F']=date("Y-m-d",$v['F']);
                    //判断料号是否存在
                    $pnoModel=BsPartno::findOne(['part_no'=>$v['A']]);
                    if(empty($pnoModel)){
                        throw new \Exception($v['A']."料号不存在");
                    }
                    //判断供应商是否存在
                    $sppModel=BsSupplier::findOne(['spp_code'=>$v['C']]);
                    if(empty($sppModel)){
                        $sppModel=BsSupplier::findOne(['group_code'=>$v['C']]);
                        if(empty($sppModel)){
                            throw new \Exception($v['C']."供应商不存在");
                        }
                    }
                    //料号关联供应商主表
                    $mainModel=RPrtSpp::findOne(['prt_pkid'=>$pnoModel->prt_pkid]);
                    if(empty($mainModel)){
                        $mainModel=new RPrtSpp();
                        $mainModel->prt_pkid=$pnoModel->prt_pkid;
                    }
                    $mainModel->opper=$createBy;
                    $mainModel->op_date=date("Y-m-d H:i:s");
                    if(!$mainModel->save()){
                        throw new \Exception(json_encode($mainModel->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                    //料号关联供应商子表
                    $childModel=RPrtSppDt::findOne(['prt_spp_pkid'=>$mainModel->prt_spp_pkid,'spp_id'=>$sppModel->spp_id]);
                    if(empty($childModel)){
                        $childModel=new RPrtSppDt();
                        $childModel->prt_spp_pkid=$mainModel->prt_spp_pkid;
                        $childModel->spp_id=$sppModel->spp_id;
                        $childModel->price=$v['E'];
                        $childModel->eff_date=$v['F'];
                        $childModel->remark=$v['G'];
                        if(!$childModel->save()){
                            throw new \Exception((json_encode($childModel->getErrors(),JSON_UNESCAPED_UNICODE)));
                        }
                    }else{
                        throw new \Exception($v['A']."料号已关联".$v['C']."供应商");
                    }
                    $succ++;
                    $trans->commit();
                }catch (\Exception $e){
                    $log[]=[
                        'file'=>basename(get_class()).":".$e->getLine(),
                        'message'=>$e->getMessage()
                    ];
                    $err++;
                    $trans->rollBack();
                }
            }
        }
        return ["succ"=>$succ,"error"=>$err,"log"=>$log];
    }
}