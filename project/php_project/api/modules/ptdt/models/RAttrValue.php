<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "r_attr_value".
 *
 * @property string $attr_val_id
 * @property string $catg_attr_id
 * @property string $attr_value
 * @property integer $yn
 * @property string $opp_date
 * @property string $opper
 * @property string $opp_ip
 *
 * @property BsCatgAttr $catgAttr
 */
class RAttrValue extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_attr_value';
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
            [['catg_attr_id', 'attr_value'], 'required'],
            [['catg_attr_id', 'yn', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['attr_value'], 'string', 'max' => 50],
            [['opp_ip'], 'string', 'max' => 16],
            [['catg_attr_id', 'attr_value'], 'unique', 'targetAttribute' => ['catg_attr_id', 'attr_value'], 'message' => 'The combination of 類別屬性ID,來自bs_catg_attr.CATG_attr_id and 屬性值 has already been taken.'],
            [['catg_attr_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsCatgAttr::className(), 'targetAttribute' => ['catg_attr_id' => 'catg_attr_id']],
            ['yn', 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attr_val_id' => '類別屬性值關聯ID',
            'catg_attr_id' => '類別屬性ID,來自bs_catg_attr.CATG_attr_id',
            'attr_value' => '屬性值',
            'yn' => '是否有效，0無效，默認1有效，',
            'opp_date' => '操作時間',
            'opper' => '操作人',
            'opp_ip' => '操作IP',
        ];
    }
}
