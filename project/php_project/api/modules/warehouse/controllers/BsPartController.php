<?php
/**
 * Created by PhpStorm.
 * User: F1677943
 * Date: 2017/8/7
 * Time: 上午 11:13
 */

namespace app\modules\warehouse\controllers;


use app\classes\Trans;
use app\controllers\BaseActiveController;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\search\BsPartSearch;
use Yii;
use yii\data\SqlDataProvider;

class BsPartController extends BaseActiveController
{
    public $modelClass = 'app\modules\warehouse\models\BsPart';

    public function actionIndex()
    {
//        $param = Yii::$app->request->queryParams;
//        $searchModel = new BsPartSearch();
//        $dataProvoder = $searchModel->search($param);
//        $model = $dataProvoder->getModels();
//        $list['rows'] = $model;
//        $list['total'] = $dataProvoder->totalCount;
//        return $list;
        $params = Yii::$app->request->queryParams;
        $sql = "SELECT s.part_id,
                     s.wh_code,
                     t.wh_name,
                     s.part_code,
                     s.part_name,
                     (case s.YN when '0' then '禁用' ELSE '启用' end) YN,
                     (hr.staff_name)OPPER,
                     s.OPP_DATE,
                     s.remarks 
              from wms.bs_part s 
              LEFT JOIN wms.bs_wh t ON s.wh_code=t.wh_code
               left JOIN  erp.hr_staff hr ON hr.staff_code=s.OPPER
              WHERE 1=1 ";
        $queryParams = [];
        if (!empty($params['wh_code'])) {
            $trans = new Trans();
            $params['wh_code'] = str_replace(['%', '_'], ['\%', '\_'], $params['wh_code']);
            $sql .= " and (t.wh_name like '%" . $params['wh_code'] . "%' ) or 
                        (s.wh_code like '%" . $params['wh_code'] . "%')";
        }
        if (!empty($params['part_code'])) {
            $trans = new Trans();
            $params['part_code'] = str_replace(['%', '_'], ['\%', '\_'], $params['part_code']);
            $sql .= " and ((s.part_code like '%" . $params['part_code'] . "%' ) or 
                        (s.part_name like '%" . $params['part_code'] . "%'))";
        }
        if (isset($params['type']) && $params['type'] != "") {
            $sql .= " and (s.YN =" . "'" . $params['type'] . "'" . ")";
        }
        if (!isset($params['type'])) {
            $sql .= " and (s.YN =1)";
        }
        $sql .= "ORDER BY s.part_id DESC";
        $totalCount = Yii::$app->db->createCommand("select count(*) from ( {$sql} ) a", $queryParams)->queryScalar();
        $provider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => $params['rows']
            ]
        ]);
        $model = $provider->getModels();
        $list['rows'] = $model;
        $list['total'] = $provider->totalCount;
        return $list;
    }

    //单笔启用禁用仓库
    public function actionOpenClose($part_id)
    {

        $BsPart = BsPart::findOne($part_id);
        try {
            if ($BsPart->YN == "0") {
                $BsPart->YN = 1;
                if (!$BsPart->save()) {
                    throw new \Exception(json_encode($BsPart->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            } else {
                $BsPart->YN = 0;
                if (!$BsPart->save()) {
                    throw new \Exception(json_encode($BsPart->getErrors(), JSON_UNESCAPED_UNICODE));
                }
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //批量启用仓库
    public function actionOpenss()
    {
        $param = Yii::$app->request->queryParams;
        try {
            $array = $param['part_id'];
            for ($i = 0; $i < count($array); $i++) {
                $model = BsPart::findOne($array[$i]);
                $model->YN = 1;
                if (!$model->save()) {
                    throw  new \Exception("启用失败");
                };
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    //批量禁用
    public function actionClosess()
    {
        $param = Yii::$app->request->queryParams;
        try {
            $array = $param['part_id'];
            for ($i = 0; $i < count($array); $i++) {
                $model = BsPart::findOne($array[$i]);
                $model->YN = 0;
                if (!$model->save()) {
                    throw  new \Exception("禁用失败");
                };
            }
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function actionView($part_id)
    {
        $sql = "SELECT t.*,w.wh_name,
            (CASE WHEN t.YN=1 THEN '启用' ELSE '禁用' END)YNS
            FROM wms.bs_part t 
            LEFT JOIN wms.bs_wh w ON w.wh_code=t.wh_code
            WHERE t.part_id=" . $part_id;
        $ret = Yii::$app->db->createCommand($sql)->queryAll();
//        $result = BsPart::findOne($part_id);
        return $ret;
    }

    public function actionAddEdit($part_id)
    {
        $post = Yii::$app->request->post();
        if ($part_id == "'add'") {
            //add
            try {
                $BsPart = new BsPart();
                $BsPart->load($post);
                if (!$BsPart->save()) {
                    throw new \Exception(json_encode($BsPart->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                return $this->success();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        } else {
            //edit
            try {
                $BsPart = BsPart::findOne($part_id);
                $BsPart->load($post);
                if (!$BsPart->save()) {
                    throw new \Exception(json_encode($BsPart->getErrors(), JSON_UNESCAPED_UNICODE));
                }
                return $this->success();
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        }
    }

    public function actionDelete($partid)
    {
        $idArr = explode(",", $partid);
        $transaction = Yii::$app->wms->beginTransaction();
        try {
            if (BsPart::deleteAll(["part_id" => $idArr])) {

            } else {
                $transaction->rollBack();
                return $this->error();
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

        $downList['whname'] = BsWh::find()->select(['wh_name', 'wh_code'])->where(['wh_state' => 'Y'])->distinct()->all();
        $downList["type"] = [
            '3' => '全部',
            '0' => '禁用',
            '1' => '启用'
        ];
        return $downList;
    }

    public function actionGetBsWhname($wh_code)
    {
        $ret = BsWh::getBsWhname($wh_code);
        return $ret;
    }
}