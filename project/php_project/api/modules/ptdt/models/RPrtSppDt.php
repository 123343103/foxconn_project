<?php

namespace app\modules\ptdt\models;

use Yii;

/**
 * This is the model class for table "r_prt_spp_dt".
 *
 * @property string $prt_spp_dt_pkid
 * @property string $prt_spp_pkid
 * @property string $spp_id
 * @property string $price
 * @property string $crrncy
 * @property string $eff_date
 * @property string $remark
 *
 * @property RPrtSpp $prtSppPk
 */
class RPrtSppDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_prt_spp_dt';
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
            [['prt_spp_pkid', 'spp_id', 'crrncy'], 'integer'],
            [['price'], 'number'],
            [['eff_date'], 'safe'],
            [['remark'], 'string', 'max' => 100],
            [['prt_spp_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => RPrtSpp::className(), 'targetAttribute' => ['prt_spp_pkid' => 'prt_spp_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_spp_dt_pkid' => '料號供應商明細pkid',
            'prt_spp_pkid' => '料號供應商pkid,pdt.r_prt_spp.prt_spp_pkid',
            'spp_id' => '供應商pkid,spp.bs_supplier.spp_id',
            'price' => '採購單價',
            'crrncy' => '幣別erp.bs_pubdata.bsp_stype=&#039;jybb&#039;',
            'eff_date' => '有效期至',
            'remark' => '備注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrtSppPk()
    {
        return $this->hasOne(RPrtSpp::className(), ['prt_spp_pkid' => 'prt_spp_pkid']);
    }
}
