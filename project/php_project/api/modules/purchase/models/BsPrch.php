<?php

namespace app\modules\purchase\models;

use Yii;

/**
 * This is the model class for table "bs_prch".
 *
 * @property string $prch_id
 * @property integer $yn_can
 * @property string $can_rsn
 * @property string $prch_no
 * @property string $req_dct
 * @property string $leg_id
 * @property string $dpt_id
 * @property string $area_id
 * @property string $contact_info
 * @property string $pay_type
 * @property string $tax
 * @property string $cur_id
 * @property string $addr_id
 * @property string $remarks
 * @property string $total_amount
 * @property string $tax_fee
 * @property integer $prch_status
 * @property string $apper
 * @property string $app_date
 * @property string $app_ip
 *
 * @property BsPrchDt[] $bsPrchDts
 */
class BsPrch extends \yii\db\ActiveRecord
{
    const REQUEST_STATUS_STATUS=45;
    const REQUEST_STATUS_CLOSE=1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_prch';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('prch');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['req_dct', 'leg_id', 'dpt_id', 'area_id','rcp_id', 'prch_status', 'apper'], 'integer'],
            [['total_amount', 'tax_fee'], 'number'],
            [['app_date'], 'safe'],
            [['prch_no', 'contact_info'], 'string', 'max' => 30],
            [['remarks','can_rsn'], 'string', 'max' => 300],
            [['app_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prch_id' => 'Prch ID',
            'prch_no' => 'Prch No',
            'req_dct' => 'Req Dct',
            'leg_id' => 'Leg ID',
            'dpt_id' => 'Dpt ID',
            'area_id' => 'Area ID',
            'contact_info' => 'Contact Info',
            'remarks' => 'Remarks',
            'total_amount' => 'Total Amount',
            'tax_fee' => 'Tax Fee',
            'prch_status' => 'Prch Status',
            'apper' => 'Apper',
            'app_date' => 'App Date',
            'app_ip' => 'App Ip',
            'rcp_id'=>'Rcp Id'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPrchDts()
    {
        return $this->hasMany(BsPrchDt::className(), ['prch_id' => 'prch_id']);
    }
    public static function getBsPrchInfoOne($id)
    {
        return self::find()->where(['prch_id' => $id])->one();
    }
}
