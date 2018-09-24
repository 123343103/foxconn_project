<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "lqt_land".
 *
 * @property string $land_id
 * @property string $lqt_no
 * @property string $payterms
 * @property string $en_servicescope
 * @property string $ischoise
 * @property integer $orderby
 * @property string $itemcname
 * @property string $itemcode
 * @property string $trucktype
 * @property string $calcscope1
 * @property string $calcscope2
 * @property string $uom
 * @property string $rate
 * @property string $minicharge
 * @property string $taxrate
 * @property string $taxtype
 * @property string $remarks
 * @property string $truckgroup
 * @property string $costtype
 * @property integer $maxcharge
 * @property string $currency
 * @property string $issend
 * @property string $ap_date
 * @property string $source
 * @property string $update_date
 * @property integer $error_send
 */
class LqtLand extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lqt_land';
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
            [['land_id', 'lqt_id'], 'required'],
            [['land_id','lqt_id', 'orderby', 'maxcharge', 'error_send'], 'integer'],
            [['calcscope1', 'calcscope2', 'rate', 'minicharge', 'taxrate'], 'number'],
            [['remarks'], 'string'],
            [['ap_date', 'update_date'], 'safe'],
            [['lqt_no'], 'string', 'max' => 30],
            [['payterms', 'en_servicescope', 'itemcname', 'itemcode', 'trucktype', 'uom', 'taxtype'], 'string', 'max' => 250],
            [['ischoise'], 'string', 'max' => 2],
            [['truckgroup', 'costtype', 'currency'], 'string', 'max' => 200],
            [['issend'], 'string', 'max' => 1],
            [['source'], 'string', 'max' => 50],
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'land_id' => 'PKID',
            'lqt_id' => '報價單號',
            'payterms' => '成交條件',
            'en_servicescope' => '服務範圍',
            'ischoise' => '是否選擇',
            'orderby' => '排序號',
            'itemcname' => '費用項目',
            'itemcode' => '費用項目代碼',
            'trucktype' => '車型',
            'calcscope1' => '計費起始值',
            'calcscope2' => '計費截止值',
            'uom' => '計算單位',
            'rate' => '價格',
            'minicharge' => '最小收費',
            'taxrate' => '稅率',
            'taxtype' => '稅種',
            'remarks' => '備註',
            'truckgroup' => '車種',
            'costtype' => '費用類型',
            'maxcharge' => '最大收費',
            'currency' => '報價幣別',
            'issend' => '是否拋轉',
            'ap_date' => '拋轉時間',
            'source' => '拋轉數據的來源地 ( 新增字段)',
            'update_date' => '數據更新時間  (新增字段)',
            'error_send' => '是否發送錯誤郵件',
        ];
    }
    //关联报价单头表
    public function getLqtno()
    {
        return $this->hasOne(LqtHead::className(),['lqt_id'=>'lqt_id']);
    }
//獲取陸運報價詳細信息
    public static function getLandInfo($lqtid)
    {
        $queryParams = [];
        $queryParams[':lqt_id'] = $lqtid;
        $sql = "select t.rate,
t.land_id,
t.maxcharge,
t.minicharge,
t.taxrate,
t.taxtype,
t.trucktype,
t.uom,
t.currency from lqt_land t where t.lqt_id=:lqt_id
and (t.trucktype='零担' or t.trucktype='零擔')";
        return self::getDb()->createCommand($sql, $queryParams)->queryAll();
    }
}
