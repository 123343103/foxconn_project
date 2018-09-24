<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "lqt_head".
 *
 * @property string $lqt_id
 * @property string $lqt_no
 * @property integer $lqt_type
 * @property string $TRANSMODE
 * @property string $TRANSTYPE
 * @property string $logqt_cmp
 * @property integer $STATUS
 * @property string $FR_DIST
 * @property string $FR_CITY
 * @property string $FR_DVS
 * @property string $FR_TWN
 * @property string $TO_DIST
 * @property string $TO_CITY
 * @property string $TO_DVS
 * @property string $TO_TWN
 * @property string $EFFECTDATE
 * @property string $EXPIREDATE
 * @property string $CNCY
 * @property string $TQ_CITY
 * @property string $TQ_DVS
 * @property string $TQ_UNIT
 * @property string $QT_CLASS
 * @property string $ORIGIN
 * @property string $DST_NATION
 * @property string $remarks
 * @property string $GSP_DATE
 */
class LqtHead extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lqt_head';
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
            [['lqt_type', 'STATUS'], 'integer'],
            [['EFFECTDATE', 'EXPIREDATE', 'GSP_DATE'], 'safe'],
            [['CNCY', 'remarks'], 'string'],
            [['lqt_no', 'TRANSMODE', 'TRANSTYPE', 'FR_DIST', 'FR_CITY', 'FR_DVS', 'FR_TWN', 'TO_DIST', 'TO_CITY', 'TO_DVS', 'TO_TWN'], 'string', 'max' => 30],
            [['logqt_cmp', 'TQ_CITY', 'TQ_DVS', 'QT_CLASS', 'ORIGIN', 'DST_NATION'], 'string', 'max' => 50],
            [['TQ_UNIT'], 'string', 'max' => 10],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'lqt_id' => '物流報價PKID',
            'lqt_no' => '報價單號',
            'lqt_type' => '報價類型,0陸運,1快遞',
            'TRANSMODE' => '運輸模式',
            'TRANSTYPE' => '運輸類型',
            'logqt_cmp' => '所屬物流公司代碼(管道)',
            'STATUS' => '報價單狀態 0.無效1有效',
            'FR_DIST' => '起運地-省級代碼',
            'FR_CITY' => '起運地-市級代碼',
            'FR_DVS' => '起運地-區級代碼',
            'FR_TWN' => '起運地-鎮級代碼',
            'TO_DIST' => '目的地-省級代碼',
            'TO_CITY' => '目的地-市級代碼',
            'TO_DVS' => '目的地-區級代碼',
            'TO_TWN' => '目的地-鎮級代碼',
            'EFFECTDATE' => '生效日期',
            'EXPIREDATE' => '截止日期',
            'CNCY' => '報價幣別',
            'TQ_CITY' => '地級市及以上時效',
            'TQ_DVS' => '縣級及以下區域時效',
            'TQ_UNIT' => '時效單位',
            'QT_CLASS' => '陸運快慢件標示欄位',
            'ORIGIN' => '起運地(拼音，適用於陸運)',
            'DST_NATION' => '目的地(拼音，適用於陸運)',
            'remarks' => '備注',
            'GSP_DATE' => '抓取時間',
        ];
    }

    public static function getLqtNo($TransMode, $TransType, $FromProvince, $FromCity, $ToProvince, $ToCity)
    {
        $queryParams = [];
        $queryParams[':TransType'] = $TransType;
        $sql = "select T.lqt_id,T.TQ_CITY,T.TQ_DVS
from lqt_head T where T.TRANSTYPE=:TransType";
        //只有快递的才有运输模式，为20
        if ($TransMode == "20") {
            $queryParams[':TransMode'] = $TransMode;
            $sql .= " AND T.TRANSMODE=:TransMode";
        }
        if ($FromProvince > -1) {
            $queryParams[':FR_DIST'] = $FromProvince;
            $sql .= " AND T.FR_DIST=:FR_DIST";
        }
        if ($FromCity > -1) {
            $queryParams[':FR_CITY'] = $FromCity;
            $sql .= " AND T.FR_CITY=:FR_CITY";
        } else {
            $queryParams[':FR_CITY'] = $FromProvince;
            $sql .= " AND T.FR_CITY=:FR_CITY";
        }
        if ($ToProvince > -1) {
            $queryParams[':TO_DIST'] = $ToProvince;
            $sql .= " AND T.TO_DIST=:TO_DIST";
        }
        if ($ToCity > -1) {
            $queryParams[':TO_CITY'] = $ToCity;
            $sql .= " AND T.TO_CITY=:TO_CITY";
        } else {
            $queryParams[':TO_CITY'] = $ToProvince;
            $sql .= " AND T.TO_CITY=:TO_CITY";
        }
        if ($TransType == "301") {
            $sql .= " AND (T.QT_CLASS='标准件' OR T.QT_CLASS='標準件')";
        }
        if ($TransType == "302") {
            $sql .= " AND (T.QT_CLASS='快件' OR T.QT_CLASS='快件')";
        }
        $sql .= " AND T.STATUS='1' ORDER BY T.GSP_DATE";
        file_put_contents('log.txt', self::getDb()->createCommand($sql,$queryParams)->getRawSql());
        return self::getDb()->createCommand($sql, $queryParams)->queryAll();
    }

}
