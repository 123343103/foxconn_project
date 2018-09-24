<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/24
 * Time: 上午 09:36
 */
namespace app\modules\warehouse\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsCategory;
use app\modules\common\models\BsPubdata;
use app\modules\warehouse\models\BsInvt;
use app\modules\warehouse\models\BsSitInvt;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\LBsInvtList;
use app\modules\warehouse\models\search\BsInvtSearch;
use yii\data\Pagination;
use yii\data\SqlDataProvider;

class ProductStockQueryController extends BaseActiveController{
    public $modelClass='app\modules\warehouse\models\BsInvt';
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        $dataProvider=BsInvtSearch::StockQuery($params);
        return [
            "total"=>$dataProvider->totalCount,
            "rows"=>$dataProvider->getModels()
        ];
    }


    public function actionOptions(){
        return [
            "pdt_category"=>BsCategory::find()->select("catg_name")->where(["catg_level"=>1])->indexBy("catg_id")->column(),
            "wh_name"=>BsWh::find()->select("wh_name")->where(["wh_state"=>"Y"])->indexBy("wh_name")->column(),
            "wh_code"=>BsWh::find()->select("wh_code")->where(["wh_state"=>"Y"])->indexBy("wh_code")->column(),
            "wh_attr"=>["100879"=>"外租","100880"=>"自有"],
            "sssss"=>BsSitInvt::find()->select("batch_no")->indexBy("batch_no")->groupBy("batch_no")->column(),
            "wh_type"=>\Yii::$app->db->createCommand("SELECT DISTINCT
	bsp_id,
	bsp_svalue 
FROM
	erp.bs_pubdata
WHERE bsp_id IS NOT NULL
 AND bsp_stype='CKLB'
 AND bsp_status=10 ")->queryAll(),
//            "aaaa"=>BsSitInvt::find()->select(['pb.bsp_id','pb.bsp_svalue'])->leftJoin('wms.bs_wh wh','wh.wh_code = '.BsSitInvt::tableName().'.wh_code')->leftJoin('erp.bs_pubdata pb','pb.bsp_id=wh.wh_type')->column(),
        ];
    }
}
?>