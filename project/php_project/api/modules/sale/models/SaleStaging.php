<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_staging" 订单分期付款记录表.
 *
 * @property integer $stag_id
 * @property integer $saph_id
 * @property integer $soh_id
 * @property integer $stag_qty
 * @property integer $stag_times
 * @property string $stag_date
 * @property string $stag_cost
 * @property string $stag_desrition
 */
class SaleStaging extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_staging'; // 订单分期付款记录表
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
            [['stag_id', 'saph_id', 'soh_id', 'stag_qty', 'stag_times'], 'integer'],
            [['stag_date'], 'safe'],
            [['stag_cost'], 'number'],
            [['stag_desrition'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stag_id' => 'Stag ID',
            'saph_id' => '报价单 ID',
            'soh_id' => '销售订单 ID',
            'stag_qty' => '记录分期数',
            'stag_times' => '第几期',
            'stag_date' => '每期分期日期',
            'stag_cost' => '約定每期付款費用',
            'stag_desrition' => '备注',
        ];
    }
}
