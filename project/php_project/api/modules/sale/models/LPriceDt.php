<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "l_price_dt".
 *
 * @property string $l_price_dt_id
 * @property string $l_price_id
 * @property string $prt_pkid
 * @property integer $is_gift
 * @property string $sapl_quantity
 * @property string $uprice_ntax_o
 * @property string $uprice_tax_o
 * @property string $uprice_ntax_c
 * @property string $uprice_tax_c
 * @property string $tprice_ntax_o
 * @property string $tprice_tax_o
 * @property string $tprice_ntax_c
 * @property string $tprice_tax_c
 * @property string $pack_type
 * @property string $whs_id
 * @property string $cess
 * @property string $discount
 * @property string $distribution
 * @property string $tax_freight
 * @property string $freight
 * @property integer $transport
 * @property integer $sapl_status
 * @property string $suttle
 * @property string $gross_weight
 * @property string $request_date
 * @property string $consignment_date
 * @property string $sapl_remark
 *
 * @property LPriceInfo $lPrice
 */
class LPriceDt extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_price_dt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_price_id', 'prt_pkid', 'is_gift', 'whs_id', 'distribution', 'transport', 'sapl_status'], 'integer'],
            [['sapl_quantity', 'uprice_ntax_o', 'uprice_tax_o', 'uprice_ntax_c', 'uprice_tax_c', 'tprice_ntax_o', 'tprice_tax_o', 'tprice_ntax_c', 'tprice_tax_c', 'cess', 'discount', 'tax_freight', 'freight', 'suttle', 'gross_weight'], 'number'],
            [['request_date', 'consignment_date'], 'safe'],
            [['pack_type'], 'string', 'max' => 20],
            [['sapl_remark'], 'string', 'max' => 200],
            [['l_price_id'], 'exist', 'skipOnError' => true, 'targetClass' => LPriceInfo::className(), 'targetAttribute' => ['l_price_id' => 'l_price_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_price_dt_id' => '報價日志明細pkid',
            'l_price_id' => '報價日志pkid，由此產生審核流程',
            'prt_pkid' => '料號pkid,  pdt.bs_partno',
            'is_gift' => '是否贈品,默認0不是贈品。若原幣總價為0，是贈品。',
            'sapl_quantity' => '數量',
            'uprice_ntax_o' => '單價(未稅)-原幣',
            'uprice_tax_o' => '單價(含稅)-原幣',
            'uprice_ntax_c' => '單價(未稅)-本幣',
            'uprice_tax_c' => '單價(含稅)-本幣',
            'tprice_ntax_o' => '總價(未稅)-原幣',
            'tprice_tax_o' => '總價(含稅)-原幣',
            'tprice_ntax_c' => '總價(未稅)-本幣',
            'tprice_tax_c' => '總價(含稅-本幣',
            'pack_type' => '包裝方式',
            'whs_id' => '發貨倉庫.wms.bs_wh',
            'cess' => '稅率',
            'discount' => '折扣率=商品單價/原幣含稅單價',
            'distribution' => '配送方式',
            'tax_freight' => '含稅物流費用',
            'freight' => '未稅物流費用',
            'transport' => '運輸方式',
            'sapl_status' => '狀態. 默?0 未提交，1提交',
            'suttle' => '淨重',
            'gross_weight' => '毛重',
            'request_date' => '需求交期',
            'consignment_date' => '交貨日期',
            'sapl_remark' => '備註',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLPrice()
    {
        return $this->hasOne(LPriceInfo::className(), ['l_price_id' => 'l_price_id']);
    }
}
