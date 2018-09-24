<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "logisticsquote_land_detail".
 *
 * @property integer $SALESQUOTATIONID
 * @property integer $SALESQUOTATIONDETAILID
 * @property string $PAYTERMS
 * @property string $EN_SERVICESCOPE
 * @property string $ISCHOISE
 * @property integer $ORDERBY
 * @property string $ITEMCNAME
 * @property string $ITEMCODE
 * @property string $TRUCKTYPE
 * @property string $CALCSCOPE1
 * @property string $CALCSCOPE2
 * @property string $UOM
 * @property string $RATE
 * @property string $MINICHARGE
 * @property string $TAXRATE
 * @property string $TAXTYPE
 * @property string $REMARKS
 * @property string $TRUCKGROUP
 * @property string $COSTTYPE
 * @property string $MAXCHARGE
 * @property string $CURRENCY
 */
class LogisticsquoteLandDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logisticsquote_land_detail';
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
            [['SALESQUOTATIONID', 'SALESQUOTATIONDETAILID'], 'required'],
            [['SALESQUOTATIONID', 'SALESQUOTATIONDETAILID', 'ORDERBY'], 'integer'],
            [['CALCSCOPE1', 'CALCSCOPE2', 'RATE', 'MINICHARGE', 'TAXRATE', 'MAXCHARGE'], 'number'],
            [['PAYTERMS', 'EN_SERVICESCOPE', 'ITEMCNAME', 'ITEMCODE', 'TRUCKTYPE', 'UOM', 'TAXTYPE'], 'string', 'max' => 100],
            [['ISCHOISE'], 'string', 'max' => 4],
            [['REMARKS'], 'string', 'max' => 1024],
            [['TRUCKGROUP', 'COSTTYPE', 'CURRENCY'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'SALESQUOTATIONID' => '報價ID ',
            'SALESQUOTATIONDETAILID' => '報價详情ID',
            'PAYTERMS' => '成交條件',
            'EN_SERVICESCOPE' => '服務範圍 ',
            'ISCHOISE' => '是否選擇',
            'ORDERBY' => '排序號 ',
            'ITEMCNAME' => '費用項目',
            'ITEMCODE' => '費用項目代碼 ',
            'TRUCKTYPE' => '車型',
            'CALCSCOPE1' => '計費起始值',
            'CALCSCOPE2' => '計費截止值 ',
            'UOM' => '計算單位',
            'RATE' => '價格 ',
            'MINICHARGE' => '最小收費 ',
            'TAXRATE' => '稅率',
            'TAXTYPE' => '稅種 ',
            'REMARKS' => '備註',
            'TRUCKGROUP' => '車種',
            'COSTTYPE' => '費用類型',
            'MAXCHARGE' => '最大收費 ',
            'CURRENCY' => '報價幣別',
        ];
    }

    //获取陆运报价信息
    public static function getLandInfo($salesquotationid)
    {
        $sql = " SELECT S.SALESQUOTATIONID,
 S.SALESQUOTATIONDETAILID,
 S.ITEMCODE,
 S.ITEMCNAME,
 S.MINICHARGE,
 S.MAXCHARGE,
 S.RATE,
 S.TRUCKTYPE,
 S.UOM,
 S.TAXRATE,
 S.TAXTYPE,
 S.CURRENCY
 FROM wms.logisticsquote_land_detail S
 WHERE S.SALESQUOTATIONID = {$salesquotationid}
 AND (S.TRUCKTYPE = '零担' OR S.TRUCKTYPE = '零擔')";
 return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

    //获取提货费与送货费
    public static function getOtherFee($salesquotationid,$itemcode)
    {
        $sql="select S.RATE,S.MINICHARGE,S.MAXCHARGE from wms.logisticsquote_land_detail S where 
              S.SALESQUOTATIONID={$salesquotationid} AND S.ITEMCODE='{$itemcode}'";
        return Yii::$app->getDb('wms')->createCommand($sql)->queryAll();
    }

}
