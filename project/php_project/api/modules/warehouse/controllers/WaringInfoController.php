<?php

/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/6/12
 * Time: 上午 09:40
 */


namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\warehouse\models\BsInvWarn;
use app\modules\warehouse\models\BsInvWarnH;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\LInvWarn;
use app\modules\warehouse\models\LInvWarnH;
use app\modules\warehouse\models\search\BsInvWarnSearch;
use app\modules\warehouse\models\show\WhAdmShow;
use app\modules\warehouse\models\WhAdm;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\web\NotFoundHttpException;

/*
 * 仓库设置控制器
 */

class WaringInfoController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsWh';

    /**
     * Lists all BsWh models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new BsInvWarnSearch();
        $dataProvider = $searchModel->search($params);
//        return $dataProvider;
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionAdd()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new BsInvWarnSearch();
        $dataProvider = $searchModel->searchProduct($params);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionView($biw_h_pkid)
    {
        $searchModel = new BsInvWarnSearch();
        $dataProvider = $searchModel->searchWaringInfo($biw_h_pkid);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionEdit($part_no, $wh_code)
    {
        if (Yii::$app->request->isPost) {
            $db = Yii::$app->get('wms');//指向数据库2
            $post = Yii::$app->request->post();
            $transaction = $db->beginTransaction();
            try {
                $BsInvWarn = BsInvWarn::getBsInvWarnOne($part_no, $wh_code);//BsWh::findOne($id);
                $BsInvWarn->up_nums = $post["up_nums"];
                $BsInvWarn->down_nums = $post["down_nums"];
                $BsInvWarn->save_num = $post["save_num"];
                $BsInvWarn->remarks = $post["remarks"];
                $BsInvWarn->OPPER = $post['OPPER'];//操作人
                $BsInvWarn->OPP_DATE = $post['OPP_DATE'];//操作时间
                $BsInvWarn->OPP_IP = $post['OPP_IP'];//ip地址
//                $BsInvWarn->load($post);
                if (!$BsInvWarn->save()) {
                    throw new \Exception(json_encode($BsInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
                }

                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    //删除
    public function actionDelete($id)
    {
        $idArr = explode(",", $id);
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            for ($i=0;$i<count($idArr);$i++)
            {
                $BsInvWarnH=BsInvWarnH::findOne(['biw_h_pkid'=>$idArr[$i]]);
                $BsInvWarnH->YN=0;
                if(!$BsInvWarnH->save())
                {
                    throw new Exception(json_encode($BsInvWarnH->getFirstError(),JSON_UNESCAPED_UNICODE));
                }
                BsInvWarn::deleteAll(['biw_h_pkid'=>$idArr[$i]]);
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
        return $this->success();

    }

    public function actionDownList()
    {
//        $downList['developCenters']  = $this->developCenters();    //仓库列表

        $downList['productTypes'] = $this->productTypes();      //一阶分类
        $downList['whname'] = BsWh::find()->select(['wh_name', 'wh_id'])->distinct()->all();
        $downList["so_type"] = [
            '0' => '全部',
            '1' => '未提交',
            '10' => '审核中',
            '20' => '审核完成',
            '30' => '驳回'
        ];
        return $downList;
    }

    //获取预警详情信息
    public function actionWaring($biw_h_pkid = '')
    {
        $searchModel = new BsInvWarnSearch();
        $dataProvider = $searchModel->searchWaring($biw_h_pkid);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $dataProvider;

    }

    private function productTypes()
    {
        return BsCategory::getLevelOne();
    }

    /**
     * @return array
     */
    public function actionCreateWarehouse()
    {
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->wms->beginTransaction();
            try {
                $BsWhInfo = new BsWh();
                $WhAdmInfo = new WhAdm();
                if (!($BsWhInfo->load($data) && $BsWhInfo->save())) {
                    $errors = $BsWhInfo->getErrors();
                    $str = '';
                    foreach ($errors as $key => $val) {
                        $str .= $key . implode(',', $val);
                    }
                    throw new \Exception($str);
                }
                $WhAdmInfo->adm_id = (string)($this->findAdmid() + 1);//主键
                $WhAdmInfo->wh_code = $BsWhInfo['wh_code'];//仓库代码
                $WhAdmInfo->OPPER = $BsWhInfo['OPPER'];//操作人
                $WhAdmInfo->OPP_DATE = $BsWhInfo['OPP_DATE'];//操作时间
                $WhAdmInfo->opp_ip = $BsWhInfo['opp_ip'];//ip地址
                if (!($WhAdmInfo->load($data) && $WhAdmInfo->save())) {
                    $errors = $WhAdmInfo->getErrors();
                    $str = '';
                    foreach ($errors as $key => $val) {
                        $str .= $key . implode(',', $val);
                    }
                    throw new \Exception($str);
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
        return $this->success();
    }


    public function actionUpdateWarehouse($id)
    {
        if (Yii::$app->request->isPost) {
            $db = Yii::$app->get('wms');//指向数据库2
            $post = Yii::$app->request->post();
            $transaction = $db->beginTransaction();
            try {
                $BsWhInfo = BsWh::findOne($id);
                $WhAdmInfo = WhAdm::findOne(['wh_code' => $BsWhInfo->wh_code]);
                $BsWhInfo->load($post);
                $WhAdmInfo->load($post);
                $WhAdmInfo->OPPER = $BsWhInfo['OPPER'];//操作人
                $WhAdmInfo->OPP_DATE = $BsWhInfo['OPP_DATE'];//操作时间
                $WhAdmInfo->opp_ip = $BsWhInfo['opp_ip'];//ip地址
                if (!$BsWhInfo->save()) {
                    throw new \Exception(json_encode($BsWhInfo->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                if (!$WhAdmInfo->save()) {
                    throw new \Exception(json_encode($WhAdmInfo->getErrors(), JSON_UNESCAPED_UNICODE));

                }
                $transaction->commit();
                return $this->success();
            } catch (\Exception $e) {
//                file_put_contents('log.txt', $e->getMessage());
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }

    /**
     * Finds the BsWh model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return BsWh the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BsWh::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /*
     * 获取wh_adm表中主键id
     */
    public function findAdmid()
    {
        $query = WhAdm::find()->select('MAX(adm_id) adm_id');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $modelWhAdm = $dataProvider->getModels();
        return $modelWhAdm[0]['adm_id'];
    }


    /*
     * 获取一条数据
     */
    public function actionModels($id)
    {
        $result = WhAdmShow::getBsWhInfoOne($id);
        return $result;
    }

    /**
     * 通过输入的仓库代码判断是否已经存在
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionGetWarehouseInfo($id)
    {
        return BsWh::getBsWhInfoOne($id);
    }

    //保存
    public function actionSave()
    {
        if (Yii::$app->request->isPost) {
            $db = Yii::$app->get('wms');//指向数据库2
            $post = Yii::$app->request->post();
            $transaction = $db->beginTransaction();
            try {
                $BsInvWarnH = BsInvWarnH::findOne(['biw_h_pkid' => $post['warmInfo'][0]['biw_h_pkid']]);
                $BsInvWarnH->OPP_DATE = $post['OPP_DATE'];
                $BsInvWarnH->OPPER = $post['OPPER'];
                $BsInvWarnH->OPP_IP = $post['OPP_IP'];
                if (!$BsInvWarnH->save()) {
                    throw new \Exception(json_encode($BsInvWarnH->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                BsInvWarn::deleteAll(['biw_h_pkid' => $post['warmInfo'][0]['biw_h_pkid']]);
                for ($i = 0; $i < count($post['warmInfo']); $i++) {
                    $BsInvWarn = new BsInvWarn();
                    $BsInvWarn->biw_h_pkid = $post['warmInfo'][$i]['biw_h_pkid'];
                    $BsInvWarn->part_no = $post['warmInfo'][$i]['part_no'];
                    $BsInvWarn->up_nums = $post['warmInfo'][$i]['up_nums'];
                    $BsInvWarn->down_nums = $post['warmInfo'][$i]['down_nums'];
                    $BsInvWarn->save_num = $post['warmInfo'][$i]['save_num'];
                    $BsInvWarn->remarks = $post['warmInfo'][$i]['remarks'];
                    if (!$BsInvWarn->save()) {
                        throw new \Exception(json_encode($BsInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
                return $this->success("",$BsInvWarnH->biw_h_pkid);
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }
    }


    public function actionWarinfo()
    {
        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();
            $sql = "SELECT
	a.inv_id,
	a.wh_id,
	b.biw_h_pkid,
	b.part_no,
	c.pdt_name,
	b.down_nums,
	b.save_num,
	d.invt_num,
	b.up_nums,
	e.wh_name,
	a.YN,
	b.remarks
FROM
	wms.bs_inv_warn_h a
LEFT JOIN wms.bs_inv_warn b ON a.biw_h_pkid = b.biw_h_pkid
LEFT JOIN erp.bs_product c ON b.part_no = c.pdt_no
LEFT JOIN wms.bs_wh e ON e.wh_id = a.wh_id
LEFT JOIN wms.bs_invt d ON (
	d.wh_id = a.wh_id && d.part_no = b.part_no
)
WHERE
	1 = 1
AND a.yn = 1 ";

            $queryParams = null;

            if (!empty($post["wh_id"])) {
                $sql = $sql . '  and a.wh_id =\'' . $post["wh_id"] . '\'';

            }


            $provider = new SqlDataProvider([
                'sql' => $sql
            ]);
            return $provider;
        }
    }

    public function actionCreatesave()
    {
//        $biwphid="";
        if (Yii::$app->request->getIsPost()) {
            $biwpkid = "";
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->wms->beginTransaction();
            $BsInvWarnH = new BsInvWarnH();
            try {
                list($s1, $s2) = explode(' ', microtime());
                $inv_id = (string)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
                $BsInvWarnH->wh_id = $data["wh_id"];
                $BsInvWarnH->inv_id = $inv_id;
                $BsInvWarnH->OPPER = $data["OPPER"];//操作人
                $BsInvWarnH->OPP_DATE = $data["OPP_DATE"];//操作时间
                $BsInvWarnH->OPP_IP = $data["OPP_IP"];//'//获取ip地址
                $BsInvWarnH->YN = 1;
                $BsInvWarnH->so_type = 1;
                if (!($BsInvWarnH->save())) {
                    throw new \Exception(json_encode($BsInvWarnH->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                for ($i = 0; $i < count($data["part_no"]); $i++) {
                    $BsInvWarn = new BsInvWarn();
                    $BsInvWarn->biw_h_pkid = $BsInvWarnH->attributes['biw_h_pkid'];
                    $BsInvWarn->part_no = $data["part_no"][$i];
                    $BsInvWarn->up_nums = $data["up_nums"][$i];
                    $BsInvWarn->down_nums = $data["down_nums"][$i];
                    $BsInvWarn->save_num = $data["save_nums"][$i];
                    $BsInvWarn->remarks = $data["remarks"][$i];
                    if (!$BsInvWarn->save()) {
                        throw new \Exception(json_encode($BsInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
        }

        return $this->success("",$BsInvWarnH->attributes['biw_h_pkid']);


    }

//    public function actionCreatesave()
//    {
////        $biwphid="";
//        if (Yii::$app->request->isPost) {
//            $biwpkid="";
//            $data = Yii::$app->request->post();
//            $transaction = Yii::$app->wms->beginTransaction();
//            try {
//                //判断该仓库有没有未提交的审核 如果有就update  如果没有就create
//                $wh_id = $data["wh_id"];
//                $model1 = BsInvWarnH::find()->where(['and', ['wh_id' => $wh_id], ['so_type' => null]])->one();
//
//                if ($model1) {
//                    //执行update
//                    //更新bs_inv_warn_h 表
//                    $model1->OPPER = $data["OPPER"];//操作人
//                    $model1->OPP_DATE = $data["OPP_DATE"];//操作时间
//                    $model1->OPP_IP = $data["OPP_IP"];//'//获取ip地址
//                    if (!($model1->save())) {
//                        throw new \Exception(json_encode($model1->getErrors(), JSON_UNESCAPED_UNICODE));
//                    }
//                    $pkid = $model1->biw_h_pkid;
//                    $biwpkid=$pkid;
////                    $biwphid=$pkid;
//                    //跟新bs_inv_warn表(先将上一次保存未提交的数据删除再添加)
//                    if (BsInvWarn::deleteAll(["biw_h_pkid" => $pkid])) {
//                        //新增
//                        for ($i = 0; $i < count($data["part_no"]); $i++) {
//                            $BsInvWarn = new BsInvWarn();
//                            $BsInvWarn->biw_h_pkid = $pkid;
//                            $BsInvWarn->part_no = $data["part_no"][$i];
//                            $BsInvWarn->up_nums = $data["up_nums"][$i];
//                            $BsInvWarn->down_nums =$data["down_nums"][$i];
//                            $BsInvWarn->save_num = $data["save_nums"][$i];
//                            $BsInvWarn->remarks = $data["remarks"][$i];
//                            if (!$BsInvWarn->save()) {
//                                throw new \Exception(json_encode($BsInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
//                            }
//                        }
//
//                    } else {
//                        throw new \Exception(json_encode($this->error()));
//                    }
//                    $transaction->commit();
//
//                } else {
//                    //执行create
//                    $biw_h_pkid = $this->nextPkID();
//                    $biwpkid=$biw_h_pkid[0]['next'];
//                    $BsInvWarnH = new BsInvWarnH();
//                    //保存bs_inv_warn_h表中的数据
//                    list($s1, $s2) = explode(' ', microtime());
//                    $inv_id = (string)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
//                    $BsInvWarnH->inv_id = $inv_id;
//                    $BsInvWarnH->wh_id = $data["wh_id"];
//                    $BsInvWarnH->OPPER = $data["OPPER"];//操作人
//                    $BsInvWarnH->OPP_DATE = $data["OPP_DATE"];//操作时间
//                    $BsInvWarnH->OPP_IP = $data["OPP_IP"];//'//获取ip地址
//                    $BsInvWarnH->YN = 0;//未审核都是无效的
//                    if (!($BsInvWarnH->save())) {
//                        throw new \Exception(json_encode($BsInvWarnH->getErrors(), JSON_UNESCAPED_UNICODE));
//                    }
//                    //保存bs_inv_warn表中的数据
//                    for ($i = 0; $i < count($data["part_no"]); $i++) {
//                        $BsInvWarn = new BsInvWarn();
//                        $BsInvWarn->biw_h_pkid = $biw_h_pkid[0]["next"];
//                        $BsInvWarn->part_no = $data["part_no"][$i];
//                        $BsInvWarn->up_nums = $data["up_nums"][$i];
//                        $BsInvWarn->down_nums = $data["down_nums"][$i];
//                        $BsInvWarn->save_num = $data["save_nums"][$i];
//                        $BsInvWarn->remarks = $data["remarks"][$i];
//                        if (!$BsInvWarn->save()) {
//                            throw new \Exception(json_encode($BsInvWarn->getErrors(), JSON_UNESCAPED_UNICODE));
//                        }
//                    }
//
//                    $transaction->commit();
//                }
//
//
//            } catch (\Exception $e) {
//                $transaction->rollBack();
//                return $this->error($e->getMessage());
//            }
//        }
//
//        return $this->success($biwpkid);
//
//
//    }

    public function nextPkID()
    {

        $sql = "SELECT
	Auto_increment next
FROM
	information_schema.`TABLES`
WHERE
	Table_Schema = 'wms'
AND table_name = 'bs_inv_warn_h'
LIMIT 1";


        $connection = Yii::$app->get('wms');
        $command = $connection->createCommand($sql);
//        $provider = new SqlDataProvider([
//            'sql' => $sql
//        ]);
        return $command->queryAll();
    }

    public function actionGettypebywhid($wh_id)
    {
        $model = BsInvWarnH::find()->where(['and', ['wh_id' => $wh_id], ['so_type' => '10']])->one();
        if ($model) {
            return true;
        } else {
            return false;
        }
//        return $wh_id;

    }


}