<?php

namespace app\modules\sale\models;

use Yii;

/**
 * This is the model class for table "l_price_file".
 *
 * @property string $l_price_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $sorts
 *
 * @property LPriceInfo $lPrice
 */
class LPriceFile extends \app\models\Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_price_file';
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
            [['l_price_id', 'sorts'], 'integer'],
            [['file_old'], 'string', 'max' => 100],
            [['file_new'], 'string', 'max' => 50],
            [['l_price_id'], 'exist', 'skipOnError' => true, 'targetClass' => LPriceInfo::className(), 'targetAttribute' => ['l_price_id' => 'l_price_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_price_id' => '報價日志pkid，由此產生審核流程',
            'file_old' => '附件原名稱',
            'file_new' => '附件新名稱，路徑(yyMMdd)+隨機碼',
            'sorts' => '排序',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLPrice()
    {
        return $this->hasOne(LPriceInfo::className(), ['l_price_id' => 'l_price_id']);
    }
}
