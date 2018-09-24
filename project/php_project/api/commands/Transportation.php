<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/11/25
 * Time: 下午 02:29
 */

namespace app\commands;

use app\modules\ptdt\models\BsDeliv;
use app\modules\ptdt\models\BsPack;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\ptdt\models\BsShip;
use app\modules\warehouse\models\LqtExpress;
use app\modules\warehouse\models\LqtHead;
use app\modules\warehouse\models\LqtLand;
use yii\base\Exception;

class Transportation
{

    //参数为料号id,目的地省，目的地市，下单数量(该方法为主方法只需调用此方法即可)
    public function getLogisticsCost($prtpkid, $ToProvince, $ToCity, $pdtnum)
    {
        //判断是否免运费,参数为料号id,目的地省，目的地市
        $Isfee = $this->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);
        $model = BsPartno::getPratno($prtpkid);//获取PdtID
        $pdtid = $model['pdt_pkid'];
        $carrytype = $this->getCarryTypeNew($pdtid);//返回运输类型
        if ($carrytype == '-1') {
            return "-1";//此类商品不承运
        } else {
            if ($Isfee) {
                return true;//
            } //不免运费
            else {
                $isspeed = $this->getCarryTypeNew($pdtid);//用来获取运输类型
                $logisticsinfo = $this->getMinLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $isspeed, true);//获取运费最小的运输方式与运费，时效
                // $logisticsinfo = $this->getAllLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $isspeed);//获取全部的运输方式与运费，时效
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
            if ($YnPartialFee = 0) {
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
                return -1;//此类商品属性不承运
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

//获取全部的物流费用
    public function getAllLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $isspeed)
    {
        $Trans = $this->getTransType($isspeed);//获取运输类型
        $lstlogist = array();
        $YnFee = false;
        $YnFee = $this->getJudgeYnFee($prtpkid, $ToProvince, $ToCity);
        foreach ($Trans as $key => $val) {
            //快递的金额≤91的时候，不进行比对陆运
            $hasless91 = false;
            if ($val == '301' || $val == '302') {
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
            $lstcost = $this->getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $val);
            if (count($lstcost) > 0) {
                if ((double)$lstcost[0] != -1) {
                    if ($YnFee) {
                        $loginfo['LogisticsCost'] = 0;
                        $loginfo['Requiretime'] = "";
                        $loginfo['TransMode'] = "";
                    } else {
                        $loginfo['LogisticsCost'] = $lstcost[0];
                        $loginfo['Requiretime'] = $lstcost[1] == "" ? "" : $lstcost[1];
                        $loginfo['TransMode'] = $val;
                    }
                    array_push($lstlogist, $loginfo);
                }
            }
        }
        return $lstlogist;
    }

//获取最小的物流费用
    public function getMinLogisticsInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $isspeed, $IsMin)
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
                if ($val == '301' || $val == '302') {
                    if ($v_temp_cost <= 91 && $v_temp_cost > 0 && $v_temp_cost != -1) {
                        $hasless91 = true;
                        break;
                    }
                }
                if ($hasless91) {
                    break;
                }
                if ($IsMin) {
                    $lstcost = array();
                    $lstcost = $this->getShipInfo($prtpkid, $ToProvince, $ToCity, $pdtnum, $val);
                    if (count($lstcost) > 0) {
                        if ($v_temp_cost == 0 && (double)$lstcost[0] != -1) {
                            $v_temp_cost = (double)($lstcost[0]);
                            $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                            $TransMode = $val;
                        } else {
                            if ($v_temp_cost > (double)$lstcost[0] && (double)$lstcost[0] != -1) {
                                $v_temp_cost = (double)($lstcost[0]);
                                $requireTime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                $TransMode = $val;
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
            array_push($TransType, "201");
            array_push($TransType, "202");
            array_push($TransType, "203");   // 優速快遞
        } else if ($isspeed == 2) {
            array_push($TransType, "203");  // 液体只可用优速
        } else if ($isspeed == 3)   // 含锂电池 经济和优速快递
        {
            array_push($TransType, "202");
            array_push($TransType, "203");
        }
        array_push($TransType, "301");
        array_push($TransType, "302");

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
            foreach ($ship as $k => $v) {
                if ($v["province_no"] != null && $v["city_no"] != null) {
                    $FromProvince = (double)($v["province_no"]);
                    $FromCity = (double)($v["city_no"]);
                    $City = $this->getCity($ToCity, $FromCity);
                    //该单的总重量除以包装数量 = 每一个销售包装重量要小于80KG才可用快递
                    $WeightK = round($weightvolume[0] / $weightvolume[2], 2);//保留两位小数
                    if ($WeightK <= 80) //單件重量不超過80KG
                    {
                        //标准快递、经济快递、优速快递
                        if ($transtype == "201" || $transtype == "202" || $transtype == "203") {
                            //體積重/重量較大者（将材积转换重量比较）round四舍五入，bccomp比较大小
                            $Weight = bccomp($weightvolume[0], round($weightvolume[1] / 6000, 2)) == -1 ? round($weightvolume[1] / 6000, 2) : $weightvolume[0];
                            $lstcost = array();
                            foreach ($City as $ke => $va) {
                                //获取快递运费
                                $lstcost = $this->getEconomyExpressFee($Weight, $FromProvince, $va['fromcity'], $ToProvince, $va['tocity'], '20', $transtype);
                                if ((double)$lstcost[0] != -1) {
                                    break;//当运费不为-1时挑出循环
                                }
                            }
                            if ($v_temp_cost == 0) {
                                $v_temp_cost = round((double)$lstcost[0], 2);
                                $requiretime = $lstcost[1] == "" ? "0" : $lstcost[1];
                            } else if ($v_temp_cost == -1 && (double)$lstcost[0] != -1) {
                                $v_temp_cost = round((double)$lstcost[0], 2);
                                $requiretime = $lstcost[1] == "" ? "0" : $lstcost[1];
                            } else {
                                //如果收货地省没有发货地区的信息就从所有发货地区中获取运费最少的发货区所算出的运费
                                if ((double)$lstcost[0] != -1 && (double)$lstcost[0] < $v_temp_cost) {
                                    $v_temp_cost = round((double)$lstcost[0], 2);
                                    $requiretime = $lstcost[1] == "" ? "0" : $lstcost[1];
                                }
                            }

                        } //普通零擔或快件零擔
                        elseif ($transtype == "301" || $transtype == "302") {
                            //按CBM進行換算為m3
                            //體積重/重量較大者（将材积转换重量比较）round四舍五入，bccomp比较大小
                            $weight = bccomp(round($weightvolume[0] / 250.0, 3), round($weightvolume[1] / 1000000.0, 3)) == -1 ? round($weightvolume[1] / 1000000.0, 3) : round($weightvolume[0] / 250.0, 3);
                            if ($weight < 1.0) {
                                $weight = 1.0;
                            }
                            $lstcmb = array();
                            //获取陆运费用
                            $lstcmb = $this->getLandFee($weight, $FromProvince, $FromCity, $ToProvince, $ToCity, "", $transtype);
                            if ($v_temp_cost == 0) {
                                $v_temp_cost = round((double)$lstcmb[0], 2);
                                $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                            } else if ($v_temp_cost == -1 && (double)$lstcmb[0] != -1) {
                                $v_temp_cost = round((double)$lstcmb[0], 2);
                                $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                            } else {
                                if ((double)$lstcmb[0] != -1 && (double)$lstcmb[0] < $v_temp_cost) {
                                    $v_temp_cost = round((double)$lstcmb[0], 2);
                                    $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                                }
                            }
                        }
                        array_push($LogisticsInfo, $v_temp_cost);
                        array_push($LogisticsInfo, $requiretime);

                    } else {
                        if ($transtype == "301" || $transtype == "302") {
                            $weight = bccomp(round($weightvolume[0] / 250.0, 3), round($weightvolume[1] / 1000000.0, 3)) == -1 ? round($weightvolume[1] / 1000000.0, 3) : round($weightvolume[0] / 250.0, 3);
                            if ($weight < 1.0) {
                                $weight = 1.0;
                            }
                            $lstcmb = array();
                            $lstcmb = $this->getLandFee($weight, $FromProvince, $FromCity, $ToProvince, $ToCity, "", $transtype);
                            if ($v_temp_cost == 0) {
                                $v_temp_cost = round((double)$lstcmb[0], 2);
                                $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                            } else if ($v_temp_cost == -1 && (double)$lstcmb[0] != -1) {
                                $v_temp_cost = round((double)$lstcmb[0], 2);
                                $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
                            } else {
                                if ((double)$lstcmb[0] != -1 && (double)$lstcmb[0] < $v_temp_cost) {
                                    $v_temp_cost = round((double)$lstcmb[0], 2);
                                    $requiretime = (string)((double)($lstcmb[1] == "" ? "0" : $lstcmb[1])) / 24.0;
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
            $VOrdNum = (double)(round($pdtnum, 4)) / (double)$weightnum['pdt_qty'];
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
        $lqtno = LqtHead::getLqtNo($TransMode, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity);
        if (count($lqtno) > 0) {
            $requiretime = $lqtno[0]['TQ_CITY'];//时效
            $Express = LqtExpress::getWeightMin($Weight, $lqtno[0]['lqt_id']);
            if (count($Express) == 1) {
                if ((double)$Express[0]['min_value'] == 0) {
                    $fee = $Express[0]['firstprice'];
                } else {
                    $fee = $Express[0]['firstprice'];//获取首重价格
                    $fee = $fee + (ceil($Weight - (int)$Express[0]['min_value'])) / (double)$Express[0]['nextweight'] * (double)$Express[0]['next_rate'];
                    $pdtMinWeight = (double)$Express[0]["min_value"];//最小区间值
                    $pdtWeightMin = (double)$Express[0]["weightmin"];//首重重量
                    $otherInterval = LqtExpress::getOtherInterval($lqtno[0]['lqt_id'], $pdtMinWeight, $pdtWeightMin);
                    if ($otherInterval != null && count($otherInterval) > 0) {
                        for ($i = 0; $i < count($otherInterval); $i++) {
                            $fee = $fee + (double)$otherInterval[$i]['next_rate'] * ((int)$otherInterval[$i]['max_value'] - (int)$otherInterval[$i]['MIN_VALUE']) / (double)$otherInterval[0]['nextweight'];//计算比重量的最小值还要小的区间值
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
    public function getLandFee($weight, $FromProvince, $FromCity, $ToProvince, $ToCity, $TransMode, $TransType)
    {
        $fee = -1;
        $requiretime = "";
        $LanFee = array();
        $lqtno = LqtHead::getLqtNo($TransMode, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity);
        if (count($lqtno) > 0) {
            foreach ($lqtno as $k => $v) {
                $p_temp_other_fee = 0;
                $p_temp_fee = -1;
                $landinfo = LqtLand::getLandInfo($v['lqt_id']);
                if (count($landinfo) > 0) {
                    foreach ($landinfo as $ke => $va) {
                        if ($va['uom'] == "CBM") {
                            $p_temp_fee = $weight * (double)$va['rate'];
                            if ($p_temp_fee < (double)$va['minicharge'] && (double)$va['maxcharge'] != 0) {
                                $p_temp_fee = (double)$va['minicharge'];
                            }
                            if ($p_temp_fee > (double)$va['maxcharge'] && (double)$va['maxcharge'] != 0) {
                                $p_temp_fee = (double)$va['maxcharge   '];
                            }
                        }
                    }
                    $p_temp_other_fee = $p_temp_other_fee + 0;// 提貨費、送貨費還是原來的50，新拋送的報價提送貨費都為0
                }
                if ($fee == -1) {
                    $fee = $p_temp_fee + $p_temp_other_fee;
                    $requiretime = (string)$v['TQ_CITY'];
                } else if ($fee > ($p_temp_fee + $p_temp_other_fee) && ($p_temp_fee + $p_temp_other_fee) != -1) {
                    $fee = $p_temp_fee + $p_temp_other_fee;
                    $requiretime = (string)$v['TQ_CITY'];
                }
            }
        }
        array_push($LanFee, $fee);
        array_push($LanFee, $requiretime);
        return $LanFee;
    }
}