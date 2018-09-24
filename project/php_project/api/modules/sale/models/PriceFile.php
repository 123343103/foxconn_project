<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "price_file".
 *
 * @property string $price_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $sorts
 *
 * @property PriceInfo $price
 */
class PriceFile extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.price_file';
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
            [['price_id', 'sorts'], 'integer'],
            [['file_old'], 'string', 'max' => 100],
            [['file_new'], 'string', 'max' => 50],
            [['price_id'], 'exist', 'skipOnError' => true, 'targetClass' => PriceInfo::className(), 'targetAttribute' => ['price_id' => 'price_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'price_id' => 'Price ID',
            'file_old' => 'File Old',
            'file_new' => 'File New',
            'sorts' => 'Sorts',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrice()
    {
        return $this->hasOne(PriceInfo::className(), ['price_id' => 'price_id']);
    }
}
