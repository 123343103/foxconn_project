<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "req_value".
 *
 * @property string $req_id
 * @property string $cur_id
 * @property string $pat_id
 * @property integer $pac_id
 * @property integer $pay_type
 * @property integer $Is_org
 * @property string $ex_rate
 * @property string $org_prf
 * @property string $prd_org_amount
 * @property string $tax_freight
 * @property string $freight
 * @property string $req_tax_amount
 * @property string $req_amount
 * @property string $prd_loc_amount
 * @property string $loc_prf
 *
 * @property ReqInfo $req
 */
class ReqValue extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_value';
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
            [['req_id'], 'required'],
            [['req_id', 'cur_id', 'pat_id', 'pac_id', 'pay_type', 'Is_org'], 'integer'],
            [['ex_rate', 'org_prf', 'prd_org_amount', 'tax_freight', 'freight', 'req_tax_amount', 'req_amount', 'prd_loc_amount', 'loc_prf'], 'number'],
            [['req_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReqInfo::className(), 'targetAttribute' => ['req_id' => 'req_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_id' => 'Req ID',
            'cur_id' => 'Cur ID',
            'pat_id' => 'Pat ID',
            'pac_id' => 'Pac ID',
            'pay_type' => 'Pay Type',
            'Is_org' => 'Is Org',
            'ex_rate' => 'Ex Rate',
            'org_prf' => 'Org Prf',
            'prd_org_amount' => 'Prd Org Amount',
            'tax_freight' => 'Tax Freight',
            'freight' => 'Freight',
            'req_tax_amount' => 'Req Tax Amount',
            'req_amount' => 'Req Amount',
            'prd_loc_amount' => 'Prd Loc Amount',
            'loc_prf' => 'Loc Prf',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReq()
    {
        return $this->hasOne(ReqInfo::className(), ['req_id' => 'req_id']);
    }
}
