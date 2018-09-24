<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "bs_brand".
 *
 * @property integer $BRAND_ID
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
 * @property string $CREATEDBY
 * @property string $CREATEDDT
 */
class BsBrand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['BRAND_NAME_CN'], 'required'],
            [['BRAND_ID', 'ISCOMMEND', 'BRAND_SORT'], 'integer'],
            [['CREATEDDT'], 'safe'],
            [['BRAND_NAME_CN', 'BRAND_NAME_EN', 'BRAND_OTHERNAME', 'BRAND_URL'], 'string', 'max' => 100],
            [['BRAND_LOGO', 'PROMOTIONTEXT'], 'string', 'max' => 50],
            [['FIRSTLETTER'], 'string', 'max' => 1],
            [['BRAND_PRO'], 'string', 'max' => 2000],
            [['CREATEDBY'], 'string', 'max' => 20],
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
            'CREATEDBY' => 'Createdby',
            'CREATEDDT' => 'Createddt',
        ];
    }
}
