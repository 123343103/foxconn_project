<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "bs_sit_invt".
 *
 * @property string $invt_id
 * @property string $wh_code
 * @property string $wh_name
 * @property string $st_code
 * @property string $catg_name
 * @property string $pdt_name
 * @property string $brand_name
 * @property string $batch_no
 * @property string $tp_spec
 * @property string $part_no
 * @property string $unit_name
 * @property string $invt_num
 * @property string $lock_num
 * @property string $opp_date
 */
class BsSitInvt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_sit_invt';
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
            [['invt_id', 'wh_code', 'wh_name', 'st_code', 'part_no', 'unit_name'], 'required'],
            [['invt_id'], 'integer'],
            [['invt_num', 'lock_num'], 'number'],
            [['opp_date'], 'safe'],
            [['wh_code', 'st_code', 'part_no', 'unit_name'], 'string', 'max' => 30],
            [['wh_name', 'pdt_name', 'tp_spec'], 'string', 'max' => 200],
            [['catg_name', 'brand_name', 'batch_no'], 'string', 'max' => 100],
            [['part_no', 'wh_code', 'wh_name', 'st_code', 'unit_name'], 'unique', 'targetAttribute' => ['part_no', 'wh_code', 'wh_name', 'st_code', 'unit_name'], 'message' => 'The combination of 倉庫代碼,bs_wh.wh_code, 倉庫名稱, 儲位碼，bs_st.st_code, 料號 and 數量單位，中文名稱 has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invt_id' => 'pkid',
            'wh_code' => '倉庫代碼,bs_wh.wh_code',
            'wh_name' => '倉庫名稱',
            'st_code' => '儲位碼，bs_st.st_code',
            'catg_name' => '類別名稱，中文名稱',
            'pdt_name' => '品名',
            'brand_name' => '品牌，中文名稱',
            'batch_no' => '批次',
            'tp_spec' => '規格，中文名稱',
            'part_no' => '料號',
            'unit_name' => '數量單位，中文名稱',
            'invt_num' => '可用庫存量。入庫增加，揀貨減少。小於0為異常情況',
            'lock_num' => '鎖定庫存量。揀貨增加，出庫減少。小於0為異常情況',
            'opp_date' => '更新時間',
        ];
    }
}
