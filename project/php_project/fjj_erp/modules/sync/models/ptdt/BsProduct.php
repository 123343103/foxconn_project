<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "bs_product".
 *
 * @property string $pdt_PKID
 * @property string $pdt_no
 * @property string $catg_id
 * @property string $pdt_name
 * @property string $brand_id
 * @property string $unit
 * @property string $pdt_attribute
 * @property string $pdt_form
 * @property integer $pdt_status
 * @property string $pdt_keyword
 * @property string $pdt_label
 * @property string $pdt_title
 * @property string $crt_date
 * @property string $crter
 * @property string $crt_ip
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property BsPartno[] $bsPartnos
 * @property BsCategory $catg
 * @property BsBrand $brand
 */
class BsProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_product';
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
            [['pdt_no', 'catg_id', 'pdt_name', 'brand_id', 'unit', 'pdt_attribute', 'pdt_form'], 'required'],
            [['catg_id', 'brand_id', 'unit', 'pdt_attribute', 'pdt_form', 'pdt_status', 'crter', 'opper'], 'integer'],
            [['crt_date', 'opp_date'], 'safe'],
            [['pdt_no'], 'string', 'max' => 30],
            [['pdt_name', 'pdt_label'], 'string', 'max' => 100],
            [['pdt_keyword', 'pdt_title'], 'string', 'max' => 200],
            [['crt_ip', 'opp_ip'], 'string', 'max' => 16],
            [['catg_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsCategory::className(), 'targetAttribute' => ['catg_id' => 'catg_id']],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsBrand::className(), 'targetAttribute' => ['brand_id' => 'BRAND_ID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pdt_PKID' => 'Pdt  Pkid',
            'pdt_no' => 'Pdt No',
            'catg_id' => 'Catg ID',
            'pdt_name' => 'Pdt Name',
            'brand_id' => 'Brand ID',
            'unit' => 'Unit',
            'pdt_attribute' => 'Pdt Attribute',
            'pdt_form' => 'Pdt Form',
            'pdt_status' => 'Pdt Status',
            'pdt_keyword' => 'Pdt Keyword',
            'pdt_label' => 'Pdt Label',
            'pdt_title' => 'Pdt Title',
            'crt_date' => 'Crt Date',
            'crter' => 'Crter',
            'crt_ip' => 'Crt Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPartnos()
    {
        return $this->hasMany(BsPartno::className(), ['pdt_PKID' => 'pdt_PKID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatg()
    {
        return $this->hasOne(BsCategory::className(), ['catg_id' => 'catg_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrand()
    {
        return $this->hasOne(BsBrand::className(), ['BRAND_ID' => 'brand_id']);
    }
}
