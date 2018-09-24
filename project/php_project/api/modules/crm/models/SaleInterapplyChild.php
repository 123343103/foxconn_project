<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_interapply_l".
 *
 * @property integer $sial_id
 * @property integer $siah_id
 * @property string $sial_date
 * @property string $sial_address
 * @property string $sial_item
 * @property string $sial_description
 * @property string $sial_custpeople1
 * @property string $sial_custpeople2
 * @property string $sial_custpeople3
 * @property string $sial_sial_custpeople1post
 * @property string $sial_custpeople2post
 * @property string $sial_custpeople3post
 * @property string $sial_comppeole1
 * @property string $sial_comppeole2
 * @property string $sial_appcost
 * @property string $sial_cost
 * @property string $sial_remark
 */
class SaleInterapplyChild extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_interapply_l';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sial_id'], 'required'],
            [['sial_id', 'siah_id'], 'integer'],
            [['sial_date'], 'safe'],
            [['sial_appcost', 'sial_cost'], 'number'],
            [['sial_address', 'sial_item', 'sial_description'], 'string', 'max' => 40],
            [['sial_custpeople1', 'sial_custpeople2', 'sial_custpeople3', 'sial_sial_custpeople1post', 'sial_custpeople2post', 'sial_custpeople3post', 'sial_comppeole1', 'sial_comppeole2'], 'string', 'max' => 20],
            [['sial_remark'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sial_id' => 'Sial ID',
            'siah_id' => '關聯銷售客戶交際費用支出申請主表',
            'sial_date' => '交際日期',
            'sial_address' => '地點',
            'sial_item' => '餐項',
            'sial_description' => '業務內容',
            'sial_custpeople1' => '客戶公司參加人員1',
            'sial_custpeople2' => '客戶公司參加人員2',
            'sial_custpeople3' => '客戶公司參加人員3',
            'sial_sial_custpeople1post' => '人員1職務',
            'sial_custpeople2post' => '人員2職務',
            'sial_custpeople3post' => '人員3職務',
            'sial_comppeole1' => '公司參????員1',
            'sial_comppeole2' => '公司參加人員2',
            'sial_appcost' => '申請金額',
            'sial_cost' => '實際批準金額',
            'sial_remark' => '備註',
        ];
    }
}
