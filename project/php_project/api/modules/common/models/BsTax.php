<?php

namespace app\modules\common\models;

use Yii;

/**
 * This is the model class for table "bs_tax".
 *
 * @property string $tax_pkid
 * @property string $tax_no
 * @property string $tax_name
 * @property string $tax_value
 * @property integer $yn
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 */
class BsTax extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_tax';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tax_value'], 'required'],
            [['tax_value'], 'number'],
            [['yn', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['tax_no'], 'string', 'max' => 10],
            [['tax_name'], 'string', 'max' => 50],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_pkid' => '稅率pkid',
            'tax_no' => '稅率代碼',
            'tax_name' => '稅率名稱',
            'tax_value' => '稅率值',
            'yn' => '是否有效，默認1有效',
            'opper' => '修改人',
            'opp_date' => '修改時間',
            'opp_ip' => '修改人IP',
        ];
    }
}
