<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/9/12
 * Time: 9:44
 */

namespace app\modules\ptdt\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\BsCategory;
use app\modules\ptdt\models\BsDeliv;
use app\modules\ptdt\models\BsDetails;
use app\modules\ptdt\models\BsImages;
use app\modules\ptdt\models\BsMachine;
use app\modules\ptdt\models\BsPack;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsPrice;
use app\modules\ptdt\models\BsProduct;
use app\modules\ptdt\models\BsShip;
use app\modules\ptdt\models\BsStock;
use app\modules\ptdt\models\BsWarr;
use app\modules\ptdt\models\FpPas;
use app\modules\ptdt\models\FpPrice;
use app\modules\ptdt\models\LDeliv;
use app\modules\ptdt\models\LDetails;
use app\modules\ptdt\models\LImages;
use app\modules\ptdt\models\LMachine;
use app\modules\ptdt\models\LPack;
use app\modules\ptdt\models\LPartno;
use app\modules\ptdt\models\LPdtPdt;
use app\modules\ptdt\models\LProduct;
use app\modules\ptdt\models\LPrtAttr;
use app\modules\ptdt\models\LPrtWh;
use app\modules\ptdt\models\LShip;
use app\modules\ptdt\models\LStock;
use app\modules\ptdt\models\LWarr;
use app\modules\ptdt\models\RAttrValue;
use app\modules\ptdt\models\RPdtPdt;
use app\modules\ptdt\models\RPrtAttr;
use app\modules\ptdt\models\RPrtWh;
use app\modules\ptdt\models\search\FpPriceSearch;
use app\modules\warehouse\models\BsWh;
use yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class GoodsReleasedController extends BaseActiveController
{
    public $modelClass = 'app\modules\ptdt\models\FpPrice';

    /**
     * @return mixed
     * 首页
     */
    public function actionIndex()
    {
        $search = new FpPriceSearch();
        return $search->search(Yii::$app->request->queryParams);
    }

    public function actionDelete($id)
    {
        $model = $this->getModel($id);
        $model->status = FpPrice::STATUS_DELETE;
        if ($result = $model->save()) {
            return $this->success('', '删除料号' . $model["part_no"] . '的定价信息');
        } else {
            return $this->error();
        }
    }

    //商品上架
    public function actionUpShelf($partno)
    {
        $trans = \Yii::$app->db->beginTransaction();
        if (\Yii::$app->request->isPost) {
            try {
                $params = \Yii::$app->request->post();

                //商品信息
                $model = BsProduct::findOne(['pdt_name' => $params["pdt_name"]]);
                if (!$model) {
                    $model = new BsProduct();
                    $model->pdt_status = 3;
                }
                $model->setAttributes($params);
                if($model->isNewRecord){
                    $model->crt_date=$model->opp_date=date("Y-m-d H:i:s");
                }
                if (!($model->validate() && $model->save())) {
                    throw new \Exception("商品信息保存失败");
                }

//                //商品日志信息
                $logPdtModel = new LProduct();
                $logPdtModel->setAttributes($model->attributes);
                $logPdtModel->pdt_pkid = $model->primaryKey;
                $logPdtModel->category_id = $model->catg_id;
                $logPdtModel->yn = 0;
                if (!($logPdtModel->validate() && $logPdtModel->save())) {
                    throw new \Exception("商品日志信息保存失败");
                }

                //商品图片
                if(isset($params["pdt_img"])){
                    $images = isset($params["pdt_img"]) ? $params["pdt_img"] : [];
                    BsImages::deleteAll(["pdt_pkid" => $model->primaryKey, "img_type" => 0]);
                    $columns=["pdt_pkid","fl_old","fl_new","orderby","img_type"];
                    $rows=[];
                    $logColumns=["l_pdt_pkid","fl_old","fl_new","orderby","img_type",'yn'];
                    $logRows=[];
                    $imgModel = new BsImages();
                    $logImgModel=new LImages();
                    foreach ($images as $k => $image) {
                        $row=[
                            "pdt_pkid"=>$model->primaryKey,
                            "fl_old"=>$image,
                            "fl_new"=>$image,
                            "orderby"=>$k,
                            "img_type"=>0
                        ];
                        $imgModel->setAttributes($row);
                        if (!$imgModel->validate()) {
                            throw new \Exception("图片信息验证失败");
                        }
                        $rows[]=$row;


                        $logRow=[
                            "l_pdt_pkid"=>$logPdtModel->primaryKey,
                            "fl_old"=>$image,
                            "fl_new"=>$image,
                            "orderby"=>$k,
                            "img_type"=>0,
                            "yn"=>0
                        ];
                        $logImgModel->setAttributes($logRow);
                        if (!$logImgModel->validate()) {
                            throw new \Exception("图片日志信息验证失败");
                        }
                        $logRows[]=$logRow;
                    }
                    if(!BsImages::getDb()->createCommand()->batchInsert(BsImages::tableName(),$columns,$rows)->execute()){
                        throw new \Exception("图片信息保存失败");
                    }
                    if(!LImages::getDb()->createCommand()->batchInsert(LImages::tableName(),$logColumns,$logRows)->execute()){
                        throw new \Exception("图片日志信息保存失败");
                    }
                }

                if (isset($params["details"])) {
                    BsDetails::deleteAll(["pdt_pkid" => $model->primaryKey, "prt_pkid" => null]);
                    $detailModel = new BsDetails();
                    $detailModel->pdt_pkid = $model->primaryKey;
                    $detailModel->details = $params["details"];
                    if (!($detailModel->validate() && $detailModel->save())) {
                        throw new \Exception("详细信息保存失败");
                    }

                    $logDetailModel = new LDetails();
                    $logDetailModel->setAttributes($detailModel->attributes);
                    $logDetailModel->l_pdt_pkid = $logPdtModel->pdt_pkid;
                    $logDetailModel->yn = 0;
                    if (!($logDetailModel->validate() && $logDetailModel->save())) {
                        throw new \Exception("料号详细信息日志修改失败");
                    }
                }


                if (isset($params["RPdtPdt"])) {
                    RPdtPdt::deleteAll(["pdt_pkid" => $model->primaryKey]);
                    foreach ($params["RPdtPdt"]["r_pdt_pkid"] as $r_pdt_pkid) {
                        $rPdtModel = new RPdtPdt();
                        $rPdtModel->pdt_pkid = $model->primaryKey;
                        $rPdtModel->r_pdt_pkid = $r_pdt_pkid;
                        if (!($rPdtModel->validate() && $rPdtModel->save())) {
                            throw new \Exception("关联商品信息保存失败");
                        }

                        $logRPdtModel = new LPdtPdt();
                        $logRPdtModel->setAttributes($rPdtModel->attributes);
                        $logRPdtModel->yn = 0;
                        if (!($logRPdtModel->validate() && $logRPdtModel->save())) {
                            throw new \Exception("关联商品信息日志保存失败");
                        }
                    }
                }
                $trans->commit();
                return $this->success(["pdt_id" => $model->primaryKey]);
            } catch (\Exception $e) {
                $trans->rollBack();
                return $this->error($e->getMessage());
            }
        } else {
            $priceModel = FpPrice::findOne(["part_no" => $partno]);
            if ($priceModel && $productModel = BsProduct::findOne(["pdt_name" => $priceModel->pdt_name])) {
                $model = BsProduct::getProductInfo($productModel->primaryKey);
                $prtModel = BsPartno::findBySql("select * from pdt.bs_partno where bs_partno.part_no=:partno and ((bs_partno.part_status=2 and bs_partno.check_status=3) or (bs_partno.part_status=3 and bs_partno.check_status=2) or bs_partno.part_status=4)", [":partno" => $partno])->one();
                $model["isUpshelf"] = isset($prtModel->primaryKey);
            } else {
                $model = FpPrice::findBySql("select pdt_name,category_id catg_id,unit,brand_id,concat_ws('->',c3.catg_name,c2.catg_name,c1.catg_name) cat_three_level from pdt.fp_price left join pdt.bs_brand on fp_price.brand=bs_brand.brand_name_cn left join pdt.bs_category c1 on c1.catg_id=fp_price.category_id left join pdt.bs_category c2 on c2.catg_id=c1.p_catg_id left join pdt.bs_category c3 on c3.catg_id=c2.p_catg_id where status=1 and part_no=:partno", [":partno" => $partno])->asArray()->one();
                if ($model["unit"]) {
                    $pubModel = BsPubdata::find()->where(["bsp_stype" => "jydw", "bsp_svalue" => $model['unit'], "bsp_status" => 10])->one();
                    $model["unit"] = isset($pubModel->bsp_id) ? $pubModel->bsp_id : "";
                }
                $relatedProduct = FpPrice::findBySql("select distinct bs_product.pdt_pkid,bs_product.pdt_name,bs_product.pdt_no,bs_category.catg_name,0 selected from pdt.bs_product join pdt.bs_partno on bs_partno.pdt_pkid=bs_product.pdt_pkid left join pdt.bs_category on bs_product.catg_id = bs_category.catg_id where bs_product.catg_id in(select r_catg.catg_r_id from pdt.fp_price left join pdt.r_catg on fp_price.category_id=r_catg.catg_id where fp_price.part_no=:partno) and ((bs_partno.part_status=2 and bs_partno.check_status=3) or (bs_partno.part_status=3 and bs_partno.check_status=2) or (bs_partno.part_status=4))", [":partno" => $partno])->asArray()->all();
                $model["isUpshelf"] = 0;
                $model["related_product"] = array_map(function ($product) {
                    $product["selected"] = 0;
                    return $product;
                }, $relatedProduct);
            }
            return $model;
        }
    }


    public function actionUpShelf2($id, $type = "")
    {
        if (\Yii::$app->request->isPost) {
            $params = \Yii::$app->request->post();
            $trans = \Yii::$app->pdt->beginTransaction();
            try {

                /*-----------------------------------料号信息---------------------------------------*/
                $model = BsPartno::find()->where(["pdt_pkid" => $id, "part_no" => $params["BsPartno"]["part_no"]])->andWhere(["or", ["part_status" => BsPartno::STATUS_UNCOMMIT], ["part_status" => BsPartno::STATUS_DOWNSHELF]])->one();
                if (!$model) {
                    $model = new BsPartno();
                    $model->part_status = BsPartno::STATUS_UNCOMMIT;
                }
                $model->load($params);

                $logPdtModel = LProduct::find()->where(['pdt_pkid' => $model->pdt_pkid])->orderBy("l_pdt_pkid desc")->one();
                $logPartModel = new LPartno();
                $logPartModel->setAttributes($model->attributes);

                if ($model->isNewRecord) {
                    $model->crt_date = $model->opp_date;
                    $model->crt_ip = $model->opp_ip;
                    $model->crter = $model->opper;
                    $model->opper = null;
                    $model->opp_ip = null;
                    $model->opp_date = null;
                }
                if (!($model->validate() && $model->save())) {
                    throw new \Exception("料号信息修改失败");
                }

                $logPartModel->l_pdt_pkid = $logPdtModel->l_pdt_pkid;
                $logPartModel->part_status = BsPartno::STATUS_UNCOMMIT;
                $logPartModel->yn = 0;
                if (!($logPartModel->validate() && $logPartModel->save())) {
                    throw new \Exception("料号日志信息修改失败");
                }


                /*-----------------------------------销售价格---------------------------------------*/
                $count = isset($params['BsPrice']['maxqty']) ? count($params['BsPrice']['maxqty']) : 0;
                BsPrice::deleteAll(["prt_pkid" => $model->primaryKey]);
                for ($x = 0; $x < $count; $x++) {
                    $priceModel = new BsPrice();
                    $priceModel->prt_pkid = $model->primaryKey;
                    $priceModel->item = $x;
                    $priceModel->minqty = $params['BsPrice']['minqty'][$x];
                    $priceModel->maxqty = $params['BsPrice']['maxqty'][$x];
                    $priceModel->price = $params['BsPrice']['price'][$x];
                    $priceModel->currency = $params['BsPrice']['currency'][$x];
                    if (!($priceModel->validate() && $priceModel->save())) {
                        throw new \Exception("销售价格保存失败");
                    }
                }

                /*-----------------------------------自提仓库---------------------------------------*/

                $count = isset($params["RPrtWh"]["wh_id"]) ? count($params["RPrtWh"]["wh_id"]) : 0;
                RPrtWh::deleteAll(["prt_pkid" => $model->primaryKey]);
                for ($x = 0; $x < $count; $x++) {
                    $whModel = new RPrtWh();
                    $whModel->prt_pkid = $model->primaryKey;
                    $whModel->wh_id = $params["RPrtWh"]["wh_id"][$x];
                    if (!($whModel->validate() && $whModel->save())) {
                        throw new \Exception("自提仓库信息保存失败");
                    }

                    $logWhModel = new LPrtWh();
                    $logWhModel->setAttributes($whModel->attributes);
                    $logWhModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                    if (!($logWhModel->validate() && $logWhModel->save())) {
                        throw new \Exception("自提仓库日志信息保存失败");
                    }
                }

                /*-----------------------------------备货期---------------------------------------*/

                $count = isset($params["BsStock"]["min_qty"]) ? count($params["BsStock"]["min_qty"]) : 0;
                BsStock::deleteAll(["prt_pkid" => $model->primaryKey]);
                for ($x = 0; $x < $count; $x++) {
                    $stockModel = new BsStock();
                    $stockModel->prt_pkid = $model->primaryKey;
                    $stockModel->item = $x + 1;
                    $stockModel->max_qty = $params["BsStock"]["max_qty"][$x];
                    $stockModel->min_qty = $params["BsStock"]["min_qty"][$x];
                    $stockModel->stock_time = $params["BsStock"]["stock_time"][$x];
                    if (!($stockModel->validate() && $stockModel->save())) {
                        throw new \Exception("备货期信息保存失败");
                    }

                    $logStockModel = new LStock();
                    $logStockModel->setAttributes($stockModel->attributes);
                    $logStockModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                    $logStockModel->yn = 0;
                    if (!($logStockModel->validate() && $logStockModel->save())) {
                        throw new \Exception("备货期日志信息保存失败");
                    }
                }
                /*-----------------------------------发货地---------------------------------------*/

                $count = isset($params["BsShip"]["country_no"]) ? count($params["BsShip"]["country_no"]) : 0;
                BsShip::deleteAll(["prt_pkid" => $model->primaryKey]);
                for ($x = 0; $x < $count; $x++) {
                    $shipModel = new BsShip();
                    $shipModel->prt_pkid = $model->primaryKey;
                    $shipModel->country_name = $params["BsShip"]["country_name"][$x];
                    $shipModel->country_no = $params["BsShip"]["country_no"][$x];
                    $shipModel->province_name = $params["BsShip"]["province_name"][$x];
                    $shipModel->province_no = $params["BsShip"]["province_no"][$x];
                    $shipModel->city_name = $params["BsShip"]["city_name"][$x];
                    $shipModel->city_no = $params["BsShip"]["city_no"][$x];
                    if (!($shipModel->validate() && $shipModel->save())) {
                        throw new \Exception("发货地数据保存失败");
                    }

                    $logShipModel = new LShip();
                    $logShipModel->setAttributes($shipModel->attributes);
                    $logShipModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                    $logShipModel->yn = 0;
                    if (!($logShipModel->validate() && $logShipModel->save())) {
                        throw new \Exception("发货地日志信息保存失败");
                    }
                }


                /*-----------------------------------免运费收货地---------------------------------------*/

                $count = isset($params["BsDeliv"]["country_no"]) ? count($params["BsDeliv"]["country_no"]) : 0;
                BsDeliv::deleteAll(["prt_pkid" => $model->primaryKey]);
                for ($x = 0; $x < $count; $x++) {
                    $delivModel = new BsDeliv();
                    $delivModel->prt_pkid = $model->primaryKey;
                    $delivModel->country_name = $params["BsDeliv"]["country_name"][$x];
                    $delivModel->country_no = $params["BsDeliv"]["country_no"][$x];
                    $delivModel->province_name = $params["BsDeliv"]["province_name"][$x];
                    $delivModel->province_no = $params["BsDeliv"]["province_no"][$x];
                    $delivModel->city_name = $params["BsDeliv"]["city_name"][$x];
                    $delivModel->city_no = $params["BsDeliv"]["city_no"][$x];
                    if (!($delivModel->validate() && $delivModel->save())) {
                        throw new \Exception("收货地数据保存失败");
                    }

                    $logDelivModel = new LDeliv();
                    $logDelivModel->setAttributes($delivModel->attributes);
                    $logDelivModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                    $logDelivModel->yn = 0;
                    if (!($logDelivModel->validate() && $logDelivModel->save())) {
                        throw new \Exception("收货地日志信息保存失败");
                    }
                }


                /*-----------------------------------规格参数---------------------------------------*/

                $attrs = isset($params["RPrtAttr"]) ? $params["RPrtAttr"] : [];
                RPrtAttr::deleteAll(["prt_pkid" => $model->primaryKey]);
                foreach ($attrs as $k => $v) {
                    if (!isset($v["attr_val_id"])) {
                        continue;
                    }
                    if (is_array($v["attr_val_id"]) && count($v["attr_val_id"]) > 0) {
                        foreach ($v["attr_val_id"] as $m) {
                            $attrModel = new RPrtAttr();
                            $attrModel->prt_pkid = $model->primaryKey;
                            $attrModel->catg_attr_id = $k;
                            $attrModel->attr_name = $v["attr_name"];
                            $attrModel->attr_val_id = $m;
//                            $attrModel->attr_value = RAttrValue::find()->select("attr_value")->where(["attr_val_id" => $m])->scalar();
                            if (!($attrModel->validate() && $attrModel->save())) {
                                throw new \Exception("规格参数数据保存失败");
                            }

                            $logAttrModel = new LPrtAttr();
                            $logAttrModel->setAttributes($attrModel->attributes);
                            if (!($logAttrModel->validate() && $logAttrModel->save())) {
                                throw new \Exception("规格参数日志数据保存失败");
                            }
                        }
                    } else {
                        $attrModel = new RPrtAttr();
                        $attrModel->prt_pkid = $model->primaryKey;
                        $attrModel->catg_attr_id = $k;
                        $attrModel->attr_name = $v["attr_name"];
                        $attrModel->attr_val_id = $v["attr_val_id"];
                        if ($v["attr_type"] == 3) {
                            $attrModel->attr_value = $v["attr_value"];
                        } else {
//                            $attrModel->attr_value = RAttrValue::find()->select("attr_value")->where(["attr_val_id" => $attrModel->attr_val_id])->scalar();
                        }
                        if (!($attrModel->validate() && $attrModel->save())) {
                            throw new \Exception("规格参数数据保存失败");
                        }

                        $logAttrModel = new LPrtAttr();
                        $logAttrModel->setAttributes($attrModel->attributes);
                        if (!($logAttrModel->validate() && $logAttrModel->save())) {
                            throw new \Exception("规格参数日志数据保存失败");
                        }
                    }
                }

                /*-----------------------------------包装信息---------------------------------------*/

                BsPack::deleteAll(["prt_pkid" => $model->primaryKey]);
                $count = isset($params["BsPack"]) ? count($params["BsPack"]) : 0;
                foreach ($params["BsPack"] as $pack) {
                    $packModel = new BsPack();
                    $packModel->prt_pkid = $model->primaryKey;
                    $packModel->pck_type = $pack["pck_type"];
                    $packModel->pdt_length = $pack["pdt_length"];
                    $packModel->pdt_width = $pack["pdt_width"];
                    $packModel->pdt_height = $pack["pdt_height"];
                    $packModel->pdt_weight = $pack["pdt_weight"];
                    $packModel->pdt_qty = $pack["pdt_qty"];
                    isset($pack["pdt_mater"]) && $packModel->pdt_mater = $pack["pdt_mater"];
                    isset($pack["plate_num"]) && $packModel->plate_num = $pack["plate_num"];
                    isset($pack["net_weight"]) && $packModel->net_weight = $pack["net_weight"];
                    if (!($packModel->validate() && $packModel->save())) {
                        throw new \Exception("包装信息保存失败");
                    }

                    $logPackModel = new LPack();
                    $logPackModel->setAttributes($packModel->attributes);
                    $logPackModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                    $logPackModel->yn = 0;
                    if (!($logPackModel->validate() && $logPackModel->save())) {
                        throw new \Exception("包装日志信息保存失败");
                    }
                }


                /*-----------------------------------延保方案---------------------------------------*/

                BsWarr::deleteAll(["prt_pkid" => $model->primaryKey]);
                $count = isset($params["BsWarr"]["wrr_prd"]) ? count($params["BsWarr"]["wrr_prd"]) : 0;
                for ($x = 0; $x < $count; $x++) {
                    $warrModel = new BsWarr();
                    $warrModel->prt_pkid = $model->primaryKey;
                    $warrModel->item = $x + 1;
                    $warrModel->wrr_prd = $params["BsWarr"]["wrr_prd"][$x];
                    $warrModel->wrr_fee = $params["BsWarr"]["wrr_fee"][$x];
                    if (!($warrModel->validate() && $warrModel->save())) {
                        throw new \Exception("延保方案信息保存失败");
                    }

                    $logWarrModel = new LWarr();
                    $logWarrModel->setAttributes($warrModel->attributes);
                    $logWarrModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                    $logWarrModel->yn = 0;
                    if (!($logWarrModel->validate() && $logWarrModel->save())) {
                        throw new \Exception("延保方案日志信息保存失败");
                    }
                }

                /*-----------------------------------设备信息---------------------------------------*/
                BsMachine::deleteAll(["prt_pkid" => $model->primaryKey]);
                $macModel = new BsMachine();
                $macModel->prt_pkid = $model->primaryKey;
                $macModel->load($params);
                if (!($macModel->validate() && $macModel->save())) {
                    throw new \Exception("设备信息保存失败");
                }

                $logMacModel = new LMachine();
                $logMacModel->setAttributes($macModel->attributes);
                $logMacModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                if (!($logMacModel->validate() && $logMacModel->save())) {
                    throw new \Exception("设备信息日志保存失败");
                }


                /*-----------------------------------料号详情---------------------------------------*/
                $detailModel = BsDetails::find()->where(["prt_pkid" => $model->primaryKey])->one();
                if (!$detailModel) {
                    $detailModel = new BsDetails();
                    $detailModel->pdt_pkid = $model->pdt_pkid;
                    $detailModel->prt_pkid = $model->primaryKey;
                }
                $detailModel->load($params);
                if (!($detailModel->validate() && $detailModel->save())) {
                    throw new \Exception("料号详情保存失败");
                }

                $logDetailModel = new LDetails();
                $logDetailModel->setAttributes($detailModel->attributes);
                $logDetailModel->l_pdt_pkid = $logPartModel->l_pdt_pkid;
                $logDetailModel->l_prt_pkid = $logPartModel->l_prt_pkid;
                $logDetailModel->yn = 0;
                if (!($logDetailModel->validate() && $logDetailModel->save())) {
                    throw new \Exception("料号详细信息修改失败");
                }

                $trans->commit();
                return $this->success(['l_prt_pkid' => $logPartModel->primaryKey]);
            } catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
        } else {
            return BsProduct::getProductInfo($id);
        }
    }


    public function actionPartnoList($id)
    {
        $productModel = BsProduct::findOne($id);
        return FpPrice::findBySql("select distinct trim(pdt_name) pdt_name,fp_price.part_no,IF(c3.catg_no='EQ',1,0) isDevice,concat_ws('->',c3.catg_name,c2.catg_name,c1.catg_name) catg_three_level from fp_price left join bs_partno on  bs_partno.part_no=fp_price.part_no left join pdt.bs_category c1 on c1.catg_id=fp_price.category_id left join pdt.bs_category c2 on c2.catg_id=c1.p_catg_id left join pdt.bs_category c3 on c3.catg_id=c2.p_catg_id where (bs_partno.prt_pkid is null or bs_partno.part_status=1) having pdt_name=:pdt_name", [":pdt_name" => $productModel->pdt_name])->asArray()->all();
    }


    public function actionPartnoInfo($id, $partno = "")
    {
        if ($partno && $partnoModel = BsPartno::findOne(["part_no" => $partno])) {
            return BsProduct::getPartnoInfo($partnoModel->primaryKey);
        }
        $result['partno'] = FpPrice::find()->select("pdt_level cm_pos,risk_level leg_lv,limit_day `l/t`,isproxy is_agent,istitle is_first,tp_spec")->where(["part_no" => $partno])->asArray()->one();
        $result['partno']['is_batch']=0;
        $result["partno"]["spp"]=BsProduct::getDb()->createCommand("select group_concat(distinct supplier_name) from fp_pas where part_no=:partno and supplier_name!='' and  effective_date<NOW() and expiration_date>NOW()",[":partno"=>$partno])->queryScalar();
        $result["price"] = [];
        $result["stock"] = [];
        $result["ship"] = [];
        $result["deliv"] = [];
        $result["warr"] = [];
        $attrs = \Yii::$app->pdt->createCommand("select
  bs_catg_attr.catg_id,
  bs_catg_attr.isrequired,
  bs_catg_attr.catg_attr_id,
  bs_catg_attr.attr_name,
  bs_catg_attr.attr_type,
  r_attr_value.attr_val_id,
  r_attr_value.attr_value
from
  bs_catg_attr
  left join r_attr_value on r_attr_value.catg_attr_id = bs_catg_attr.catg_attr_id
  left join  bs_product on bs_catg_attr.catg_id=bs_product.catg_id
where bs_catg_attr.status=1 and bs_product.pdt_pkid=:id", [":id" => $id])->queryAll();
        $data = [];
        foreach ($attrs as $item) {
            $data[$item["catg_attr_id"]]["name"] = $item["attr_name"];
            $data[$item["catg_attr_id"]]["type"] = $item["attr_type"];
            $data[$item["catg_attr_id"]]["isrequired"] = $item["isrequired"];
            if ($item["attr_type"] == 3) {
                $data[$item["catg_attr_id"]]["val"] = '';
            } else {
                $data[$item["catg_attr_id"]]["sel"] = [];
            }
            isset($data[$item["catg_attr_id"]]["items"]) ?: $data[$item["catg_attr_id"]]["items"] = [];
            if ($item["attr_val_id"]) {
                $data[$item["catg_attr_id"]]["items"][$item["attr_val_id"]] = $item["attr_value"];
            }
        }
        $result["attrs"] = $data;
        $result["wh"] = BsWh::find()->with(["whAdms.hrStaff"])->where(["yn_deliv" => 1])->asArray()->all();
        $result["wh"] = array_map(function ($row) {
            $row["staff_name"] = isset($row["whAdms"]["hrStaff"]["staff_name"]) ? $row["whAdms"]["hrStaff"]["staff_name"] : "";
            $row["staff_mobile"] = isset($row["whAdms"]["hrStaff"]["staff_mobile"]) ? $row["whAdms"]["hrStaff"]["staff_mobile"] : "";
            $row["wh_address"] = $row["wh_addr"];
            $row["selected"] = 0;
            unset($row["whAdms"]);
            return $row;
        }, $result["wh"]);
        return $result;
    }

    /**
     * @return mixed
     * 核价信息查询
     */
    public function actionLoadContent()
    {
        $params = Yii::$app->request->queryParams;
        $query = (new Query())->select([
            'FpPas.payment_terms',         //付款条件
            'FpPas.trading_terms',          //交货条件
            'FpPas.supplier_code',          //供应商编号
            'FpPas.supplier_name_shot',    //供应商简称
            'FpPas.delivery_address',       //交货地点
            'FpPas.unite',                  //交易单位
            'FpPas.min_order',              //最小订购量
            'FpPas.currency',               //交易币别
            'truncate(FpPas.buy_price,5) buy_price',              //采购价
            'FpPas.num_area',              //量价区间
            'FpPas.limit_day',              //期限
            'DATE_FORMAT(FpPas.expiration_date,"%Y-%m-%d") expiration_date',         //有效期
//            'FpPas.supplier_name',          //供应商名称
//            'FpPas.rmb_price',              //单价
//            'FpPas.min_price',              //底价
//            'format(FpPas.ws_upper_price,3)',         //商品定价上限(未税)
//            'format(FpPas.ws_lower_price,3)',         //商品定价下限(未税)
//            'format(FpPas.gross_profit,3)',           //毛利润
//            'format(FpPas.gross_profit_margin,3)',    //毛利润率
//            'format(FpPas.pre_tax_profit,3)',         //税前利润
//            'format(FpPas.pre_tax_profit_rate,3)',    //税前利润率
//            'format(FpPas.after_tax_profit,3)',       //税后利润
//            'format(FpPas.after_tax_profit_margin,3)',       //税后利润率
//            'CONCAT(format(FpPas.ws_lower_price,3),"-",format(FpPas.ws_upper_price,3)) AS price_section',           //量价区间
//            'CONCAT(DATE_FORMAT(FpPas.effective_date,"%Y-%m-%d"),"~",DATE_FORMAT(FpPas.expiration_date,"%Y-%m-%d")) AS validity_date',          //定价有效期
        ])->from(FpPas::tableName() . ' FpPas')
            ->where(['part_no' => $params['id']])->distinct()//            ->all()
        ;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($params['rows']) ? $params['rows'] : 5,
            ],
        ]);
        $list['rows'] = $dataProvider->getModels();
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * @param $id
     * @return string
     * index 类别拼凑字符串
     */
    public function actionCategory($id)
    {
        $str1 = substr($id, 0, 2);
        $str2 = substr($id, 0, 4);
        $str3 = substr($id, 0, 6);
        $arr = array($str1, $str2, $str3);
        $str = '';
        foreach ($arr as $value) {
            $name = BsCategory::find()->select(['catg_id', 'catg_name'])->where(['catg_no' => $value])->one();
            $str .= $name['catg_name'] . '->';
        }

        return trim($str, '->');
    }

    /**
     * @param  下拉菜单
     * @return array
     */
    public function actionDownList()
    {
        $downList['category'] = BsCategory::getLevelOne();      //一阶分类
        $downList['property'] = BsPubdata::getList(BsPubdata::GOODS_PROPERTY);  //商品属性
        $downList['part_status'] = [
            '10' => '已发起上架',
            '20' => '未发起上架'
        ];
        return $downList;
    }

    /**
     * @param $id
     * @return array|yii\db\ActiveRecord[]
     * 级联查询类别数据
     */
    public function actionGetCategoryType($id)
    {
        return BsCategory::find()->select("catg_id,catg_name,p_catg_id,catg_no")->asArray(true)->where(['p_catg_id' => $id,"isvalid"=>1])->all();
    }

    public function actionCount($id)
    {
        $model = $this->getModel($id);
        $result = BsProduct::find()->where(['pdt_name' => $model['pdt_name']])->one();
        if ($result == null) {
            return 'error';
        } else {
            return $result;
        }
    }

    /**
     * @param $id
     * @return null|static
     * @throws yii\web\NotFoundHttpException
     * 查询某一料号定价信息
     */
    public function getModel($id)
    {
        if ($model = FpPrice::findOne(['part_no' => $id])) {
            return $model;
        } else {
            throw new yii\web\NotFoundHttpException("頁面未找到");
        }
    }
}