<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_machine".
 *
 * @property string $prt_pkid
 * @property integer $out_year
 * @property integer $rentals
 * @property integer $rental_unit
 * @property string $currency
 * @property string $recency
 * @property integer $deposit
 * @property integer $stock_num
 */
class BsMachine extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_machine';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prt_pkid'], 'required'],
            [['prt_pkid', 'out_year', 'rentals', 'rental_unit', 'deposit', 'stock_num'], 'integer'],
            [['currency'], 'string', 'max' => 10],
            [['recency'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_pkid' => 'Prt Pkid',
            'out_year' => 'Out Year',
            'rentals' => 'Rentals',
            'rental_unit' => 'Rental Unit',
            'currency' => 'Currency',
            'recency' => 'Recency',
            'deposit' => 'Deposit',
            'stock_num' => 'Stock Num',
        ];
    }

//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LMachine();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("设备信息日志保存失败");
//        }
//        return true;
//    }
}
