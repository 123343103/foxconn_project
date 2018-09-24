<?php

namespace app\modules\spp\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "supplier_purpdt".
 *
 * @property string $id
 * @property string $spp_id
 * @property string $prt_pkid
 * @property integer $status
 */
class SupplierPurpdt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_purpdt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('spp');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spp_id'], 'required'],
            [['spp_id', 'prt_pkid', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '拟采购商品id',
            'spp_id' => '供应商id(关联spp.bs_supplier.spp_id)',
            'prt_pkid' => '料号id(关联pdt.bs_partno.prt_pkid)',
            'status' => '状态',
        ];
    }
}