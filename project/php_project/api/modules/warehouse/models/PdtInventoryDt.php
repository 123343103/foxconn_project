<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "wms.pdt_inventory_dt".
 *
 * @property string $ivtdt_id
 * @property string $ivt_code
 * @property string $first_num
 * @property string $re_num
 * @property string $lose_num
 * @property string $lose_price
 * @property string $remarks
 */
class PdtInventoryDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.pdt_inventory_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ivt_code'], 'required'],
            [['first_num', 're_num', 'lose_num', 'lose_price'], 'number'],
            [['ivt_code'], 'string', 'max' => 30],
            [['part_no'], 'string', 'max' => 20],
            [['remarks','remarks1'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ivtdt_id' => 'Ivtdt ID',
            'ivt_code' => 'Ivt Code',
            'first_num' => 'First Num',
            're_num' => 'Re Num',
            'lose_num' => 'Lose Num',
            'lose_price' => 'Lose Price',
            'remarks' => 'Remarks',
            'remarks1' => 'Remarks1',
            'part_no'=>'part_no'
        ];
    }
}
