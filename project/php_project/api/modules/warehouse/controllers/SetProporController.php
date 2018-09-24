<?php
/**
 * Created by PhpStorm.
 * User: F3859386
 * Date: 2018/1/15
 * Time: 16:14
 */

namespace app\modules\warehouse\controllers;

use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsForm;
use app\modules\warehouse\models\BsRatio;
use Yii;
use yii\data\SqlDataProvider;
use yii\db\Exception;

class SetProporController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsRatio';

    public function actionIndex()
    {

        $params = Yii::$app->request->queryParams;
        $sql = "  SELECT
	a.ratio_no,
	b.bsp_svalue,
	CONCAT(round(a.upp_num * 100,2), '%') upp_num,

    CONCAT(round(a.low_num * 100,2), '%') low_num,
	
	(
		CASE a.yn
		WHEN 1 THEN
			'启用'
		WHEN 0 THEN
			'禁用'
		ELSE
			a.yn
		END
	) yn,
	a.remark,
	hr.staff_name,
	opp_date
FROM
	erp.bs_ratio a
LEFT JOIN erp.bs_pubdata b ON b.bsp_id = a.ratio_type
LEFT JOIN erp.hr_staff hr ON a.opper = hr.staff_id
WHERE
	1 = 1
        ";
        //查询
        if (!empty($params['no'])) {
            $sql .= " and a.ratio_no= '{$params['no']}' ";
        }
        if (!empty($params['type'])) {
            $sql .= " and a.ratio_type= '{$params['type']}' ";
        }
        if (isset($params['status']) && $params['status'] != "") {
            $sql .= " and a.yn= '{$params['status']}' ";
        }
        if(!isset($params['status'])){
            $sql .= " and a.yn=1 ";
        }
        $sql.="  order by a.opp_date desc";
        $totalCount = Yii::$app->get('db')->createCommand("select count(*) from ( {$sql} ) a")->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);
        return [
            'rows' => $provider->getModels(),
            'total' => $provider->totalCount,
        ];
    }
    //下拉框
    public function actionDownlist()
    {
        $downlist['type'] = Yii::$app->db->createCommand("select 
                                                                bsp_id,
                                                                bsp_svalue,
                                                                bsp_stype
                                                          from   erp.bs_pubdata
                                                          where  bsp_stype='blsz_djlx'
        ")->queryAll();
//        return $downlist['type'][0]['bsp_svalue'];
        return $downlist;
    }

    //新增下拉框
    public function actionDownlists()
    {
        $downlists['type'] = Yii::$app->db->createCommand("select 
                                                                a.bsp_id,
                                                                a.bsp_svalue,
                                                                a.bsp_stype
                                                          from   erp.bs_pubdata a
                                                          where  a.bsp_stype='blsz_djlx'
                                                          AND a.bsp_id NOT IN (SELECT a.ratio_type FROM erp.bs_ratio a)
        ")->queryAll();
//        return $downlist['type'][0]['bsp_svalue'];
        return $downlists;
    }
    //禁用
    public function actionCancel($id)
    {
        try {
            $_id = explode(',', $id);
//            return $_id;
            foreach ($_id as $vid) {
                $model = BsRatio::findOne($vid);
                $model->yn = 0;
                if ($model->save()) {
                    $msg = $this->success('操作成功');
                } else {
                    throw new \Exception(current($model->getFirstErrors()));
                }
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $msg;
    }

    //启用
    public function actionOpen($id)
    {
        try {
            $_id = explode(',', $id);
            foreach ($_id as $vid) {
                $model = BsRatio::findOne($vid);
                $model->yn = 1;
                if ($model->save()) {
                    $msg = $this->success('操作成功');
                } else {
                    throw new \Exception(current($model->getFirstErrors()));
                }
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $msg;
    }
    //新增
    public function actionCreate($id)
    {
        if ($data = Yii::$app->request->post()) {
            $transaction = BsRatio::getDb()->beginTransaction();
            try {
                $BsRatio = new BsRatio();
                $BsRatio->ratio_no=BsForm::getcode("bs_ratio",$BsRatio);
                $BsRatio->yn=1;
                $BsRatio->opper=$id;
                if ($BsRatio->load($data)) {
                    if (!$BsRatio->save()) {
                        throw new Exception(json_encode($BsRatio->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                } else {
                    throw new Exception('比例信息加载失败');
                }
                $transaction->commit();
                return $this->success('新增成功',[
                    'id'=>$BsRatio->ratio_no
                ]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }
    //修改
    public function actionUpdate($id,$_id)
    {
        if($data=Yii::$app->request->post())
        {
            $transaction=BsRatio::getDb()->beginTransaction();
            try
            {
                $BsRatio=BsRatio::findOne($id);
                $BsRatio->opper=$_id;
                if($BsRatio->load($data)){
                    if(!$BsRatio->save())
                    {
                        throw new Exception(json_encode($BsRatio->getErrors(),JSON_UNESCAPED_UNICODE));
                    }
                }else{
                    throw new Exception('比例信息更新失败');
                }
                $transaction->commit();
                return $this->success('修改成功',[
                    'id'=>$id,
                ]);
            }
            catch (Exception $e)
            {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }
    //修改页原始数据
    public function actionModels($id){
        $sql['vl']=Yii::$app->db->createCommand("
                                           select a.ratio_no,
                                           a.ratio_type,
                                           a.upp_num,
                                           a.low_num,
                                           a.yn,
                                           a.remark
                                           from erp.bs_ratio a
                                           WHERE a.ratio_no='{$id}'
                                           ")->queryAll();
        return $sql;
    }
}