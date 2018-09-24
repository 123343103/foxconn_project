<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseActiveController;
use app\modules\ptdt\models\PdVisitResume;
use app\modules\rpt\models\RptAssign;
use app\modules\rpt\models\RptCategory;
use app\modules\rpt\models\RptParam;
use app\modules\rpt\models\RptTemplate;
use app\modules\rpt\models\search\AssignListSearch;
use app\modules\rpt\models\search\CustAreaSearch;
use app\modules\rpt\models\search\CustReqSearch;
use app\modules\rpt\models\search\PrdRequireSearch;
use app\modules\rpt\models\search\RptTreeSearch;
use app\modules\rpt\models\search\TemplateParamsSearch;
use app\modules\rpt\models\search\TemplateSearch;
use app\modules\rpt\Rpt;
use app\modules\system\models\search\UserSearch;
use yii;

class RptManageController extends BaseActiveController
{
    public $modelClass = 'app\modules\rpt\models\RptTemplate';

    public function actionIndex()
    {
        $model = new RptTreeSearch();
        $dataProvider = $model->search();
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    public function actionGetTree()
    {
        $model1 = RptCategory::find()->all();
        $model3 = RptTemplate::find()->all();
        $tree = [];
        $j = 0;
        foreach ($model1 as $ck=>$cv) {
            $tree[$ck] = current($cv);
            foreach ($model3 as $k=>$v) {
                if (($v['rptt_type']==10 || $v['rptt_type']==12) && $v['rptt_cat']==$cv['rptc_id']) {
                    $tree[$ck]['template'][$j] = current($v);
                    foreach ($model3 as $kk=>$vv) {
                        if ($vv['rptt_pid']==$v['rptt_id']) {
                            $tree[$ck]['template'][$j]['rpt'][] = current($vv);
                        }
                    }
                    $j += 1;
                }
            }
        }
        return $tree;
    }

   public function actionGetTemplate()
   {
       $model = new TemplateSearch();
       $dataProvider = $model->search();
       $model = $dataProvider->getModels();
       return $model;
   }

    public function actionGetCategory()
    {
        $model = RptCategory::find()->all();
        return $model;
    }

    public function actionGetRpt()
    {
        $id = Yii::$app->request->queryParams;
//        return $id;
        $model = new RptTemplate();
        $query = $model->find()->where(['rptt_id'=>$id['id']]);
        $dataProvider = new yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
//        $dataProvider = $dataProvider->getModels();
//        $dataProvider['rptt_tempsql'];
        return $dataProvider;
//        return $dataProvider[0];
    }

   public function actionGetTemplateParams()
   {
       $tpId = Yii::$app->request->queryParams;
       $model = new TemplateParamsSearch();
       $dataProvider = $model->search($tpId);
       $model = $dataProvider->getModels();
       return $model;
   }

   // 内置对象预览  与内置对象生成的子报表预览
   public function actionPreview()
   {
       $data = Yii::$app->request->post();
        $params=[];
       if ($data['RptTemplate']['rptt_type'] == 11 || $data['RptTemplate']['rptt_type'] == 10) { // 如果模板对象类型
           if(isset($data['RptParam'])) {
               foreach ($data['RptParam'] as $k => $v) {
                   $params[$v['RptParam']['rptp_key']] = $v['RptParam']['rptp_val'];
               }
           }
           $PModel = RptTemplate::findOne($data['RptTemplate']['rptt_id']);
           $pid = empty($PModel) ? '' : $PModel->rptt_pid;
           isset($data['RptTemplate']['rptt_pid']) && $pid=$data['RptTemplate']['rptt_pid'];
           if ($data['RptTemplate']['rptt_id'] == 1 || $pid==1) {
               $model = new PrdRequireSearch();
               $dataProvider = $model->requireSearch($params);
               return $dataProvider->models;
           } else if ($data['RptTemplate']['rptt_id'] == 2 || $pid==2) {
               return CustAreaSearch::search($params);
           } else if ($data['RptTemplate']['rptt_id'] == 3 || $pid==3) {
               return CustReqSearch::search($params);
           }
       } else { // 如果SQL对象类型
           if (!empty($data['RptTemplate']['rptt_tempsql'])) {
               $connection = Yii::$app->db;
               $command = @$connection->createCommand($data['RptTemplate']['rptt_tempsql']) or die('数据库错误');
               return $command->queryAll();
           } else {
               return '参数为空';
           }
       }
   }

   // 保存模板和參數(新增与修改)
    public function actionSaveAll()
    {
        $params = Yii::$app->getRequest()->post();
        if (!empty($params['RptTemplate']['rptt_id'])) {
            $TplModel = RptTemplate::findOne($params['RptTemplate']['rptt_id']);
            if (!$TplModel) {
                $TplModel = new RptTemplate();
            }
        } else {
            $TplModel = new RptTemplate();
        }
        $res['status'] = 1;
        $res['msg'] = '保存成功';
//        return $params['RptTemplate']['rptt_id'];
//        return $res;
        if (!empty(Yii::$app->getRequest()->post()) && $TplModel->load($params)) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $TplModel->load($params);
                if(!$TplModel->save()){
                    throw  new \Exception("保存模板失败！");
                } else {
                    $tplId = $TplModel->attributes['rptt_id'];
                    if ($params['RptTemplate']['rptt_type']==11) {
                        $PrmModel = new RptParam();
                        $PrmModel::deleteAll(['rptp_tp'=>$tplId]);// 保证切换参数时，删除原有参数重新添加新的参数
                        foreach ($params['RptParam'] as $k=>$v) {
                            $PrmModel = new RptParam();
                            $PrmModel->rptp_tp = $tplId;
                            if ($PrmModel->load($v)) {
                                if(!$PrmModel->save()) {
                                    throw  new \Exception("保存参数失败！");
                                }
                            }
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            return $res;
        } else {
            $res['status'] = 0;
            $res['msg'] = '数据错误！';
            return $res;
        }
    }

    // 编号是否存在
    public function actionIsCodeExist()
    {
        $code = Yii::$app->request->post();
//        return $code;
        $model = RptTemplate::findOne(['rptt_code'=>$code['code']]);
        if(!empty($model)) {
            return 1;
        } else {
            return 0;
        }
    }

    // 模糊查找用户
    public function actionUserSearch()
    {
        $searchModel = new UserSearch();
        $params = Yii::$app->request->queryParams;
//        return $params;
        $dataProvider = $searchModel->searchLike($params);
//        return $dataProvider;
        $rows = $dataProvider->getModels();
        $list['rows'] = $rows;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    // 保存分配用户/角色
    public function actionAssignSave()
    {
        $params = Yii::$app->getRequest()->post();
//        return json_encode($params);
        if (!empty($params['RptTemplate']['rptt_id'])) {
            $transaction = Yii::$app->db->beginTransaction();
            $tpId = $params['RptTemplate']['rptt_id'];
            try {
                if (!empty($params['RptAssign']['role'])) {
                    foreach ($params['RptAssign']['role'] as $role) {
                        $model = RptAssign::findOne(['rpt_id'=>$tpId, 'roru'=>$role, 'rpta_type'=>10]);
                        if (!$model) {
                            $model = new RptAssign();
                            $model->cdate = date('Y-m-d');
                            $model->create_by = $params['uid'];
                        } else {
                            $model->udate = date('Y-m-d');
                            $model->update_by = $params['uid'];
                        }
                        $model->rpt_id = intval($tpId);
                        $model->roru = $role;
                        $model->rpta_type = '10';
                        if(!$model->save()) {
                            throw  new \Exception("分配角色失败！");
                        }
                    }
                }
                if (!empty($params['RptAssign']['user'])) {
                    foreach ($params['RptAssign']['user'] as $user) {
                        $model = RptAssign::find()->where(['rpt_id'=>$tpId, 'roru'=>$user, 'rpta_type'=>11])->one();
                        if (!$model) {
                            $model = new RptAssign();
                            $model->cdate = date('Y-m-d');
                            $model->create_by = $params['uid'];
                        } else {
                            $model->udate = date('Y-m-d');
                            $model->update_by = $params['uid'];
                        }
                        $model->rpt_id = intval($tpId);
                        $model->roru = $user;
                        $model->rpta_type = '11';
                        $model->create_by = $params['uid'];
                        if(!$model->save()) {
                            throw  new \Exception("分配用户失败！");
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            $res['status'] = 1;
            $res['msg'] = '分配报表成功。';
            return $res;
        } else {
            $res['status'] = 0;
            $res['msg'] = '没有找到要分配的报表id！';
            return $res;
        }
    }

    // 获取分配用户/角色
    public function actionGetAssignList()
    {
        $params = Yii::$app->request->queryParams;
        $assignList = new AssignListSearch();
        $dataProvider = $assignList->searchById($params);
//        return $dataProvider;
        $rows = $dataProvider->getModels();
        $list['rows'] = $rows;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    // 删除分配用户/角色
    public function actionDeleteAssign()
    {
        $params = Yii::$app->request->post();
        try {
            foreach ($params as $v) {
                $delObj = RptAssign::findOne(['rpta_id'=>$v['rpta_id']]);
                $delObj->delete();
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        $res['status'] = 1;
        $res['msg'] = '删除成功！';
        return $res;
    }

    // 删除报表 同时删除报表参数和分配关系
    public function actionDeleteRpt()
    {
        $id = Yii::$app->request->post();
//        return $id['rptId'];
        if (!empty($id)) {
            $model = RptTemplate::find()->where(['and', ['rptt_id'=>$id['rptId']],['!=', 'rptt_type', 10]])->one();
//            $model = RptTemplate::find()->where(['rptt_id'=>$id['rptId']])->one();
//            $model->sql;
//            return $model;
//            return json_encode($model);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model != null) {
                    $model->delete();
                    RptParam::deleteAll(['rptp_tp'=>$id['rptId']]);
                    RptAssign::deleteAll(['rpt_id'=>$id['rptId']]);
                } else {
                    $res['status'] = 0;
                    $res['msg'] = '删除失败，未找到该报表！';
                    return $res;
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                return $this->error($e->getMessage());
            }
            $res['status'] = 1;
            $res['msg'] = '删除报表成功。';
            return $res;
        } else {
            $res['status'] = 0;
            $res['msg'] = '删除失败，报表ID为空！';
            return $res;
        }
    }
}