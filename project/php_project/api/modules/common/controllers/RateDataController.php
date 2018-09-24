<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/21
 * Time: 下午 01:57
 */

namespace app\modules\common\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\search\BsTaxSearch;
use Yii;
use yii\data\SqlDataProvider;

class RateDataController extends BaseActiveController
{
    public $modelClass='app\modules\common\models\BsTax';
    public function actionRateSelect()
    {
        $params=Yii::$app->request->queryParams;
        if(!empty($params['tax_no']))
        {
            $sql="select * from erp.bs_tax t where t.yn=1 AND t.tax_name='{$params['tax_no']}' or t.tax_no='{$params['tax_no']}'";
        }
        else{
            $sql="select * from erp.bs_tax t where t.yn=1";
        }
        $totalCount = Yii::$app->db->createCommand("select count(*) from ( {$sql} ) a")->queryScalar();
        $provider = new SqlDataProvider([
            'db' => 'db',
            'sql' => $sql,
            'totalCount' => $totalCount,
            'pagination' => [
                'pageSize' => empty($params['rows']) ? false : $params['rows']
            ]
        ]);
        $list['rows'] = $provider->getModels();
        $list['total'] = $provider->totalCount;
        return $list;

    }
}