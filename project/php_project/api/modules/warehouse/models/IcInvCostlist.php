<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "ic_inv_costlist".
 *
 * @property integer $invcl_id
 * @property integer $invch_id
 * @property integer $whp_id
 * @property integer $whpl_id
 * @property string $invcl_nprice
 * @property string $subitem_remark
 */
class IcInvCostlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.ic_inv_costlist';
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
            [['invch_id', 'whp_id', 'whpl_id'], 'integer'],
            [['invcl_nprice'], 'number'],
            [['subitem_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invcl_id' => 'Invcl ID',
            'invch_id' => 'Invch ID',
            'whp_id' => 'Whp ID',
            'whpl_id' => 'Whpl ID',
            'invcl_nprice' => 'Invcl Nprice',
            'subitem_remark' => 'Subitem Remark',
        ];
    }
}
