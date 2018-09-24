<?php

namespace app\modules\sale\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsCurrency;
use app\modules\crm\models\CrmEmployee;
use app\modules\crm\models\CrmStoresinfo;
use app\modules\sale\models\SaleDetails;
use app\modules\sale\models\SaleDetailsTemp;
use app\modules\sale\models\SaleOperateCost;
use app\modules\sale\models\SaleSalercost;
use app\modules\sale\models\SaleStorecost;
use app\modules\sale\models\search\BsExchangeRateSearch;
use app\modules\sale\models\search\CommissionRateSearch;
use app\modules\sale\models\search\CrmSaleRolesSearch;
use app\modules\sale\models\search\OperateCostSearch;
use app\modules\sale\models\search\SaleDetailsSearch;
use app\modules\sale\models\SaleDetailsSum;
use app\modules\sale\models\search\SaleDetailsSumSearch;
use app\modules\sale\models\search\SaleDetailsTempSearch;
use app\modules\sale\models\search\SellerCostSearch;
use app\modules\sale\models\search\StoreCostSearch;
use yii\helpers\BaseJson;
use yii;

class SaleCommisionController extends BaseActiveController
{
    public $modelClass = 'app\modules\sale\models\SaleDetails';

    public function actionIndex()
    {
        $param = Yii::$app->request->queryParams;
        $start = date('Y-m-d', strtotime(date('Y-m-01', strtotime(date('Y-m-d'),time())) . ' -1 month'));
        $end = date('Y-m-d', strtotime(date('Y-m-01', strtotime(date('Y-m-d'),time())) . ' -1 day'));
        $param['SaleDetailsSearch']['saleStartDate'] = isset($param['SaleDetailsSearch']['saleStartDate']) ? $param['SaleDetailsSearch']['saleStartDate'] : $start;
        $param['SaleDetailsSearch']['saleEndDate'] = isset($param['SaleDetailsSearch']['saleEndDate']) ? $param['SaleDetailsSearch']['saleEndDate'] : $end;
//        return $param;
        $model = new SaleDetailsSearch();
        $dataProvider = $model->search($param);
        $model = $dataProvider->getModels();
        $list['rows'] = $model;
        $list['total'] = $dataProvider->totalCount;
        return $list;
    }

    /**
     * 插入订单详细數據 sale_details_temp
     *
     */
    public function actionImportSaleDetails()
    {
        static $succ = 0;
        static $err = 0;
        $arr = Yii::$app->request->post();
        SaleDetailsTemp::deleteAll();
        foreach ($arr as $k => $v) {
            //跳过第一列标题
            if ($k >= 2) {
                $saleDetailsTemp = new SaleDetailsTemp();
                $saleDetailsTemp->sdl_type = isset($v["A"]) ? $v["A"] : null;
                $saleDetailsTemp->sdl_comp = isset($v["B"]) ? $v["B"] : null;
                $saleDetailsTemp->part_no = isset($v["C"]) ? $v["C"] : null;
                $saleDetailsTemp->cust_code = isset($v["D"]) ? $v["D"] : null;
                $saleDetailsTemp->cust_shortname = isset($v["E"]) ? $v["E"] : null;
                $saleDetailsTemp->sdl_sacode = isset($v["F"]) ? $v["F"] : null;
                $saleDetailsTemp->sdl_saname = isset($v["G"]) ? $v["G"] : null;
                $saleDetailsTemp->produce_org = isset($v["H"]) ? $v["H"] : null;
                $saleDetailsTemp->sale_date = isset($v["I"]) ? $v["I"] : null;
                $saleDetailsTemp->sale_code = isset($v["J"]) ? $v["J"] : null;
                $saleDetailsTemp->recede_code = isset($v["K"]) ? $v["K"] : null;
                $saleDetailsTemp->sale_type = isset($v["L"]) ? $v["L"] : null;
                $saleDetailsTemp->sdl_qty = isset($v["M"]) ? $v["M"] : null;
                $saleDetailsTemp->sdl_unit = isset($v["N"]) ? $v["N"] : null;
                $saleDetailsTemp->unit_cvs = isset($v["O"]) ? $v["O"] : null;
                $saleDetailsTemp->unit_price = isset($v["P"]) ? $v["P"] : null;
                $saleDetailsTemp->cur_code = isset($v["Q"]) ? $v["Q"] : null;
                $saleDetailsTemp->bill_oamount = isset($v["R"]) ? $v["R"] : null;
                $saleDetailsTemp->bill_camount = isset($v["S"]) ? $v["S"] : null;
                $saleDetailsTemp->stan_cost = isset($v["T"]) ? $v["T"] : null;
                $saleDetailsTemp->sale_cost = isset($v["U"]) ? $v["U"] : null;
//                    return $v;
//                    return $saleDetails->attributes;
                $result = $saleDetailsTemp->save();
//                return $saleDetailsTemp;
                if ($result) {
                    $succ++;
                } else {
                    $err++;
                }
            }
        }
        return $this->success('成功导入<span class="red">' . $succ . '<span>条数据,失敗<span class="red">' . $err . '<span>条');
    }

    /*将销单明细临时表数据转移到销单明细表*/
    function actionTempToDetail() {
        static $succ = 0;
        static $err = 0;
        $temp = SaleDetailsTemp::find()->all();
//        return $temp[0];
        foreach ($temp as $k => $v) {
            //跳过第一列标题
            // sale_code,part_no 是唯一的
            $saleDetails = SaleDetails::find()->where(['sale_code' => $v['sale_code'], 'part_no' => $v['part_no']])->one();
            if (!$saleDetails) {
                $saleDetails = new SaleDetails();
            }
            $saleDetails->sdl_type = isset($v["sdl_type"]) ? $v["sdl_type"] : null;
            $saleDetails->sdl_comp = isset($v["sdl_comp"]) ? $v["sdl_comp"] : null;
            $saleDetails->part_no = isset($v["part_no"]) ? $v["part_no"] : null;
            $saleDetails->cust_code = isset($v["cust_code"]) ? $v["cust_code"] : null;
            $saleDetails->cust_shortname = isset($v["cust_shortname"]) ? $v["cust_shortname"] : null;
            $saleDetails->sdl_sacode = isset($v["sdl_sacode"]) ? $v["sdl_sacode"] : null;
            $saleDetails->sdl_saname = isset($v["sdl_saname"]) ? $v["sdl_saname"] : null;
            $saleDetails->produce_org = isset($v["produce_org"]) ? $v["produce_org"] : null;
            $saleDetails->sale_date = isset($v["sale_date"]) ? $v["sale_date"] : null;
            $saleDetails->sale_code = isset($v["sale_code"]) ? $v["sale_code"] : null;
            $saleDetails->recede_code = isset($v["recede_code"]) ? $v["recede_code"] : null;
            $saleDetails->sale_type = isset($v["sale_type"]) ? $v["sale_type"] : null;
            $saleDetails->sdl_qty = isset($v["sdl_qty"]) ? $v["sdl_qty"] : null;
            $saleDetails->sdl_unit = isset($v["sdl_unit"]) ? $v["sdl_unit"] : null;
            $saleDetails->unit_cvs = isset($v["unit_cvs"]) ? $v["unit_cvs"] : null;
            $saleDetails->unit_price = isset($v["unit_price"]) ? $v["unit_price"] : null;
            $saleDetails->cur_code = isset($v["cur_code"]) ? $v["cur_code"] : null;
            $saleDetails->bill_oamount = isset($v["bill_oamount"]) ? $v["bill_oamount"] : null;
            $saleDetails->bill_camount = isset($v["bill_camount"]) ? $v["bill_camount"] : null;
            $saleDetails->stan_cost = isset($v["stan_cost"]) ? $v["stan_cost"] : null;
            $saleDetails->sale_cost = isset($v["sale_cost"]) ? $v["sale_cost"] : null;
//                    return $v;
//                    return $saleDetails->attributes;
//                    return $saleDetails;
            $result = $saleDetails->save();
            if ($result) {
                $succ++;
            } else {
                $err++;
            }
        }
//        return json_encode('abc');
        return $this->success('成功导入<span class="red">' . $succ . '<span>条数据,失敗<span class="red">' . $err . '<span>条');
    }

    /*插入人力薪资*/
    public function actionImportSellerCost()
    {
        static $succ = 0;
        static $err = 0;
        $arr = Yii::$app->request->post();
//        return $arr;
        foreach ($arr as $k => $v) {
            if (strlen($v["A"]) === 6) { // 判断六位年月
                $sellerCost = SaleSalercost::find()->where(['ssc_no'=>$v["B"], 'ssc_year'=>substr($v["A"], 0, 4), 'ssc_month'=>substr($v["A"], 4, 5)])->one();
                if (!$sellerCost) {
                    $sellerCost = new SaleSalercost();
                }
                $sellerCost->ssc_year = substr($v["A"], 0, 4);
                $sellerCost->ssc_month = substr($v["A"], 4, 5);
                $sellerCost->ssc_no = isset($v["B"]) ? $v["B"] : null;
                $sellerCost->stan_wage = isset($v["C"]) ? floatval($v["C"]) : null;
                $sellerCost->real_wage = isset($v["D"]) ? floatval($v["D"]) : null;
                $result = $sellerCost->save();
                if ($result) {
                    $succ++;
                } else {
                    $err++;
                }
            } else {
                $err++;
            }
        }
        return $this->success('成功导入<span class="red">' . $succ . '<span>条数据,失敗<span class="red">' . $err . '<span>条');
    }

    /*
     * 检查销售员是否存在
     * @param string $code
     * @return boolean
    */
    public function checkSeller($code)
    {
        $check = new CrmEmployee();
        return $check->isExistSeller($code);
    }


    /*从销单名细表查询统计数据 并存入销售汇总表*/
    public function actionDetailToSum()
    {
        $param = Yii::$app->request->queryParams;
        // 没有设置时间默认查询本月 设置了月份条件按月份查找统计并保存
        $lastMonth = date('Y-m', strtotime('first day of -1 month')); // 获取上月月份
//        $lastMonth = '2016-09';
        $param['SaleDetailsSearch']['month'] = isset($param['SaleDetailsSearch']['month']) && $param['SaleDetailsSearch']['month'] ? $param['SaleDetailsSearch']['month'] : $lastMonth;
        $model = new SaleDetailsSearch();
        $dataProvider = $model->searchSum($param);
//        $param['SaleDetailsSearch']['saleStartDate'] = $param['SaleDetailsSearch']['saleStartDate'] ? $param['SaleDetailsSearch']['saleStartDate'] : 'default';
        $model = $dataProvider->getModels();
        foreach ($model as $v) {
            $year = date_parse($v['sale_date'])['year'];
            $month = date_parse($v['sale_date'])['month'];
            $month = ($month > 9) ? "$month" : ("0" . "$month");
            $saleDetailsSum = SaleDetailsSum::find()->where(['sds_sacode' => $v['sdl_sacode'], 'sds_year' => $year, 'sds_month' => $month])->one();
            if (!$saleDetailsSum) {
                $saleDetailsSum = new  SaleDetailsSum();
            }
            $saleDetailsSum['sds_comp'] = $v['sdl_comp'];
            $saleDetailsSum['sds_year'] = "$year";
            $saleDetailsSum['sds_month'] = $month;
            $saleDetailsSum['sds_sacode'] = $v['sdl_sacode'];
            $saleDetailsSum['sds_saname'] = $v['sdl_saname'];
            $saleDetailsSum['sts_code'] = $v['storeInfo']['sts_code'];
            $saleDetailsSum['sale_type'] = $v['sale_type'];
            $saleDetailsSum['bill_camount'] = $v['amountSum'];
            $saleDetailsSum['sale_cost'] = $v['costSum'];
            $saleDetailsSum['gross_profit'] = $v['amountSum'] - $v['costSum'];
            $saleDetailsSum['change_cost'] = $v['changeCost'];
            $result = $saleDetailsSum->save();
        }

        $param['SaleDetailsSumSearch']['month'] = isset($param['SaleDetailsSumSearch']['month']) && $param['SaleDetailsSumSearch']['month'] ? $param['SaleDetailsSumSearch']['month'] : $lastMonth;
        $detailSum = new SaleDetailsSumSearch();
        $detailSumSearch = $detailSum->search($param);
        $detailSumModel = $detailSumSearch->getModels();

        return $detailSumModel;
//        return $param;
//        return $saleDetailsSum;
//        return $dataProvider;
    }

    /*从人力成本表查询计算人力成本数据  并存入销售汇总表*/
    public function actionSellerCost()
    {
        // 获取销售条件 没有设置时间默认查询本月 设置了月份条件按月份查找统计并更新到汇总表
            $param = Yii::$app->request->queryParams;
            if (!empty($param['SaleDetailsSearch']['month'])) {
                $yearMonth = $param['SaleDetailsSearch']['month'];
            } else {
                $yearMonth = date('Y-m', strtotime('first day of -1 month')); // 获取上月月份
//                $yearMonth = '2016-09';
            }
            $year = substr($yearMonth, 0, 4);
            $month = substr($yearMonth, 5, 2);
            $param['SellerCostSearch']['year'] = $year;
            $param['SellerCostSearch']['month'] = $month;
            $sellerCostModel = new SellerCostSearch();
            $dataProvider = $sellerCostModel->search($param);
            $model = $dataProvider->getModels();
            // 获取间接人力总额
            $indirectTotal = $sellerCostModel->indirectTotal();
            $indirectTotal = $indirectTotal->getModels();
            $indirectTotal = $indirectTotal[0]['indirectTotal'];
            // 获取直接人力数
            $directSellerNum = $sellerCostModel->directSellerNum();
            $directSellerNum = $directSellerNum->getModels();
            $directSellerNum = $directSellerNum[0]['directSellerNum'];
            // 计算间接人力薪资
            if ($directSellerNum != 0 && $indirectTotal != 0) {
                $indirectAvg = $indirectTotal / $directSellerNum;
            } else {
                $indirectAvg = 0;
            }
//        return $indirectTotal;

            foreach ($model as $v) {
                $saleDetailsSum = SaleDetailsSum::find()->where(['sds_sacode' => $v['ssc_no'], 'sds_year' => $v['ssc_year'], 'sds_month' => $v['ssc_month']])->one();;
                if ($saleDetailsSum) {
                    $saleDetailsSum->indirect_cost = $indirectAvg;
                    $saleDetailsSum->direct_cost = $v['real_wage'] - $v['stan_wage'];
                    $saleDetailsSum->save();
//                return $saleDetailsSum;
                }
        }

        // 从汇总表获取要显示的数据
        $param['SaleDetailsSumSearch']['month'] = isset($param['SaleDetailsSumSearch']['month']) && $param['SaleDetailsSumSearch']['month'] ? $param['SaleDetailsSumSearch']['month'] : $lastMonth;
        $detailSum = new SaleDetailsSumSearch();
        $detailSumSearch = $detailSum->search($param);
        $detailSumModel = $detailSumSearch->getModels();
        return $detailSumModel;
    }

    /*插入固定费用*/
    public function actionImportStoreCost()
    {
        static $succ = 0;
        static $err = 0;
        $arr = Yii::$app->request->post();
//        return $arr;
        foreach ($arr as $k => $v) {
            if (strlen($v["D"]) === 6) { // 判断六位年月
                $rent = isset($v["E"]) ? floatval($v["E"]) : null;
                $power = isset($v["F"]) ? floatval($v["F"]) : null;
                $work = isset($v["G"]) ? floatval($v["G"]) : null;
                $storeCost = SaleStorecost::find()->where(['sts_code'=>$v["C"],'storc_year'=>substr($v["D"], 0, 4),'storc_month'=>substr($v["D"], 4, 5)])->one();
                if (!$storeCost) {
                    $storeCost = new SaleStorecost();
                }
                $storeCost->sts_code = isset($v["C"]) ? $v["C"] : null;
                $storeCost->csarea_code = isset($v["A"]) ? $v["A"] : null;
                $storeCost->dep_id = isset($v["B"]) ? $v["B"] : null;
                $storeCost->storc_year = substr($v["D"], 0, 4);
                $storeCost->storc_month = substr($v["D"], 4, 5);
                $storeCost->rent = $rent;
                $storeCost->power = $power;
                $storeCost->work = $work;
                $storeCost->total = $rent + $power + $work;
                $result = $storeCost->save();
                if ($result) {
                    $succ++;
                } else {
                    $err++;
                }
            } else {
                $err++;
            }
        }
        return $this->success('成功导入<span class="red">' . $succ . '<span>条数据,失敗<span class="red">' . $err . '<span>条');
    }

    // 从门店固定费用信息表查询固定费用总额 计算出当期分摊固定费用存入销售汇总表
    public function actionStoreCost()
    {
        // 获取销售条件 没有设置时间默认查询本月 设置了月份条件按月份查找统计并更新到汇总表
        $param = Yii::$app->request->queryParams;
        if (!empty($param['SaleDetailsSearch']['month'])) {
            $yearMonth = $param['SaleDetailsSearch']['month'];
        } else {
            $yearMonth = date('Y-m', strtotime('first day of -1 month')); // 获取上月月份
//            $yearMonth = '2016-09';
        }
        $year = substr($yearMonth, 0, 4);
        $month = substr($yearMonth, 5, 2);
        $param['StoreCostSearch']['year'] = $year;
        $param['StoreCostSearch']['month'] = $month;
        $storeCostModel = new StoreCostSearch();
        $dataProvider = $storeCostModel->search($param);
        $model = $dataProvider->getModels();
        // 获取直接人力数
        $sellerCostModel = new SellerCostSearch();
        $directSellerNum = $sellerCostModel->directSellerNum();
        $directSellerNum = $directSellerNum->getModels();
        $directSellerNum = $directSellerNum[0]['directSellerNum'];

        // 在汇总表中批量更新分摊固定费用
        foreach ($model as $v) {
            if ($directSellerNum != 0) {
                $fixed_cost = $v['total'] / $directSellerNum;
            } else {
                $fixed_cost = null;
            }
            $sds_code = $v['storeInfo']['sts_code'];
            SaleDetailsSum::updateAll(['fixed_cost'=>$fixed_cost], ['sts_code' => $sds_code, 'sds_year' => $v['storc_year'], 'sds_month' => $v['storc_month']]);
        }

        // 从汇总表获取要显示的数据
        $param['SaleDetailsSumSearch']['month'] = isset($param['SaleDetailsSumSearch']['month']) && $param['SaleDetailsSumSearch']['month'] ? $param['SaleDetailsSumSearch']['month'] : $lastMonth;
        $detailSum = new SaleDetailsSumSearch();
        $detailSumSearch = $detailSum->search($param);
        $detailSumModel = $detailSumSearch->getModels();
        return $detailSumModel;
    }

    // 插入业务费用
    public function actionImportOperateCost()
    {
        static $succ = 0;
        static $err = 0;
        $arr = Yii::$app->request->post();
//        return $arr;
        foreach ($arr as $k => $v) {
            if (strlen($v["B"]) === 6) { // 判断六位年月
                $operateCost = SaleOperateCost::find()->where(['soc_no'=>$v["A"],'soc_year'=>substr($v["B"], 0, 4),'soc_month'=>substr($v["B"], 4, 5)])->one();
                if (!$operateCost) {
                    $operateCost = new SaleOperateCost();
                }
                $operateCost->soc_no = isset($v["A"]) ? $v["A"] : null;
                $operateCost->soc_year = substr($v["B"], 0, 4);
                $operateCost->soc_month = substr($v["B"], 4, 5);
                $operateCost->operate_cost = isset($v["C"]) ? $v["C"] : null;
                $result = $operateCost->save();
                if ($result) {
                    $succ++;
                } else {
                    $err++;
                }
            } else {
                $err++;
            }
        }
        return $this->success('成功导入<span class="red">' . $succ . '<span>条数据,失敗<span class="red">' . $err . '<span>条');
    }

    // 从业务费用表查询业务费用存入销售汇总表
    public function actionOperateCost()
    {
        $param = Yii::$app->request->queryParams;
        if (!empty($param['SaleDetailsSearch']['month'])) {
            $yearMonth = $param['SaleDetailsSearch']['month'];
        } else {
            $yearMonth = date('Y-m', strtotime('first day of -1 month')); // 获取上月月份
//            $yearMonth = '2016-09';
        }
        $year = substr($yearMonth, 0, 4);
        $month = substr($yearMonth, 5, 2);
        $param['OperateCostSearch']['year'] = $year;
        $param['OperateCostSearch']['month'] = $month;
        $storeCostModel = new OperateCostSearch();
        $dataProvider = $storeCostModel->search($param);
        $model = $dataProvider->getModels();
//        return $model;

        // 在汇总表中更新业务费用 并计算更新利润和利润率
        foreach ($model as $v) {
            $saleDetailsSum = SaleDetailsSum::find()->where(['sds_sacode' => $v['soc_no'], 'sds_year' => $v['soc_year'], 'sds_month' => $v['soc_month']])->one();
            if ($saleDetailsSum) {
                $profit = $saleDetailsSum->gross_profit - $saleDetailsSum->indirect_cost - $saleDetailsSum->direct_cost - $saleDetailsSum->fixed_cost - $v['operate_cost'];
                if ($saleDetailsSum->bill_camount != 0) {
                    $profit_margin = $profit/$saleDetailsSum->bill_camount;
                }
                $saleDetailsSum->operation_cost = $v['operate_cost'];
                $saleDetailsSum->profit = $profit;
                $saleDetailsSum->profit_margin = $profit_margin;
                $saleDetailsSum->save();
//                return $saleDetailsSum;
            }
        }

        $param['SaleDetailsSumSearch']['month'] = isset($param['SaleDetailsSumSearch']['month']) && $param['SaleDetailsSumSearch']['month'] ? $param['SaleDetailsSumSearch']['month'] : $lastMonth;
        $detailSum = new SaleDetailsSumSearch();
        $detailSumSearch = $detailSum->search($param);
        $detailSumModel = $detailSumSearch->getModels();
        return $detailSumModel;
    }

    // 计算提成
    public function actionCalculate() {
        // 取出要计算提成的数据列表（某年月 某个业务员等）
        $param = Yii::$app->request->queryParams;
        $lastMonth = date('Y-m', strtotime('first day of -1 month')); // 获取上月月份
//        $lastMonth = '2016-09';
        $param['SaleDetailsSumSearch']['month'] = (!empty($param['SaleDetailsSumSearch']['month'])) ? $param['SaleDetailsSumSearch']['month'] : $lastMonth;
        $detailSum = new SaleDetailsSumSearch();
        $detailSumSearch = $detailSum->search($param);
        $detailSumModel = $detailSumSearch->getModels();

        // 取出设置的提成系数列表
        $rate = new CommissionRateSearch();
        $rate = $rate->search()->getModels();
        $rateCount = count($rate);
        if ($rateCount==0) {
            return '没有设置提成系数';
        }

        foreach ($detailSumModel as $key=>$v) {
            for ($k=1;$k<=$rateCount;$k++) {
                if ($v['bill_camount']>$rate[$rateCount-$k]['scommi_begin']) {
                    $v['ticheng'.($rateCount-$k+1)] = ($v['bill_camount'] - $rate[$rateCount-$k]['scommi_begin'])*$v['profit_margin']*$rate[$rateCount-$k]['scommi_rate'];
                    for ($i=1;$i<=$rateCount-$k;$i++) {
                        $v['ticheng'.($rateCount-$k-$i+1)] =  $rate[$rateCount-$k-$i]['scommi_values']*$rate[$rateCount-$k-$i]['scommi_rate']*$v['profit_margin'];
                    }
                    break;
                } else {
                    $v['ticheng'.($rateCount-$k+1)] = 0;
                }
            }
        }
        return $detailSumModel;
    }

    // 获取角色列表
    public function actionGetRoles()
    {
        $roles = new CrmSaleRolesSearch();
        $roles = $roles->search();
        $roles = $roles->getModels();
        return $roles;
    }

    // 获取销售点列表
    public function actionGetStores()
    {
        $stores = CrmStoresinfo::getStoreInfo();
        return $stores;
    }

    // 导入的销单是否跨月
    public function actionIsSingleMonth()
    {
        return SaleDetailsTemp::getCountMonths();
    }

    // 导入销单的月份
    public function actionGetMonth()
    {
        $date = SaleDetailsTemp::getMonth();
        $month = date('Y-m', strtotime($date['sale_date']));
        return $month;
    }

    // 销单明细表最近月份
    public function actionGetNearestMonth()
    {
        $date = SaleDetails::getNearestMonth();
        if (empty($date)) {
            return 0;
        }
        $month = date('Y-m', strtotime($date['sale_date']));
        return $month;
    }

    // 销售员是否存在
    public function actionIsSellerExist()
    {
        $sellers = SaleDetailsTemp::getJobNumbers();
        $crmEmployee = new CrmEmployee();
        foreach ($sellers as $v) {
            $result = $crmEmployee->isExistSeller($v['sdl_sacode']);
            if (!$result) {
                $res['flag'] = 0;
                $res['msg'] = '工号'.$v['sdl_sacode'].'的销售员不存在！';
                return $res;
            }
        }
        $res['flag'] = 1;
        $res['msg'] = '销售员存在';
        return $res;
    }

    // 检查销售点是否都存在（通过员工工号查找员工对应的销售点是否存在）
    public function actionIsStoreExist()
    {
        $detailTemp = new SaleDetailsTempSearch();
        $detailTemp = $detailTemp->search();
        $detailTemp = $detailTemp->getModels();
//        return $detailTemp;
        foreach ($detailTemp as $v) {
            if (!$v['storeInfo']) {
                $res['flag'] = 0;
                $res['msg'] = $v['sdl_sacode'].'所对应的销售点不存在！';
                return $res;
            }
//            return $v['storeInfo'];
        }
        $res['flag'] = 1;
        $res['msg'] = '所有员工对应的销售点都存在！';
        return $res;
    }

    // 检查关键数据是否为空
    public function actionIsEmptyData()
    {
        $detailTemp = new SaleDetailsTempSearch();
        $detailTemp = $detailTemp->search();
        $detailTemp = $detailTemp->getModels();
//        return $detailTemp;
        foreach ($detailTemp as $v) {
            if (!$v['sdl_sacode']) {
                $res['flag'] = 0;
                $res['msg'] = '工号不能为空！';
                return $res;
            }
            if (!$v['sale_date']) {
                $res['flag'] = 0;
                $res['msg'] = '销售日期不能为空！';
                return $res;
            }
            if (empty($v['cur_code'])) {
                $res['flag'] = 0;
                $res['msg'] = '币别不能为空！';
                return $res;
            }
            if (!$v['bill_camount']) {
                $res['flag'] = 0;
                $res['msg'] = '本币金额不能为空！';
                return $res;
            }
            if (!$v['stan_cost']) {
                $res['flag'] = 0;
                $res['msg'] = '标准成本不能为空！';
                return $res;
            }
            if (!$v['sale_cost']) {
                $res['flag'] = 0;
                $res['msg'] = '成本不能为空！';
                return $res;
            }
        }
        $res['flag'] = 1;
        $res['msg'] = '所有关键数据都不为空。';
        return $res;
    }

    // 币别是否存在
    public function actionIsCurExist()
    {
        $detailTemp = new SaleDetailsTempSearch();
        $detailTemp = $detailTemp->search();
        $detailTemp = $detailTemp->getModels();
//        return $detailTemp;

        $currency = BsCurrency::getAll();
//        return $currency;

        foreach ($detailTemp as $dTemp) {
            foreach ($currency as $cur) {
                if ($dTemp['cur_code'] == $cur['cur_code']){
                    break;
                }
                $res['flag'] = 0;
                $res['msg'] = '币别'.$dTemp['cur_code'].'不存在！';
                return $res;
            }
        }
        $res['flag'] = 1;
        $res['msg'] = '币别都存在。';
        return $res;
    }

    // 当期汇率是否存在
    public function actionIsRateExist()
    {
        $detailTemp = new SaleDetailsTempSearch();
        $detailTemp = $detailTemp->search();
        $detailTemp = $detailTemp->getModels();
//        return $detailTemp;
        foreach ($detailTemp as $v) {
            $param['origin'] = $v['cur_code'];
            $param['year'] = substr($v['sale_date'], 0, 4);
            $param['month'] = substr($v['sale_date'], 5, 2);
//            return $param;
            $rate = new BsExchangeRateSearch();
            $rate = $rate->search($param);
            $rate = $rate->getModels();
//            return $rate;
            if (!$rate) {
                $res['flag'] = 0;
                $res['msg'] = '没有'.$param['year'].'年'.$param['month'].'月的汇率！';
                return $res;
            }
        }
        $res['flag'] = 1;
        $res['msg'] = '存在当期汇率';
        return $res;
    }

}
