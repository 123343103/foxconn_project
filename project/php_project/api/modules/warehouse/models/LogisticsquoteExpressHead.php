<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "logisticsquote_express_head".
 *
 * @property string $QUOTATIONNO
 * @property string $QUOTATIONDATE
 * @property string $CUSTOMER
 * @property string $BU
 * @property string $CNCY
 * @property integer $ORIGIN_DISTRICTID
 * @property integer $ORIGIN_CITYID
 * @property string $GOODSTYPE
 * @property string $EFFECTDATE
 * @property string $EXPIREDATE
 * @property string $COSTCONFIRMEDDATE
 * @property string $TRANSMODE
 * @property string $TRANSTYPE
 * @property integer $DISTRICT_ID
 * @property integer $CITY_ID
 * @property string $STATUS
 * @property string $REMARK
 * @property string $TRANSITTIME1
 * @property string $TRANSITTIME2
 */
class LogisticsquoteExpressHead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logisticsquote_express_head';
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
            [['QUOTATIONNO'], 'required'],
            [['QUOTATIONDATE', 'EFFECTDATE', 'EXPIREDATE', 'COSTCONFIRMEDDATE'], 'safe'],
            [['ORIGIN_DISTRICTID', 'ORIGIN_CITYID', 'DISTRICT_ID', 'CITY_ID'], 'integer'],
            [['QUOTATIONNO', 'CNCY', 'STATUS'], 'string', 'max' => 30],
            [['CUSTOMER'], 'string', 'max' => 100],
            [['BU'], 'string', 'max' => 150],
            [['GOODSTYPE', 'TRANSMODE', 'TRANSTYPE', 'TRANSITTIME1', 'TRANSITTIME2'], 'string', 'max' => 60],
            [['REMARK'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'QUOTATIONNO' => '报价单号',
            'QUOTATIONDATE' => '报价日期',
            'CUSTOMER' => '客戶 ',
            'BU' => 'BU',
            'CNCY' => '币别',
            'ORIGIN_DISTRICTID' => '起始地省ID',
            'ORIGIN_CITYID' => '起始地市ID',
            'GOODSTYPE' => '商品類型 ',
            'EFFECTDATE' => '生效日期',
            'EXPIREDATE' => '截止日期',
            'COSTCONFIRMEDDATE' => '经管确认日期',
            'TRANSMODE' => '运输模式 5--空運20--快遞 ',
            'TRANSTYPE' => '运输类型 201--標準快遞（快遞）202--經濟快遞（快遞）501--空運普件（空運）502--空運急件（空運）503--精品快線（空運） ',
            'DISTRICT_ID' => '目的地省ID',
            'CITY_ID' => '目的地市ID',
            'STATUS' => '状态 ',
            'REMARK' => '备注',
            'TRANSITTIME1' => '配送時效：地級市及以上時效 ',
            'TRANSITTIME2' => '配送時效：縣級及以下區域時效 ',
        ];
    }

    public static function getLqtNo($TransMode, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity)
    {
        $queryParams=[];
        $sql=" SELECT IFNULL(T.QUOTATIONNO,'-1') QUOTATIONNO,
T.TRANSITTIME1,
T.TRANSITTIME2
 FROM wms.logisticsquote_express_head T
 WHERE T.STATUS = 'Confirm' AND T.EFFECTDATE<=CURDATE() AND T.EXPIREDATE>CURDATE()
 AND T.TRANSMODE = '{$TransMode}'
 AND T.TRANSTYPE ='{$TransType}'";
        if ($FromProvince > -1)
        {
            $queryParams[':FROM_DISTRICT_ID']=$FromProvince;
            $sql.=" AND T.ORIGIN_DISTRICTID = :FROM_DISTRICT_ID";
        }
        if ($FromCity > -1)
        {
            $queryParams[':FROM_CITY_ID']=$FromCity;
            $sql.=" AND T.ORIGIN_CITYID = :FROM_CITY_ID";
        }
        else
        {
            $queryParams[':FROM_CITY_ID']=$FromProvince;
            $sql.=" AND T.ORIGIN_CITYID = :FROM_CITY_ID";
        }
        if ($ToProvince > -1)
        {
            $queryParams[':TO_DISTRICT_ID']=$ToProvince;
            $sql.=" AND T.DISTRICT_ID = :TO_DISTRICT_ID";
        }
        if ($ToCity > -1)
        {
            $queryParams[':TO_CITY_ID']=$ToCity;
            $sql.=" AND T.CITY_ID = :TO_CITY_ID";
        }
        else
        {
            $queryParams[':TO_CITY_ID']=$ToProvince;
            $sql.=" AND T.CITY_ID = :TO_CITY_ID";
        }
        $sql.=" ORDER BY T.QUOTATIONDATE";
        return Yii::$app->getDb('wms')->createCommand($sql,$queryParams)->queryAll();
    }
}
