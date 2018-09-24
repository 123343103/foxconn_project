<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;
/**
 * This is the model class for table "bs_invt".
 *
 * @property string $invt_id
 * @property string $wh_id
 * @property string $wh_name
 * @property string $part_no
 * @property string $invt_num
 * @property string $lock_num
 * @property string $opp_date
 */
class BsInvt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_invt';
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
            [['wh_id', 'part_no'], 'required'],
            [['wh_id'], 'integer'],
            [['invt_num', 'lock_num'], 'number'],
            [['opp_date'], 'safe'],
            [['wh_name'], 'string', 'max' => 200],
            [['part_no'], 'string', 'max' => 30],
            [['wh_id', 'part_no'], 'unique', 'targetAttribute' => ['wh_id', 'part_no'], 'message' => 'The combination of 倉庫ID,bs_wh.wh_id and 料號 has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invt_id' => 'PKID',
            'wh_id' => '倉庫ID,bs_wh.wh_id',
            'wh_name' => '倉庫名稱',
            'part_no' => '料號',
            'invt_num' => '可用庫存。入庫增加可用庫存量。小於0為異常情況',
            'lock_num' => '鎖定量。支付：減少可用庫存量，增加鎖定量；出庫：隻減少鎖定量。小於0為異常情況',
            'opp_date' => 'Opp Date',
        ];
    }
}
