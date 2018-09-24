<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "logisticsquote_express_detail".
 *
 * @property string $QUOTATIONNO
 * @property string $ITEMNO
 * @property string $COSTNO
 * @property string $COSTNAME
 * @property string $UOM
 * @property string $WEIGHTMIN
 * @property string $FIRSTPRICE
 * @property string $NEXTWEIGHT
 * @property string $NEXT_RATE
 * @property string $MIN_VALUE
 * @property string $MAX_VALUE
 * @property string $CHARGEMIN
 * @property string $CHARGEMAX
 * @property string $TRANSITTIME
 * @property string $EFFECTDATE
 * @property string $EXPIREDATE
 * @property string $REMARK
 * @property string $COSTCONFIRMEDDATE
 * @property string $STATUS
 * @property string $TRANSITTIME1
 * @property string $TRANSITTIME2
 */
class LogisticsquoteExpressDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logisticsquote_express_detail';
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
            [['ITEMNO', 'WEIGHTMIN', 'FIRSTPRICE', 'NEXTWEIGHT', 'NEXT_RATE', 'MIN_VALUE', 'MAX_VALUE', 'CHARGEMIN', 'CHARGEMAX'], 'number'],
            [['EFFECTDATE', 'EXPIREDATE', 'COSTCONFIRMEDDATE'], 'safe'],
            [['QUOTATIONNO', 'COSTNO', 'UOM', 'TRANSITTIME', 'STATUS', 'TRANSITTIME1', 'TRANSITTIME2'], 'string', 'max' => 60],
            [['COSTNAME'], 'string', 'max' => 120],
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
            'ITEMNO' => '项次',
            'COSTNO' => '費用項目代碼 ',
            'COSTNAME' => '費用項目名稱 ',
            'UOM' => '计量单位',
            'WEIGHTMIN' => '首重重量',
            'FIRSTPRICE' => '首重价格',
            'NEXTWEIGHT' => '续重重量',
            'NEXT_RATE' => '续重价格',
            'MIN_VALUE' => '区间下限',
            'MAX_VALUE' => '区间上限',
            'CHARGEMIN' => '最小费用',
            'CHARGEMAX' => '最高费用',
            'TRANSITTIME' => '配送时效',
            'EFFECTDATE' => '生效日期',
            'EXPIREDATE' => '截止日期',
            'REMARK' => '备注',
            'COSTCONFIRMEDDATE' => '經管確認日期',
            'STATUS' => '状态',
            'TRANSITTIME1' => '配送時效：地級市及以上時效',
            'TRANSITTIME2' => '配送時效：縣級及以下區域時效',
        ];
    }

    public static function getWeightMin($Weight, $quotationno)
    {
        $sql = " SELECT 
 T.QUOTATIONNO,
 T.MIN_VALUE,
 T.MAX_VALUE,
 IFNULL(T.WEIGHTMIN,1) WEIGHTMIN,
 IFNULL(T.FIRSTPRICE,0) FIRSTPRICE,
 IFNULL(T.NEXTWEIGHT,1) NEXTWEIGHT,
 IFNULL(T.NEXT_RATE,0) NEXT_RATE,
 IFNULL(T.TRANSITTIME,0) TRANSITTIME
 FROM wms.logisticsquote_express_detail T
 WHERE T.STATUS = 'Confirm'
 AND T.QUOTATIONNO = '{$quotationno}'
 AND T.MIN_VALUE <= {$Weight}
 AND T.MAX_VALUE >=  {$Weight}";
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    public static function getOtherInterval($quotationno,$pdtMinWeight,$pdtWeightMin)
    {
        $sql=" SELECT 
 T.MIN_VALUE,
 T.MAX_VALUE,
 IFNULL(T.NEXTWEIGHT,1) NEXTWEIGHT,
 IFNULL(T.NEXT_RATE,0) NEXT_RATE,
 IFNULL(T.TRANSITTIME,0) TRANSITTIME
 FROM wms.logisticsquote_express_detail T
 WHERE T.STATUS = 'Confirm'
 AND T.QUOTATIONNO = '{$quotationno}'
 AND T.MAX_VALUE <= $pdtMinWeight
 AND T.MIN_VALUE > $pdtWeightMin";
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }
}
