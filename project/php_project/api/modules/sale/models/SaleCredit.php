<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "sale_credit".
 *
 * @property integer $credit_id
 * @property integer $saph_id
 * @property integer $soh_id
 * @property integer $cust_id
 * @property integer $credit_type
 * @property string $credit_cost
 * @property string $create_at
 * @property string $credit_desrition
 */
class SaleCredit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_credit';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['saph_id', 'soh_id', 'cust_id', 'credit_type'], 'integer'],
            [['credit_cost'], 'number'],
            [['create_at'], 'safe'],
            [['credit_desrition'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'credit_id' => 'id',
            'saph_id' => '報價單ID',
            'soh_id' => '銷售訂單ID',
            'cust_id' => '客户ID',
            'credit_type' => '帐信类型 crm_credit_maintain.id',
            'credit_cost' => '付款費用',
            'create_at' => '创建时间',
            'credit_desrition' => '备注',
        ];
    }
}
