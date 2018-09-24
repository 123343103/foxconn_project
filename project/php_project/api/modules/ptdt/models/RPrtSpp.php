<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "r_prt_spp".
 *
 * @property string $prt_spp_pkid
 * @property string $prt_pkid
 * @property string $opper
 * @property string $op_date
 * @property string $opp_ip
 *
 * @property BsPartno $prtPk
 * @property RPrtSppDt[] $rPrtSppDts
 */
class RPrtSpp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_prt_spp';
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
            [['prt_pkid', 'opper'], 'integer'],
            [['op_date'], 'safe'],
            [['opp_ip'], 'string', 'max' => 16],
            [['prt_pkid'], 'unique'],
            [['prt_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsPartno::className(), 'targetAttribute' => ['prt_pkid' => 'prt_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_spp_pkid' => '料號供應商pkid',
            'prt_pkid' => '料號pkid,pdt.bs_partno.prt_pkid',
            'opper' => '操作人',
            'op_date' => '操作時間',
            'opp_ip' => '操作IP',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrtPk()
    {
        return $this->hasOne(BsPartno::className(), ['prt_pkid' => 'prt_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRPrtSppDts()
    {
        return $this->hasMany(RPrtSppDt::className(), ['prt_spp_pkid' => 'prt_spp_pkid']);
    }
}
