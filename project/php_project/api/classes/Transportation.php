<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/25
 * Time: 下午 02:29
 */

namespace app\classes;

use app\modules\common\models\BsDistrict;
use app\modules\ptdt\models\BsDeliv;
use app\modules\ptdt\models\BsPack;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\ptdt\models\BsShip;
use app\modules\warehouse\models\LogisticsquoteExpressDetail;
use app\modules\warehouse\models\LogisticsquoteExpressHead;
use app\modules\warehouse\models\LogisticsquoteLandDetail;
use app\modules\warehouse\models\LogisticsquoteLandHead;
use app\modules\warehouse\models\LqtExpress;
use app\modules\warehouse\models\LqtHead;
use app\modules\warehouse\models\LqtLand;
use yii\base\Exception;

class Transportation
{

    //获取运输方式
    public function getAllTansType($prtpkid)
    {
        $model = BsPartno::getPratno($prtpkid);//获取PdtID
        $pdtid = $model['pdt_pkid'];
        $carrytype = $this->getCarryTypeNew($pdtid);//返回运输类型
        if ($carrytype == '-1') {
            return "-1";//此类商品不承运
        } else {
            return $this->getTransType($carrytype);//获取运输类型
        }
    }

    //参数为料号id,目的地省ID，目的地市ID，下单数量，运输方式的編碼(编码如：201,202,203,301,302)(该方法为计算运费的主方法只需调用此方法即可)
    public function getLogisticsCost($prtpkid, $ToProvince, $ToCity, $pdtnum, $TransType)
    {
        //判断是否免运费,参数为料号id,目的地省，目的地市
        $Isfee = $this->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);
        $model = BsPartno::getPratno($prtpkid);//获取PdtID
        $pdtid = $model['pdt_pkid'];
        $carrytype = $this->getCarryTypeNew($pdtid);//返回运输类型
        if ($carrytype != "-1") {
            if ($Isfee) {
                return "0";//免运费
            } //不免运费
            else {
                $logisticsinfo = array();
                $logisticsinfo = $this->getSetLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $TransType);//获取指定运输方式的运费
                return $this->getMimRateLogic($logisticsinfo);//计算含税运费
            }
        }
    }

//计算含税运费
    public function getMimRateLogic($loginfo)
    {
        $Rate = 0.17;
        if ($loginfo != null) {

            if ($loginfo[0]['TransMode'] == "201" || $loginfo[0]['TransMode'] == "202") {
                $loginfo[0]['LogisticsCost'] = round(((double)$loginfo[0]['LogisticsCost'] / 1.06) * (1 + $Rate), 2);
            } else if ($loginfo[0]['TransMode'] == "203") {
                $loginfo[0]['LogisticsCost'] = round((double)$loginfo[0]['LogisticsCost'] * (1 + $Rate), 2);
            } else if ($loginfo[0]['TransMode'] == "301" || $loginfo[0]['TransMode'] == "302") {
                $loginfo[0]['LogisticsCost'] = round((double)$loginfo[0]['LogisticsCost'] * (1 + $Rate), 2);
            }
            if ($loginfo[0]['TransMode'] == "201") {
                $loginfo[0]['TransModeName'] = "标准快递";
            }
            if ($loginfo[0]['TransMode'] == "202") {
                $loginfo[0]['TransModeName'] = "经济快递";
            }
            if ($loginfo[0]['TransMode'] == "203") {
                $loginfo[0]['TransModeName'] = "优速快递";
            }
            if ($loginfo[0]['TransMode'] == "301") {
                $loginfo[0]['TransModeName'] = "普通陆运";
            }
            if ($loginfo[0]['TransMode'] == "302") {
                $loginfo[0]['TransModeName'] = "定日达陆运";
            }
        }
        return $loginfo;
    }

    //判断是否免运费，参数为料号id,目的地省，目的地市
    public function getJudgeYnFee($prtpkid, $ToProvince, $ToCity)
    {
        $Istrue = false;
        $model = BsPartno::getPratno($prtpkid);
        $YnAllFee = $model['yn_free_delivery'];
        if ($YnAllFee == 0)//全免
        {
            $Istrue = true;

        } else if ($YnAllFee == 1)//部分城市免运费
        {
            //免运费收货地信息
            $YnPartialFee = BsDeliv::getDelivCount($prtpkid, $ToProvince, $ToCity);
            if ($YnPartialFee > 0) {
                $Istrue = true;

            }
        } else if ($YnAllFee == 3) //全国部分不免运费
        {
            $YnPartialFee = BsDeliv::getDelivCount($prtpkid, $ToProvince, $ToCity);
            if ($YnPartialFee > 0) {
                $Istrue = true;

            }
        }
        return $Istrue;
    }

    //返回运输类型(是否需考虑快递,商品形态为液体是可考虑优速快递)
    public function getCarryTypeNew($id)
    {
        $product = BsProduct::getProduct($id);
        $TypeA = 0;
        $TypeF = 0;
        if ($product != null) {
            if ($product['pdt_attribute'] == "144" || $product['pdt_attribute'] == "145" || $product['pdt_attribute'] == "146") {
                return "-1";//此类商品属性不承运
            } else {
                //--如屬液體/粉末/汽體/含鋰電池，不可選擇標準快遞和空運   优速快递可快递液体的商品
                if ($product["pdt_form"] == "149")   //(固体)
                {
                    $TypeF = 1;
                } else if ($product["pdt_form"] == "150")    //(液体)
                {
                    $TypeF = 2;
                } else if ($product["pdt_form"] == "153")    //(含鋰電池)
                {
                    $TypeF = 3;
                } else {
                    $TypeF = 0;
                }
                if ($product['pdt_attribute'] == "143")   // 普通物料
                {
                    $TypeA = 1;
                } else if ($product['pdt_attribute'] == "147")    //普通设备
                {
                    $TypeA = 1;
                }
            }
        }
        return $TypeA * $TypeF;
    }

    //获取全部运输方式的物流费用
    public function getAllLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $isspeed)
    {
        $Trans = $this->getTransType($isspeed);//获取运输类型
        $lstlogist = array();
        $YnFee = false;
        $YnFee = $this->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);
        foreach ($Trans as $key => $val) {
            //快递的金额≤91的时候，不进行比对陆运
            $hasless91 = false;
            if ($val['id'] == '301' || $val['id'] == '302') {
                if (count($lstlogist) > 0) {
                    foreach ($lstlogist as $k => $v) {
                        //return gettype($v['LogisticsCost']);
                        if ($v['LogisticsCost'] <= 91 && ($v['TransMode'] == "201" || $v['TransMode'] == "202" || $v['TransMode'] == "203")) {
                            $hasless91 = true;
                            break;
                        }
                    }
                }
            }
            if ($hasless91) {
                break;
            }
            $lstcost = array();
            $loginfo = array();
            $lstcost = $this->getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $val['id']);
            if (count($lstcost) > 0) {
                if ((double)$lstcost[0] != -1) {
                    if ($YnFee) {
                        $loginfo['LogisticsCost'] = 0;
                        $loginfo['Requiretime'] = "";
                        $loginfo['TransMode'] = "";
                    } else {
                        $loginfo['LogisticsCost'] = $lstcost[0];
                        $loginfo['Requiretime'] = $lstcost[1] == "" ? "" : $lstcost[1];
                        $loginfo['TransMode'] = $val['id'];
                    }
                    array_push($lstlogist, $loginfo);
                }
            }
        }
        return $lstlogist;
    }

    //获取指定运输方式的运费
    public function getSetLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $TansType)
    {
        $v_temp_cost = 0;
        $requireTime = "";
        $TransMode = "0";
        $MinLogistInfo = array();
        if ($pdtnum > 0) {
            $lstcost = $this->getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $TansType);
            if (count($lstcost) > 0) {
                $v_temp_cost = (double)($lstcost[0]);
                $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                $TransMode = $TansType;
            }
            $YnFee = false;
            $YnFee = $this->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);
            if ($YnFee) {
                $v_temp_cost = 0;
            } else {
                if ($v_temp_cost == 0 || $v_temp_cost == -1) {
                    throw new Exception("对不起,物流无法到达您所在城市");
                }
            }
        }
        $LogistInfo = array();
        $LogistInfo['LogisticsCost'] = $v_temp_cost;
        $LogistInfo['Requiretime'] = $requireTime;
        $LogistInfo['TransMode'] = $TransMode;
        array_push($MinLogistInfo, $LogistInfo);
        return $MinLogistInfo;
    }

//获取最小的物流费用
    public function getMinLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $isspeed, $IsMin, $TansType)
    {
        $Trans = $this->getTransType($isspeed);//获取运输类型
        $v_temp_cost = 0;
        $requireTime = "";
        $TransMode = "0";
        $MinLogistInfo = array();
        if ($pdtnum > 0) {
            foreach ($Trans as $key => $val) {
                //快递的金额≤91的时候，不进行比对陆运
                $hasless91 = false;
                if ($val['id'] == '301' || $val['id'] == '302') {
                    if ($v_temp_cost <= 91 && $v_temp_cost > 0 && $v_temp_cost != -1) {
                        $hasless91 = true;
                        break;
                    }
                }
                if ($hasless91) {
                    break;
                }
                $lstcost = array();
                if ($IsMin) {
                    $lstcost = $this->getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $val['id']);
                    if (count($lstcost) > 0) {
                        if ($v_temp_cost == 0 && (double)$lstcost[0] != -1) {
                            $v_temp_cost = (double)($lstcost[0]);
                            $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                            $TransMode = $val['id'];
                        } else {
                            if ($v_temp_cost > (double)$lstcost[0] && (double)$lstcost[0] != -1) {
                                $v_temp_cost = (double)($lstcost[0]);
                                $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                $TransMode = $val['id'];
                            }
                        }
                    }
                } //用户选择运输方式
                else {
                    if ($TansType == $val['id']) {
                        $lstcost = $this->getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $val['id']);
                        if (count($lstcost) > 0) {
                            if ($v_temp_cost == 0 && (double)$lstcost[0] != -1) {
                                $v_temp_cost = (double)($lstcost[0]);
                                $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                $TransMode = $val['id'];
                            } else {
                                if ($v_temp_cost > (double)$lstcost[0] && (double)$lstcost[0] != -1) {
                                    $v_temp_cost = (double)($lstcost[0]);
                                    $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                    $TransMode = $val['id'];
                                }
                            }
                        }
                    }
                }
            }
            $YnFee = false;
            $YnFee = $this->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);
            if ($YnFee) {
                $v_temp_cost = 0;
            } else {
                if ($v_temp_cost == 0 || $v_temp_cost == -1) {
                    throw new Exception("对不起,物流无法到达您所在城市");
                }
            }
        }
        //array_push($MinLogistInfo, $v_temp_cost);
        //array_push($MinLogistInfo, $requireTime);
        //array_push($MinLogistInfo, $TransMode);
        $LogistInfo = array();
        $LogistInfo['LogisticsCost'] = $v_temp_cost;
        $LogistInfo['Requiretime'] = $requireTime;
        $LogistInfo['TransMode'] = $TransMode;
        array_push($MinLogistInfo, $LogistInfo);
        return $MinLogistInfo;
    }

//获取运输类型
    public function getTransType($isspeed)
    {
        $TransType = array();
        if ($isspeed == 1) {
//            array_push($TransType, "201");
//            array_push($TransType, "202");
//            array_push($TransType, "203");   // 優速快遞
            $TransType[0]["id"] = 201;
            $TransType[0]["name"] = '标准快递';
            $TransType[1]["id"] = 202;
            $TransType[1]["name"] = '经济快递';
            $TransType[2]["id"] = 203;
            $TransType[2]["name"] = '优速快递';
        } else if ($isspeed == 2) {
//            array_push($TransType, "203");  // 液体只可用优速
            $TransType[0]["id"] = 203;
            $TransType[0]["name"] = '优速快递';
        } else if ($isspeed == 3)   // 含锂电池 经济和优速快递
        {
//            array_push($TransType, "202");
//            array_push($TransType, "203");
            $TransType[0]["id"] = 202;
            $TransType[0]["name"] = '经济快递';
            $TransType[1]["id"] = 203;
            $TransType[1]["name"] = '优速快递';
        }
//        array_push($TransType, "301");
//        array_push($TransType, "302");
        $TransType[3]["id"] = 301;
        $TransType[3]["name"] = '普通陆运';
        $TransType[4]["id"] = 302;
        $TransType[4]["name"] = '定日达陆运';
        return $TransType;
    }

    public function getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $transtype)
    {
        $LogisticsInfo = array();
        $ship = BsShip::getShipInfo($prtpkid, $ToProvince);//获取在收货地省所在的发货地区信息,如果没有就获取所有发货地区信息
        if (count($ship) > 0) {
            $v_temp_cost = 0;
            $requiretime = "0";
            $weightvolume = $this->getWeightAndVolume($prtpkid, $pdtnum);
            $ToProvincePY=BsDistrict::getPYTo($ToProvince)['distinct_enname'];//获取收货地拼音
            $ToCityPY=BsDistrict::getPYTo($ToCity)['distinct_enname'];//获取收货地市拼音
            foreach ($ship as $k => $v) {
                if ($v["province_no"] != null && $v["city_no"] != null) {
                    $FromProvince = (double)($v["province_no"]);
                    $FromCity = (double)($v["city_no"]);

                    $FromProvincePY = BsDistrict::getPYTo($FromProvince)['distinct_enname'];//获取发货地拼音
                    $FromCityPY =BsDistrict::getPYTo($FromCity)['distinct_enname'];//获取发货地市拼音

                    $City = $this->getCity($ToCity, $FromCity);
                    //该单的总重量除以包装数量 = 每一个销售包装重量要小于80KG才可用快递
                    $WeightK = (double)round((double)$weightvolume[0] / (double)$weightvolume[2], 2);//保留两位小数
                    if ((double)$WeightK <= 80 && (double)$WeightK>0) //單件重量不超過80KG
                    {
                        //标准快递、经济快递、优速快递
                        if ($transtype == "201" || $transtype == "202" || $transtype == "203") {
                            //體積重/重量較大者（将材积转换重量比较）round四舍五入，bccomp比较大小若二个字符串一样大则返回 0；
                            //若左边的数字字符串比右边的大则返回 +1；//若左边的数字字符串比右边的小则返回 -1。
                            $Weight = (double)max((double)$weightvolume[0],
                                round((double)$weightvolume[1] / 6000, 2));
                            $lstcost = array();
                            if((double)$Weight>0) {
                                foreach ($City as $ke => $va) {
                                    //获取快递运费
                                    $lstcost = $this->getEconomyExpressFee($Weight, $FromProvince, $va['fromcity'], $ToProvince, $va['tocity'], '20', $transtype);
                                    if ((double)$lstcost[0] != -1) {
                                        break;//当运费不为-1时挑出循环
                                    }
                                }
                                if ($v_temp_cost == 0) {
                                    $v_temp_cost = (double)round((double)$lstcost[0], 2);
                                    $requiretime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                } else if ($v_temp_cost == -1 && (double)$lstcost[0] != -1) {
                                    $v_temp_cost = (double)round((double)$lstcost[0], 2);
                                    $requiretime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                } else {
                                    //如果收货地省没有发货地区的信息就从所有发货地区中获取运费最少的发货区所算出的运费
                                    if ((double)$lstcost[0] != -1 && (double)$lstcost[0] < $v_temp_cost) {
                                        $v_temp_cost = (double)round((double)$lstcost[0], 2);
                                        $requiretime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                    }
                                }
                            }
                        } //普通零擔或快件零擔
                        elseif ($transtype == "301" || $transtype == "302") {
                            //按CBM進行換算為m3
                            //體積重/重量較大者（将材积转换重量比较）round四舍五入，bccomp比较大小
                            $weight = (double)max((double)round((double)$weightvolume[0] / 250.0, 3),
                                (double)round((double)$weightvolume[1] / 1000000.0, 3));
                           if((double)$weight>0) {
                               if ($weight < 1.0) {
                                   $weight = 1.0;
                               }
                               //return $weight;
                               $lstcmb = array();
                               //获取陆运费用
                               $lstcmb = $this->getLandFee($weight, $FromProvince, $FromCity, $ToProvince, $ToCity, $FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY, $transtype);
                               if ($v_temp_cost == 0) {
                                   $v_temp_cost = (double)round((double)$lstcmb[0], 2);
                                   $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                               } else if ($v_temp_cost == -1 && (double)$lstcmb[0] != -1) {
                                   $v_temp_cost = (double)round((double)$lstcmb[0], 2);
                                   $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                               } else {
                                   if ((double)$lstcmb[0] != -1 && (double)$lstcmb[0] < $v_temp_cost) {
                                       $v_temp_cost = (double)round((double)$lstcmb[0], 2);
                                       $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                                   }
                               }
                           }
                        }
                        array_push($LogisticsInfo, $v_temp_cost);
                        array_push($LogisticsInfo, $requiretime);

                    } else {
                        if((double)$WeightK>0) {
                            if ($transtype == "301" || $transtype == "302") {
                                $weight = (double)max((double)round((double)$weightvolume[0] / 250.0, 3),
                                    (double)round((double)$weightvolume[1] / 1000000.0, 3));
                                if ((double)$weight > 0) {
                                    if ($weight < 1.0) {
                                        $weight = 1.0;
                                    }
                                    $lstcmb = array();
                                    $lstcmb = $this->getLandFee($weight, $FromProvince, $FromCity, $ToProvince, $ToCity, $FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY, $transtype);
                                    if ($v_temp_cost == 0) {
                                        $v_temp_cost = (double)round((double)$lstcmb[0], 2);
                                        $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                                    } else if ($v_temp_cost == -1 && (double)$lstcmb[0] != -1) {
                                        $v_temp_cost = (double)round((double)$lstcmb[0], 2);
                                        $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                                    } else {
                                        if ((double)$lstcmb[0] != -1 && (double)$lstcmb[0] < $v_temp_cost) {
                                            $v_temp_cost = (double)round((double)$lstcmb[0], 2);
                                            $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                                        }
                                    }
                                }
                            }
                        }
                        array_push($LogisticsInfo, $v_temp_cost);
                        array_push($LogisticsInfo, $requiretime);
                    }
                }
            }
        } else {
            throw new Exception("没有该商品的发货地区信息，无法发货");
        }
        return $LogisticsInfo;
    }

    //获取商品重量与体积
    public function getWeightAndVolume($prtpkid, $pdtnum)
    {
        $weightnum = BsPack::getWeightAndVolume($prtpkid);
        $weightnummodel = array();
        $v_weight = 0.00;//總重量
        $v_volume = 0.00;//總體積
        $VOrdNum = 0.00; //包装数量
        if (count($weightnum) > 0) {
            $VOrdNum = (double)((double)round((double)$pdtnum, 4)) / (double)$weightnum['pdt_qty'];
            $v_volume = $VOrdNum * (double)$weightnum['pdt_length'] * (double)$weightnum['pdt_width'] * (double)$weightnum['pdt_height'];
            $v_weight = $VOrdNum * (double)$weightnum['pdt_weight'];
        }
        array_push($weightnummodel, $v_weight);
        array_push($weightnummodel, $v_volume);
        array_push($weightnummodel, $VOrdNum);
        return $weightnummodel;

    }

//计算运费时分四种情况
    public function getCity($ToCity, $FromCity)
    {
        $lst = array();
        $m1 = $this->ListAdd($ToCity, $FromCity);
        $m2 = $this->ListAdd('-1', $FromCity);
        $m3 = $this->ListAdd($ToCity, '-1');
        $m4 = $this->ListAdd('-1', '-1');
        array_push($lst, $m1);
        array_push($lst, $m2);
        array_push($lst, $m3);
        array_push($lst, $m4);
        return $lst;

    }

    public function ListAdd($ToCity, $FromCity)
    {
        $ss['tocity'] = $ToCity;
        $ss['fromcity'] = $FromCity;
        return $ss;
    }

//获取快递运费
    public function getEconomyExpressFee($Weight, $FromProvince, $FromCity, $ToProvince, $ToCity, $TransMode, $TransType)
    {
        $fee = -1;
        $requiretime = "";
        $lstcode = array();
        $lqtno = LogisticsquoteExpressHead::getLqtNo($TransMode, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity);
        if (count($lqtno) > 0) {
            $requiretime = $lqtno[0]['TRANSITTIME1'];//时效
            $quotationno = $lqtno[0]['QUOTATIONNO'];//报价单号
            if ($quotationno != '-1') {
                $Express = LogisticsquoteExpressDetail::getWeightMin($Weight, $quotationno);
                if (count($Express) == 1) {
                    if ((double)$Express[0]['MIN_VALUE'] == 0) {
                        $fee = $Express[0]['FIRSTPRICE'];
                    } else {
                        $fee = $Express[0]['FIRSTPRICE'];//获取首重价格
                        $fee = $fee + (ceil($Weight - (int)$Express[0]['MIN_VALUE'])) / (double)$Express[0]['NEXTWEIGHT'] * (double)$Express[0]['NEXT_RATE'];
                        $pdtMinWeight = (double)$Express[0]["MIN_VALUE"];//最小区间值
                        $pdtWeightMin = (double)$Express[0]["WEIGHTMIN"];//首重重量
                        $otherInterval = LogisticsquoteExpressDetail::getOtherInterval($lqtno[0]['QUOTATIONNO'], $pdtMinWeight, $pdtWeightMin);
                        if ($otherInterval != null && count($otherInterval) > 0) {
                            for ($i = 0; $i < count($otherInterval); $i++) {
                                $fee = $fee + (double)$otherInterval[$i]['NEXT_RATE'] * ((int)$otherInterval[$i]['MAX_VALUE'] - (int)$otherInterval[$i]['MIN_VALUE']) / (double)$otherInterval[0]['NEXTWEIGHT'];//计算比重量的最小值还要小的区间值
                            }
                        }
                    }
                }
            }
        }
        array_push($lstcode, $fee);
        array_push($lstcode, $requiretime);
        return $lstcode;
    }

    //获取陆运运费
    public function getLandFee($weight, $FromProvince, $FromCity, $ToProvince, $ToCity,$FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY, $TransType)
    {
        $fee = -1;
        $requiretime = "";
        $LanFee = array();
        $lqtno = LogisticsquoteLandHead::getLqtNo($TransType, $FromProvince, $FromCity, $ToProvince, $ToCity,$FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY);
        if (count($lqtno) > 0) {
            foreach ($lqtno as $k => $v) {
                $p_temp_other_fee = 0;
                $p_temp_fee = -1;
                $landinfo = LogisticsquoteLandDetail::getLandInfo($v['SALESQUOTATIONID']);
                if (count($landinfo) > 0) {
                    foreach ($landinfo as $ke => $va) {
                        if ($va['UOM'] == "CBM") {
                            $p_temp_fee = $weight * (double)$va['RATE'];
                            if ($p_temp_fee < (double)$va['MINICHARGE'] && (double)$va['MAXCHARGE'] != 0) {
                                $p_temp_fee = (double)$va['MINICHARGE'];
                            }
                            if ($p_temp_fee > (double)$va['MAXCHARGE'] && (double)$va['MAXCHARGE'] != 0) {
                                $p_temp_fee = (double)$va['MAXCHARGE   '];
                            }
                        }
                    }
                    $p_temp_other_fee = $p_temp_other_fee + 0;// 提貨費、送貨費還是原來的50，新拋送的報價提送貨費都為0
                }
                if ($fee == -1) {
                    $fee = $p_temp_fee + $p_temp_other_fee;
                    $requiretime = (string)$v['TIMEREQUIRE'];
                } else if ($fee > ($p_temp_fee + $p_temp_other_fee) && ($p_temp_fee + $p_temp_other_fee) != -1) {
                    $fee = $p_temp_fee + $p_temp_other_fee;
                    $requiretime = (string)$v['TIMEREQUIRE'];
                }
            }
        }
        array_push($LanFee, $fee);
        array_push($LanFee, $requiretime);
        return $LanFee;
    }
}