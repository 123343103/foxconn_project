<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/22
 * Time: 上午 09:43
 */

namespace app\modules\sync\models\ptdt;


use yii\db\ActiveRecord;

class TmpPartno extends ActiveRecord
{
    public static function tableName()
    {
        return 'tmp_partno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ["PART_NO","required"],
            [["PDT_NAME",
                "CATEGORY_ID",
                "TP_SPEC",
                "STATUS",
                "BRAND",
                "UNIT",
                "APPLICANT",
                "APPLYDEP",
                "APPLYDATE",
                "CREATDATE",
                "THEDTABASE",
                "PDT_MANAGER",
                "PRICE_TYPE",
                "PRICE_FROM",
                "ISKZ",
                "ISPROXY",
                "ISONLINESELL",
                "RISK_LEVEL",
                "MARKET_PRICE",
                "LOWER_PROFIT",
                "UPPER_PROFIT",
                "VALID_DATE",
                "ISTITLE",
                "ARCHRIVAL",
                "PDT_LEVEL",
                "ISTO_XS",
                "LIRUNSX",
                "LIRUNXX",
                "SO_NBR",
                "SALEAREA",
                "USEFOR",
                "PACKAGESPC",
                "ISRELATION",
                "RELATION_REMARK",
                "P_FLAG",
                "PARTNO_REMARK",
                "ISTITLE_SO_NBR",
                "ISTITLE_STATUS",
                "ISTITLE_EDITOR",
                "BRAND2",
                "CENTER",
                "BU",
                "COMMODITY",
                "PRICEDATE",
                "ISVALID",
                "VALIDSTATUS",
                "VALID_SO_NBR"
            ],"safe"]
        ];
    }
}