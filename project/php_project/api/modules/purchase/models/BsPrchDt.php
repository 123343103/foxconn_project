<?php

namespace app\modules\purchase\models;

use Yii;

/**
 * This is the model class for table "bs_prch_dt".
 *
 * @property string $prch_dt_id
 * @property string $prch_id
 * @property string $prt_pkid
 * @property string $spp_id
 * @property string $prch_num
 * @property string $price
 * @property string $price_tax
 * @property string $total_amount
 * @property string $total_am_tax
 * @property string $deliv_date
 *
 * @property BsPrch $prch
 * @property RReqPrch[] $rReqPrches
 * @property BsReqDt[] $reqDts
 */
class BsPrchDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_prch_dt';
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
            [['prch_id', 'spp_id','tax','cur_id'], 'integer'],
            [['prch_num', 'price', 'price_tax', 'total_amount', 'total_am_tax'], 'number'],
            [['part_no','pay_condition','goods_condition'],'string'],
            [['deliv_date'], 'safe'],
            [['prch_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsPrch::className(), 'targetAttribute' => ['prch_id' => 'prch_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prch_dt_id' => 'Prch Dt ID',
            'prch_id' => 'Prch ID',
            'part_no' => 'Part No',
            'spp_id' => 'Spp ID',
            'prch_num' => 'Prch Num',
            'price' => 'Price',
            'price_tax' => 'Price Tax',
            'total_amount' => 'Total Amount',
            'total_am_tax' => 'Total Am Tax',
            'deliv_date' => 'Deliv Date',
            'tax' => 'Tax',
            'cur_id' => 'Cur ID',
            'pay_condition'=>'Pay Condition',
            'goods_condition'=>'Goods Condition'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrch()
    {
        return $this->hasOne(BsPrch::className(), ['prch_id' => 'prch_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRReqPrches()
    {
        return $this->hasMany(RReqPrch::className(), ['prch_dt_id' => 'prch_dt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqDts()
    {
        return $this->hasMany(BsReqDt::className(), ['req_dt_id' => 'req_dt_id'])->viaTable('r_req_prch', ['prch_dt_id' => 'prch_dt_id']);
    }
}
