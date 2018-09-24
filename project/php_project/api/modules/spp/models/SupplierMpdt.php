<?php

namespace app\modules\spp\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "supplier_mpdt".
 *
 * @property string $id
 * @property string $spp_id
 * @property string $mian_pdt
 * @property string $pdt_ad
 * @property string $pdt_sca
 * @property string $sale_quan
 * @property double $market_share
 * @property string $open_sale
 * @property string $agency
 * @property integer $status
 */
class SupplierMpdt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'supplier_mpdt';
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
            [['spp_id', 'status'], 'integer'],
            [['market_share'], 'number'],
            [['mian_pdt'], 'string', 'max' => 50],
            [['pdt_ad', 'pdt_sca'], 'string', 'max' => 200],
            [['sale_quan'], 'string', 'max' => 20],
            [['open_sale', 'agency'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '商品id',
            'spp_id' => '供应商id(关联spp.bs_supplier.spp_id)',
            'mian_pdt' => '主营商品',
            'pdt_ad' => '商品优势与不足',
            'pdt_sca' => '销售渠道与区域',
            'sale_quan' => '年销售量(单位)',
            'market_share' => '市场份额(%)',
            'open_sale' => '是否公开销售(Y:是,N:否)',
            'agency' => '能否代理(Y:是,N:否)',
            'status' => '状态',
        ];
    }
}