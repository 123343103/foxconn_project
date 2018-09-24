<?php

namespace app\modules\crm\models;

use Yii;

/**
 * This is the model class for table "bs_credit".
 *
 * @property string $credit_id
 * @property string $cust_id
 * @property integer $company_id
 * @property integer $app_amount
 * @property integer $grant_amount
 * @property string $aval_amount
 * @property string $sum_amount
 * @property integer $app_man
 * @property string $app_date
 *
 * @property BsCreditDt[] $bsCreditDts
 */
class BsCredit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_credit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'company_id'], 'required'],
            [['cust_id', 'company_id', 'app_amount', 'grant_amount', 'app_man'], 'integer'],
            [['aval_amount', 'sum_amount'], 'number'],
            [['app_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'credit_id' => 'Credit ID',
            'cust_id' => 'Cust ID',
            'company_id' => 'Company ID',
            'app_amount' => 'App Amount',
            'grant_amount' => 'Grant Amount',
            'aval_amount' => 'Aval Amount',
            'sum_amount' => 'Sum Amount',
            'app_man' => 'App Man',
            'app_date' => 'App Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsCreditDts()
    {
        return $this->hasMany(BsCreditDt::className(), ['credit_id' => 'credit_id']);
    }
}
