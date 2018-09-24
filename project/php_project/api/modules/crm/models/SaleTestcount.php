<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_testcount_h".
 *
 * @property integer $sath_id
 * @property integer $saph_id
 * @property string $quotedprice_code
 * @property string $quotedprice_date
 * @property integer $cust_id
 * @property integer $branch_id
 * @property integer $quotedprice_person
 * @property integer $cust_risk
 * @property integer $is_pre_pay
 * @property integer $is_ensure
 * @property string $others_risk
 * @property integer $is_group_auth
 * @property integer $group_auth_limit
 * @property string $group_auth_period
 * @property string $cust_payment_terms
 * @property string $cust_trading_terms
 * @property string $supplier_payment_terms
 * @property string $supplier_trading_terms
 * @property integer $status
 * @property integer $submit_person
 * @property string $submit_date
 * @property integer $creator
 * @property string $created_date
 * @property integer $updator
 * @property string $updated_date
 */
class SaleTestcount extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_testcount_h';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['saph_id', 'cust_id'], 'required'],
            [['saph_id', 'cust_id', 'branch_id', 'quotedprice_person', 'cust_risk', 'is_pre_pay', 'is_ensure', 'is_group_auth', 'group_auth_limit', 'status', 'submit_person', 'creator', 'updator'], 'integer'],
            [['quotedprice_date', 'group_auth_period', 'submit_date', 'created_date', 'updated_date'], 'safe'],
            [['quotedprice_code'], 'string', 'max' => 50],
            [['others_risk'], 'string', 'max' => 255],
            [['cust_payment_terms', 'cust_trading_terms', 'supplier_payment_terms', 'supplier_trading_terms'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sath_id' => 'Sath ID',
            'saph_id' => 'Saph ID',
            'quotedprice_code' => 'Quotedprice Code',
            'quotedprice_date' => 'Quotedprice Date',
            'cust_id' => 'Cust ID',
            'branch_id' => 'Branch ID',
            'quotedprice_person' => 'Quotedprice Person',
            'cust_risk' => 'Cust Risk',
            'is_pre_pay' => 'Is Pre Pay',
            'is_ensure' => 'Is Ensure',
            'others_risk' => 'Others Risk',
            'is_group_auth' => 'Is Group Auth',
            'group_auth_limit' => 'Group Auth Limit',
            'group_auth_period' => 'Group Auth Period',
            'cust_payment_terms' => 'Cust Payment Terms',
            'cust_trading_terms' => 'Cust Trading Terms',
            'supplier_payment_terms' => 'Supplier Payment Terms',
            'supplier_trading_terms' => 'Supplier Trading Terms',
            'status' => 'Status',
            'submit_person' => 'Submit Person',
            'submit_date' => 'Submit Date',
            'creator' => 'Creator',
            'created_date' => 'Created Date',
            'updator' => 'Updator',
            'updated_date' => 'Updated Date',
        ];
    }
}
