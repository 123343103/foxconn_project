<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/12/14
 * Time: 下午 03:37
 */

namespace app\modules\warehouse\controllers;

use app\classes\Transportation;
use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\show\BsDistrictShow;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\warehouse\models\LogisticsquoteExpressDetail;
use app\modules\warehouse\models\LogisticsquoteExpressHead;
use app\modules\warehouse\models\LogisticsquoteLandDetail;
use app\modules\warehouse\models\LogisticsquoteLandHead;
use Yii;

class FreightCalculationController extends BaseActiveController
{
    public $modelClass = true;

    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        $sql = "SELECT
	p.part_no,
	p.tp_spec,
	t.pdt_name,
	b.brand_name_cn,
	pu.bsp_svalue unit,
	IFNULL(pa.pdt_length,0) pdt_length,
	IFNULL(pa.pdt_width,0) pdt_width,
	IFNULL(pa.pdt_height,0) pdt_height,
	IFNULL(pa.pdt_weight,0) pdt_weight,
	pa.pdt_qty,
	IFNULL(pa.net_weight,0) net_weight
FROM
	pdt.bs_partno p
LEFT JOIN pdt.bs_product t ON p.pdt_pkid = t.pdt_pkid
LEFT JOIN pdt.bs_brand b ON t.brand_id = b.brand_id
LEFT JOIN erp.bs_pubdata pu ON t.unit = pu.bsp_id
LEFT JOIN pdt.bs_pack pa ON p.prt_pkid = pa.prt_pkid
WHERE
	p.part_no = '{$param['part_no']}'
AND pa.pck_type = 2";
        return Yii::$app->getDb('pdt')->createCommand($sql)->queryOne();
    }

    //获取发货地信息
    public function actionShipInfo($partno)
    {
        $sql = "select prt_pkid from pdt.bs_partno where part_no='{$partno}'";
        $prtpkid = Yii::$app->getDb('pdt')->createCommand($sql)->queryOne()['prt_pkid'];
        if ($prtpkid == null) {
            $prtpkid = 0;
        }
        $sqls = "select province_no,city_no,province_name,city_name from pdt.bs_ship where prt_pkid={$prtpkid}";
        return Yii::$app->getDb('pdt')->createCommand($sqls)->queryAll();
    }

    /*所在地区一级省份*/
    public function actionDistrict()
    {
        return BsDistrict::getDisProvince();
    }
    //获取子类地址
    public function actionChild($id)
    {
        return BsDistrictShow::getChildByParentId($id);
    }
    //运费试算结果
    public function actionFreight()
    {
        $param = Yii::$app->request->queryParams;
        $trans = new Transportation();
        $Partno=$param['partno'];//料号
        $sql="select p.prt_pkid from pdt.bs_partno p where p.part_no='{$Partno}'";
        $re=Yii::$app->getDb('pdt')->createCommand($sql)->queryOne();
        $prtpkid = $re['prt_pkid'];//料号的ID
        $FromProvince = $param['FromProvince'];//起始地省
        $FromCity = $param['FromCity'];//起始地市
        $ToProvince = $param['ToProvince'];//目的地省
        $ToCity = $param['ToCity'];//目的地市
        $pdtnum = $param['pdtnum'];//数量
        $TransType = $param['TransType'];//运输类型
        $WeightAndVolume = $trans->getWeightAndVolume($prtpkid, $pdtnum);//获取料号重量与体积
        $Weight = round((double)$WeightAndVolume[0],2);//总重量
        $Volume = round((double)$WeightAndVolume[1],2);//总体积
//        $Weight =  $this->sctonum((double)$WeightAndVolume[0],2);//总重量
//        $Volume =  $this->sctonum((double)$WeightAndVolume[1],2);//总体积
        $Isfee = $trans->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);//是否免运费
        $isExpress = false;
        $YNpdt = false;
        $ToProvincePY = BsDistrict::getPYTo($ToProvince)['distinct_enname'];//获取收货地拼音
        $ToCityPY = BsDistrict::getPYTo($ToCity)['distinct_enname'];//获取收货地市拼音
        $FromProvincePY = BsDistrict::getPYTo($FromProvince)['distinct_enname'];//获取发货地拼音
        $FromCityPY = BsDistrict::getPYTo($FromCity)['distinct_enname'];//获取发货地市拼音
        $YNpdt = $this->CheckPdtModel($TransType, $prtpkid);
        $isExpress = $this->IsProdType($prtpkid);
        $calweight="";//计费重量
        $calculationFun="";//含税价格
        $path="";//计费路径
        $NoTax="";//未税价格
        $frontdesk="";//前台显示价格
        $HasFee=false;//是否报价
        //计费重量
        if ($param['TransType'] == '201' || $param['TransType'] == '202') {
            $CalWeight = (double)round(max((double)$WeightAndVolume[0],
                round((double)$WeightAndVolume[1] / 6000, 2)),2);//获取最大的数据
            $calweight = "max(" . (double)$WeightAndVolume[1]
                . "/6000," . (double)round((double)$WeightAndVolume[0],2) . ")=" . $CalWeight;//计费重量
            //该单的总重量除以包装数量 = 每一个销售包装重量要小于80KG才可用快递
            $WeightK = (double)round((double)$WeightAndVolume[0] / (double)$WeightAndVolume[2], 2);//保留两位小数
//            $WeightK = (double)$this->sctonum((double)$this->sctonum($WeightAndVolume[0],2) / (double)$this->sctonum($WeightAndVolume[2],2), 2);//保留两位小数
            $modellist=null;
            if ((double)$WeightK <= 80&&(double)$WeightK >0&&(double)$CalWeight>0&&!$Isfee) {
                //计费路径
                if ($YNpdt==1 && $isExpress==1) {
                    $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity);
                    $path = "市--市";
                    if (count($modellist) == 0) {
                        $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromCity, $ToProvince, $ToProvince);
                        $path = "市--省";
                        if (count($modellist) == 0) {
                            $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromProvince, $ToProvince, $ToCity);
                            $path = "省--市";
                            if (count($modellist) == 0) {
                                $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromProvince, $ToProvince, $ToProvince);
                                $path = "省--省";
                            }
                        }
                    }
                }
            }
            //含税价格
            if (count($modellist) == 1) {
                if ((double)$modellist[0]['MIN_VALUE'] == 0) {
                    $fee = (double)$modellist[0]['FIRSTPRICE'];
                    $calculationFun = $modellist[0]['FIRSTPRICE'];
                } else {
                    $fee = $modellist[0]['FIRSTPRICE'];//获取首重价格
                    $calculationFun = $fee . "+(" . ceil(round($CalWeight,2) - (int)$modellist[0]['MIN_VALUE']) . "/" . (double)$modellist[0]['NEXTWEIGHT'] . ")*" . (double)$modellist[0]['NEXT_RATE'];
                    $fee = $fee + (ceil(round($CalWeight,2) - (int)$modellist[0]['MIN_VALUE'])) / (double)$modellist[0]['NEXTWEIGHT'] * (double)$modellist[0]['NEXT_RATE'];
                    $pdtMinWeight = (double)$modellist[0]["MIN_VALUE"];//最小区间值
                    $pdtWeightMin = (double)$modellist[0]["WEIGHTMIN"];//首重重量
                    $otherInterval = LogisticsquoteExpressDetail::getOtherInterval($modellist[0]['QUOTATIONNO'], $pdtMinWeight, $pdtWeightMin);
                    if ($otherInterval != null && count($otherInterval) > 0) {
                        for ($i = 0; $i < count($otherInterval); $i++) {
                            $fee = $fee + (double)$otherInterval[$i]['NEXT_RATE'] * ((int)$otherInterval[$i]['MAX_VALUE'] - (int)$otherInterval[$i]['MIN_VALUE']) / (double)$otherInterval[0]['NEXTWEIGHT'];//计算比重量的最小值还要小的区间值
                            $calculationFun .="+" . ((int)$otherInterval[$i]['MAX_VALUE'] - (int)$otherInterval[$i]['MIN_VALUE']) . "*". $otherInterval[$i]['NEXT_RATE']."/".(double)$otherInterval[0]['NEXTWEIGHT'];
                        }
                    }
                    $calculationFun .= "=" . round($fee,2) . "元";//用于显示在页面的含税公式
                }
                $HasFee=true;//是否有报价
                //未税价格 = 含税价 / 1.06
                $TotalNoTax = round(round($fee,2) / (1 + 0.06),2);
                $NoTax = round($fee,2) . "/(1+6%)=" . $TotalNoTax;//用于显示在页面的未税公式
                //前台显示的价格
                $prices = round($TotalNoTax * (1 + 0.17),2);
                $frontdesk = $TotalNoTax . "(未税价)*(1+%17)=" . $prices;//用于显示在页面的前台应该显示的价格
            } else {
                $HasFee=false;//是否有报价
                $path = "--";
                $calculationFun = "无报价资料!";
                if ($WeightK > 80) {
                    $path = "无法快递";
                    $calculationFun = "单一包装重量>80KG";
                } elseif ($isExpress==0) {
                    $path = "无法快递";
                    $calculationFun = "商品属性不适合运送";
                } elseif ($YNpdt==0) {
                    $path = "无法快递";
                    $calculationFun = "商品形态不适合运送";
                }elseif ((double)$WeightK<=0&&(double)$CalWeight>0)
                {
                    $path = "无法快递";
                    $calculationFun = "商品包装信息异常";
                }elseif ($Isfee){
                    $path = "--";
                    $calculationFun = "免运费";
                    $NoTax=0;
                    $frontdesk=0;
                }

            }
        }
        elseif ($param['TransType'] == '301' || $param['TransType'] == '302') {
            $CalWeight = (double)max(max(round((double)$WeightAndVolume[0] / 250.0, 3),
                round($WeightAndVolume[1]/ 1000000.0, 3)), 1);//获取最大的数据
            $calweight = "max(max(" . (double)round($WeightAndVolume[1],3) . "/1000000,"
                . (double)round($WeightAndVolume[0],3) . "/250),1)=" . $CalWeight;
            $isExpress = $this->IsProdType($prtpkid);
            $landmodel=null;
            if ($isExpress==1&&(double)$CalWeight>0&&!$Isfee) {
                $landmodel = $this->getLanFee($TransType, $FromProvince, $FromCity, $ToProvince, $ToCity, $FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY);
                $path = "市--市";
                if (count($landmodel) == 0) {
                    $landmodel = $this->getLanFee($TransType, $FromProvince, $FromCity, $ToProvince, $ToProvince, $FromProvincePY, $FromCityPY, $ToProvincePY, $ToProvincePY);
                    $path = "市--省";
                    if (count($landmodel) == 0) {
                        $landmodel = $this->getLanFee($TransType, $FromProvince, $FromProvince, $ToProvince, $ToCity, $FromProvincePY, $FromProvincePY, $ToProvincePY, $ToCityPY);
                        $path = "省--市";
                        if (count($landmodel) == 0) {
                            $landmodel = $this->getLanFee($TransType, $FromProvince, $FromProvince, $ToProvince, $ToProvince, $FromProvincePY, $FromProvincePY, $ToProvincePY, $ToProvincePY);
                            $path = "省--省";
                        }
                    }
                }
            }
            if ($landmodel != null) {
                $fee = -1;
                $p_temp_other_feet = 0;
                $p_temp_other_fees = 0;
                $p_temp_other_fee=0;
                $p_temp_fee = -1;
                $unitfee=0;
                $minfee=0;
                $maxfee=0;
                if (count($landmodel) > 0) {
                    foreach ($landmodel as $ke => $va) {
                        if ($va['UOM'] == "CBM") {
                            $p_temp_fee = $CalWeight * (double)$va['RATE'];
                            $minfee=(double)$va['MINICHARGE'];
                            $maxfee=(double)$va['MAXCHARGE'];
                            $unitfee=(double)$va['RATE'];
                            if ($p_temp_fee < (double)$va['MINICHARGE'] && (double)$va['MAXCHARGE'] != 0) {
                                $p_temp_fee = (double)$va['MINICHARGE'];
                            }
                            if ($p_temp_fee > (double)$va['MAXCHARGE'] && (double)$va['MAXCHARGE'] != 0) {
                                $p_temp_fee = (double)$va['MAXCHARGE'];
                            }
                        }
                    }
                    $p_temp_other_feet = 0;// 提貨費、送貨費還是原來的50，新拋送的報價提送貨費都為0
                    $p_temp_other_fees = 0;// 提貨費、送貨費還是原來的50，新拋送的報價提送貨費都為0
                    $p_temp_other_fee = $p_temp_other_feet+$p_temp_other_fees;// 提貨費、送貨費還是原來的50，新拋送的報價提送貨費都為0
                }
                if ($fee == -1) {
                    $fee = $p_temp_fee + $p_temp_other_fee;
                } else if ($fee > ($p_temp_fee + $p_temp_other_fee) && ($p_temp_fee + $p_temp_other_fee) != -1) {
                    $fee = $p_temp_fee + $p_temp_other_fee;
                }
                $NoTax=$p_temp_other_feet."(提货费)+".$p_temp_other_fees."(送货费)+".round($fee-$p_temp_other_fee,2)."(".$unitfee."/CBM";
                if($minfee>0)
                {
                    $NoTax.=",最低收费:".$minfee."元";
                }
                if($maxfee>0)
                {
                    $NoTax.=",最高收费:".$maxfee."元";
                }
                $HasFee=true;//是否有报价
                $NoTax.=")=".round($fee,2)."元";//未税价格
                //含税价=未税价*（1+11%）
                $taxfee=round(round($fee,2)*(1+0.11),2);//含税价格
                $calculationFun=round($fee,2)."*(1+11%)=".$taxfee;//含税价格公式
                $frontdesk=round($fee,2)."(未税价)*(1+17%)=".round(round($fee,2)*(1+0.17),2);//前台显示价格
            }
            else{
                $HasFee=false;//是否有报价
                $path="--";
                $calculationFun="无报价资料！";
                if($isExpress==0)
                {
                  $path="无法快递";
                  $calculationFun="商品属性不适合陆运";
                }elseif ((double)$CalWeight<=0)
                {
                    $path = "无法快递";
                    $calculationFun = "商品包装信息异常";
                }elseif ($Isfee){
                    $path = "--";
                    $calculationFun = "免运费";
                    $NoTax=0;
                    $frontdesk=0;
                }
            }
        }
        elseif ($param['TransType'] == '203') {
            $CalWeight = (double)max((double)round($WeightAndVolume[0],2),
                (double)round((double)$WeightAndVolume[1] / 6000, 2));//获取最大的数据
            $calweight = "max(" . (double)$WeightAndVolume[1] . "/6000," . (double)round($WeightAndVolume[0],2) . ")=" . $CalWeight;
            //该单的总重量除以包装数量 = 每一个销售包装重量要小于80KG才可用快递
            $WeightK = (double)round((double)$WeightAndVolume[0] / (double)$WeightAndVolume[2], 2);//保留两位小数
            $modellist=null;
            if((double)$WeightK<=80&&(double)$WeightK>0&&(double)$CalWeight>0&&!$Isfee)
           {
               //计费路径
               if ($YNpdt && $isExpress) {
                   $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity);
                   $path = "市--市";
                   if (count($modellist) == 0) {
                       $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromCity, $ToProvince, $ToProvince);
                       $path = "市--省";
                       if (count($modellist) == 0) {
                           $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromProvince, $ToProvince, $ToCity);
                           $path = "省--市";
                           if (count($modellist) == 0) {
                               $modellist = $this->getExpressInfo($CalWeight, "20", $TransType, $FromProvince, $FromProvince, $ToProvince, $ToProvince);
                               $path = "省--省";
                           }
                       }
                   }
               }
           }
            //含税价格
            if (count($modellist) == 1) {
                if ((double)$modellist[0]['MIN_VALUE'] == 0) {
                    $fee = (double)$modellist[0]['FIRSTPRICE'];
                    $NoTax = $modellist[0]['FIRSTPRICE'];
                } else {
                    $fee = $modellist[0]['FIRSTPRICE'];//获取首重价格
                    $NoTax = $fee . "+(" . ceil($CalWeight - (int)$modellist[0]['MIN_VALUE']) . "/" . (double)$modellist[0]['NEXTWEIGHT'] . ")*" . (double)$modellist[0]['NEXT_RATE'];
                    $fee = $fee + (ceil($CalWeight - (int)$modellist[0]['MIN_VALUE'])) / (double)$modellist[0]['NEXTWEIGHT'] * (double)$modellist[0]['NEXT_RATE'];
                    $pdtMinWeight = (double)$modellist[0]["MIN_VALUE"];//最小区间值
                    $pdtWeightMin = (double)$modellist[0]["WEIGHTMIN"];//首重重量
                    $otherInterval = LogisticsquoteExpressDetail::getOtherInterval($modellist[0]['QUOTATIONNO'], $pdtMinWeight, $pdtWeightMin);
                    if ($otherInterval != null && count($otherInterval) > 0) {
                        for ($i = 0; $i < count($otherInterval); $i++) {
                            $fee = $fee + (double)$otherInterval[$i]['NEXT_RATE'] * ((int)$otherInterval[$i]['MAX_VALUE'] - (int)$otherInterval[$i]['MIN_VALUE']) / (double)$otherInterval[0]['NEXTWEIGHT'];//计算比重量的最小值还要小的区间值
                            $NoTax .="+" . ((int)$otherInterval[$i]['MAX_VALUE'] - (int)$otherInterval[$i]['MIN_VALUE']) . "*". $otherInterval[$i]['NEXT_RATE']."/".(double)$otherInterval[0]['NEXTWEIGHT'];
                        }
                    }
                    $NoTax .= "=" . round($fee,2) . "元";//用于显示在页面的未税公式
                }
                $HasFee=true;//是否有报价
                //含税价格 = 未税价* 1.06
                $Tax = round(round($fee,2) * (1 + 0.06),2);
                $calculationFun = round($fee,2) . "*(1+6%)=" . $Tax;//用于显示在页面的含税公式
                //前台显示的价格
                $prices = round(round($fee,2) * (1 + 0.17),2);
                $frontdesk = round($fee,2) . "(未税价)*(1+%17)=" . $prices;//用于显示在页面的前台应该显示的价格
            } else {
                $HasFee=false;//是否有报价
                $path = "--";
                $calculationFun = "无报价资料!";
                if ($WeightK > 80) {
                    $path = "无法快递";
                    $calculationFun = "单一包装重量>80KG";
                } elseif (!$isExpress) {
                    $path = "无法快递";
                    $calculationFun = "商品属性不适合运送";
                } elseif (!$YNpdt) {
                    $path = "无法快递";
                    $calculationFun = "商品形态不适合运送";
                }elseif ((double)$CalWeight<=0&&(double)$WeightK>0)
                {
                    $path = "无法快递";
                    $calculationFun = "商品包装信息异常";
                }elseif ($Isfee){
                    $path = "--";
                    $calculationFun = "免运费";
                    $NoTax=0;
                    $frontdesk=0;
                }
            }
        }
       $list['Weight']=$Weight;//总重量
       $list['Volume']=$Volume;//总体积
       $list['calweight']=$calweight;//计费重量
       $list['path']=$path;//计费路径
       $list['calculationFun']=$calculationFun;//含税价格
       $list['NoTax']=$NoTax;//未税价格
       $list['frontdesk']=$frontdesk;//前台显示价格
       $list['HasFee']=$HasFee;//是否报价
        return $list;
    }

    //获取报价信息
    public function getExpressInfo($Weight, $TransModel, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity)
    {
        $headlist = LogisticsquoteExpressHead::getLqtNo($TransModel, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity);
        if (count($headlist) > 0) {
            $quotationno = $headlist[0]['QUOTATIONNO'];//报价单号
            if ($quotationno != "-1") {
                $Express = LogisticsquoteExpressDetail::getWeightMin($Weight,$quotationno);
                return $Express;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    //获取陆运报价信息
    public function getLanFee($TransType, $FromProvince, $FromCity, $ToProvince, $ToCity, $FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY)
    {
        $headlist = LogisticsquoteLandHead::getLqtNo($TransType, $FromProvince, $FromCity, $ToProvince, $ToCity, $FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY);
        if (count($headlist) > 0) {
            $Landlist = LogisticsquoteLandDetail::getLandInfo($headlist[0]['SALESQUOTATIONID']);
            if (count($Landlist) > 0) {
                return $Landlist;
            } else {
                return $Landlist;
            }
        } else {
            return null;
        }
    }

    ///// 內容：根據商品形態，決定是否可運輸。
    /// 标准快递:固體
    /// 经济快递:固體、含鋁電池
    /// 优速快递:固體、液體、含鋁電池
    /// 陸運：固體、液體、粉末、含鋁電池
    public function CheckPdtModel($TansType, $id)
    {
        $model = BsPartno::getPratno($id);//获取PdtID
        $pdtid = $model['pdt_pkid'];
        $product = BsProduct::getProduct($pdtid);
        $YN = 0;
        switch ($TansType) {
            case "201":
                //标准快递:固體
                if ($product["pdt_form"] == "149") {
                    $YN = 1;
                }
                break;
            case "202":
                //经济快递:固體、含鋁電池
                if ($product["pdt_form"] == "149" || $product["pdt_form"] == "153") {
                    $YN = 1;
                }
                break;
            case "203":
                //优速快递:固體、液體、含鋁電池
                if ($product["pdt_form"] == "149" || $product["pdt_form"] == "150" || $product["pdt_form"] == "153") {
                    $YN = 1;
                }
                break;
        }
        return $YN;
    }

    /// 內容：判斷商品屬性。快遞隻能運送：普通物料、普通設備
    public function IsProdType($id)
    {
        $PA = 0;
        $model = BsPartno::getPratno($id);//获取PdtID
        $pdtid = $model['pdt_pkid'];
        $product = BsProduct::getProduct($pdtid);
        ///普通物料:143;普通設備:147
        if ($product['pdt_attribute'] == "143" || $product['pdt_attribute'] == "147") {
            $PA = 1;
        }
        return $PA;
    }
    //将科学计数法转换为浮点数保留小数位
    public function sctonum($num, $double = 0){
       $nums=round($num,2);
        if(false !== stripos($num, "e")){
            $a = explode("e",strtolower($num));
            $nums= bcmul($a[0], bcpow(10, $a[1], $double), $double);
        }
        return $nums;
    }
public function  actionTest($num,$doub)
{
    $CalWeight = bccomp($this->sctonum((double)$this->sctonum(40,3) / 250.0, 3),
        $this->sctonum((double)$this->sctonum(0,3) / 1000000.0, 3)) == -1 ?
        $this->sctonum((double)$this->sctonum(0,3) / 1000000.0, 3) :
        $this->sctonum((double)$this->sctonum(40,3) / 250.0, 3);
    return max(0.16,1);
}
}

