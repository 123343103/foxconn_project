<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2016/12/28
 * Time: 上午 09:34
 */
namespace app\modules\crm\controllers;
use app\controllers\BaseActiveController;
use app\modules\crm\models\CrmCustomerInfo;
use app\modules\crm\models\CrmSaleQuotedprice;
use app\modules\crm\models\CrmSaleQuotedpriceChild;
use app\modules\crm\models\SaleTestcount;
use app\modules\crm\models\SaleTestcountChild;
use app\modules\crm\models\show\CrmCustomerInfoShow;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\search\BsProductSearch;
use app\modules\ptdt\models\search\FpPartNoSearch;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class CrmQuotePriceController extends BaseActiveController{
    public $modelClass = 'app\modules\crm\models\CrmSaleQuotedprice';
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        return CrmSaleQuotedprice::search($params);
    }
    public function actionCreate(){
        $trans=\Yii::$app->db->beginTransaction();
        try{
            $model=new CrmSaleQuotedprice();
            $childModel=new CrmSaleQuotedpriceChild();
            $post=\Yii::$app->request->post();
            $model->load($post);
            $childModel->load($post);
            if(!($model->validate() && $model->save())){
                throw new \Exception(json_encode($model->getErrors()));
            }
            $saph_id=$model->primaryKey;
            if(count($childModel->part_no)>0){
                for($x=0;$x<count($childModel->part_no);$x++){
                    $_childModel=new CrmSaleQuotedpriceChild();
                    $_childModel->part_no=$childModel->part_no[$x];
                    $_childModel->brand=$childModel->brand[$x];
                    $_childModel->num=$childModel->num[$x];
                    $_childModel->order_num=$childModel->order_num[$x];
                    $_childModel->ws_local_unit_price=$childModel->ws_local_unit_price[$x];
                    $_childModel->local_unit_price=$childModel->local_unit_price[$x];
                    $_childModel->ws_local_total_price=$childModel->ws_local_total_price[$x];
                    $_childModel->local_total_price=$childModel->local_total_price[$x];
                    $_childModel->remark=$childModel->remark[$x];
                    $_childModel->saph_id=$saph_id;
                    if(!($_childModel->validate() && $_childModel->save())){
                        throw new \Exception(json_encode($_childModel->getErrors()));
                    }
                }
            }
            $testModel=new SaleTestcount();
            $testChildModel=new SaleTestcountChild();
            $testModel->load($post);
            $testModel->saph_id=$model->primaryKey;
            $testModel->cust_id=$model->cust_id;
            if(!($testModel->validate() && $testModel->save())){
                throw new \Exception(json_encode($testModel->getErrors()));
            }
            $testChildModel->load($post);
            if(count($testChildModel->pdt_id)>0){
                for($x=0;$x<count($testChildModel->pdt_id);$x++){
                    $_testChildModel=clone $testChildModel;
                    $_testChildModel->saph_id=$model->primaryKey;
                    $_testChildModel->sath_id=$testModel->primaryKey;
                    $_testChildModel->pdt_id=$testChildModel->pdt_id[$x];
                    $_testChildModel->num=$testChildModel->num[$x];
                    $_testChildModel->ws_local_lower_price=$testChildModel->ws_local_lower_price[$x];
                    $_testChildModel->ws_local_upper_price=$testChildModel->ws_local_upper_price[$x];
                    $_testChildModel->local_logistics_cost=$testChildModel->local_logistics_cost[$x];
                    $_testChildModel->ws_local_quoted_price=$testChildModel->ws_local_quoted_price[$x];
                    $_testChildModel->local_quoted_price=$testChildModel->local_quoted_price[$x];
                    $_testChildModel->profit_margin=$testChildModel->profit_margin[$x];
                    if(!($_testChildModel->validate() && $_testChildModel->save())){
                        throw new \Exception(json_encode($_testChildModel->getErrors()));
                    }
                }
            }
            $trans->commit();
            return $this->success();
        }catch(\Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function actionSelectCustomer(){
        $params=\Yii::$app->request->queryParams;
        $query=CrmCustomerInfo::find();
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>8
            ]
        ]);
        $res["rows"]=$dataProvider->getModels();
        $res["total"]=$dataProvider->totalCount;
        return $res;
    }

    public function actionSelectProduct(){
        $model=new BsProductSearch();
        $dataProvider=$model->search(\Yii::$app->request->queryParams);
        return [
            "rows"=>$dataProvider->getModels(),
            "total"=>$dataProvider->totalCount
        ];
    }

    public function actionPriceInfo($id){
        $model=PartnoPriceShow::find()->where(["part_no"=>$id]);
        return $model->all();
    }
}