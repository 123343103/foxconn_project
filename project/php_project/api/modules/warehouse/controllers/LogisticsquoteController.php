<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/7/18
 * Time: 上午 09:20
 */

namespace app\modules\warehouse\controllers;

use app\controllers\BaseActiveController;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\show\BsDistrictShow;
use app\modules\warehouse\models\BsTransport;
use app\modules\warehouse\models\LogisticsquoteExpressHead;
use app\modules\warehouse\models\search\LogisticsquoteExpressHeadSearch;
use Yii;
use yii\data\ActiveDataProvider;

class LogisticsquoteController extends BaseActiveController
{
    public $modelClass = true;


    public function actionIndex()
    {
//        $searchModel = new LqtHeadSearch();
        // $queryParams = Yii::$app->request->queryParams;
//        //dumpE($queryParams);
//        if (!empty($queryParams['startcityid']) && empty($queryParams['endcityid'])) {
//            throw new \Exception(json_encode('目的地不能为空！', JSON_UNESCAPED_UNICODE));
//        } else if (!empty($queryParams['endcityid']) && empty($queryParams['startcityid'])) {
//            throw new \Exception(json_encode('起始地不能为空！', JSON_UNESCAPED_UNICODE));
//        } else {
//            $dataProvider = $searchModel->search($queryParams);
//        }
//        $model = $dataProvider->getModels();
//        $list['rows'] = $model;
//        $list['total'] = $dataProvider->totalCount;
//        return $list;
    }

    //获取计费途径
    public function actionLqtExpressHead()
    {
        $queryParams = Yii::$app->request->queryParams;
        //计费途径
        $headlist = $this->getExpressHead($queryParams['salesquotationno'],
            $queryParams['startproviceid'], $queryParams['startcityid'], $queryParams['endproviceid'],
            $queryParams['endcityid'], $queryParams['transtype'], "市--市");
        if (count($headlist) == 0) {
            $headlist = $this->getExpressHead($queryParams['salesquotationno'],
                $queryParams['startproviceid'], $queryParams['startcityid'],
                $queryParams['endproviceid'], $queryParams['endproviceid'], $queryParams['transtype'], "市--省");
            if (count($headlist) == 0) {
                $headlist = $this->getExpressHead($queryParams['salesquotationno'],
                    $queryParams['startproviceid'], $queryParams['startproviceid'],
                    $queryParams['endproviceid'], $queryParams['endcityid'], $queryParams['transtype'], "省--市");
                if (count($headlist) == 0) {
                    $headlist = $this->getExpressHead($queryParams['salesquotationno'],
                        $queryParams['startproviceid'], $queryParams['startproviceid'],
                        $queryParams['endproviceid'], $queryParams['endproviceid'], $queryParams['transtype'], "省--省");
                }
            }
        }
        return $headlist;
    }

    //获取快递报价信息的头信息
    public function getExpressHead($lqtno, $origindistrictid, $origincityid, $districtid, $cityid, $transtype, $transMethod)
    {
        //根据报价单号查询
        if ($lqtno != null) {
            $sql = "SELECT
	T.QUOTATIONNO,
	T.QUOTATIONDATE,
	T.EFFECTDATE,
	T.EXPIREDATE,
	T.COSTCONFIRMEDDATE,
	T.CNCY,
	T.REMARK,
	T.ORIGIN_DISTRICTID,
	T.ORIGIN_CITYID,
	T.DISTRICT_ID,
	T.CITY_ID,
	T. STATUS,
	CASE T.TRANSTYPE
WHEN 201 THEN
	'标准快递'
WHEN 202 THEN
	'经济快递'
WHEN 203 THEN
	'优速快递'
WHEN 301 THEN
	'普通陆运'
ELSE
	'定日达陆运'
END AS TRANSTYPE,
'' AS transMethod,
dc.district_name ORIGIN__DISTRICT_name,
bd.district_name ORIGIN_CITYID_name,
di.district_name DISTRICT_ID_name,
 ci.district_name CITY_ID_name
FROM
	wms.logisticsquote_express_head T
	LEFT JOIN erp.bs_district dc ON t.ORIGIN_DISTRICTID=dc.district_id
LEFT JOIN erp.bs_district bd ON t.ORIGIN_CITYID=bd.district_id
LEFT JOIN erp.bs_district di ON t.DISTRICT_ID=di.district_id
LEFT JOIN erp.bs_district ci ON t.CITY_ID=ci.district_id
WHERE 1=1";
            $sql .= " AND T.QUOTATIONNO='{$lqtno}'";
        } //根据起始地与目的地查询
        else {
            $sql = "SELECT
	T.QUOTATIONNO,
	T.QUOTATIONDATE,
	T.EFFECTDATE,
	T.EXPIREDATE,
	T.COSTCONFIRMEDDATE,
	T.CNCY,
	T.REMARK,
	T.ORIGIN_DISTRICTID,
	T.ORIGIN_CITYID,
	T.DISTRICT_ID,
	T.CITY_ID,
	T. STATUS,
	CASE T.TRANSTYPE
WHEN 201 THEN
	'标准快递'
WHEN 202 THEN
	'经济快递'
WHEN 203 THEN
	'优速快递'
WHEN 301 THEN
	'普通陆运'
ELSE
	'定日达陆运'
END AS TRANSTYPE,
'{$transMethod}' AS transMethod,
dc.district_name ORIGIN__DISTRICT_name,
bd.district_name ORIGIN_CITYID_name,
di.district_name DISTRICT_ID_name,
 ci.district_name CITY_ID_name
FROM
	wms.logisticsquote_express_head T
	LEFT JOIN erp.bs_district dc ON t.ORIGIN_DISTRICTID=dc.district_id
LEFT JOIN erp.bs_district bd ON t.ORIGIN_CITYID=bd.district_id
LEFT JOIN erp.bs_district di ON t.DISTRICT_ID=di.district_id
LEFT JOIN erp.bs_district ci ON t.CITY_ID=ci.district_id
WHERE 1=1";
            if ($origindistrictid != null && $districtid != null) {
                $sql .= " AND T.ORIGIN_DISTRICTID = {$origindistrictid}
                     AND T.DISTRICT_ID = {$districtid}";
            }
            if ($cityid != null) {
                $sql .= " AND T.CITY_ID = {$cityid}";
            }
            if ($origincityid != null) {
                $sql .= " AND T.ORIGIN_CITYID ={$origincityid}";
            }
            $sql .= " AND T.TRANSMODE = '20'
                         AND T.TRANSTYPE ={$transtype}
                          AND UPPER(T.STATUS) = 'CONFIRM'
                          ORDER BY T.EFFECTDATE DESC";
        }
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    //获取快递报价信息的详细信息
    public function actionLqtExpressDetail()
    {
        $queryParams = Yii::$app->request->queryParams;
        $sql = "SELECT
	T.ITEMNO,
	T.UOM,
	T.WEIGHTMIN,
	T.FIRSTPRICE,
	T.NEXTWEIGHT,
	T.MIN_VALUE,
	T.MAX_VALUE,
	T.NEXT_RATE,
	T.CHARGEMIN,
	T.CHARGEMAX,
	T.TRANSITTIME,
	T.EFFECTDATE,
	T.EXPIREDATE,
	T. STATUS
FROM
	wms.logisticsquote_express_detail T
WHERE
	T.QUOTATIONNO = '{$queryParams['quotationno']}'
ORDER BY
	T.MIN_VALUE";
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    //计费途径
    public function actionLandHead()
    {
        $queryParams = Yii::$app->request->queryParams;
        $headlist = $this->getLandHead($queryParams['salesquotationno'],
            $queryParams['startproviceid'], $queryParams['startcityid'], $queryParams['endproviceid'],
            $queryParams['endcityid'], $queryParams['transtype']);//市到市
        if (count($headlist) == 0) {
            $headlist = $this->getLandHead($queryParams['salesquotationno'],
                $queryParams['startproviceid'], $queryParams['startcityid'],
                $queryParams['endproviceid'], $queryParams['endproviceid'], $queryParams['transtype']);//市到省
            if (count($headlist) == 0) {
                $headlist = $this->getLandHead($queryParams['salesquotationno'],
                    $queryParams['startproviceid'], $queryParams['startproviceid'],
                    $queryParams['endproviceid'], $queryParams['endcityid'], $queryParams['transtype']);//省到市
                if (count($headlist) == 0) {
                    $headlist = $this->getLandHead($queryParams['salesquotationno'],
                        $queryParams['startproviceid'], $queryParams['startproviceid'],
                        $queryParams['endproviceid'], $queryParams['endproviceid'], $queryParams['transtype']);//省到省
                }
            }
        }
        return $headlist;
    }

    //获取陆运的头信息
    public function getLandHead($lqtno, $origindistrictid, $origincityid, $districtid, $cityid, $transtype)
    {
        $sql = "SELECT
	T.SALESQUOTATIONID,
	T.SALESQUOTATIONNO,
	T.CARGOTYPE,
	T. STATUS,
	T.TIMEREQUIRE,
	T.TIMEREQUIREUNIT,
	T.QUOTATIONCURRENCY,
	T.BUTYPE,
	T.FROM_DISTRICT_ID,
	T.FROM_CITY_ID,
	T.TO_DISTRICT_ID,
	T.TO_CITY_ID,
	T.SALESQUOTATIONDATE,
	T.EFFECTDATE,
	T.EXPIREDATE,
	T.QUTATIONCLASS,
	T.ORIGIN,
	T.DESTINATION
FROM
	wms.LOGISTICSQUOTE_LAND_HEAD T
WHERE 1=1";
        if ($lqtno != null) {
            $sql .= " AND T.SALESQUOTATIONNO='{$lqtno}'";
        } else {
            if ($origindistrictid != null && $districtid != null) {
                $sql .= " AND T.FROM_DISTRICT_ID = {$origindistrictid}
                     AND T.TO_DISTRICT_ID = {$districtid}";
            }
            if ($cityid != 0) {
                $sql .= " AND T.TO_CITY_ID = {$cityid}";
            }
            if ($origincityid != 0) {
                $sql .= " AND T.FROM_CITY_ID ={$origincityid}";
            }
            if ($transtype == '301') {
                $sql .= " AND (T.QUTATIONCLASS = '標準件' OR T.QUTATIONCLASS = '标准件')";
            }
            if ($transtype == '302') {
                $sql .= " AND (T.QUTATIONCLASS = '快件' OR T.QUTATIONCLASS = '快件')";
            }
        }
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    //获取陆运报价的详情信息
    public function actionLandDetail()
    {
        $queryParams = Yii::$app->request->queryParams;
        $sql = "SELECT
	A.SALESQUOTATIONID,
	A.ITEMCODE,
	A.ITEMCNAME,
	A.UOM,
	A.RATE,
	A.TAXRATE,
	A.TAXTYPE,
	A.TRUCKGROUP,
	A.COSTTYPE,
	A.MINICHARGE,
	A.MAXCHARGE,
	A.CURRENCY
FROM
	wms.LOGISTICSQUOTE_LAND_HEAD T,
	wms.LOGISTICSQUOTE_LAND_DETAIL A
WHERE
	T.SALESQUOTATIONID = A.SALESQUOTATIONID
AND T.SALESQUOTATIONNO = '{$queryParams['quotationno']}'";
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
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

//获取快递报价的详情
//    public function actionExpressDetail()
//    {
//        $model = new LqtExpressSearch();
//        $queryParams = Yii::$app->request->queryParams;
//        $dataProvider = $model->search($queryParams);
//        return [
//            'rows' => $dataProvider->getModels(),
//            'total' => $dataProvider->totalCount
//        ];
//    }

    //获取陆运报价的详情
//    public function actionLandDetail()
//    {
//        $model = new LqtLandSearch();
//        $queryParams = Yii::$app->request->queryParams;
//        $dataProvider = $model->search($queryParams);
//        return [
//            'rows' => $dataProvider->getModels(),
//            'total' => $dataProvider->totalCount
//        ];
//    }

    public function actionGetTrans()
    {
        $query = BsTransport::find()->select("tran_code,tran_sname");
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $query->andFilterWhere(['grade' => '1']);
        $result = $dataProvider->getModels();
        return $result;
    }
}
