<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "fp_partno".
 *
 * @property string $PART_NO
 * @property string $PDT_NAME
 * @property string $CATEGORY_ID
 * @property string $TP_SPEC
 * @property integer $STATUS
 * @property string $BRAND
 * @property string $UNIT
 * @property string $APPLYDEP
 * @property string $CREATDATE
 * @property string $PDT_MANAGER
 * @property integer $ISKZ
 * @property integer $ISPROXY
 * @property integer $ISONLINESELL
 * @property integer $RISK_LEVEL
 * @property integer $PDT_LEVEL
 * @property integer $ISTITLE
 * @property string $MARKET_PRICE
 * @property integer $YN_DEL
 */
class FpPartno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fp_partno';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->controller->module->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PART_NO'], 'required'],
            [['STATUS', 'ISKZ', 'ISPROXY', 'ISONLINESELL', 'RISK_LEVEL', 'PDT_LEVEL', 'ISTITLE', 'YN_DEL'], 'integer'],
            [['CREATDATE'], 'safe'],
            [['MARKET_PRICE'], 'number'],
            [['PART_NO', 'BRAND', 'UNIT'], 'string', 'max' => 50],
            [['PDT_NAME', 'TP_SPEC'], 'string', 'max' => 1000],
            [['CATEGORY_ID', 'APPLYDEP'], 'string', 'max' => 20],
            [['PDT_MANAGER'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'PART_NO' => 'Part  No',
            'PDT_NAME' => 'Pdt  Name',
            'CATEGORY_ID' => 'Category  ID',
            'TP_SPEC' => 'Tp  Spec',
            'STATUS' => 'Status',
            'BRAND' => 'Brand',
            'UNIT' => 'Unit',
            'APPLYDEP' => 'Applydep',
            'CREATDATE' => 'Creatdate',
            'PDT_MANAGER' => 'Pdt  Manager',
            'ISKZ' => 'Iskz',
            'ISPROXY' => 'Isproxy',
            'ISONLINESELL' => 'Isonlinesell',
            'RISK_LEVEL' => 'Risk  Level',
            'PDT_LEVEL' => 'Pdt  Level',
            'ISTITLE' => 'Istitle',
            'MARKET_PRICE' => 'Market  Price',
            'YN_DEL' => 'Yn  Del',
        ];
    }
}
