<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "bs_category_unit".
 *
 * @property integer $id
 * @property integer $unit_code
 * @property string $unit_name
 * @property string $createat
 * @property string $createby
 * @property string $updateat
 * @property string $updateby
 */
class BsCategoryUnit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_category_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unit_name'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unit_code' => 'Unit Code',
            'unit_name' => 'Unit Name',
            'createat' => 'Createat',
            'createby' => 'Createby',
            'updateat' => 'Updateat',
            'updateby' => 'Updateby',
        ];
    }
}
