<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_stock".
 *
 * @property string $STOCK_PKID
 * @property string $prt_pkid
 * @property integer $item
 * @property string $min_qty
 * @property string $max_qty
 * @property integer $stock_time
 * @property string $stock_Unit
 */
class BsStock extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_stock';
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
            [['item', 'stock_time'], 'integer'],
            [['min_qty', 'max_qty'], 'number'],
            [['prt_pkid'], 'string', 'max' => 30],
            [['stock_Unit'], 'string', 'max' => 10],
            [['prt_pkid', 'min_qty', 'max_qty'], 'unique', 'targetAttribute' => ['prt_pkid', 'min_qty', 'max_qty'], 'message' => 'The combination of Prt Pkid, Min Qty and Max Qty has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'STOCK_PKID' => 'Stock  Pkid',
            'prt_pkid' => 'Prt Pkid',
            'item' => 'Item',
            'min_qty' => 'Min Qty',
            'max_qty' => 'Max Qty',
            'stock_time' => 'Stock Time',
            'stock_Unit' => 'Stock  Unit',
        ];
    }


//    public function afterSave($insert, $changedAttributes ){
//        $logModel=new LStock();
//        $logModel->setAttributes($this->attributes);
//        $logModel->l_prt_pkid=$this->prt_pkid;
//        $logModel->yn=0;
//        if(!($logModel->validate() && $logModel->save())){
//            throw new \Exception("备货期日志信息保存失败");
//        }
//        return $logModel->save();
//    }

}
