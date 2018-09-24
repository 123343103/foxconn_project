<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "logisticsquote_land_head".
 *
 * @property integer $SALESQUOTATIONID
 * @property string $SALESQUOTATIONNO
 * @property string $ORIGIN
 * @property string $TRANSIT
 * @property string $DESTINATION
 * @property string $ORIGINPORT
 * @property string $DESTINATIONPORT
 * @property string $TIMEREQUIRE
 * @property string $CARRIERTYPE
 * @property string $BUADDR
 * @property string $SALESQUOTATIONDATE
 * @property string $BUCODE
 * @property string $BUTYPE
 * @property string $AREACODE
 * @property string $RECEIVESITE
 * @property string $QUOTATIONTYPE
 * @property string $QUOTATIONCURRENCY
 * @property string $SETTLEMODE
 * @property string $SETTLECURRENCY
 * @property string $PAYMENTCYCLE
 * @property string $TERMSCODE
 * @property string $IMEXTYPE
 * @property string $BUSINESSTYPE
 * @property string $EXCHANGERATE
 * @property string $PAYMENTPAYEE
 * @property string $RECEIVEPAYEE
 * @property string $EFFECTDATE
 * @property string $EXPIREDATE
 * @property string $BUSINESSNO
 * @property string $BUSINESSNAME
 * @property string $REMARKS
 * @property string $APPENDED
 * @property string $CARGOTYPE
 * @property string $TIMEREQUIREUNIT
 * @property string $QUOTATIONUNIT
 * @property string $TAXRATE
 * @property string $ISTAX
 * @property string $BLPNAME
 * @property string $FROM_DISTRICT_ID
 * @property string $FROM_CITY_ID
 * @property string $FROM_DEVISION_ID
 * @property string $FROM_TOWN_ID
 * @property string $TO_DISTRICT_ID
 * @property string $TO_CITY_ID
 * @property string $TO_DEVISION_ID
 * @property string $TO_TOWN_ID
 * @property string $STATUS
 * @property string $PRODUCTTYPE
 * @property string $QUTATIONCLASS
 */
class LogisticsquoteLandHead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logisticsquote_land_head';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['SALESQUOTATIONID'], 'integer'],
            [['SALESQUOTATIONDATE', 'EFFECTDATE', 'EXPIREDATE'], 'safe'],
            [['SALESQUOTATIONNO', 'TRANSIT', 'ORIGINPORT', 'DESTINATIONPORT', 'TIMEREQUIRE', 'CARRIERTYPE', 'BUADDR', 'BUCODE', 'BUTYPE', 'AREACODE', 'RECEIVESITE', 'QUOTATIONTYPE', 'QUOTATIONCURRENCY', 'SETTLEMODE', 'SETTLECURRENCY', 'PAYMENTCYCLE', 'TERMSCODE', 'IMEXTYPE', 'BUSINESSTYPE', 'EXCHANGERATE', 'PAYMENTPAYEE', 'RECEIVEPAYEE', 'BUSINESSNO', 'BUSINESSNAME', 'CARGOTYPE'], 'string', 'max' => 100],
            [['ORIGIN', 'DESTINATION'], 'string', 'max' => 80],
            [['REMARKS'], 'string', 'max' => 1024],
            [['APPENDED'], 'string', 'max' => 4000],
            [['TIMEREQUIREUNIT'], 'string', 'max' => 20],
            [['QUOTATIONUNIT', 'TAXRATE', 'BLPNAME', 'FROM_DISTRICT_ID', 'FROM_CITY_ID', 'FROM_DEVISION_ID', 'FROM_TOWN_ID', 'TO_DISTRICT_ID', 'TO_CITY_ID', 'TO_DEVISION_ID', 'TO_TOWN_ID', 'STATUS', 'PRODUCTTYPE'], 'string', 'max' => 60],
            [['ISTAX'], 'string', 'max' => 4],
            [['QUTATIONCLASS'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SALESQUOTATIONID' => '报价ID',
            'SALESQUOTATIONNO' => '報價單號',
            'ORIGIN' => '起運地',
            'TRANSIT' => '中轉地',
            'DESTINATION' => '目的地',
            'ORIGINPORT' => '起運港/機場',
            'DESTINATIONPORT' => '目的港/機場 ',
            'TIMEREQUIRE' => '時效',
            'CARRIERTYPE' => '方案類型',
            'BUADDR' => '客戶簡稱',
            'SALESQUOTATIONDATE' => '報價日期',
            'BUCODE' => '客戶代碼 ',
            'BUTYPE' => '客戶類型',
            'AREACODE' => '片區 ',
            'RECEIVESITE' => '收款廠區 ',
            'QUOTATIONTYPE' => '報價類型',
            'QUOTATIONCURRENCY' => '報價幣別 ',
            'SETTLEMODE' => '結算方式  ',
            'SETTLECURRENCY' => '結算幣別',
            'PAYMENTCYCLE' => '付款週期',
            'TERMSCODE' => '交易條件',
            'IMEXTYPE' => '進出口別',
            'BUSINESSTYPE' => '業務類型 ',
            'EXCHANGERATE' => '匯率 ',
            'PAYMENTPAYEE' => '付款法人 ',
            'RECEIVEPAYEE' => '收款法人 ',
            'EFFECTDATE' => '生效時間 ',
            'EXPIREDATE' => '失效時間',
            'BUSINESSNO' => '業務帳號',
            'BUSINESSNAME' => '業務姓名',
            'REMARKS' => '備註',
            'APPENDED' => '附注 ',
            'CARGOTYPE' => '運方式 ',
            'TIMEREQUIREUNIT' => '時效單位',
            'QUOTATIONUNIT' => '物流中心 ',
            'TAXRATE' => '稅率 ',
            'ISTAX' => '是否含稅 ',
            'BLPNAME' => 'BLP名稱 ',
            'FROM_DISTRICT_ID' => '發貨省ID ',
            'FROM_CITY_ID' => '發貨市ID',
            'FROM_DEVISION_ID' => '發貨區ID ',
            'FROM_TOWN_ID' => '發貨鎮ID ',
            'TO_DISTRICT_ID' => '收貨省ID ',
            'TO_CITY_ID' => '收貨市ID ',
            'TO_DEVISION_ID' => '收貨區ID',
            'TO_TOWN_ID' => '收貨鎮ID ',
            'STATUS' => '報價單狀態',
            'PRODUCTTYPE' => '貨品類型 ',
            'QUTATIONCLASS' => '快慢件',
        ];
    }

    //获取陆运的头信息及报价单号
    public static function getLqtNo($TransType, $FromProvince, $FromCity, $ToProvince, $ToCity,$FromProvincePY, $FromCityPY, $ToProvincePY, $ToCityPY)
    {
        $queryParams = [];
        $sql = " SELECT T.SALESQUOTATIONID,
 T.QUTATIONCLASS,
 T.STATUS,
 T.FROM_DISTRICT_ID,
 T.FROM_CITY_ID,
 T.TO_DISTRICT_ID,
 T.TO_CITY_ID,
 T.TIMEREQUIRE,
 T.TIMEREQUIREUNIT,
 T.QUOTATIONUNIT,
 T.TAXRATE,
 T.ISTAX,
 T.SALESQUOTATIONNO,
 T.CARRIERTYPE,
 T.CARGOTYPE
 FROM wms.logisticsquote_land_head T
 WHERE 1=1 AND T.EFFECTDATE<=CURDATE() AND T.EXPIREDATE>CURDATE()";
        if ($FromProvince > -1) {
            $queryParams[':FROM_DISTRICT_ID'] = $FromProvince;
            $sql .= " AND T.FROM_DISTRICT_ID = :FROM_DISTRICT_ID";
        }
        if ($FromCity > -1) {
            $queryParams[':FROM_CITY_ID'] = $FromCity;
            $sql .= " AND T.FROM_CITY_ID = :FROM_CITY_ID";
            //拼音
//            $queryParams[':ORIGIN']=$FromCityPY;
//            $sql.=" AND T.ORIGIN = :ORIGIN";
        } else {
            $queryParams[':FROM_CITY_ID'] = $FromProvince;
            $sql .= " AND T.FROM_CITY_ID = :FROM_CITY_ID";
            //拼音
//            $queryParams[':ORIGIN']=$FromProvincePY;
//            $sql.=" AND T.ORIGIN = :ORIGIN";
        }
        if ($ToProvince > -1) {
            $queryParams[':TO_DISTRICT_ID'] = $ToProvince;
            $sql .= " AND T.TO_DISTRICT_ID = :TO_DISTRICT_ID";
        }
        if ($ToCity > -1) {
            $queryParams[':TO_CITY_ID'] = $ToCity;
            $sql .= " AND T.TO_CITY_ID = :TO_CITY_ID";
            //拼音
//            $queryParams[':DESTINATION']=$ToCityPY;
//            $sql.=" AND T.DESTINATION = :DESTINATION";

        } else {
            $queryParams[':TO_CITY_ID'] = $ToProvince;
            $sql .= " AND T.TO_CITY_ID = :TO_CITY_ID";
           //拼音
//            $queryParams[':DESTINATION']=$ToProvincePY;
//            $sql .= " AND T.DESTINATION = :DESTINATION";
        }
        $sql.=" AND T.STATUS = '1'";
        if ($TransType == "301") {
            $sql.=" AND (T.QUTATIONCLASS = '標準件' OR T.QUTATIONCLASS = '标准件' OR T.QUTATIONCLASS = '標准件' OR T.QUTATIONCLASS = '标準件')";
        } else {
            $sql.=" AND (T.QUTATIONCLASS = '快件' OR T.QUTATIONCLASS = '快件')";
        }
        //file_put_contents('log1.txt', self::getDb()->createCommand($sql,$queryParams)->getRawSql());
        return Yii::$app->getDb('wms')->createCommand($sql,$queryParams)->queryAll();
    }
}
