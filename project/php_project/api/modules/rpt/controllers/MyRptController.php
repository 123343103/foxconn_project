<?php

namespace app\modules\rpt\controllers;

use app\controllers\BaseActiveController;
use app\modules\rpt\models\RptAssign;
use app\modules\rpt\models\RptTemplate;
use app\modules\rpt\models\search\AssignListSearch;
use app\modules\rpt\models\search\TemplateSearch;
use yii;
use yii\helpers\Json;

class MyRptController extends BaseActiveController
{
    public $modelClass = 'app\modules\rpt\models\RptTemplate';

    public function actionIndex()
    {
        $params = Yii::$app->getRequest()->post();
//        return $params;
        $assignList = new AssignListSearch();
        $assignListArr = $assignList->searchIdByUser($params)->getModels();
        $idArr = [];
        foreach ($assignListArr as $key=>$value ) {
            $idArr[] = $value['rpt_id'];
        }
        $prm['idArr'] = $idArr;
        if (isset($params['rows'])) {
            $prm['rows'] = $params['rows'];
        }
        if (!empty($params['TemplateSearch'])) {
            $prm['TemplateSearch'] = $params['TemplateSearch'];
        }
        $rptModel = new TemplateSearch();
        $dataProvider = $rptModel->searchAssigned($prm);
        $rptData = $dataProvider->getModels();
        $rptList['rows'] = $rptData;
        $rptList['total'] = $dataProvider->totalCount;
        return $rptList;
    }

    public function actionRptCount($uid){
        $res=RptAssign::getDb()->createCommand("select count(distinct rpt_id) from rpt_assign where (rpt_assign.rpta_type=10  and rpt_assign.roru in(
select item_name from auth_assignment where user_id=:uid) or rpt_assign.rpta_type=11 and rpt_assign.roru=:uid);",[":uid"=>$uid])->queryScalar();
        return (int)$res;
    }

}

