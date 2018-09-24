<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_brand".
 *
 * @property string $BRAND_ID
 * @property string $BRAND_NAME_CN
 * @property string $BRAND_NAME_EN
 * @property string $BRAND_OTHERNAME
 * @property string $BRAND_URL
 * @property string $BRAND_LOGO
 * @property integer $ISCOMMEND
 * @property string $FIRSTLETTER
 * @property string $PROMOTIONTEXT
 * @property integer $BRAND_SORT
 * @property string $BRAND_PRO
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 *
 * @property BsProduct[] $bsProducts
 */
class BsBrand extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pdt.bs_brand';
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
            [['BRAND_NAME_CN'], 'required'],
            [['ISCOMMEND', 'BRAND_SORT', 'OPPER'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['BRAND_NAME_CN', 'BRAND_NAME_EN', 'BRAND_OTHERNAME', 'BRAND_URL'], 'string', 'max' => 100],
            [['BRAND_LOGO', 'PROMOTIONTEXT'], 'string', 'max' => 50],
            [['FIRSTLETTER'], 'string', 'max' => 1],
            [['BRAND_PRO'], 'string', 'max' => 2000],
            [['OPP_IP'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'BRAND_ID' => 'Brand  ID',
            'BRAND_NAME_CN' => 'Brand  Name  Cn',
            'BRAND_NAME_EN' => 'Brand  Name  En',
            'BRAND_OTHERNAME' => 'Brand  Othername',
            'BRAND_URL' => 'Brand  Url',
            'BRAND_LOGO' => 'Brand  Logo',
            'ISCOMMEND' => 'Iscommend',
            'FIRSTLETTER' => 'Firstletter',
            'PROMOTIONTEXT' => 'Promotiontext',
            'BRAND_SORT' => 'Brand  Sort',
            'BRAND_PRO' => 'Brand  Pro',
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'OPP_IP' => 'Opp  Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsProducts()
    {
        return $this->hasMany(BsProduct::className(), ['brand_id' => 'BRAND_ID']);
    }
}
