<?php
namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsBrand;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsProduct;
use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\BsInvWarnH;
use app\modules\warehouse\models\BsWhM;
use app\modules\warehouse\models\InvWarner;
use app\modules\warehouse\models\InvWarnerH;
use app\modules\warehouse\models\LInvWarn;
use app\modules\warehouse\models\LInvWarner;
use app\modules\warehouse\models\LInvWarnerH;
use app\modules\warehouse\models\Search\ProductInfoSearch;
use app\modules\warehouse\models\Search\SetInventoryWarningSearch;
use app\modules\warehouse\models\Search\SetInvWarnerSearch;
use app\modules\warehouse\models\show\SetInventoryWarningShow;
use app\modules\warehouse\models\WarehouseStaffCode;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;


class SetInventoryWarningController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsInvWarnH';

    public function actionIndex()
    {
        $searchModel = new SetInventoryWarningSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    //根据选择的预警人员加载对应的预警信息
    public  function actionProductinfo(){
        $searchModel = new SetInventoryWarningSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->searchproductInfo($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }
    //按商品类别搜索
//    public function actionSearchcategory(){
//        //return '123';
//        $searchModel=new ProductInfoSearch();
//        $queryParams = Yii::$app->request->queryParams;
//       // return $queryParams;
//        $dataProvider = $searchModel->searchcategory($queryParams);
//        //return $dataProvider;
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list['total'] = $dataProvider->totalCount;
//        return $list;
//    }

    //批量添加数据
    public function  actionNumadd(){
        $searchModel=new ProductInfoSearch();
        $queryParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }


    /* 新增*/
    public function actionCreate()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->wms->beginTransaction();
            try {
                if (count($data['partnoArr'])==1)
                {
                    throw new \Exception(json_encode('请选择所负责的商品！', JSON_UNESCAPED_UNICODE));
                }
                $InWarnerInfo = new InvWarner();
                $InWarnerInfo->OPP_DATE = date('Y-m-d H:i:s', time());//操作时间
                $InWarnerInfo->OPP_IP =  Yii::$app->request->getUserIP();//'//获取ip地址
                $InWarnerInfo->so_type=$data['InvWarner']['so_type'];//10
                $InWarnerInfo->YN=0;
                if (!($InWarnerInfo->load($data) && $InWarnerInfo->save())) {
                    throw new \Exception(json_encode($InWarnerInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                foreach ($data['partnoArr'] as $val)
                {
                    if (!empty($val['BsInvWarn']['part_no'])&&!empty($val['BsInvWarnH']['wh_id'])) {
                        //Inv_Warner_H表
                        $id=$InWarnerInfo->LIW_PKID;
                        $InWarnerHInfo=new InvWarnerH();
                        $InWarnerHInfo->LIW_PKID=$InWarnerInfo->LIW_PKID;//获取InvWarner表中主键id存入Inv_Warner_H表
                        $InvpkidInfo=$this->getInvpkidInfo($val["BsInvWarnH"]["inv_id"]);
                        $InWarnerHInfo->inv_warn_PKID=$InvpkidInfo;
                        if (!($InWarnerHInfo->save())) {
                            throw new \Exception(json_encode($InWarnerHInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                }
                if($data["is_apply"]=="1"){
                    $LInvWarner=new LInvWarner();
                    $LInvWarner->LIW_PKID=$this->findLInvAdmid()+1;//findLInvAdmidfindLInvHAdmid
                    $LInvWarner->staff_code=$data["InvWarner"]["staff_code"];
                    $LInvWarner->OPP_DATE=date('Y-m-d H:i:s', time());//操作时间
                    $LInvWarner->OPP_IP=Yii::$app->request->getUserIP();//'//获取ip地址
                    $LInvWarner->OPPER=$InWarnerInfo->OPPER;
                    if (!($LInvWarner->save())) {
                        throw new \Exception(json_encode($LInvWarner->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                    foreach ($data['partnoArr'] as $val){
                        if (!empty($val['BsInvWarn']['part_no'])&&!empty($val['BsInvWarnH']['wh_id'])) {
                            $LInvWarnerH=new LInvWarnerH();
                            //查找LInvWarnerH表的inv_warn_PKID
                            $Invwarnidinfo=$this->getInvwarnidinfo($val["BsInvWarnH"]["inv_id"]);
                            $LInvWarnerH->LIW_PKID= $LInvWarner->LIW_PKID;
                            $LInvWarnerH->inv_warn_PKID=$Invwarnidinfo;//
                            if (!($LInvWarnerH->save())) {
                                throw new \Exception(json_encode($LInvWarnerH->getErrors(), JSON_UNESCAPED_UNICODE));
                            }
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        $msg = array('id'=>$id);
        return $this->success('',$msg);
    }

    private function getInvpkidInfo($Invid){
        $sql='select n.inv_warn_PKID from wms.bs_inv_warn_h h left join wms.bs_inv_warn n on h.biw_h_pkid=n.biw_h_pkid where h.inv_id=:Invid and h.so_type=20 and h.YN=1';
        $Invwarnidinfo=Yii::$app->db->createCommand($sql)->bindValue(":Invid",$Invid)->queryOne();
        return $Invwarnidinfo["inv_warn_PKID"];
    }
    private  function getInvwarnidinfo($Invid){
        $sql='select w.inv_warn_PKID from wms.L_inv_warn_h h left join wms.l_inv_warn w on h.biw_h_pkid=w.biw_h_pkid where h.inv_id=:Invid and h.so_type=20 and h.YN=1';
        $Invwarnidinfo=Yii::$app->db->createCommand($sql)->bindValue(":Invid",$Invid)->queryOne();
        return $Invwarnidinfo["inv_warn_PKID"];
    }


    //修改
    public function actionUpdate($id){
        if(Yii::$app->request->isPost){
           // $db = Yii::$app->get('db');//指向数据库1(root用戶)
            $db2 = Yii::$app->get('wms');//指向数据库2(wms用戶)
            $data = Yii::$app->request->post();
            $transaction = $db2->beginTransaction();
            try {
                //删除inv_warner_h表中的数据
                //$command =Yii::$app->db->createCommand('DELETE FROM wms.inv_warner_h  WHERE inv_warn_PKID in(select inv_warner_ID from wms.inv_warner  where `staff_code`=:id)')->bindValue(':id', $id)->execute();
//                  //删除inv_warner表中的数据
                // $command =Yii::$app->db->createCommand('DELETE FROM wms.inv_warner WHERE `staff_code`=:id')->bindValue(':id', $id)->execute();
//                    if($data["so_type"]=="40"&&$data["is_apply"]=="1"){//40表示審核完成，審核完成之後再修改必須提交
//                        //$command=Yii::$app->db->createCommand('DELETE FROM wms.l_inv_warner_h  WHERE inv_warn_PKID in(select LIW_PKID from wms.l_inv_warner  where `staff_code`=:id)')->bindValue(':id', $id)->execute();
//                       // $command =Yii::$app->db->createCommand('DELETE FROM wms.l_inv_warner WHERE `staff_code`=:id')->bindValue(':id', $id)->execute();
//                    }

                if (count($data['partnoArr']) == 1) {
                    throw new \Exception(json_encode('请选择所负责的商品！', JSON_UNESCAPED_UNICODE));
                }
                $sql="select so_type from wms.inv_warner where LIW_PKID=:id";
                $staffinfo=Yii::$app->db->createCommand($sql)->bindValue(":id",$id)->queryOne();
                if($staffinfo["so_type"]=="10"){
                    InvWarner::deleteAll(['LIW_PKID'=>$id]);
                    InvWarnerH::deleteAll(['LIW_PKID'=>$id]);
                }
                //Inv_Warner表
                $InWarnerInfo = new InvWarner();
                $InWarnerInfo->OPP_DATE = date('Y-m-d H:i:s', time());//操作时间
                $InWarnerInfo->OPP_IP =  Yii::$app->request->getUserIP();//'//获取ip地址
                $InWarnerInfo->so_type=$data['InvWarner']['so_type'];//10
                $InWarnerInfo->YN=0;
                if (!($InWarnerInfo->load($data) && $InWarnerInfo->save())) {
                    throw new \Exception(json_encode($InWarnerInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                foreach ($data['partnoArr'] as $val) {
                    if (!empty($val['BsInvWarn']['part_no']) && !empty($val['BsInvWarnH']['wh_id'])) {
                        $id = $InWarnerInfo->LIW_PKID;
                        //Inv_Warner_H表
                        $InWarnerHInfo = new InvWarnerH();
                        $InWarnerHInfo->LIW_PKID = $InWarnerInfo->LIW_PKID;//获取InvWarner表中主键id存入Inv_Warner_H表
                        $InvpkidInfo = $this->getInvpkidInfo($val["BsInvWarnH"]["inv_id"]);
                        $InWarnerHInfo->inv_warn_PKID = $InvpkidInfo;
                        if (!($InWarnerHInfo->save())) {
                            throw new \Exception(json_encode($InWarnerHInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                    }
                    if($data["is_apply"]=="1"&&($data["so_type"]=="10"||$data["so_type"]=="40")){
                        $LInvWarner=new LInvWarner();
                        $LInvWarner->LIW_PKID=$this->findLInvAdmid()+1;//findLInvAdmidfindLInvHAdmid
                        $LInvWarner->staff_code=$data["InvWarner"]["staff_code"];
                        $LInvWarner->OPP_DATE=date('Y-m-d H:i:s', time());//操作时间
                        $LInvWarner->OPP_IP=Yii::$app->request->getUserIP();//'//获取ip地址
                        $LInvWarner->OPPER=$InWarnerInfo->OPPER;
                        if (!($LInvWarner->save())) {
                            throw new \Exception(json_encode($LInvWarner->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        foreach ($data['partnoArr'] as $val) {
                            if (!empty($val['BsInvWarn']['part_no']) && !empty($val['BsInvWarnH']['wh_id'])) {
                                $LInvWarnerH = new LInvWarnerH();
                                //查找LInvWarnerH表的inv_warn_PKID
                                $Invwarnidinfo = $this->getInvwarnidinfo($val["BsInvWarnH"]["inv_id"]);
                                $LInvWarnerH->LIW_PKID = $LInvWarner->LIW_PKID;
                                $LInvWarnerH->inv_warn_PKID = $Invwarnidinfo;//
                                if (!($LInvWarnerH->save())) {
                                    throw new \Exception(json_encode($LInvWarnerH->getErrors(), JSON_UNESCAPED_UNICODE));
                                }
                            }
                        }
                    }
                }
            }catch (\Exception $e){
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            $transaction->commit();
        }
        $msg = array('id'=>$id);
        return $this->success('',$msg);
    }

    protected  function findModel($id){
        $sql = "select w.LIW_PKID,w.staff_code,w.remarks,w.so_type,s.staff_email,s.staff_mobile,s.staff_name,date_format(DATE(w.OPP_DATE),'%Y/%c/%d')OPP_DATE,(select staff_name from erp.hr_staff h where h.staff_code=w.OPPER)OPPER from wms.inv_warner w left join erp.hr_staff s on w.staff_code=s.staff_code where w.LIW_PKID=:id";
        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
        //$sql1 = "select b.wh_code,b.part_no,c.wh_name,b.up_nums,b.down_nums from wms.inv_warner a left join wms.bs_inv_warn  b on b.inv_id=a.inv_id left join wms.bs_wh c on b.wh_code=c.wh_code where a.staff_code=:id";
       // $whinfo = Yii::$app->db->createCommand($sql1)->bindValue(':id', $id)->queryAll();
//        $sql2='select b.BRAND_NAME_CN,c.category_sname,h.part_no,h.wh_name,h.pdt_name,h.pdt_model,h.up_nums,h.down_nums,h.remarks from (SELECT
//                wa.wh_code,
//                wa.part_no,
//            wa.up_nums,
//            wa.down_nums,
//            (select b.wh_name from wms.bs_wh b where b.wh_code=wa.wh_code)wh_name,
//            (select p.pdt_name from erp.bs_product p where p.pdt_no=wa.part_no)pdt_name,
//            (select p.pdt_model from erp.bs_product p where p.pdt_no=wa.part_no)pdt_model,
//            (select p.brand_id from erp.bs_product p where p.pdt_no=wa.part_no)brand_id,
//            (select p.bs_category_id from  erp.bs_product p WHERE p.pdt_no=wa.part_no)bs_category_id,
//            w.remarks
//             FROM
//                wms.inv_warner w
//            LEFT JOIN wms.bs_inv_warn wa ON w.inv_id = wa.inv_id
//            WHERE w.staff_code = :id)h left join erp.bs_brand b on b.BRAND_ID=h.brand_id
//            left join erp.bs_category c on c.category_id=h.bs_category_id';
//        $sql2="select bt.invt_num,bh.inv_id,b.BRAND_NAME_CN,c.category_sname,sss.pdt_model,sss.part_no,sss.pdt_name,sss.down_nums,sss.up_nums,(select bs.wh_name from wms.bs_wh bs where bs.wh_id=bh.wh_id)wh_name,bh.wh_id from
//(select wa.part_no,(select p.pdt_name from erp.bs_product p where p.pdt_no=wa.part_no)pdt_name,
//(select p.pdt_model from erp.bs_product p where p.pdt_no=wa.part_no)pdt_model,
//        (select p.brand_id from erp.bs_product p where p.pdt_no=wa.part_no)brand_id,
//        (select p.bs_category_id from  erp.bs_product p WHERE p.pdt_no=wa.part_no)bs_category_id,
//ss.staff_code,wa.down_nums,wa.up_nums,wa.biw_h_pkid from (
//select h.inv_warn_PKID ,w.staff_code
//from wms.inv_warner w LEFT JOIN wms.inv_warner_h h  on w.LIW_PKID=h.LIW_PKID WHERE w.staff_code=:id and w.YN=1)ss
//LEFT JOIN wms.bs_inv_warn wa on wa.inv_warn_PKID=ss.inv_warn_PKID)sss
//LEFT JOIN erp.bs_brand b ON b.BRAND_ID = sss.brand_id
//LEFT JOIN erp.bs_category c ON c.category_id = sss.bs_category_id
//LEFT JOIN wms.bs_inv_warn_h bh on bh.biw_h_pkid=sss.biw_h_pkid
//left JoIN wms.bs_invt bt on bt.part_no=sss.part_no and bt.wh_id=bh.wh_id";
       $sql2=" select bh.inv_id, bh.wh_id,(select wh_name from wms.bs_wh where wh_id=bh.wh_id)wh_name,bt.invt_num,b.BRAND_NAME_CN,c.category_sname,s.pdt_model,s.part_no,s.down_nums,s.up_nums,s.pdt_name  from (
           select wa.biw_h_pkid, wa.part_no,wa.down_nums,wa.up_nums,wa.inv_warn_PKID,
(select p.pdt_model from erp.bs_product p where p.pdt_no=wa.part_no)pdt_model,
         (select p.brand_id from erp.bs_product p where p.pdt_no=wa.part_no)brand_id,
        (select p.bs_category_id from erp.bs_product p WHERE p.pdt_no=wa.part_no)bs_category_id,
(select p.pdt_name from erp.bs_product p where p.pdt_no=wa.part_no)pdt_name
from wms.inv_warner_h h left join wms.bs_inv_warn wa on h.inv_warn_PKID=wa.inv_warn_PKID
 where h.LIW_PKID=:id)s left join wms.bs_inv_warn_h bh on bh.biw_h_pkid=s.biw_h_pkid
left join wms.bs_invt bt on bt.part_no=s.part_no and bt.wh_id=bh.wh_id
LEFT JOIN erp.bs_brand b ON b.BRAND_ID = s.brand_id
LEFT JOIN erp.bs_category c ON c.category_id = s.bs_category_id";
        $productinfo =Yii::$app->db->createCommand($sql2)->bindValue(':id', $id)->queryAll();
        $infoall = [$basicinfo, $productinfo];
        if($infoall!==null){
            return $infoall;
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
    * 详情及修改页面调用
    */
    public function actionModels($id)
    {
        return $this->findModel($id);
    }
    /*
     * 获取lInvWarner表中主键id
     */
    public function findLInvAdmid()
    {
        $query = LInvWarner::find()->select('MAX(LIW_PKID) LIW_PKID');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelBsInvWarn = $dataProvider->getModels();
        return $modelBsInvWarn[0]['LIW_PKID'];
    }
    /*
     * 获取lInvWarnerH表中主键id
     */
    public function findLInvHAdmid()
    {
        $query = LInvWarnerH::find()->select('MAX(LIW_PKID) LIW_PKID');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelBsInvWarn = $dataProvider->getModels();
//        if(empty($modelBsInvWarn)){
//            $modelBsInvWarn[0]['LIW_PKID']="0";
//        }
        return $modelBsInvWarn[0]['LIW_PKID'];
    }
    /**
     * 下拉菜单数据
     * @return mixed
     */
    public function actionDownList()
    {
        $StaffCode['whname'] = BsWhM::getWhCodeAll();
        $StaffCode["categoryname"] = BsCategory::getLevelOne();
        return $StaffCode;
    }

    /*详情*/
//    public function actionView($id)
//    {
//        $sql = "select s.staff_code,s.staff_name,s.staff_email,s.staff_mobile from erp.hr_staff s where s.staff_code=:id";
//        $basicinfo = Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryOne();
//        $sql1 = "select b.wh_code,b.part_no,c.wh_name,b.up_nums,b.down_nums from wms.inv_warner a left join wms.bs_inv_warn  b on b.inv_id=a.inv_id left join wms.bs_wh c on b.wh_code=c.wh_code where a.staff_code=:id";
//        $whinfo = Yii::$app->db->createCommand($sql1)->bindValue(':id', $id)->queryAll();
//        $queryParams = [];
//        $sql2='select b.BRAND_NAME_CN,c.category_sname,h.part_no,h.wh_name,h.pdt_name,h.pdt_model,h.up_nums,h.down_nums from (SELECT
//                wa.wh_code,
//                wa.part_no,
//            wa.up_nums,
//            wa.down_nums,
//            (select b.wh_name from wms.bs_wh b where b.wh_code=wa.wh_code)wh_name,
//            (select p.pdt_name from erp.bs_product p where p.pdt_no=wa.part_no)pdt_name,
//            (select p.pdt_model from erp.bs_product p where p.pdt_no=wa.part_no)pdt_model,
//            (select p.brand_id from erp.bs_product p where p.pdt_no=wa.part_no)brand_id,
//            (select p.bs_category_id from  erp.bs_product p WHERE p.pdt_no=wa.part_no)bs_category_id
//             FROM
//                wms.inv_warner w
//            LEFT JOIN wms.bs_inv_warn wa ON w.inv_id = wa.inv_id
//            WHERE w.staff_code = :id)h left join erp.bs_brand b on b.BRAND_ID=h.brand_id
//            left join erp.bs_category c on c.category_id=h.bs_category_id';
//        $productinfo =Yii::$app->db->createCommand($sql2)->bindValue(':id', $id)->queryAll();
//        $infoall = [$basicinfo, $whinfo, $productinfo];
//        return $infoall;
//    }
    public function  getWhname($code){
        $query = BsWhM::find()->select('wh_code')->filterWhere(["wh_name"=>$code]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelBsWhM = $dataProvider->getModels();
        return $modelBsWhM[0]['wh_code'];
    }

    public function actionGetWhnameInfo($code){
        //$query=BsWhM::find()->select('wh_name')->andFilterWhere(["wh_name"=>$code])->all();
        $sql="select b.wh_code  from wms.bs_wh b WHERE b.wh_name=:code";
        $whinfo = Yii::$app->db->createCommand($sql)->bindValue(':code', $code)->queryOne();
        return $whinfo;
    }
    public function actionGetCheckInfo($code){
        $sql="SELECT * FROM wms.inv_warner  where staff_code=:code and so_type<>10";
        $staffinfo=Yii::$app->db->createCommand($sql)->bindValue(':code', $code)->queryAll();
        return $staffinfo;
    }
    public function actionInvidInfo($id){
        $sql="SELECT * FROM wms.inv_warner where staff_code=:id";
        $InvidInfo=Yii::$app->db->createCommand($sql)->bindValue(':id', $id)->queryAll();
        return $InvidInfo;
    }

    //导出
    public function actionExport()
    {
        $model=new SetInventoryWarningSearch();
        $dataProvider=$model->searchApply(Yii::$app->request->queryParams);
        return $dataProvider->getModels();
    }
    //验证当前人员是否有已送审的信息
    public function actionGetStaffCheck($code){
        $sql='select * from wms.inv_warner where staff_code=:code and so_type=20';
        $info=Yii::$app->db->createCommand($sql)->bindValue(':code', $code)->queryAll();
        return $info;
    }
}