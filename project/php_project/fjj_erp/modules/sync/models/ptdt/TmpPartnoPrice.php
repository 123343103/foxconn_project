<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/25
 * Time: 上午 10:49
 */

namespace app\modules\sync\models\ptdt;


use yii\db\ActiveRecord;

class TmpPartnoPrice extends ActiveRecord
{
    public static function tableName()
    {
        return 'tmp_partno_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ["PART_NO","required"],
            [["STATUS",
                "PDT_MANAGER",
                "PRICE_TYPE",
                "PRICE_FROM",
                "MARKET_PRICE",
                "VALID_DATE",
                "P_FLAG",
                "SUPPLIER_CODE",
                "SUPPLIER_NAME_SHOT",
                "DELIVERY_ADDRESS",
                "PAYMENT_TERMS",
                "TRADING_TERMS",
                "MIN_PRICE",
                "WS_UPPER_PRICE",
                "WS_LOWER_PRICE",
                "GROSS_PROFIT",
                "GROSS_PROFIT_MARGIN",
                "PRE_TAX_PROFIT",
                "PRE_TAX_PROFIT_RATE",
                "AFTER_TAX_PROFIT",
                "AFTER_TAX_PROFIT_MARGIN",
                "NUM_AREA",
                "UPPER_LIMIT_PROFIT",
                "LOWER_LIMIT_PROFIT_MARGIN",
                "PRE_WS_LOWER_PRICE",
                "LIMIT_DAY",
                "PDT_LEVEL",
                "UNTIL",
                "MIN_ORDER",
                "CURRENCY",
                "BUY_PRICE"],"safe"]
        ];
    }

}