<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/12/14
 * Time: 15:56
 */

namespace app\modules\common\tools;

use app\classes\Trans;
use app\modules\common\models\BsPayment;
use app\modules\common\models\BsPubdata;
use app\modules\sale\models\OrdDt;
use app\modules\sale\models\OrdFile;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdPay;
use app\modules\sale\models\OrdStatus;
use app\modules\sale\models\PriceDt;
use app\modules\sale\models\PriceFile;
use app\modules\sale\models\PriceInfo;
use app\modules\sale\models\PricePay;
use app\modules\sale\models\SaleCustrequireL;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\Html;

class SplitOrder
{
    public function getSplitOrder($id){
        $queryW = $this->getQueryW($id);
        $queryT = $this->getQueryT($id);
        $name = '订单已提交';
        $trans = new Trans();
        /*将上门自提与送货上门数据分开*/
        $arr1 = array_filter($queryW,function($value){
            return !empty($value['whs_id']);
        });
        $arr2 = array_filter($queryT,function($value){
            return !empty($value['transport']);
        });
        /*仓库自提订单--start--*/
        /*获取订单商品信息*/
        if(!empty($arr1)) {
            foreach ($arr1 as $key1 => $val1) {
                $arr3[$key1] = $this->getWhouse($id, $val1['whs_id']);
            }
            if (!empty($arr3)) {
                /*订单拆分*/
                foreach ($arr3 as $key3 => $val3) {
                    $model = PriceInfo::findOne($val3[0]['price_id']);
                    $pm = BsPayment::findOne($model['pac_id']);
                    $pb = BsPubdata::findOne($model['pay_type']);
                    $arr = Json::decode(Json::encode($model), true);
                    /*生成订单*/
                    $LPriceInfo = new OrdInfo();
                    foreach($arr as $key => $value){
                        $arr[$key] = Html::decode($value);
                    }
                    $LPriceInfo->setAttributes($arr);
                    $status = OrdStatus::find()->where(['or', ['=', 'os_name', $trans->c2t($name)], ['=', 'os_name', $trans->t2c($name)]])->andWhere(['=', 'yn', '1'])->one();
                    $LPriceInfo->os_id = $status['os_id'];
                    $LPriceInfo->ord_date = $model['price_date'];
                    $LPriceInfo->ord_type = $model['price_type'];
                    if (!$LPriceInfo->save()) {
                        throw new \Exception('订单生成失败！');
                    }
                    $price_id = $model->price_id;
                    $ord_id = $LPriceInfo->ord_id;
                    /*对应订单商品信息*/
                    foreach ($val3 as $key5 => $val5) {
                        $child = PriceDt::findOne($val5['price_dt_id']);
                        $arr1 = Json::decode(Json::encode($child), true);
                        $childModel = new OrdDt();
                        foreach($arr1 as $key => $value){
                            $arr1[$key] = Html::decode($value);
                        }
                        $childModel->setAttributes($arr1);
                        $childModel->ord_id = $ord_id;
                        if (!$childModel->save()) {
                            throw new \Exception('订单生成失败！');
                        }
                    }
                    /*订单对应附件信息*/
                    $reqFile = PriceFile::find()->where(['price_id' => $id])->all();
                    if(!empty($reqFile)) {
                        $reqFiles = Json::decode(Json::encode($reqFile), true);
                        foreach ($reqFiles as $k => $v) {
                            $pFileModel = new OrdFile();
                            foreach($v as $key => $value){
                                $v[$key] = Html::decode($value);
                            }
//                    $v["ord_id"] = $ord_id;
                            $pFileModel->setAttributes($v);
                            $pFileModel->ord_id = $ord_id;
                            if (!$pFileModel->save()) {
                                throw new \Exception(Json::encode(current($pFileModel->getFirstErrors())));
                            }
                        }
                    }
                    /*订单对应价格信息*/
                    $sql = 'SELECT SUM(sapl_quantity*uprice_tax_o) AS prd_org_amount, SUM(tax_freight) AS tax_freight, SUM(tax_freight + sapl_quantity*uprice_tax_o) AS req_tax_amount FROM `oms`.`ord_dt` WHERE `ord_id`=' . $ord_id;
                    $total = Yii::$app->oms->createCommand($sql)->queryOne();
                    $reqPay = PricePay::find()->where(['price_id' => $price_id])->all();
                    $reqPays = Json::decode(Json::encode($reqPay), true);
                    $count = 0;
                    $per = $total['req_tax_amount'] / $model['req_tax_amount'];
                    foreach ($reqPays as $kp => $vp) {
                        $pPayModel = new OrdPay();
                        $pPayModel->ord_id = $ord_id;
                        $pPayModel->yn_pay = 0;
//                $pPayModel->setAttributes($vp);
                        $pPayModel->credit_id = $vp['credit_id'];
                        $stag_cost = round($vp['stag_cost'] * $per);
                        $pay = OrdInfo::findOne($ord_id);
                        if ($kp < count($reqPays) - 1) {
                            $pPayModel->stag_cost = $stag_cost;
                            if($pm['pac_code'] == 'pre-pay' && $pb['bsp_svalue'] == '全额') {
                                $pay->prd_org_amount = $stag_cost;
                                $pay->req_tax_amount = $stag_cost;
                            }else{
                                $pay->prd_org_amount = $total['prd_org_amount'];
                                $pay->req_tax_amount = $total['req_tax_amount'];
                            }
                            $count += $stag_cost;
                        }
                        if ($kp == count($reqPays) - 1) {
                            $pPayModel->stag_cost = $total['req_tax_amount'] - $count;
                            if($pm['pac_code'] == 'pre-pay' && $pb['bsp_svalue'] == '全额') {
                                $pay->prd_org_amount = $total['prd_org_amount'] - $count;
                                $pay->req_tax_amount = $total['req_tax_amount'] - $count;
                            }else{
                                $pay->prd_org_amount = $total['prd_org_amount'];
                                $pay->req_tax_amount = $total['req_tax_amount'];
                            }
                        }
                        if (!$pay->save()) {
                            throw new \Exception(Json::encode(current($pay->getFirstErrors())));
                        }
                        if (!$pPayModel->save()) {
                            throw new \Exception(Json::encode(current($pPayModel->getFirstErrors())));
                        }
                    }
                }
            }
        }
        /*仓库自提订单--end--*/
        /*送货上门 --start--*/
        /*获取订单商品信息*/
        if(!empty($arr2)) {
            foreach ($arr2 as $key2 => $val2) {
                $arr4[$key2] = $this->getTransport($id, $val2['transport'], $val2['catg_id']);
            }
            if (!empty($arr4)) {
                /*订单拆分*/
                foreach ($arr4 as $key4 => $val4) {
                    $model2 = PriceInfo::findOne($val4[0]['price_id']);
                    $pm2 = BsPayment::findOne($model2['pac_id']);
                    $pb2 = BsPubdata::findOne($model2['pay_type']);
                    $arr2 = Json::decode(Json::encode($model2), true);
                    /*生成订单*/
                    $LPriceInfo2 = new OrdInfo();
                    foreach($arr2 as $key => $value){
                        $arr2[$key] = Html::decode($value);
                    }
                    $LPriceInfo2->setAttributes($arr2);
                    $status2 = OrdStatus::find()->where(['or', ['=', 'os_name', $trans->c2t($name)], ['=', 'os_name', $trans->t2c($name)]])->andWhere(['=', 'yn', '1'])->one();
                    $LPriceInfo2->os_id = $status2['os_id'];
                    $LPriceInfo2->ord_date = $model2['price_date'];
                    $LPriceInfo2->ord_type = $model2['price_type'];
                    if (!$LPriceInfo2->save()) {
                        throw new \Exception('订单生成失败！');
                    }
                    $price_id2 = $model2->price_id;
                    $ord_id2 = $LPriceInfo2->ord_id;
                    /*对应订单商品信息*/
                    foreach ($val4 as $key6 => $val6) {
                        $child2 = PriceDt::findOne($val6['price_dt_id']);
                        $arr6 = Json::decode(Json::encode($child2), true);
                        $childModel2 = new OrdDt();
                        foreach($arr6 as $key => $value){
                            $arr6[$key] = Html::decode($value);
                        }
                        $childModel2->setAttributes($arr6);
                        $childModel2->ord_id = $ord_id2;
                        if (!$childModel2->save()) {
                            throw new \Exception('订单生成失败！');
                        }
                    }
                    /*订单对应附件信息*/
                    $reqFile2 = PriceFile::find()->where(['price_id' => $price_id2])->all();
                    if(!empty($reqFile2)) {
                        $reqFiles2 = Json::decode(Json::encode($reqFile2), true);
                        foreach ($reqFiles2 as $kf2 => $vf2) {
                            $pFileModel2 = new OrdFile();
                            $vf2["ord_id"] = $ord_id2;
                            foreach($vf2 as $key => $value){
                                $vf2[$key] = Html::decode($value);
                            }
                            $pFileModel2->setAttributes($vf2);
                            if (!$pFileModel2->save()) {
                                throw new \Exception(Json::encode(current($pFileModel2->getFirstErrors())));
                            }
                        }
                    }
//                /*订单对应价格信息*/
                    $sql2 = 'SELECT SUM(sapl_quantity*uprice_tax_o) AS prd_org_amount, SUM(tax_freight) AS tax_freight, SUM(tax_freight + sapl_quantity*uprice_tax_o) AS req_tax_amount FROM `oms`.`ord_dt` WHERE `ord_id`=' . $ord_id2;
                    $total2 = Yii::$app->oms->createCommand($sql2)->queryOne();
                    $reqPay2 = PricePay::find()->where(['price_id' => $price_id2])->all();
                    $reqPays2 = Json::decode(Json::encode($reqPay2), true);
                    $count2 = 0;
                    $per2 = $total2['req_tax_amount'] / $model2['req_tax_amount'];
                    foreach ($reqPays2 as $kp2 => $vp2) {
                        $pPayModel2 = new OrdPay();
                        $pPayModel2->ord_id = $ord_id2;
                        $pPayModel2->yn_pay = 0;
//                $pPayModel->setAttributes($vp);
                        $pPayModel2->credit_id = $vp2['credit_id'];
                        $stag_cost2 = round($vp2['stag_cost'] * $per2);
                        $pay2 = OrdInfo::findOne($ord_id2);
                        if ($kp2 < count($reqPays2) - 1) {
                            $pPayModel2->stag_cost = $stag_cost2;
                            if($pm2['pac_code'] == 'pre-pay' && $pb2['bsp_svalue'] == '全额'){
                                $pay2->prd_org_amount = $stag_cost2;
                                $pay2->req_tax_amount = $stag_cost2;
                            }else{
                                $pay2->prd_org_amount = $total2['prd_org_amount'];
                                $pay2->req_tax_amount = $total2['req_tax_amount'];
                            }
                            $count2 += $stag_cost2;
                        }
                        if ($kp2 == count($reqPays2) - 1) {
                            $pPayModel2->stag_cost = $total2['req_tax_amount'] - $count2;
                            if($pm2['pac_code'] == 'pre-pay' && $pb2['bsp_svalue'] == '全额') {
                                $pay2->prd_org_amount = $total2['prd_org_amount'] - $count2;
                                $pay2->req_tax_amount = $total2['req_tax_amount'] - $count2;
                            }else{
                                $pay2->prd_org_amount = $total2['prd_org_amount'];
                                $pay2->req_tax_amount = $total2['req_tax_amount'];
                            }

                        }
                        if (!$pay2->save()) {
                            throw new \Exception(Json::encode(current($pay2->getFirstErrors())));
                        }
                        if (!$pPayModel2->save()) {
                            throw new \Exception(Json::encode(current($pPayModel2->getFirstErrors())));
                        }
                    }
                }
            }
        }
        $model3 = PriceInfo::findOne($id);
        $model3->audit_id = PriceInfo::STATUS_REVIEW_OVER;
        if (!$model3->save()) {
            throw new \Exception(current($model3->getFirstErrors()));
        }
        /*送货上门 --end--*/
    }

    /**
     * @param $id
     * @return array
     * 根据自提仓库拆分订单
     */
    public function getQueryW($id){
        $query = (new Query())
            ->select([
                'odl.whs_id',
                'count(*)'
            ])
            ->from(['odl' => 'oms.price_dt'])
            ->where(['odl.price_id' => $id])
            ->groupBy("whs_id")
            ->all()
        ;
        return $query;
    }

    /**
     * @param $id
     * @param $wid
     * 根据报价单id 和 仓库id 查询商品
     */
    public function getWhouse($id,$wid){
        $query = (new Query())
            ->select([
                'bpn.prt_pkid',                      // 商品id
                'bpn.part_no pdt_no',                // 商品料号
                'bpn.min_order',                     // 最小起訂量
                'bpn.isselftake self_take',          // 是否可以自提
                'price.price',                       // 價格(含稅)
                'bpt.pdt_name',                      // 商品名稱
                'odl.sapl_quantity',                 // 下单数量
                'odl.price_dt_id',                   // 報價明細pkid
                'odl.price_id',                      // 報價單pkid
                'bp.pdt_qty',                        // 包裝內商品數量
                'odl.uprice_ntax_o',                 // 單價(未稅)-原幣
                'odl.cess',                          // 税率
                'odl.discount',                      // 折扣
                'odl.transport transport_id',        // 运输方式code
                'btp_2.tran_sname transport_name',   // 运输方式
                'odl.distribution',                  // 配送方式id
                'btp_1.tran_sname distribution_name',// 配送方式
                'odl.freight',                       // 未稅物流費用
                'odl.tax_freight',                   // 含稅物流費用
                'odl.whs_id',                        // 出仓仓库id
                'wh.wh_name wh_name',                // 出仓仓库
                'odl.request_date',                  // 需求交期
                'odl.consignment_date',              // 交期
                'odl.sapl_remark',                   // 备注
                'odl.uprice_tax_o',                  // 商品单价（含税）
                'odl.tprice_ntax_o',                 // 商品总价（未税）
                'odl.tprice_tax_o',                  // 商品总价（含税）
                'odl.suttle',                        // 重量
            ])
            ->from(['odl' => 'oms.price_dt'])
            ->leftJoin('oms.price_info odh', 'odl.price_id=odh.price_id AND odl.sapl_status!=' .SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_price price', 'price.prt_pkid = bpn.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->leftJoin('pdt.bs_pack bp', 'bp.prt_pkid=bpn.prt_pkid')
            ->leftJoin('wms.bs_transport btp_1','btp_1.tran_id = odl.distribution')
            ->leftJoin('wms.bs_transport btp_2','btp_2.tran_code = odl.transport')
            ->leftJoin('wms.bs_wh wh','wh.wh_id = odl.whs_id')
//            ->leftJoin('select odl.sapl_quantity')
            ->where((
                (
                    'ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                )
                OR (
                    '! ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                    AND 'odl.sapl_quantity <= price.maxqty'
                )
            ))
            ->andWhere(['odl.whs_id' => $wid])
            ->andwhere(['odl.price_id' => $id])
            ->groupBy("price_dt_id")
            ->all()
            ;
        return $query;
    }

    /**
     * @param $id
     * @return array
     * 根据类别和运输方式进行分类 拆分订单
     */
    public function getQueryT($id){
        $query = (new Query())
            ->select([
                'odl.transport',
                'bpt.catg_id',
                'count(*)'
            ])
            ->from(['odl' => 'oms.price_dt'])
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->where(['odl.price_id' => $id])
            ->groupBy(["odl.transport","bpt.catg_id"])
            ->all()
        ;
        return $query;
    }

    /**
     * @param $id
     * @param $wid
     * 根据报价单id 和 仓库id 查询商品
     */
    public function getTransport($id,$tpid,$caid){
        $query = (new Query())
            ->select([
                'bpn.prt_pkid',                      // 商品id
                'bpn.part_no pdt_no',                // 商品料号
                'bpn.min_order',                     // 最小起訂量
                'bpn.isselftake self_take',          // 是否可以自提
                'price.price',                       // 價格(含稅)
                'bpt.pdt_name',                      // 商品名稱
                'bpt.catg_id',                       // 商品类别
                'odl.sapl_quantity',                 // 下单数量
                'odl.price_dt_id',                   // 報價明細pkid
                'odl.price_id',                      // 報價單pkid
                'bp.pdt_qty',                        // 包裝內商品數量
                'odl.uprice_ntax_o',                 // 單價(未稅)-原幣
                'odl.cess',                          // 税率
                'odl.discount',                      // 折扣
                'odl.transport transport_id',        // 运输方式code
                'btp_2.tran_sname transport_name',   // 运输方式
                'odl.distribution',                  // 配送方式id
                'btp_1.tran_sname distribution_name',// 配送方式
                'odl.freight',                       // 未稅物流費用
                'odl.tax_freight',                   // 含稅物流費用
                'odl.whs_id',                        // 出仓仓库id
                'wh.wh_name wh_name',                // 出仓仓库
                'odl.request_date',                  // 需求交期
                'odl.consignment_date',              // 交期
                'odl.sapl_remark',                   // 备注
                'odl.uprice_tax_o',                  // 商品单价（含税）
                'odl.tprice_ntax_o',                 // 商品总价（未税）
                'odl.tprice_tax_o',                  // 商品总价（含税）
                'odl.suttle',                        // 重量
            ])
            ->from(['odl' => 'oms.price_dt'])
            ->leftJoin('oms.price_info odh', 'odl.price_id=odh.price_id AND odl.sapl_status!=' .SaleCustrequireL::STATUS_DELETE)
            ->leftJoin('pdt.bs_partno bpn', 'bpn.prt_pkid=odl.prt_pkid')
            ->leftJoin('pdt.bs_price price', 'price.prt_pkid = bpn.prt_pkid')
            ->leftJoin('pdt.bs_product bpt', 'bpt.pdt_pkid=bpn.pdt_pkid')
            ->leftJoin('pdt.bs_pack bp', 'bp.prt_pkid=bpn.prt_pkid')
            ->leftJoin('wms.bs_transport btp_1','btp_1.tran_id = odl.distribution')
            ->leftJoin('wms.bs_transport btp_2','btp_2.tran_code = odl.transport')
            ->leftJoin('wms.bs_wh wh','wh.wh_id = odl.whs_id')
//            ->leftJoin('select odl.sapl_quantity')
            ->where((
                (
                    'ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                )
                OR (
                    '! ISNULL(price.maxqty)'
                    AND 'odl.sapl_quantity >= price.minqty'
                    AND 'odl.sapl_quantity <= price.maxqty'
                )
            ))
            ->andWhere(['and',['odl.transport' => $tpid],['bpt.catg_id' => $caid]])
            ->andwhere(['odl.price_id' => $id])
            ->groupBy("price_dt_id")
            ->all()
        ;
        return $query;
    }

}