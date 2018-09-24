<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "category_unit".
 *
 * @property integer $ID
 * @property integer $UNIT_CODE
 * @property string $UNIT_NAME
 * @property string $CREATEDDT
 * @property string $CREATEDBY
 * @property string $LASTEDITDT
 * @property string $LASTEDITBY
 */
class CategoryUnit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category_unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'UNIT_CODE', 'UNIT_NAME'], 'required'],
            [['ID', 'UNIT_CODE'], 'integer'],
            [['CREATEDDT', 'LASTEDITDT'], 'safe'],
            [['UNIT_NAME', 'CREATEDBY', 'LASTEDITBY'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'UNIT_CODE' => 'Unit  Code',
            'UNIT_NAME' => 'Unit  Name',
            'CREATEDDT' => 'Createddt',
            'CREATEDBY' => 'Createdby',
            'LASTEDITDT' => 'Lasteditdt',
            'LASTEDITBY' => 'Lasteditby',
        ];
    }
}
