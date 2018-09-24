<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "l_invt_re".
 *
 * @property string $l_r_id
 * @property integer $l_types
 * @property string $wh_code
 * @property string $wh_name
 * @property string $st_code
 * @property string $l_r_no
 * @property string $pdt_name
 * @property string $batch_no
 * @property string $part_no
 * @property string $unit_name
 * @property string $lock_nums
 * @property string $invt_nums
 * @property string $opp_date
 * @property string $opper
 * * @property integer $yn
 */
class LInvtRe extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_invt_re';
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
            [['l_types', 'wh_code', 'wh_name', 'l_r_no', 'part_no', 'unit_name', 'yn'], 'required'],
            [['l_types', 'yn'], 'integer'],
            [['lock_nums', 'invt_nums'], 'number'],
            [['opp_date'], 'safe'],
            [['wh_code', 'st_code', 'l_r_no', 'part_no', 'unit_name', 'opper'], 'string', 'max' => 30],
            [['wh_name', 'pdt_name'], 'string', 'max' => 200],
            [['batch_no'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_r_id' => 'PKID',
            'l_types' => '異動類型，0支付，1採購入庫，2訂單出庫，3異動入库，4異動出库，5報廢入庫，6報廢出庫，7調撥入庫，8調撥出庫，9撿貨。',
            'wh_code' => '倉庫代碼',
            'wh_name' => '倉庫名稱',
            'st_code' => '儲位代碼(支付時為空)',
            'l_r_no' => '對應單號，與異動類型有關',
            'pdt_name' => '品名',
            'batch_no' => '批次',
            'part_no' => '料號',
            'unit_name' => '數量單位，中文名稱',
            'lock_nums' => '鎖定異動量，支付時，異動量為正的訂單數量。訂單出庫，異動量為負的出庫數量',
            'invt_nums' => '庫存異動量，入庫單，異動量為正的入庫數。出庫時，異動量為負的出庫數。',
            'opp_date' => '操作日期',
            'opper' => '操作人(工号-姓名)',
            'yn' => '是否統計.0未統計.',
        ];
    }
}
