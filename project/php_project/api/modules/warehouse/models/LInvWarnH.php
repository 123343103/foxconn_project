<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "l_inv_warn_h".
 *
 * @property string $biw_h_pkid
 * @property string $inv_id
 * @property string $wh_id
 * @property integer $so_type
 * @property integer $yn
 * @property string $remarks
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 */
class LInvWarnH extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_inv_warn_h';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inv_id', 'wh_id'], 'required'],
            [['wh_id', 'so_type', 'yn'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['inv_id', 'OPPER'], 'string', 'max' => 30],
            [['remarks'], 'string', 'max' => 200],
            [['OPP_IP'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'biw_h_pkid' => 'Biw H Pkid',
            'inv_id' => 'Inv ID',
            'wh_id' => 'Wh ID',
            'so_type' => 'So Type',
            'yn' => 'Yn',
            'remarks' => 'Remarks',
            'OPPER' => 'Opper',
            'OPP_DATE' => 'Opp  Date',
            'OPP_IP' => 'Opp  Ip',
        ];
    }
}
