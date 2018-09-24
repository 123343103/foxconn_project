<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/9/6
 * Time: 上午 10:46
 */

namespace app\modules\ptdt\controllers;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsBusinessType;
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
use app\modules\ptdt\models\FpPartno;
use app\modules\ptdt\models\FpPrice;
use app\modules\ptdt\models\LDeliv;
use app\modules\ptdt\models\LDetails;
use app\modules\ptdt\models\LMachine;
use app\modules\ptdt\models\LPack;
use app\modules\ptdt\models\LPartno;
use app\modules\ptdt\models\LProduct;
use app\modules\ptdt\models\LPrtAttr;
use app\modules\ptdt\models\LPrtWh;
use app\modules\ptdt\models\LShip;
use app\modules\ptdt\models\LStock;
use app\modules\ptdt\models\LWarr;
use app\modules\ptdt\models\OffApply;
use app\modules\ptdt\models\OffApplyDt;
use app\modules\ptdt\models\OffFile;
use app\modules\ptdt\models\OffReason;
use app\modules\ptdt\models\RAttrValue;
use app\modules\ptdt\models\RPdtPdt;
use app\modules\ptdt\models\RPrtAttr;
use app\modules\ptdt\models\RPrtWh;
use app\modules\ptdt\models\show\BsPartnoSelectorShow;
use app\modules\system\models\RUserCtgDt;
use app\modules\system\models\Verifyrecord;
use app\modules\warehouse\models\BsPart;
use app\modules\warehouse\models\BsWh;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class ProductListController extends BaseActiveController
{
    public $modelClass='app\modules\ptdt\models\BsPartno';
    public function actionIndex(){
        $params=\Yii::$app->request->queryParams;
        return BsProduct::search($params);
    }

    public function actionEdit($id){
        if (\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            $trans=\Yii::$app->pdt->beginTransaction();
            try{

                $model=BsProduct::findOne($id);
                $model->setAttributes($params);
                if(!($model->validate() && $model->save())){
                    throw new \Exception("商品信息保存出错");
                }

                RPdtPdt::deleteAll(["pdt_pkid"=>$id]);
                $count=isset($params["RPdtPdt"]["r_pdt_pkid"])?count($params["RPdtPdt"]["r_pdt_pkid"]):0;
                for($x=0;$x<$count;$x++){
                    $rpdtModel=new RPdtPdt();
                    $rpdtModel->pdt_pkid=$model->primaryKey;
                    $rpdtModel->r_pdt_pkid=$params["RPdtPdt"]["r_pdt_pkid"][$x];
                    if(!($rpdtModel->validate() && $rpdtModel->save())){
                        throw new \Exception("关联商品保存出错");
                    }
                }

                $detailModel=BsDetails::findOne(["pdt_pkid"=>$model->primaryKey,"prt_pkid"=>null]);
                if(!$detailModel){
                    $detailModel=new BsDetails();
                    $detailModel->pdt_pkid=$model->primaryKey;
                }
                $detailModel->details=$params["details"];
                if(!($detailModel->validate() && $detailModel->save())){
                    throw new \Exception("商品详情保存出错");
                }

                if(!empty($params["pdt_img"])){
                    $batchKeys=["pdt_pkid","img_type","fl_new","orderby"];
                    $batchVals=[];
                    foreach($params["pdt_img"] as $k=>$img){
                        $batchVals[]=[$model->primaryKey,0,$img,$k];
                    }
                    \Yii::$app->pdt->createCommand()->delete(BsImages::tableName(),["pdt_pkid"=>$model->primaryKey])->execute();
                    \Yii::$app->pdt->createCommand()->batchInsert(BsImages::tableName(),$batchKeys,$batchVals)->execute();
                }

                if(!empty($params["upload3D"])){
                    BsImages::deleteAll(["pdt_pkid"=>$model->primaryKey,"img_type"=>1]);
                    $imgModel=new BsImages();
                    $imgModel->pdt_pkid=$model->primaryKey;
                    $imgModel->img_type=1;
                    $imgModel->fl_new=$params["upload3D"];
                    $imgModel->orderby=0;
                    if(!($imgModel->validate() && $imgModel->save())){
                        throw new \Exception("3D图片保存失败");
                    }
                }



                $trans->commit();
                return $this->success();
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }else{
            return BsProduct::getProductInfo($id);
        }
    }


    public function actionPartnoList(){
        $params=\Yii::$app->request->queryParams;
        return BsProduct::partnoSearch($params);
    }

    public function actionPartnoInfo($id,$name=""){
        switch ($name){
            case "base":
                $model=BsProduct::getDb()->createCommand("select bs_partno.*,bs_details.details,bs_details.details from pdt.bs_partno left join pdt.bs_details on bs_details.prt_pkid=bs_partno.prt_pkid  where bs_partno.prt_pkid=:id",[":id"=>$id])->queryOne();
                $model["is_batch"]=0;
                $model["details"]=Html::decode($model["details"]);
                $LPrtModel=LPartno::find()->where(["part_no"=>$model["part_no"]])->orderBy("l_prt_pkid desc")->one();
                $model["l_prt_pkid"]=isset($LPrtModel->primaryKey)?$LPrtModel->primaryKey:"";
                $model["spp"]=(String)BsProduct::getDb()->createCommand("select group_concat(distinct supplier_name) from pdtprice_pas where part_no=:partno and supplier_name!='' and  effective_date<NOW() and expiration_date>NOW()",[":partno"=>$model["part_no"]])->queryScalar();
                $model["pdt"]=BsProduct::getDb()->createCommand("select if(cat_3.catg_no='EQ',1,0) isDevice,bs_details.details from pdt.bs_product left join pdt.bs_category cat_1 on cat_1.catg_id=bs_product.catg_id left join pdt.bs_category cat_2 on cat_2.catg_id=cat_1.p_catg_id left join pdt.bs_category cat_3 on cat_3.catg_id=cat_2.p_catg_id left join pdt.bs_details on bs_details.pdt_pkid=bs_product.pdt_pkid and bs_details.prt_pkid is null where bs_product.pdt_pkid=:id",[":id"=>$model['pdt_pkid']])->queryOne();
                $model["l_t"]=$model["l/t"];
                unset($model["l/t"]);
                return $model;
                break;
            case "pas":
                $model=BsPartno::find()->where(["prt_pkid"=>$id])->one();
                return BsProduct::getDb()->createCommand("select payment_terms,trading_terms,supplier_code,supplier_name_shot,delivery_address,unite,min_order,currency,limit_day,num_area,truncate(buy_price,5) buy_price,expiration_date from pdtprice_pas where part_no=:part_no",[":part_no"=>$model["part_no"]])->queryAll();
                break;
            case "price":
                $prices=BsPrice::find()->select(["prt_pkid","truncate(minqty,2) minqty","IF(maxqty,truncate(maxqty,2),'') maxqty","price","currency"])->with("currency")->where(["prt_pkid"=>$id])->asArray()->all();
                return array_map(function($model){
                    (int)$model["price"]==-1 && $model["price"]=(int)$model["price"];
                    $model["currency_id"]=isset($model["currency"]["bsp_id"])?$model["currency"]["bsp_id"]:"";
                    $model["currency_name"]=isset($model["currency"]["bsp_svalue"])?$model["currency"]["bsp_svalue"]:"";;
                    unset($model["currency"]);
                    return $model;
                },$prices);
                break;
            case "attrs":
                $attrs=BsProduct::getDb()->createCommand("select bs_catg_attr.catg_id,bs_catg_attr.isrequired,bs_catg_attr.catg_attr_id,bs_catg_attr.attr_name,bs_catg_attr.attr_type,r_attr_value.attr_val_id,r_attr_value.attr_value from bs_catg_attr left join r_attr_value on r_attr_value.catg_attr_id = bs_catg_attr.catg_attr_id left join  bs_product on bs_catg_attr.catg_id=bs_product.catg_id left join bs_partno on bs_product.pdt_pkid = bs_partno.pdt_pkid where bs_catg_attr.status=1 and bs_partno.prt_pkid=:id order by catg_attr_id",[":id"=>$id])->queryAll();
                $data=[];
                foreach ($attrs as $item){
                    $data[$item["catg_attr_id"]]["name"]=$item["attr_name"];
                    $data[$item["catg_attr_id"]]["type"]=$item["attr_type"];
                    $data[$item["catg_attr_id"]]["isrequired"]=$item["isrequired"];
                    if($item["attr_type"]==3){
                        $data[$item["catg_attr_id"]]["val"]=(String)RPrtAttr::find()->select("attr_value")->where(["catg_attr_id"=>$item["catg_attr_id"],"prt_pkid"=>$id])->scalar();
                    }else{
                        $selections=RPrtAttr::find()->select("attr_val_id")->where(["catg_attr_id"=>$item["catg_attr_id"],"prt_pkid"=>$id])->column();
                    }
                    isset($data[$item["catg_attr_id"]]["items"]) || $data[$item["catg_attr_id"]]["items"]=[];
                    isset($data[$item["catg_attr_id"]]["selected"]) || $data[$item["catg_attr_id"]]["selected"]=[];
                    if($item["attr_val_id"]){
                        $data[$item["catg_attr_id"]]["items"][$item["attr_val_id"]]=[
                            "name"=>$item["attr_value"]
                        ];
                        if(in_array($item["attr_val_id"],$selections)){
                            $data[$item["catg_attr_id"]]["selected"][$item["attr_val_id"]]=$item["attr_value"];
                        }
                    }
                }
                return $data;
                break;
            case "stock":
                return BsProduct::getDb()->createCommand("select truncate(min_qty,2) min_qty,truncate(max_qty,2) max_qty,stock_time from pdt.bs_stock where prt_pkid=:id",[":id"=>$id])->queryAll();
                break;
            case "ship":
                return BsProduct::getDb()->createCommand("select * from pdt.bs_ship where prt_pkid=:id",[":id"=>$id])->queryAll();
                break;
            case "deliv":
                return BsProduct::getDb()->createCommand("select * from pdt.bs_deliv where prt_pkid=:id",[":id"=>$id])->queryAll();
                break;
            case "pack":
                return BsProduct::getDb()->createCommand("select prt_pkid,pck_type,pdt_length,pdt_width,pdt_height,pdt_weight,net_weight,pdt_mater,pdt_qty,plate_num from pdt.bs_pack where prt_pkid=:id order by pck_type asc",[":id"=>$id])->queryAll();
                break;
            case "wh":
                $whs=BsWh::find()->with(["whAdms.hrStaff"])->where(["yn_deliv"=>1])->asArray()->all();
                $rprtWhArr=RPrtWh::find()->select("wh_id")->where(["prt_pkid"=>$id])->asArray()->column();
                return array_map(function($row) use ($rprtWhArr){
                    $row["staff_name"]=isset($row["whAdms"]["hrStaff"]["staff_name"])?$row["whAdms"]["hrStaff"]["staff_name"]:"";
                    $row["staff_mobile"]=isset($row["whAdms"]["hrStaff"]["staff_mobile"])?$row["whAdms"]["hrStaff"]["staff_mobile"]:"";
                    $row["wh_address"]=$row["wh_addr"];
                    $row["selected"]=in_array($row["wh_id"],$rprtWhArr);
                    unset($row["whAdms"]);
                    return $row;
                },$whs);
                break;
            case "machine":
                $model=BsProduct::getDb()->createCommand("select * from pdt.bs_machine where prt_pkid=:id",[":id"=>$id])->queryOne();
                $model["warr"]=BsProduct::getDb()->createCommand("select * from pdt.bs_warr where prt_pkid=:id",[":id"=>$id])->queryAll();
                return $model;
                break;
        }
        return BsProduct::getPartnoInfo($id);
    }

    public function actionDownShelf($id){
        if(\Yii::$app->request->isPost){
            try{
                $params=\Yii::$app->request->post();
                $prtModel=BsPartno::findOne($id);
                $prtModel->load($params);
                if(!($prtModel->validate() && $prtModel->save())){
                    throw new \Exception("");
                }
                $logPrtModel=new LPartno();
                $logPrtModel->setAttributes($prtModel->attributes);
                $logPrtModel->part_status=0;
                $logPrtModel->check_status=0;
                $logPrtModel->l_pdt_pkid=$prtModel->pdt_pkid;
                if(!($logPrtModel->validate() && $logPrtModel->save())){
                    throw new \Exception("");
                }

                return $this->success(["l_prt_pkid"=>$logPrtModel->primaryKey]);
            }catch (\Exception $e){
                return $this->error();
            }
        }
        return OffReason::find()->asArray()->all();
    }

    //修改料号信息
    public function actionEditPartno($id){
        $trans=\Yii::$app->pdt->beginTransaction();
        try{
            $params=\Yii::$app->request->post();

            $model=BsPartno::findOne($id);
            $model->load($params);
            /*-----------------------------------商品信息---------------------------------------*/

            $pdtModel=BsProduct::findOne($model->pdt_pkid);
            $pdtModel->load($params);
            $logPdtModel=new LProduct();
            $logPdtModel->setAttributes($pdtModel->attributes);
            $logPdtModel->category_id=$pdtModel->catg_id;
            $logPdtModel->yn=0;
            if(!($logPdtModel->validate() && $logPdtModel->save())){
                throw new \Exception("商品信息保存失败");
            }


            /*-----------------------------------料号信息---------------------------------------*/

            $logPrtModel=new LPartno();
            $logPrtModel->setAttributes($model->attributes);
            $logPrtModel->part_status=0;
            $logPrtModel->check_status=0;
            $logPrtModel->l_pdt_pkid=$logPdtModel->primaryKey;
            $logPrtModel->part_status=1;
            $logPrtModel->yn=0;
            if(!($logPrtModel->validate() && $logPrtModel->save())){
                throw new \Exception("料号基本信息保存失败");
            }

            /*-----------------------------------销售价格---------------------------------------*/
//            $count=isset($params['BsPrice']['maxqty'])?count($params['BsPrice']['maxqty']):0;
//            BsPrice::deleteAll(["prt_pkid"=>$model->primaryKey]);
//            for($x=0;$x<$count;$x++){
//                $priceModel=new BsPrice();
//                $priceModel->prt_pkid=$model->primaryKey;
//                $priceModel->item=$x;
//                $priceModel->minqty=$params['BsPrice']['minqty'][$x];
//                $priceModel->maxqty=$params['BsPrice']['maxqty'][$x];
//                $priceModel->price=$params['BsPrice']['price'][$x];
//                $priceModel->currency=$params['BsPrice']['currency'][$x];
//                if(!($priceModel->validate() && $priceModel->save())){
//                    throw new \Exception("销售价格保存失败");
//                }
//            }

            /*-----------------------------------自提仓库---------------------------------------*/

            $count=isset($params["RPrtWh"]["wh_id"])?count($params["RPrtWh"]["wh_id"]):0;
            for($x=0;$x<$count;$x++){
                $whModel=new LPrtWh();
                $whModel->l_prt_pkid=$logPrtModel->primaryKey;
                $whModel->wh_id=$params["RPrtWh"]["wh_id"][$x];
                if(!($whModel->validate() && $whModel->save())){
                    throw new \Exception("自提仓库信息保存失败");
                }
            }

            /*-----------------------------------备货期---------------------------------------*/

            $count=isset($params["BsStock"]["min_qty"])?count($params["BsStock"]["min_qty"]):0;
            for($x=0;$x<$count;$x++){
                $stockModel=new LStock();
                $stockModel->l_prt_pkid=$logPrtModel->primaryKey;
                $stockModel->max_qty=$params["BsStock"]["max_qty"][$x];
                $stockModel->min_qty=$params["BsStock"]["min_qty"][$x];
                $stockModel->stock_time=$params["BsStock"]["stock_time"][$x];
                if(!($stockModel->validate() && $stockModel->save())){
                    throw new \Exception("备货期信息保存失败");
                }
            }
            /*-----------------------------------发货地---------------------------------------*/

            $count=isset($params["BsShip"]["country_no"])?count($params["BsShip"]["country_no"]):0;
            for($x=0;$x<$count;$x++){
                $shipModel=new LShip();
                $shipModel->l_prt_pkid=$logPrtModel->primaryKey;
                $shipModel->country_name=$params["BsShip"]["country_name"][$x];
                $shipModel->country_no=$params["BsShip"]["country_no"][$x];
                $shipModel->province_name=$params["BsShip"]["province_name"][$x];
                $shipModel->province_no=$params["BsShip"]["province_no"][$x];
                $shipModel->city_name=$params["BsShip"]["city_name"][$x];
                $shipModel->city_no=$params["BsShip"]["city_no"][$x];
                if(!($shipModel->validate() && $shipModel->save())){
                    throw new \Exception("发货地数据保存失败");
                }
            }


            /*-----------------------------------免运费收货地---------------------------------------*/

            $count=isset($params["BsDeliv"]["country_no"])?count($params["BsDeliv"]["country_no"]):0;
            for($x=0;$x<$count;$x++){
                $delivModel=new LDeliv();
                $delivModel->l_prt_pkid=$logPrtModel->primaryKey;
                $delivModel->country_name=$params["BsDeliv"]["country_name"][$x];
                $delivModel->country_no=$params["BsDeliv"]["country_no"][$x];
                $delivModel->province_name=$params["BsDeliv"]["province_name"][$x];
                $delivModel->province_no=$params["BsDeliv"]["province_no"][$x];
                $delivModel->city_name=$params["BsDeliv"]["city_name"][$x];
                $delivModel->city_no=$params["BsDeliv"]["city_no"][$x];
                if(!($delivModel->validate() && $delivModel->save())){
                    throw new \Exception("发货地数据保存失败");
                }
            }


            /*-----------------------------------规格参数---------------------------------------*/

            $attrs=isset($params["RPrtAttr"])?$params["RPrtAttr"]:[];
            LPrtAttr::deleteAll(["prt_pkid"=>$model->primaryKey]);
            foreach ($attrs as $k=>$v){
                if(!isset($v["attr_val_id"])){
                    continue;
                }
                if(is_array($v["attr_val_id"]) && count($v["attr_val_id"])>0){
                    foreach ($v["attr_val_id"] as $m){
                        $attrModel=new LPrtAttr();
                        $attrModel->prt_pkid=$model->primaryKey;
                        $attrModel->catg_attr_id=$k;
                        $attrModel->attr_name=$v["attr_name"];
                        $attrModel->attr_val_id=$m;
//                        $attrModel->attr_value=RAttrValue::find()->select("attr_value")->where(["attr_val_id"=>$m])->scalar();
                        if(!($attrModel->validate() && $attrModel->save())){
                            throw new \Exception("规格参数数据保存失败");
                        }
                    }
                }else{
                    $attrModel=new LPrtAttr();
                    $attrModel->prt_pkid=$model->primaryKey;
                    $attrModel->catg_attr_id=$k;
                    $attrModel->attr_name=$v["attr_name"];
                    $attrModel->attr_val_id=$v["attr_val_id"];
                    if($v["attr_type"]==3){
                        $attrModel->attr_value=$v["attr_value"];
                    }else{
//                        $attrModel->attr_value=RAttrValue::find()->select("attr_value")->where(["attr_val_id"=>$attrModel->attr_val_id])->scalar();
                    }
                    if(!($attrModel->validate() && $attrModel->save())){
                        throw new \Exception("规格参数数据保存失败");
                    }
                }
            }

            /*-----------------------------------包装信息---------------------------------------*/

            $count=isset($params["BsPack"])?count($params["BsPack"]):0;
            foreach ($params["BsPack"] as $pack){
                $packModel=new LPack();
                $packModel->l_prt_pkid=$logPrtModel->primaryKey;
                $packModel->pck_type=$pack["pck_type"];
                $packModel->pdt_length=$pack["pdt_length"];
                $packModel->pdt_width=$pack["pdt_width"];
                $packModel->pdt_height=$pack["pdt_height"];
                $packModel->pdt_weight=$pack["pdt_weight"];
                $packModel->pdt_qty=$pack["pdt_qty"];
                isset($pack["pdt_mater"]) && $packModel->pdt_mater=$pack["pdt_mater"];
                isset($pack["plate_num"]) && $packModel->plate_num=$pack["plate_num"];
                isset($pack["net_weight"]) && $packModel->net_weight=$pack["net_weight"];
                if(!($packModel->validate() && $packModel->save())){
                    throw new \Exception("包装信息保存失败");
                }
            }


            /*-----------------------------------延保方案---------------------------------------*/

            $count=isset($params["BsWarr"]["wrr_prd"])?count($params["BsWarr"]["wrr_prd"]):0;
            for($x=0;$x<$count;$x++){
                $warrModel=new LWarr();
                $warrModel->l_prt_pkid=$logPrtModel->primaryKey;
                $warrModel->item = $x + 1;
                $warrModel->wrr_prd=$params["BsWarr"]["wrr_prd"][$x];
                $warrModel->wrr_fee=$params["BsWarr"]["wrr_fee"][$x];
                $warrModel->yn=0;
                if(!($warrModel->validate() && $warrModel->save())){
                    throw new \Exception("延保方案信息保存失败");
                }
            }

            /*-----------------------------------设备信息---------------------------------------*/
            $macModel=new BsMachine();
            $macModel->load($params);
            $logMacModel=new LMachine();
            $logMacModel->l_prt_pkid=$logPrtModel->primaryKey;
            $logMacModel->setAttributes($macModel->attributes);
            if(!($logMacModel->validate() && $logMacModel->save())){
                throw new \Exception("设备信息保存失败");
            }





            /*-----------------------------------料号详情---------------------------------------*/
            if(isset($params["BsDetails"]["details"])){
                $logDetailModel=new LDetails();
                $logDetailModel->l_pdt_pkid=$logPdtModel->primaryKey;
                $logDetailModel->l_prt_pkid=$logPrtModel->primaryKey;
                $logDetailModel->details=$params["BsDetails"]["details"];
                if(!($logDetailModel->validate() && $logDetailModel->save())){
                    throw new \Exception("料号详情保存失败");
                }
            }








            $trans->commit();
            return $this->success([
                "l_prt_pkid"=>$logPrtModel->primaryKey
            ]);
        }catch (\Exception $e){
            $trans->rollback();
            return $this->error($e->getMessage());
        }
    }

    public function actionRedoUpshelf($id){
        if(\Yii::$app->request->isPost){
            $trans=\Yii::$app->pdt->beginTransaction();
            try{
                $params=\Yii::$app->request->post();

                $model=BsPartno::findOne($id);
                $model->load($params);
                $model=BsPartno::findOne($id);
                $model->load($params);
                if(!($model->validate() && $model->save())){
                    throw new \Exception("料号基本信息保存失败");
                }
                /*-----------------------------------商品信息---------------------------------------*/


                $logPdtModel=LProduct::find()->where(["pdt_pkid"=>$model->pdt_pkid])->orderBy("l_pdt_pkid desc")->one();


                /*-----------------------------------料号信息---------------------------------------*/

                $logPrtModel=new LPartno();
                $logPrtModel->setAttributes($model->attributes);
                $logPrtModel->l_pdt_pkid=$logPdtModel->primaryKey;
                $logPrtModel->part_status=0;
                $logPrtModel->check_status=0;
                $logPrtModel->yn=0;
                if(!($logPrtModel->validate() && $logPrtModel->save())){
                    throw new \Exception("料号日志信息保存失败");
                }

                /*-----------------------------------销售价格---------------------------------------*/
                $count=isset($params['BsPrice']['maxqty'])?count($params['BsPrice']['maxqty']):0;
                BsPrice::deleteAll(["prt_pkid"=>$model->primaryKey]);
                for($x=0;$x<$count;$x++){
                    $priceModel=new BsPrice();
                    $priceModel->prt_pkid=$model->primaryKey;
                    $priceModel->item=$x;
                    $priceModel->minqty=$params['BsPrice']['minqty'][$x];
                    $priceModel->maxqty=$params['BsPrice']['maxqty'][$x];
                    $priceModel->price=$params['BsPrice']['price'][$x];
                    $priceModel->currency=$params['BsPrice']['currency'][$x];
                    if(!($priceModel->validate() && $priceModel->save())){
                        throw new \Exception("销售价格保存失败");
                    }
                }

                /*-----------------------------------自提仓库---------------------------------------*/
                RPrtWh::deleteAll(["prt_pkid"=>$model->primaryKey]);
                $count=isset($params["RPrtWh"]["wh_id"])?count($params["RPrtWh"]["wh_id"]):0;
                for($x=0;$x<$count;$x++){
                    $whModel=new RPrtWh();
                    $whModel->prt_pkid=$model->primaryKey;
                    $whModel->wh_id=$params["RPrtWh"]["wh_id"][$x];
                    if(!($whModel->validate() && $whModel->save())){
                        throw new \Exception("自提仓库信息保存失败");
                    }
                }

                /*-----------------------------------备货期---------------------------------------*/
                BsStock::deleteAll(["prt_pkid"=>$model->primaryKey]);
                $count=isset($params["BsStock"]["min_qty"])?count($params["BsStock"]["min_qty"]):0;
                for($x=0;$x<$count;$x++){
                    $stockModel=new BsStock();
                    $stockModel->prt_pkid=$model->primaryKey;
                    $stockModel->max_qty=$params["BsStock"]["max_qty"][$x];
                    $stockModel->min_qty=$params["BsStock"]["min_qty"][$x];
                    $stockModel->stock_time=$params["BsStock"]["stock_time"][$x];
                    if(!($stockModel->validate() && $stockModel->save())){
                        throw new \Exception("备货期信息保存失败");
                    }
                }
                /*-----------------------------------发货地---------------------------------------*/

                BsShip::deleteAll(["prt_pkid"=>$model->primaryKey]);
                $count=isset($params["BsShip"]["country_no"])?count($params["BsShip"]["country_no"]):0;
                for($x=0;$x<$count;$x++){
                    $shipModel=new BsShip();
                    $shipModel->prt_pkid=$model->primaryKey;
                    $shipModel->country_name=$params["BsShip"]["country_name"][$x];
                    $shipModel->country_no=$params["BsShip"]["country_no"][$x];
                    $shipModel->province_name=$params["BsShip"]["province_name"][$x];
                    $shipModel->province_no=$params["BsShip"]["province_no"][$x];
                    $shipModel->city_name=$params["BsShip"]["city_name"][$x];
                    $shipModel->city_no=$params["BsShip"]["city_no"][$x];
                    if(!($shipModel->validate() && $shipModel->save())){
                        throw new \Exception("发货地数据保存失败");
                    }
                }


                /*-----------------------------------免运费收货地---------------------------------------*/
                BsDeliv::deleteAll(["prt_pkid"=>$model->primaryKey]);
                $count=isset($params["BsDeliv"]["country_no"])?count($params["BsDeliv"]["country_no"]):0;
                for($x=0;$x<$count;$x++){
                    $delivModel=new BsDeliv();
                    $delivModel->prt_pkid=$model->primaryKey;
                    $delivModel->country_name=$params["BsDeliv"]["country_name"][$x];
                    $delivModel->country_no=$params["BsDeliv"]["country_no"][$x];
                    $delivModel->province_name=$params["BsDeliv"]["province_name"][$x];
                    $delivModel->province_no=$params["BsDeliv"]["province_no"][$x];
                    $delivModel->city_name=$params["BsDeliv"]["city_name"][$x];
                    $delivModel->city_no=$params["BsDeliv"]["city_no"][$x];
                    if(!($delivModel->validate() && $delivModel->save())){
                        throw new \Exception("发货地数据保存失败");
                    }
                }


                /*-----------------------------------规格参数---------------------------------------*/
                RPrtAttr::deleteAll(["prt_pkid" => $model->primaryKey]);
                $attrs=isset($params["RPrtAttr"])?$params["RPrtAttr"]:[];
                foreach ($attrs as $k=>$v){
                    if(!isset($v["attr_val_id"])){
                        continue;
                    }
                    if(is_array($v["attr_val_id"]) && count($v["attr_val_id"])>0){
                        foreach ($v["attr_val_id"] as $m){
                            $attrModel=new RPrtAttr();
                            $attrModel->prt_pkid=$model->primaryKey;
                            $attrModel->catg_attr_id=$k;
                            $attrModel->attr_name=$v["attr_name"];
                            $attrModel->attr_val_id=$m;
//                            $attrModel->attr_value=RAttrValue::find()->select("attr_value")->where(["attr_val_id"=>$m])->scalar();
                            if(!($attrModel->validate() && $attrModel->save())){
                                throw new \Exception("规格参数数据保存失败");
                            }
                        }
                    }else{
                        $attrModel=new RPrtAttr();
                        $attrModel->prt_pkid=$model->primaryKey;
                        $attrModel->catg_attr_id=$k;
                        $attrModel->attr_name=$v["attr_name"];
                        $attrModel->attr_val_id=$v["attr_val_id"];
                        if($v["attr_type"]==3){
                            $attrModel->attr_value=$v["attr_value"];
                        }else{
//                            $attrModel->attr_value=RAttrValue::find()->select("attr_value")->where(["attr_val_id"=>$attrModel->attr_val_id])->scalar();
                        }
                        if(!($attrModel->validate() && $attrModel->save())){
                            throw new \Exception("规格参数数据保存失败");
                        }
                    }
                }

                /*-----------------------------------包装信息---------------------------------------*/
                BsPack::deleteAll(["prt_pkid" => $model->primaryKey]);
                $count=isset($params["BsPack"])?count($params["BsPack"]):0;
                foreach ($params["BsPack"] as $pack){
                    $packModel=new BsPack();
                    $packModel->prt_pkid=$model->primaryKey;
                    $packModel->pck_type=$pack["pck_type"];
                    $packModel->pdt_length=$pack["pdt_length"];
                    $packModel->pdt_width=$pack["pdt_width"];
                    $packModel->pdt_height=$pack["pdt_height"];
                    $packModel->pdt_weight=$pack["pdt_weight"];
                    $packModel->pdt_qty=$pack["pdt_qty"];
                    isset($pack["pdt_mater"]) && $packModel->pdt_mater=$pack["pdt_mater"];
                    isset($pack["plate_num"]) && $packModel->plate_num=$pack["plate_num"];
                    isset($pack["net_weight"]) && $packModel->net_weight=$pack["net_weight"];
                    if(!($packModel->validate() && $packModel->save())){
                        throw new \Exception("包装信息保存失败");
                    }
                }


                /*-----------------------------------延保方案---------------------------------------*/
                BsWarr::deleteAll(["prt_pkid" => $model->primaryKey]);
                $count=isset($params["BsWarr"]["wrr_prd"])?count($params["BsWarr"]["wrr_prd"]):0;
                for($x=0;$x<$count;$x++){
                    $warrModel=new BsWarr();
                    $warrModel->prt_pkid=$model->primaryKey;
                    $warrModel->item = $x + 1;
                    $warrModel->wrr_prd=$params["BsWarr"]["wrr_prd"][$x];
                    $warrModel->wrr_fee=$params["BsWarr"]["wrr_fee"][$x];
                    if(!($warrModel->validate() && $warrModel->save())){
                        throw new \Exception("延保方案信息保存失败");
                    }
                }

                /*-----------------------------------设备信息---------------------------------------*/
                BsMachine::deleteAll(["prt_pkid" => $model->primaryKey]);
                $macModel=new BsMachine();
                $macModel->load($params);
                $macModel->prt_pkid=$model->primaryKey;
                $macModel->setAttributes($macModel->attributes);
                if(!($macModel->validate() && $macModel->save())){
                    throw new \Exception("设备信息保存失败");
                }





                /*-----------------------------------料号详情---------------------------------------*/
                BsDetails::deleteAll(["prt_pkid"=>$model->primaryKey]);
                $logDetailModel=new BsDetails();
                $logDetailModel->pdt_pkid=$model->pdt_pkid;
                $logDetailModel->prt_pkid=$model->primaryKey;
                $logDetailModel->details=$params["BsDetails"]["details"];
                if(!($logDetailModel->validate() && $logDetailModel->save())){
                    throw new \Exception(Json::encode($logDetailModel->errors));
//                throw new \Exception("料号详情保存失败");
                }








                $trans->commit();
                return $this->success([
                    "l_prt_pkid"=>$logPrtModel->primaryKey
                ]);
            }catch (\Exception $e){
                $trans->rollback();
                return $this->error($e->getMessage());
            }
        }else{
            try{
                $model=BsPartno::findOne($id);
                /*-----------------------------------商品信息---------------------------------------*/

                $logPdtModel=LProduct::find()->where(["pdt_pkid"=>$model->pdt_pkid])->orderBy("l_pdt_pkid desc")->one();

                /*-----------------------------------料号信息---------------------------------------*/
                $logPrtModel=new LPartno();
                $logPrtModel->setAttributes($model->attributes);
                $logPrtModel->l_pdt_pkid=$logPdtModel->primaryKey;
                $logPrtModel->part_status=0;
                $logPrtModel->check_status=0;
                $logPrtModel->yn=0;
                if(!($logPrtModel->validate() && $logPrtModel->save())){
                    throw new \Exception("料号基本信息保存失败");
                }
                return $this->success(["l_prt_pkid"=>$logPrtModel->primaryKey]);
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }
//        $trans=\Yii::$app->pdt->beginTransaction();
//        try{
//            $params=\Yii::$app->request->post();
//
//            $model=BsPartno::findOne($id);
//            $model->load($params);
//            /*-----------------------------------商品信息---------------------------------------*/
//
//
//            $logPdtModel=LProduct::find()->where(["pdt_pkid"=>$model->pdt_pkid])->orderBy("l_pdt_pkid desc")->one();
//
//
//            /*-----------------------------------料号信息---------------------------------------*/
//
//            $logPrtModel=new LPartno();
//            $logPrtModel->setAttributes($model->attributes);
//            $logPrtModel->l_pdt_pkid=$logPdtModel->primaryKey;
//            $logPrtModel->part_status=0;
//            $logPrtModel->check_status=0;
//            $logPrtModel->yn=0;
//            if(!($logPrtModel->validate() && $logPrtModel->save())){
//                throw new \Exception("料号基本信息保存失败");
//            }
//
//            /*-----------------------------------销售价格---------------------------------------*/
//            $count=isset($params['BsPrice']['maxqty'])?count($params['BsPrice']['maxqty']):0;
//            BsPrice::deleteAll(["prt_pkid"=>$model->primaryKey]);
//            for($x=0;$x<$count;$x++){
//                $priceModel=new BsPrice();
//                $priceModel->prt_pkid=$model->primaryKey;
//                $priceModel->item=$x;
//                $priceModel->minqty=$params['BsPrice']['minqty'][$x];
//                $priceModel->maxqty=$params['BsPrice']['maxqty'][$x];
//                $priceModel->price=$params['BsPrice']['price'][$x];
//                $priceModel->currency=$params['BsPrice']['currency'][$x];
//                if(!($priceModel->validate() && $priceModel->save())){
//                    throw new \Exception("销售价格保存失败");
//                }
//            }
//
//            /*-----------------------------------自提仓库---------------------------------------*/
//
//            $count=isset($params["RPrtWh"]["wh_id"])?count($params["RPrtWh"]["wh_id"]):0;
//            for($x=0;$x<$count;$x++){
//                $whModel=new LPrtWh();
//                $whModel->l_prt_pkid=$logPrtModel->primaryKey;
//                $whModel->wh_id=$params["RPrtWh"]["wh_id"][$x];
//                if(!($whModel->validate() && $whModel->save())){
//                    throw new \Exception("自提仓库信息保存失败");
//                }
//            }
//
//            /*-----------------------------------备货期---------------------------------------*/
//
//            $count=isset($params["BsStock"]["min_qty"])?count($params["BsStock"]["min_qty"]):0;
//            for($x=0;$x<$count;$x++){
//                $stockModel=new LStock();
//                $stockModel->l_prt_pkid=$logPrtModel->primaryKey;
//                $stockModel->max_qty=$params["BsStock"]["max_qty"][$x];
//                $stockModel->min_qty=$params["BsStock"]["min_qty"][$x];
//                $stockModel->stock_time=$params["BsStock"]["stock_time"][$x];
//                if(!($stockModel->validate() && $stockModel->save())){
//                    throw new \Exception("备货期信息保存失败");
//                }
//            }
//            /*-----------------------------------发货地---------------------------------------*/
//
//            $count=isset($params["BsShip"]["country_no"])?count($params["BsShip"]["country_no"]):0;
//            for($x=0;$x<$count;$x++){
//                $shipModel=new LShip();
//                $shipModel->l_prt_pkid=$logPrtModel->primaryKey;
//                $shipModel->country_name=$params["BsShip"]["country_name"][$x];
//                $shipModel->country_no=$params["BsShip"]["country_no"][$x];
//                $shipModel->province_name=$params["BsShip"]["province_name"][$x];
//                $shipModel->province_no=$params["BsShip"]["province_no"][$x];
//                $shipModel->city_name=$params["BsShip"]["city_name"][$x];
//                $shipModel->city_no=$params["BsShip"]["city_no"][$x];
//                if(!($shipModel->validate() && $shipModel->save())){
//                    throw new \Exception("发货地数据保存失败");
//                }
//            }
//
//
//            /*-----------------------------------免运费收货地---------------------------------------*/
//
//            $count=isset($params["BsDeliv"]["country_no"])?count($params["BsDeliv"]["country_no"]):0;
//            for($x=0;$x<$count;$x++){
//                $delivModel=new LDeliv();
//                $delivModel->l_prt_pkid=$logPrtModel->primaryKey;
//                $delivModel->country_name=$params["BsDeliv"]["country_name"][$x];
//                $delivModel->country_no=$params["BsDeliv"]["country_no"][$x];
//                $delivModel->province_name=$params["BsDeliv"]["province_name"][$x];
//                $delivModel->province_no=$params["BsDeliv"]["province_no"][$x];
//                $delivModel->city_name=$params["BsDeliv"]["city_name"][$x];
//                $delivModel->city_no=$params["BsDeliv"]["city_no"][$x];
//                if(!($delivModel->validate() && $delivModel->save())){
//                    throw new \Exception("发货地数据保存失败");
//                }
//            }
//
//
//            /*-----------------------------------规格参数---------------------------------------*/
//
//            $attrs=isset($params["RPrtAttr"])?$params["RPrtAttr"]:[];
//            foreach ($attrs as $k=>$v){
//                if(!isset($v["attr_val_id"])){
//                    continue;
//                }
//                if(is_array($v["attr_val_id"]) && count($v["attr_val_id"])>0){
//                    foreach ($v["attr_val_id"] as $m){
//                        $attrModel=new LPrtAttr();
//                        $attrModel->prt_pkid=$model->primaryKey;
//                        $attrModel->catg_attr_id=$k;
//                        $attrModel->attr_name=$v["attr_name"];
//                        $attrModel->attr_val_id=$m;
//                        $attrModel->attr_value=RAttrValue::find()->select("attr_value")->where(["attr_val_id"=>$m])->scalar();
//                        if(!($attrModel->validate() && $attrModel->save())){
//                            throw new \Exception("规格参数数据保存失败");
//                        }
//                    }
//                }else{
//                    $attrModel=new LPrtAttr();
//                    $attrModel->prt_pkid=$model->primaryKey;
//                    $attrModel->catg_attr_id=$k;
//                    $attrModel->attr_name=$v["attr_name"];
//                    $attrModel->attr_val_id=$v["attr_val_id"];
//                    if($v["attr_type"]==3){
//                        $attrModel->attr_value=$v["attr_value"];
//                    }else{
//                        $attrModel->attr_value=RAttrValue::find()->select("attr_value")->where(["attr_val_id"=>$attrModel->attr_val_id])->scalar();
//                    }
//                    if(!($attrModel->validate() && $attrModel->save())){
//                        throw new \Exception("规格参数数据保存失败");
//                    }
//                }
//            }
//
//            /*-----------------------------------包装信息---------------------------------------*/
//
//            $count=isset($params["BsPack"])?count($params["BsPack"]):0;
//            foreach ($params["BsPack"] as $pack){
//                $packModel=new LPack();
//                $packModel->l_prt_pkid=$logPrtModel->primaryKey;
//                $packModel->pck_type=$pack["pck_type"];
//                $packModel->pdt_length=$pack["pdt_length"];
//                $packModel->pdt_width=$pack["pdt_width"];
//                $packModel->pdt_height=$pack["pdt_height"];
//                $packModel->pdt_weight=$pack["pdt_weight"];
//                isset($pack["pdt_mater"]) && $packModel->pdt_mater=$pack["pdt_mater"];
//                isset($pack["plate_num"]) && $packModel->plate_num=$pack["plate_num"];
//                $packModel->pdt_qty=$pack["pdt_qty"];
//                if(!($packModel->validate() && $packModel->save())){
//                    throw new \Exception("包装信息保存失败");
//                }
//            }
//
//
//            /*-----------------------------------延保方案---------------------------------------*/
//
//            $count=isset($params["BsWarr"]["wrr_prd"])?count($params["BsWarr"]["wrr_prd"]):0;
//            for($x=0;$x<$count;$x++){
//                $warrModel=new LWarr();
//                $warrModel->l_prt_pkid=$logPrtModel->primaryKey;
//                $warrModel->wrr_prd=$params["BsWarr"]["wrr_prd"][$x];
//                $warrModel->wrr_fee=$params["BsWarr"]["wrr_fee"][$x];
//                $warrModel->yn=0;
//                if(!($warrModel->validate() && $warrModel->save())){
//                    throw new \Exception("延保方案信息保存失败");
//                }
//            }
//
//            /*-----------------------------------设备信息---------------------------------------*/
//            $macModel=new BsMachine();
//            $macModel->load($params);
//            $logMacModel=new LMachine();
//            $logMacModel->l_prt_pkid=$logPrtModel->primaryKey;
//            $logMacModel->setAttributes($macModel->attributes);
//            if(!($logMacModel->validate() && $logMacModel->save())){
//                throw new \Exception("设备信息保存失败");
//            }
//
//
//
//
//
//            /*-----------------------------------料号详情---------------------------------------*/
//            $logDetailModel=new LDetails();
//            $logDetailModel->l_pdt_pkid=$logPdtModel->primaryKey;
//            $logDetailModel->l_prt_pkid=$logPrtModel->primaryKey;
//            $logDetailModel->details=$params["BsDetails"]["details"];
//            if(!($logDetailModel->validate() && $logDetailModel->save())){
//                throw new \Exception(Json::encode($logDetailModel->errors));
////                throw new \Exception("料号详情保存失败");
//            }
//
//
//
//
//
//
//
//
//            $trans->commit();
//            return $this->success([
//                "l_prt_pkid"=>$logPrtModel->primaryKey
//            ]);
//        }catch (\Exception $e){
//            $trans->rollback();
//            return $this->error($e->getMessage());
//        }
    }

    public function actionPrice($id){
        if(\Yii::$app->request->isPost){
            $params=\Yii::$app->request->post();
            try{
                $columns=["prt_pkid","item","minqty","maxqty","price","currency"];
                $rows=[];
                $priceModel=new BsPrice();
                foreach($params["BsPrice"]["minqty"] as $k=>$v){
                    $row=[
                        "prt_pkid"=>$id,
                        "item"=>$k,
                        "minqty"=>$params["BsPrice"]["minqty"][$k],
                        "maxqty"=>$params["BsPrice"]["maxqty"][$k],
                        "price"=>$params["BsPrice"]["price"][$k],
                        "currency"=>$params["BsPrice"]["currency"][$k]
                    ];
                    $priceModel->setAttributes($row);
                    if(!$priceModel->validate()){
                        throw new \Exception("数据验证失败");
                    }
                    $rows[]=$row;
                }
                BsPrice::deleteAll(["prt_pkid"=>$id]);
                if(!BsPrice::getDb()->createCommand()->batchInsert(BsPrice::tableName(),$columns,$rows)->execute()){
                    throw new \Exception("数据保存失败");
                }
                return $this->success();
            }catch (\Exception $e){
                return $this->error($e->getMessage());
            }
        }else{
            $price=BsPrice::find()->select(["truncate(minqty,2) minqty","IF(maxqty,truncate(maxqty,2),'') maxqty","price","currency"])->with("currency")->where(["prt_pkid"=>$id])->asArray()->orderBy("item asc")->all();
            $price=array_map(function($row){
                $row["currency_name"]=$row["currency"]["bsp_svalue"];
                $row["currency_id"]=$row["currency"]["bsp_id"];
                unset($row["currency"]);
                return $row;
            },$price);
            $prtModel=BsPartno::find()->where(["prt_pkid"=>$id])->one();
            $packModel=BsPack::find()->where(["prt_pkid"=>$id,"pck_type"=>2])->one();
            return [
                "price"=>$price,
                "minOrder"=>isset($prtModel->min_order)?$prtModel->min_order:"",
                "pdt_qty"=>isset($packModel->pdt_qty)?$packModel->pdt_qty:""
            ];

        }
    }

    public function actionProductSelector(){
        $params=\Yii::$app->request->queryParams;
        $catgs=[];
        isset($params["uid"]) && $catgs=RUserCtgDt::find()->select('ctg_pkid')->where(["user_id"=>$params["uid"]])->column();
        $query=BsPartnoSelectorShow::find()->joinWith("product",false)
            ->leftJoin(BsCategory::tableName()." c1","c1.catg_id=".BsProduct::tableName().".catg_id")
            ->leftJoin(BsCategory::tableName()." c2","c2.catg_id=c1.p_catg_id")
            ->leftJoin(BsCategory::tableName()." c3","c3.catg_id=c2.p_catg_id")
            ->where([
                "or",
                [BsPartno::tableName().".part_status"=>BsPartno::STATUS_UPSHELF,BsPartno::tableName().".check_status"=>BsPartno::CHECK_PASS],
                [BsPartno::tableName().".part_status"=>BsPartno::STATUS_DOWNSHELF,BsPartno::tableName().".check_status"=>BsPartno::CHECK_REJECT],
                [BsPartno::tableName().".part_status"=>BsPartno::STATUS_MODIFY]
            ])->andWhere(["in","c1.catg_id",$catgs]);
        $provider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageParam"=>"page",
                "pageSizeParam"=>"rows"
            ]
        ]);

        if(isset($params["filters"])){
            $filters=explode(",",$params["filters"]);
            $query->andFilterWhere(["not in",BsPartno::tableName().".prt_pkid",$filters]);
        }

        if(isset($params["kwd"])){
            $query->andFilterWhere([
                "or",
                ["like","pdt_name",$params["kwd"]],
                ["like","part_no",$params["kwd"]],
            ]);
        }

        if(isset($params["catg_id"])){
            $query->andFilterWhere([
                "or",
                ["c1.catg_id"=>$params["catg_id"]],
                ["c2.catg_id"=>$params["catg_id"]],
                ["c3.catg_id"=>$params["catg_id"]]
            ]);
        }
        return [
            "rows"=>$provider->models,
            "total"=>$provider->totalCount
        ];
    }

    public function actionGetBusType($code=""){
        $model=BsBusinessType::findOne(["business_code"=>$code]);
        return isset($model->primaryKey)?$model->primaryKey:"";
    }

    public function actionGetVerifyPk($id){
        $model=Verifyrecord::find()->where(["vco_busid"=>$id])->andWhere(["in","bus_code",["pdtsel","pdtdowmsel","uptepdtsel","pdtreupshelf"]])->orderBy("vco_id desc")->one();
        return isset($model->vco_id)?$model->vco_id:"";
    }

    public function actionGetNextLevel($id){
        if(empty($id)){
            return BsCategory::find()->select("catg_name")->where(["catg_level"=>1,"isvalid"=>1])->asArray()->indexBy("catg_id")->column();
        }
        return BsCategory::find()->select("catg_name")->where(["p_catg_id"=>$id,"isvalid"=>1])->asArray()->indexBy("catg_id")->column();
    }

    public function actionOptions(){
        return BsProduct::getOptions();
    }

}