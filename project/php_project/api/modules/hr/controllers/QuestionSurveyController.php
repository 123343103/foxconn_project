<?php
/**
 * User: F3859386
 * Date: 2017/10/20
 */
namespace app\modules\hr\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\hr\models\BsQstInvst;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\InvstContent;
use app\modules\hr\models\InvstDpt;
use app\modules\hr\models\InvstOptions;
use app\modules\hr\models\Search\AnswSearch;
use app\modules\hr\models\show\InvstContentShow;
use app\modules\system\models\SystemLog;
use yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\classes\Trans;
/**
 * 问卷调查控制器
 */
class QuestionSurveyController extends BaseActiveController
{
    public $modelClass = 'app\modules\hr\models\BsQstInvst';

    public function actionIndex()
    {
        $params=Yii::$app->request->queryParams;
        if(empty($params['rows'])){
            return [
                'invst_type'=>BsPubdata::getData(BsPubdata::QUESTION_TYPE)
            ];
        }
        $querySql="select a.invst_id,
                          a.invst_state,
                          a.invst_subj,
                          e.bsp_svalue,
                          a.yn_close,
                          a.yn_de,
                          d.organization_name,
                          group_concat(c.organization_name)dd,                      
                          a.clo_nums,
                          a.invst_path                
                   from erp.bs_qst_invst a 
                   LEFT JOIN erp.invst_dpt b ON a.invst_id = b.invst_id
                   LEFT JOIN erp.hr_organization c ON b.dpt_id=c.organization_id
                   LEFT JOIN erp.hr_organization d ON a.invst_dpt=d.organization_id
                   LEFT JOIN erp.bs_pubdata e ON e.bsp_id=a.invst_type
                   WHERE a.yn_de=0 ";
        $queryParams=[];
        //列表查询
        if(!empty($params['invst_subj'])){
            $trans=new Trans();
            $params['invst_subj']=str_replace(['%','_'],['\%','\_'],$params['invst_subj']);
            $queryParams[':invst_subj1']='%'.$params['invst_subj'].'%';
            $queryParams[':invst_subj2']='%'.$trans->c2t($params['invst_subj']).'%';
            $queryParams[':invst_subj3']='%'.$trans->t2c($params['invst_subj']).'%';
            $querySql.=" and (a.invst_subj like :invst_subj1 or a.invst_subj like :invst_subj2 or a.invst_subj like :invst_subj3)";
        }

        if(!empty($params['invst_type'])){
            $trans=new Trans();
            $params['invst_type']=str_replace(['%','_'],['\%','\_'],$params['invst_type']);
            $queryParams[':invst_type1']='%'.$params['invst_type'].'%';
            $queryParams[':invst_type2']='%'.$trans->c2t($params['invst_type']).'%';
            $queryParams[':invst_type3']='%'.$trans->t2c($params['invst_type']).'%';
            $querySql.=" and (a.invst_type like :invst_type1 or a.invst_type like :invst_type2 or a.invst_type like :invst_type3)";
        }
//        if(!empty($params['invst_type'])){
//            $queryParams[':invst_type1']=$params['invst_type'];
//            $querySql.=" and a.invst_type = :invst_type1";
//        }
        if(!empty($params['organization_name'])){
            $trans=new Trans();
            $params['organization_name']=str_replace(['%','_'],['\%','\_'],$params['organization_name']);
            $queryParams[':organization_name1']='%'.$params['organization_name'].'%';
            $queryParams[':organization_name2']='%'.$trans->c2t($params['organization_name']).'%';
            $queryParams[':organization_name3']='%'.$trans->t2c($params['organization_name']).'%';
//            $countSql.=" and (a.invst_dpt like :invst_dpt1 or a.invst_dpt like :invst_dpt2 or a.invst_dpt like :invst_dpt3)";
            $querySql.=" and (d.organization_name like :organization_name1 or d.organization_name like :organization_name2 or d.organization_name like :organization_name3)";
        }
        $querySql.=" GROUP BY a.invst_id order by invst_id desc";
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a",$queryParams)->queryScalar();
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

    //代填写问卷提醒 部门id
    public function actionSurveyShow($oid,$staff_code)
    {
        $params=Yii::$app->request->queryParams;
        $querySql="SELECT
                        invst_id,
                        invst_subj,
                        invst_path,
                        yn_close
                    FROM
                        bs_qst_invst bqi
                    WHERE
                        bqi.invst_state = 1
                    AND (
                        bqi.yn_de = 0
                        OR bqi.yn_de IS NULL
                    )
                    AND (
                        bqi.yn_close = 0
                        OR bqi.yn_close IS NULL
                    )
                    AND bqi.invst_id IN (
                        SELECT DISTINCT
                            (id.invst_id)
                        FROM
                            invst_dpt id
                        WHERE
                            id.dpt_id IN (
                                SELECT
                                    organization_id
                                FROM
                                    hr_organization
                                WHERE
                                    FIND_IN_SET(
                                        organization_id,
                                        getParList (:oid)
                                    )
                            )
                    )
                    AND bqi.invst_id NOT IN (
                        SELECT
                            invst_id
                        FROM
                            bs_qst_answ
                        WHERE
                            staff_code = :staff_coed
                    )
                    ORDER BY invst_start desc
              ";
        $queryParams[':oid']=$oid;
        $queryParams[':staff_coed']=$staff_code;
        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a",$queryParams)->queryScalar();
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
   //删除问卷信息
    public function actionDelete($id)
    {
        $model = BsQstInvst::findOne($id);
        $model->yn_de = BsQstInvst::QUESTION_STATUS_DEL;
        if ($model->save()) {
            $msg = array('id' => $id, 'msg' => '删除问卷信息');
            return $this->success('',$msg);
        } else {
            return $this->error();
        }
    }
    //批量删除问卷信息
    public function actionDeletes()
    {
        $post=Yii::$app->request->queryParams;
        try{
            $array = $post['id'];
            for ($i=0;$i<count($array);$i++) {
                $model = BsQstInvst::findOne($array[$i]);
                $model->yn_de = BsQstInvst::QUESTION_STATUS_DEL;
                if(!$model->save()){
                    throw  new \Exception("删除失败");
                };
            }
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
   //关闭按钮
    public function actionCloses()
    {
        $post=Yii::$app->request->queryParams;
        try{
            $array = $post['id'];
            for ($i=0;$i<count($array);$i++) {
                $model = BsQstInvst::findOne($array[$i]);
                $model->yn_close = BsQstInvst::QUESTION_STATUS_CLOSE;
                if(!$model->save()){
                    throw  new \Exception("关闭失败");
                };
            }
            return $this->success();
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
    }
   //关闭问卷
    public function actionCloReason($id)
    {
        $_id=explode(',',$id);
        $_succ="关闭失败";
        foreach ($_id as $vid) {
            $data = Yii::$app->request->post();
            $model = BsQstInvst::findOne($vid);
            $model->yn_close = BsQstInvst::QUESTION_STATUS_CLOSE;
            if($model->load($data) && $model->save()){
                $_succ= $this->success('关闭成功');
            }
        }
        return $_succ;
        return $this->error(json_encode($model->getErrors(),JSON_UNESCAPED_UNICODE));
    }
    //
    public function actionGetAddr()
    {

        $params=Yii::$app->request->queryParams;
        //获取id
        $queryParams = [':id' => $params['id']];
        if (empty($params['rows'])) {
            return Yii::$app->db->createCommand("select a.invst_path,a.invst_id from erp.bs_qst_invst a where a.invst_id = :id", $queryParams)->queryOne();
        }
        $provider=new SqlDataProvider([
            'db'=>'db',
            'params'=>$queryParams,
            'pagination'=>[
                'pageSize'=>$params['rows']
            ]
        ]);
        return [
            'rows'=>$provider->getModels(),
        ];
    }
    //问卷列表
    public function actionResponsesList()
    {
        $params=Yii::$app->request->queryParams;
        //获取id
        $queryParams = [':id' => $params['id']];
//        $attrModel = new BsQstAnsw();
//        $attrModel->answ_id = $id;
        if (empty($params['rows'])) {
            return Yii::$app->db->createCommand("select a.invst_subj,a.invst_id from erp.bs_qst_invst a where a.invst_id = :id", $queryParams)->queryOne();
        }
//        if(empty($params['rows'])){
//            return BsPubdata::getData(BsPubdata::QUESTION_TYPE);
//        }
//        $countSql="select count(*) from erp.bs_qst_answ a where a.answ_id != 0";
        $querySql="select a.answ_id,
                          a.invst_id,
                          a.staff_id,
                          a.staff_code,
                          a.staff_name,
                          a.dpt_name,
                          b.invst_subj,
                          a.answ_datetime
                   from erp.bs_qst_answ a LEFT JOIN bs_qst_invst b ON a.invst_id = b.invst_id
                   where a.invst_id=".$queryParams[':id']." ";
        $queryParams=[];
        if(!empty($params['staff_code'])){
            $trans=new Trans();
            $params['staff_code']=str_replace(['%','_'],['\%','\_'],$params['staff_code']);
            $queryParams[':staff_code1']='%'.$params['staff_code'].'%';
            $queryParams[':staff_code2']='%'.$trans->c2t($params['staff_code']).'%';
            $queryParams[':staff_code3']='%'.$trans->t2c($params['staff_code']).'%';
            $querySql.=" and (a.staff_code like :staff_code1 or a.staff_code like :staff_code2 or a.staff_code like :staff_code3)";
        }
        if(!empty($params['staff_name'])){
        $trans=new Trans();
        $params['staff_name']=str_replace(['%','_'],['\%','\_'],$params['staff_name']);
        $queryParams[':staff_name1']='%'.$params['staff_name'].'%';
        $queryParams[':staff_name2']='%'.$trans->c2t($params['staff_name']).'%';
        $queryParams[':staff_name3']='%'.$trans->t2c($params['staff_name']).'%';
        $querySql.=" and (a.staff_name like :staff_name1 or a.staff_name like :staff_name2 or a.staff_name like :staff_name3)";
    }
        if(!empty($params['dpt_name'])){
            $trans=new Trans();
            $params['dpt_name']=str_replace(['%','_'],['\%','\_'],$params['dpt_name']);
            $queryParams[':dpt_name1']='%'.$params['dpt_name'].'%';
            $queryParams[':dpt_name2']='%'.$trans->c2t($params['dpt_name']).'%';
            $queryParams[':dpt_name3']='%'.$trans->t2c($params['dpt_name']).'%';
            $querySql.=" and (a.dpt_name like :dpt_name1 or a.dpt_name like :dpt_name2 or a.dpt_name like :dpt_name3)";
        }

        $totalCount=Yii::$app->get('db')->createCommand("select count(*) from ( {$querySql} ) a", $queryParams)->queryScalar();
        $querySql.=" order by answ_id ASC";
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

    /**
     * @return mixed
     * 公共参数
     */
    public function actionDownList(){
        //问卷类型
        $downList['questionType'] = BsPubdata::getList(BsPubdata::QUESTION_TYPE);
        //主办单位
        $downList['organization'] = HrOrganization::find()->select(['organization_id','organization_code','organization_name'])->where(['!=','organization_state',HrOrganization::STATUS_DELETE])->asArray()->all();//->andWhere(['=','organization_level',HrOrganization::LEVEL_MANUFACTURING])
        return $downList;
    }
    //获取问卷信息数 为关闭的 未删除的 进行中的问卷信息
    public function actionSurveyCount($oid,$staff_coed)
    {
//        $result = BsQstInvst::find()->where(['or',['yn_close'=>null],['yn_close' => 0]])->andWhere(['or',['yn_de'=>null],['yn_de' => 0]])->andWhere(['invst_state'=>1])->count();
////        dumpE($result);
//        return $result;
        $params=Yii::$app->request->queryParams;
        $querySql="SELECT
                        invst_id,
                        invst_subj,
                        invst_path,
                        yn_close
                    FROM
                        bs_qst_invst bqi
                    WHERE
                        bqi.invst_state = 1
                    AND (
                        bqi.yn_de = 0
                        OR bqi.yn_de IS NULL
                    )
                    AND (
                        bqi.yn_close = 0
                        OR bqi.yn_close IS NULL
                    )
                    AND bqi.invst_id IN (
                        SELECT DISTINCT
                            (id.invst_id)
                        FROM
                            invst_dpt id
                        WHERE
                            id.dpt_id IN (
                                SELECT
                                    organization_id
                                FROM
                                    hr_organization
                                WHERE
                                    FIND_IN_SET(
                                        organization_id,
                                        getParList (:oid)
                                    )
                            )
                    )
                    AND bqi.invst_id NOT IN (
                        SELECT
                            invst_id
                        FROM
                            bs_qst_answ
                        WHERE
                            staff_code = :staff_coed
                    )
              ";
        $queryParams[':oid']=$oid;
        $queryParams[':staff_coed']=$staff_coed;
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
        return $provider->totalCount;
    }

    public function actionGetList($organization_id,$organization_ids,$bsp_id)//主办单位、调查对象、类型
    {
         $array=explode(",",$organization_ids);
        $arr=[];
        $sql1="SELECT
			t.organization_name
		FROM
			erp.hr_organization t
		WHERE
			t.organization_id=:organization_id";//主办单位

        $organization_names=null;
        if(!empty($array)) {
            for ($i = 0; $i < count($array); $i++) {
                $sql2 = "SELECT
			t.organization_name
		FROM
			erp.hr_organization t
		WHERE
			t.organization_id =:organization_id1";//调查对象
                $queryParam = [
                    ':organization_id1' => $array[$i],
                ];
                $organization_names = Yii::$app->get('db')->createCommand($sql2, $queryParam)->queryAll();
                $arr[$i]=$organization_names;
            }
        }

        $sql3="SELECT
	t.bsp_svalue
FROM
	erp.bs_pubdata t
WHERE
	t.bsp_stype = 'wjlb'
AND t.bsp_id =:bsp_id";//类型

        $queryParams=[
            ':bsp_id'=>$bsp_id
        ];
        $queryParam1=[
            ':organization_id'=>$organization_id,
        ];
        $organization_name=null;
        if(!empty($organization_id)) {
            $organization_name = Yii::$app->get('db')->createCommand($sql1, $queryParam1)->queryAll();
        }
        $bsp_id=Yii::$app->get('db')->createCommand($sql3, $queryParams)->queryAll();
        return [
            'organization_name'=>$organization_name,
            'organization_names'=>$arr,
            'bsp_id'=>$bsp_id
        ];
    }
    //问卷详情
    public function actionViews($invstid,$answ_id=null)
    {
        $sqldpt="select a.organization_name from erp.invst_dpt t,erp.hr_organization a where t.invst_id=:invst_id and t.dpt_id=a.organization_id";
        $qustSql1="SELECT
	                  bqi.yn_close,
	                  bqi.invst_subj,
	                  bqi.invst_start,
	                  bqi.invst_end,
	                  ic.cnt_id,
	                  ic.cnt_tpc,
	                  ic.cnt_type,
	                  (select bsp_svalue from erp.bs_pubdata bp where  bsp_stype='wjlb' AND bqi.invst_type=bp.bsp_id ) bsp_sname,
	                  (select t.organization_name from erp.hr_organization t where  bqi.invst_dpt=t.organization_id) organization_name
                   FROM
	               erp.bs_qst_invst bqi,
	               erp.invst_content ic
                    WHERE
	                bqi.invst_id = ic.invst_id
                    AND ic.invst_id = :invst_id ORDER BY ic.cnt_id";
        $qustSql3="SELECT
	bqi.yn_close,
	bqi.invst_subj,
	bqi.invst_start,
	bqi.invst_end,
	(
		SELECT
			bsp_svalue
		FROM
			erp.bs_pubdata bp
		WHERE
			bsp_stype = 'wjlb'
		AND bqi.invst_type = bp.bsp_id
	) bsp_sname,
	(
		SELECT
			t.organization_name
		FROM
			erp.hr_organization t
		WHERE
			bqi.invst_dpt = t.organization_id
	) organization_name
FROM
	erp.bs_qst_invst bqi
WHERE bqi.invst_id = :invst_id";
                    $queryParams=[
                        ':invst_id'=>$invstid
                    ];
        $invstcontent=Yii::$app->get('db')->createCommand($qustSql1, $queryParams)->queryAll();
        $invstcontents=Yii::$app->get('db')->createCommand($qustSql3, $queryParams)->queryAll();

        $idpt=Yii::$app->get('db')->createCommand($sqldpt, $queryParams)->queryAll();
        $qustSql2="SELECT
	                      ic.cnt_tpc,
	                      ic.cnt_id,
	                      io.opt_code,
	                      io.opt_name
                   FROM
	                      erp.invst_content ic,
	                      erp.invst_options io
                   WHERE
	                      ic.cnt_id = io.cnt_id
                          AND ic.invst_id = :invst_id ORDER BY io.cnt_id,io.opt_code";
        $invstoptions=Yii::$app->get('db')->createCommand($qustSql2, $queryParams)->queryAll();

        if(empty($answ_id))
        {
            $answ=null;
            $opt=null;
        }
        else
        {
            $answSql="SELECT
	                        ia.answ_id,
	                        ia.cnt_id,
	                        ia.answs
                      FROM
	                        erp.invst_answ ia,
	                        erp.bs_qst_answ bqa
                      WHERE
	                        ia.answ_id = bqa.answ_id
                      AND bqa.answ_id = :answ_id
                      AND bqa.invst_id=:invst_id
                      ORDER BY
	                        ia.cnt_id";

            $optSql="SELECT
       	                io.answ_id,
                     	io.cnt_id,
                     	io.opt_code,
                     	bqa.staff_code,
	                    bqa.staff_name,
	                    bqa.dpt_name
                     FROM
                     	erp.invst_opt io,
                     	erp.bs_qst_answ bqa
                     WHERE
                     	io.answ_id = bqa.answ_id
                     AND bqa.answ_id = :answ_id
                     AND bqa.invst_id=:invst_id
                     ORDER BY
                     	io.cnt_id";
            $queryParams1=[
                ':answ_id'=>$answ_id,
                ':invst_id'=>$invstid
            ];
            $answ=Yii::$app->get('db')->createCommand($answSql, $queryParams1)->queryAll();
            $opt=Yii::$app->get('db')->createCommand($optSql, $queryParams1)->queryAll();
        }
        return [
            'invstcontent'=>$invstcontent,
            'invstoptions'=>$invstoptions,
            'answ'=>$answ,
            'opt'=>$opt,
            'dpt'=>$idpt,
            'invstcontents'=>$invstcontents
        ];
    }

    //新增问卷
    public function actionAdd()
    {
        date_default_timezone_set("Asia/Shanghai");// 设置时区（亚洲）date("Y-m-d H:i:s")
        $model=new BsQstInvst();
        $hr_staff = new HrStaff();
        $transaction = \Yii::$app->db->beginTransaction();
        $post = \Yii::$app->request->post();
        $number=0;
        try{
            $model->load($post);//
            $hr_staff->load($post);
            $tran=new Trans();
            $username = $hr_staff->getStaffById($hr_staff['staff_code']);//获取用户名
            $array=array('A','B','C','D','E','F','G','H','I','J','K','L','M');//定义选项字母
            $arrays=array('否','是');
            $model['crter']=$username['staff_id'];
            $model['crt_date']=date("Y-m-d H:i:s");
            $model['crt_ip']=$_SERVER["REMOTE_ADDR"];
            $model['invst_state']='0';
            $model['yn_de']=0;
            $model['yn_close']=0;
            $model['is_send']=0;
            $model['invst_subj']=$tran->t2c($model['invst_subj']);
            $model['remarks']=$tran->t2c($model['remarks']);
            if(!$model->save())
            {
                throw new \Exception("新增失败");
            }
            if(!empty($post['BsQstInvst']['dpt_id']))
            {
                for ($a=0;$a< count($post['BsQstInvst']['dpt_id']);$a++)
                {
                    $invstdpt=new InvstDpt();
                    $invstdpt->load($post);
                    $invstdpt['invst_id']=$model->invst_id;
                    $invstdpt['dpt_id']=$post['BsQstInvst']['dpt_id'][$a];
                    if(!$invstdpt->save()){
                        throw new \Exception(json_encode($invstdpt->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }
            }
            if(!empty($post['product']))
            {
                foreach ($post['product'] as $val)
                {
                    if(!empty($val['InvstContent']['cnt_tpc'])) {
                        $number++;
                        $instcontent = new InvstContent();
                        $instcontent->load($post);
                        $instcontent['invst_id'] = $model->invst_id;
                        $instcontent['cnt_tpc'] = $tran->t2c($val['InvstContent']['cnt_tpc']);
                        $instcontent['cnt_type'] = (int)$val['InvstContent']['cnt_type'];
                        if (!$instcontent->save()) {
                            throw new \Exception(json_encode($instcontent->getErrors(), JSON_UNESCAPED_UNICODE));
                        }
                        if ((int)$val['InvstContent']['cnt_type'] == 1 || (int)$val['InvstContent']['cnt_type'] == 2) {
                            if (!empty($val['InvstOptions'])) {
                                $i = 0;
                                foreach ($val['InvstOptions'] as $vals) {
                                    if($vals['opt_name']!=null||$vals['opt_name']!="") {
                                        $invstoptions = new InvstOptions();
                                        $invstoptions->load($post);
                                        $invstoptions['cnt_id'] = $instcontent->cnt_id;
                                        $invstoptions['opt_code'] = $array[$i];
                                        $invstoptions['opt_name'] = $tran->t2c($vals['opt_name']);
                                        if (!$invstoptions->save()) {
                                            throw new \Exception(json_encode($invstoptions->getErrors(), JSON_UNESCAPED_UNICODE));
                                        }
                                        $i++;
                                    }
                                }
                            }
                        } else if ((int)$val['InvstContent']['cnt_type'] == 4) {
                            for ($j = 0; $j < 2; $j++) {
                                $invstoptions = new InvstOptions();
                                $invstoptions->load($post);
                                $invstoptions['cnt_id'] = $instcontent->cnt_id;
                                $invstoptions['opt_code'] = $j;
                                $invstoptions['opt_name'] = $tran->c2t($arrays[$j]);
                                if (!$invstoptions->save()) {
                                    throw new \Exception(json_encode($invstoptions->getErrors(), JSON_UNESCAPED_UNICODE));
                                }
                            }
                        }
                    }
                }
            }
            $models=BsQstInvst::findOne($model['invst_id']);
            $models->load($post);
            $models['invst_subj']=$tran->t2c($models['invst_subj']);
            $models['remarks']=$tran->t2c($models['remarks']);
            $models['invst_nums']=$number;
            if(!$models->save())
            {
                throw new \Exception("新增失败");
            }
            $transaction->commit();
            return $this->success('新增成功！');
        }
        catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }
    //获取问卷的题目和选项
    public function actionQsnCountResult($id)
    {
        $sql11="UPDATE invst_options t1
                INNER JOIN ( SELECT a.cnt_id,b.opt_code,a.alls,b.nums,round(b.nums / a.alls, 4) AS pers
                FROM( SELECT a.cnt_id,count(a.opt_code) AS alls
                FROM invst_opt a
                LEFT JOIN invst_content b ON a.cnt_id = b.cnt_id
                WHERE b.invst_id = ".$id."
                GROUP BY a.cnt_id) a
                LEFT JOIN (SELECT b.cnt_id,b.opt_code,count(b.opt_code) AS nums
                FROM invst_opt b
                LEFT JOIN invst_content c ON c.cnt_id = b.cnt_id
                WHERE c.invst_id = ".$id."
                GROUP BY b.cnt_id,b.opt_code) b ON a.cnt_id = b.cnt_id
                ) t2 ON t1.cnt_id = t2.cnt_id
                AND t1.opt_code = t2.opt_code
                SET t1.opt_nums = t2.nums,
                t1.opt_rate = t2.pers";
        $str = Yii::$app->db->createCommand($sql11)->execute();

        $sql="SELECT *
              FROM invst_content a
              LEFT JOIN invst_options b ON a.cnt_id= b.cnt_id WHERE a.invst_id=".$id." ";
        $provider=new SqlDataProvider([
            'db'=>'db',
            'sql'=>$sql,
        ]);
        $query=InvstContent::find()->where(['invst_id'=>$id]);
        $query1=BsQstInvst::getBsQstInvstInfoOne($id);
        $dataProvider1 = new yii\data\ActiveDataProvider([
            'query'=>$query1,
        ]);

        $dataProvider = new yii\data\ActiveDataProvider([
            'query'=>$query,
        ]);
        return [
            'bsqsn'=>$dataProvider1,
            'InvstCon'=>$dataProvider->getModels(),
            'rows'=>$provider->getModels()
        ];
    }

    //获取答案详情
    public function actionLoadAnsw($invstid,$cntid,$c,$d)
    {
        $sql='SELECT
                  t.*, a.cnt_tpc,
                  a.invst_id,
                  a.cnt_id,
                  b.staff_code,
                  b.staff_name
              FROM
	          invst_answ t
              LEFT JOIN invst_content a ON t.cnt_id = a.cnt_id
              LEFT JOIN bs_qst_answ b ON b.answ_id = t.answ_id
              WHERE
                  a.invst_id = '."$invstid".'
              AND t.cnt_id = '."$cntid";
        $provider=new SqlDataProvider([
            'db'=>'db',
            'sql'=>$sql,
        ]);
        return [
            'rows'=>$provider->getModels()
        ];
    }
    public function actionExport()
    {
//        $searchModel = new AnswSearch();
//        $queryParams=Yii::$app->request->queryParams;
//        $dataProvider = $searchModel->export($queryParams);
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list['total'] = $dataProvider->totalCount;
//        $list['head']=[
//            '工号',
//            '姓名',
//            '调研日期',
//        ];
//        $head=Yii::$app->db->createCommand("select b.cnt_tpc from erp.bs_qst_invst a
//                                            LEFT JOIN erp.invst_content b ON a.invst_id=b.invst_id
//                                            where a.invst_id=".$queryParams['id']."
//                                            ")->queryAll();
//        if(!empty($head)){
//            $head=array_column($head,'cnt_tpc');
//            $list['head']=array_merge($list['head'],$head);
//        }
//        return $list;
        $params=Yii::$app->request->queryParams;
        $queryParams=[':id'=>$params['id']];
        $sql="SELECT b.answ_id,
	                 b.answ_datetime,
	                 b.staff_name,
	                 b.staff_code,
	                 b.dpt_name,
	                  CONCAT(co.cnt_tpc,'(',(case co.cnt_type when 1 then '单选' when 2 then '多选' when 3 then '文本输入' when 4 then '判断题' else '未知' end),')') cnt_tpc,
	                 IFNULL(GROUP_CONCAT(CASE d.opt_code WHEN '1' THEN '是' when '0' then '否' ELSE d.opt_code END ),e.answs) answer
              FROM erp.bs_qst_invst a
              LEFT JOIN erp.bs_qst_answ b ON b.invst_id = a.invst_id
              LEFT JOIN erp.invst_content co ON co.invst_id = a.invst_id
              LEFT JOIN erp.invst_opt d ON d.answ_id = b.answ_id AND d.cnt_id = co.cnt_id AND co.cnt_type <> 3
              LEFT JOIN erp.invst_answ e ON e.answ_id = b.answ_id AND e.cnt_id = co.cnt_id AND co.cnt_type = 3
              WHERE a.invst_id = :id
              ";
        if(!empty($params['staff_code'])){
            $trans=new Trans();
            $params['staff_code']=str_replace(['%','_'],['\%','\_'],$params['staff_code']);
            $queryParams[':staff_code1']='%'.$params['staff_code'].'%';
            $queryParams[':staff_code2']='%'.$trans->c2t($params['staff_code']).'%';
            $queryParams[':staff_code3']='%'.$trans->t2c($params['staff_code']).'%';
            $sql.=" and (b.staff_code like :staff_code1 or b.staff_code like :staff_code2 or b.staff_code like :staff_code3)";
        }
        if(!empty($params['staff_name'])){
            $trans=new Trans();
            $params['staff_name']=str_replace(['%','_'],['\%','\_'],$params['staff_name']);
            $queryParams[':staff_name']='%'.$params['staff_name'].'%';
            $queryParams[':staff_name']='%'.$trans->c2t($params['staff_name']).'%';
            $queryParams[':staff_name']='%'.$trans->t2c($params['staff_name']).'%';
            $sql.=" and (b.staff_name like :staff_name or b.staff_name like :staff_name or b.staff_name like :staff_name)";
        }
        if(!empty($params['dpt_name'])){
            $trans=new Trans();
            $params['dpt_name']=str_replace(['%','_'],['\%','\_'],$params['dpt_name']);
            $queryParams[':dpt_name']='%'.$params['dpt_name'].'%';
            $queryParams[':dpt_name']='%'.$trans->c2t($params['dpt_name']).'%';
            $queryParams[':dpt_name']='%'.$trans->t2c($params['dpt_name']).'%';
            $sql.=" and (b.dpt_name like :dpt_name or b.dpt_name like :dpt_name or b.dpt_name like :dpt_name)";
        }
        $sql.=" GROUP BY b.answ_id ASC, d.cnt_id ASC
              ORDER BY b.answ_id ASC, co.cnt_id ASC";
        $result=Yii::$app->db->createCommand($sql,$queryParams)->queryAll();
        $params['subj']='<<'.$params['subj'].'>>'.'调研人员结果统计.xls';
        $data['title']=[$params['subj']];
        $data['th']=['序号','调研时间','姓名','工号'];
        $data['tr']=[];
        if(!empty($result)) {
            $index = 0;
            foreach ($result as $key => $val) {
                if (isset($data['tr'][$val['answ_id']])) {
                    $data['tr'][$val['answ_id']][] = $val['answer'];
                } else {
                    $data['tr'][$val['answ_id']] = $val;
                    $index++;
                }
                if (count($data['tr']) == 1) {
                        $data['th'][] =$key+1 . '.' . $val['cnt_tpc'];

                }
                $data['tr'][$val['answ_id']]['answ_id'] = $index;
                unset($data['tr'][$val['answ_id']]['dpt_name']);
                unset($data['tr'][$val['answ_id']]['cnt_tpc']);
            }
        }
        return $data;
    }

    //及时统计问卷结果
    public function updateQsn($id)
    {
        $sql11="UPDATE invst_options t1
                INNER JOIN ( SELECT a.cnt_id,b.opt_code,a.alls,b.nums,round(b.nums / a.alls, 4) AS pers
                FROM( SELECT a.cnt_id,count(a.opt_code) AS alls
                FROM invst_opt a
                LEFT JOIN invst_content b ON a.cnt_id = b.cnt_id
                WHERE b.invst_id = ".$id."
                GROUP BY a.cnt_id) a
                LEFT JOIN (SELECT b.cnt_id,b.opt_code,count(b.opt_code) AS nums
                FROM invst_opt b
                LEFT JOIN invst_content c ON c.cnt_id = b.cnt_id
                WHERE c.invst_id = ".$id."
                GROUP BY b.cnt_id,b.opt_code) b ON a.cnt_id = b.cnt_id
                ) t2 ON t1.cnt_id = t2.cnt_id
                AND t1.opt_code = t2.opt_code
                SET t1.opt_nums = t2.nums,
                t1.opt_rate = t2.pers";
        $str = Yii::$app->db->createCommand($sql11)->execute();
        return $str;
    }


    public function actionGetQsnCnt()
    {
        $res2=[];
        $res=[];
        $sql="SELECT t.cnt_id FROM invst_content t WHERE t.invst_id=87";
        $res=Yii::$app->db->createCommand($sql)->queryAll();
        $res=array_column($res,'cnt_id');
        if(!empty($res))
        {
            $sql2="SELECT
                    t.answ_id,
                    t.staff_code,
                    t.staff_name,
                    t.answ_datetime,
                    op.opt_code opt_code1,
                    GROUP_CONCAT(DISTINCT(op1.opt_code)) opt_code2,
                    an.answs answs3,
                    op2.opt_code opt_code4
                    FROM
                        erp.bs_qst_answ t
                    LEFT JOIN erp.invst_answ an
                    on an.answ_id=t.answ_id
                    LEFT JOIN erp.invst_opt op
                    on op.answ_id=t.answ_id
                    LEFT JOIN erp.invst_opt op1
                    on op1.answ_id=t.answ_id
                    LEFT JOIN erp.invst_opt op2
                    on op2.answ_id=t.answ_id
                    LEFT JOIN erp.invst_content co
                    ON co.invst_id=87
                    LEFT JOIN erp.invst_content co1
                    ON co1.cnt_id=op.cnt_id
                    LEFT JOIN erp.invst_content co2
                    ON co2.cnt_id=an.cnt_id
                    LEFT JOIN erp.invst_content co3
                    ON co3.cnt_id=op2.cnt_id
                    WHERE
                        t.invst_id =87
                    AND op.cnt_id=".$res[0]."
                    AND op1.cnt_id=".$res[1]."
                    AND an.cnt_id=".$res[2]."
                    and op2.cnt_id=".$res[3]."
                    AND co1.invst_id=87
                    GROUP BY t.answ_id ";
            $res2=Yii::$app->db->createCommand($sql2)->queryAll();
        }
        return $res2;
    }

}