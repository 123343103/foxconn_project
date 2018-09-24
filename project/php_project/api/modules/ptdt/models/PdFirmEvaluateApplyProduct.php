<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "pd_firm_evaluate_apply_product".
 *
 * @property string $product_id
 * @property string $apply_id
 * @property string $evaluate_child_id
 * @property string $report_id
 * @property string $report_child_id
 * @property string $report_product_id
 * @property integer $product_no
 * @property string $product_name
 * @property string $product_size
 * @property string $product_brand
 * @property string $product_unit
 * @property string $delivery_terms
 * @property string $payment_terms
 * @property string $currency_type
 * @property string $product_price
 * @property string $price_max
 * @property string $price_min
 * @property string $price_range
 * @property string $price_average
 * @property string $profit_margin
 * @property string $product_type_1
 * @property string $product_type_2
 * @property string $product_type_3
 * @property string $product_type_4
 * @property string $product_type_5
 * @property string $product_type_6
 * @property integer $product_status
 * @property string $product_remark
 */
class PdFirmEvaluateApplyProduct extends Common
{
    /**
     * 表名
     */
    public static function tableName()
    {
        return 'pd_firm_evaluate_apply_product';
    }

    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            [['apply_id', 'evaluate_child_id', 'report_id', 'report_child_id', 'report_product_id', 'product_no', 'product_status'], 'integer'],
            [['product_name', 'product_size', 'product_brand', 'product_unit', 'delivery_terms', 'payment_terms', 'currency_type', 'product_price', 'price_max', 'price_min', 'price_range', 'price_average', 'profit_margin', 'product_type_1', 'product_type_2', 'product_type_3', 'product_type_4', 'product_type_5', 'product_type_6', 'product_remark'], 'string', 'max' => 255],
        ];
    }

    /**
     * 属性标签
     */
    public function attributeLabels()
    {
        return [
            'product_id' => '评鉴申请商品表id',
            'apply_id' => '评鉴申请表id',
            'evaluate_child_id' => '评鉴子表id',
            'report_id' => '厂商呈报主表id',
            'report_child_id' => '厂商呈报子表id',
            'report_product_id' => '厂商呈报商品表id',
            'product_no' => '商品料号',
            'product_name' => '商品品名',
            'product_size' => '商品规格型号',
            'product_brand' => '商品品牌',
            'product_unit' => '商品单位',
            'delivery_terms' => '交货条件',
            'payment_terms' => '付款条件',
            'currency_type' => '交易类别',
            'product_price' => '商品定价',
            'price_max' => '定价上限',
            'price_min' => '定价下限',
            'price_range' => '量价区间',
            'price_average' => '市场均价',
            'profit_margin' => '利润率',
            'product_type_1' => '一阶',
            'product_type_2' => '二阶',
            'product_type_3' => '三阶',
            'product_type_4' => '四阶',
            'product_type_5' => '五阶',
            'product_type_6' => '六阶',
            'product_status' => '状态',
            'product_remark' => '备注',
        ];
    }
}
