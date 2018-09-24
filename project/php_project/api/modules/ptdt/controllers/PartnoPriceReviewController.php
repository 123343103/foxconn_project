<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/12/6
 * Time: 下午 02:28
 */

namespace app\modules\ptdt\controllers;


use app\controllers\BaseActiveController;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\FpPas;
use app\modules\ptdt\models\PartnoPrice;
use app\modules\ptdt\models\FpPartNo;
use app\modules\ptdt\models\search\PdPartnoPriceReviewSearch;
use app\modules\ptdt\models\show\PartnoPriceShow;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

class PartnoPriceReviewController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\PartNoPriceShow';




    public function actionIndex(){
        $searchModel=new PdPartnoPriceReviewSearch();
        $dataProvider=$searchModel->search(\Yii::$app->request->queryParams);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list["total"] = $dataProvider->totalCount;
        return $list;
    }


    public function actionEdit($id){
        $trans=\Yii::$app->db->beginTransaction();
        try {
            $model = PartnoPrice::findOne($id);
            if (!$model) {
                throw new \Exception("定价记录不存在");
            }
            $post = \Yii::$app->request->post();
            $part_model =BsProduct::findOne(['pdt_no'=>$model->part_no]);
            if (!$part_model) {
                throw new \Exception("料号记录不存在");
            }

            $model->status = 3;
            $part_model->status = 3;

            $numArr = \Yii::$app->request->post("num_area");
            $priceArr = \Yii::$app->request->post("buy_price");
            FpPas::deleteAll(['part_no' => $model->part_no]);
            for ($x = 0; $x < count($numArr); $x++) {
                $pasModel = new FpPas();
                $pasModel->load($post);
                $pasModel->num_area = $numArr[$x];
                $pasModel->buy_price = $priceArr[$x];
                if(!$pasModel->save()){
                    throw new \Exception($pasModel->getErrors());
                }
            }
            if(!$model->save()){
                throw new \Exception($model->getErrors());
            }
            if (!$part_model->save()) {
                throw  new \Exception(Json::encode($part_model->getErrors()));
            }
            $trans->commit();
            return $this->success();
        }catch (Exception $e){
            $trans->rollBack();
            return $this->error($e->getMessage());
        }
    }



    public function actionDelete($id){
        $idArr=explode(",",$id);
        if(PartnoPrice::deleteAll(["id"=>$idArr])){
            return $this->success();
        }else{
            return $this->error();
        }
    }


    public function actionTest()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PartnoPriceShow::find()->where(['part_no'=>'EQ0105AB05001-0002J'])
        ]);
        return $dataProvider;
    }



    public function actionModels($id)
    {
        $model = PartnoPriceShow::findOne($id);
        return $model;

    }
}