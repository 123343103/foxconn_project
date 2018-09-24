<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_pack".
 *
 * @property string $l_pack_pkid
 * @property string $l_prt_pkid
 * @property integer $pck_type
 * @property string $pdt_length
 * @property string $pdt_width
 * @property string $pdt_height
 * @property string $pdt_weight
 * @property string $pdt_mater
 * @property string $pdt_qty
 * @property integer $plate_num
 * @property integer $yn
 */
class LPack extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_pack';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_prt_pkid'], 'required'],
            [['l_prt_pkid', 'pck_type', 'plate_num', 'yn'], 'integer'],
            [['pdt_length', 'pdt_width', 'pdt_height', 'pdt_weight', 'pdt_qty'], 'number'],
            [['pdt_mater'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_pack_pkid' => 'L Pack Pkid',
            'l_prt_pkid' => 'L Prt Pkid',
            'pck_type' => 'Pck Type',
            'pdt_length' => 'Pdt Length',
            'pdt_width' => 'Pdt Width',
            'pdt_height' => 'Pdt Height',
            'pdt_weight' => 'Pdt Weight',
            'pdt_mater' => 'Pdt Mater',
            'pdt_qty' => 'Pdt Qty',
            'plate_num' => 'Plate Num',
            'yn' => 'Yn',
        ];
    }
}
