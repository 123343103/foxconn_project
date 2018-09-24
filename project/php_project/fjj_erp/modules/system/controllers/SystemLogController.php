<?php

namespace app\modules\system\controllers;

use app\controllers\BaseController;

use app\modules\system\models\search\LogSearch;
use app\modules\system\models\SystemLog;
use yii;
/**
 * log控制器
 */
class SystemLogController extends BaseController
{
    private $_url="system/system-log/";//对应api控制器

    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $queryParam=Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($queryParam);
        $model = $dataProvider->getModels();
        $list['rows']=$model;
        $list['total'] = $dataProvider->totalCount;
        if (Yii::$app->request->isAjax) {
            return yii\helpers\Json::encode($list);
        }
        return $this->render('index',[
            'search'=>$queryParam['LogSearch'
            ]]);

    }
}
