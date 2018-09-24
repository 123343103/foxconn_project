<?php
/**
 * Created by PhpStorm.
 * User: F1676624
 * Date: 2016/11/22
 * Time: 上午 09:43
 */

namespace app\modules\ptdt\models;


use app\models\Common;
use app\modules\common\models\BsPayCondition;
use app\modules\common\models\BsPubdata;
use app\modules\common\models\BsTradConditions;
use yii\db\ActiveRecord;

class FpPas extends Common
{

    public static function tableName()
    {
        return 'pdt.fp_pas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['part_no'], 'required'],
            [['id',
        'part_no',
        'area',
        'bu',
        'material',
        'supplier_code',
        'supplier_name_shot',
        'supplier_name',
        'customer',
        'bs_model',
        'distribution',
        'quote_currency',
        'quote_price',
        'rmb_price',
        'limit_day',
        'limit_number',
        'exchange_rate',
        'check_date',
        'checker',
        'effective_date',
        'expiration_date',
        'delivery_address',
        'model',
        'remark',
        'payment_terms',
        'trading_terms',
        'unit',
        'min_order',
        'currency',
        'buy_price',
        'min_price',
        'ws_upper_price',
        'ws_lower_price',
        'min_num',
        'max_num',
        'gross_profit',
        'gross_profit_margin',
        'pre_tax_profit',
        'pre_tax_profit_rate',
        'after_tax_profit',
        'after_tax_profit_margin',
        'flag',
        'num_area',
        'upper_limit_profit',
        'lower_limit_profit',
        'upper_limit_profit_margin',
        'lower_limit_profit_margin',
        'filename',
        'pre_ws_lower_price',
        'price_fd',
        'creatby'
], 'safe']
        ];
    }

    public function fields(){
        $fields=parent::fields();
        $fields["payment_terms"]=function(){
            $payment_terms=BsPayCondition::find()->select("pat_sname")->where(["pat_id"=>$this->payment_terms])->scalar();
            return $payment_terms?$payment_terms:"";
        };
        $fields["trading_terms"]=function(){
            $trading_terms=BsTradConditions::find()->select("tcc_sname")->where(["tcc_id"=>$this->trading_terms])->scalar();
            return $trading_terms?$trading_terms:"";
        };
        $fields['currency']=function(){
            $currency=BsPubdata::find()->select("bsp_svalue")->where(["bsp_id"=>$this->currency])->scalar();
            return $currency?$currency:"";
        };
        return $fields;
    }

    /**
     * @param $id
     * 获取模型
     */
    public static function getModel($id)
    {

        return self::findOne($id);
    }
}