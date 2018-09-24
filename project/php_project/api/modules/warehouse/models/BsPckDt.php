<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "bs_pck_dt".
 *
 * @property string $pck_dt_pkid
 * @property string $shpn_pkid
 * @property string $pck_pkid
 * @property string $st_id
 * @property string $pck_nums
 * @property string $marks
 * @property string $sol_id
 * @property string $pack_date
 * @property string $part_no
 * @property string $part_name
 * @property string $rack_code
 * @property string $L_invt_bach
 * @property string $req_num
 *
 * @property BsPck $pckPk
 * @property ShpNtDt $shpnPk
 * @property OrdShpDt[] $ordShpDts
 */
class BsPckDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_pck_dt';
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
            [['shpn_pkid', 'pck_pkid', 'sol_id'], 'integer'],
            [['pack_date'], 'safe'],
            [['req_num'], 'number'],
            [['st_id', 'part_name', 'rack_code'], 'string', 'max' => 200],
            [['pck_nums'], 'string', 'max' => 100],
            [['marks'], 'string', 'max' => 2000],
            [['part_no'], 'string', 'max' => 20],
            [['L_invt_bach'], 'string', 'max' => 255],
            [['pck_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsPck::className(), 'targetAttribute' => ['pck_pkid' => 'pck_pkid']],
            [['shpn_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => ShpNtDt::className(), 'targetAttribute' => ['shpn_pkid' => 'shpn_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pck_dt_pkid' => 'Pck Dt Pkid',
            'shpn_pkid' => 'Shpn Pkid',
            'pck_pkid' => 'Pck Pkid',
            'st_id' => 'St ID',
            'pck_nums' => 'Pck Nums',
            'marks' => 'Marks',
            'sol_id' => 'Sol ID',
            'pack_date' => 'Pack Date',
            'part_no' => 'Part No',
            'part_name' => 'Part Name',
            'rack_code' => 'Rack Code',
            'L_invt_bach' => 'L Invt Bach',
            'req_num' => 'Req Num',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPckPk()
    {
        return $this->hasOne(BsPck::className(), ['pck_pkid' => 'pck_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShpnPk()
    {
        return $this->hasOne(ShpNtDt::className(), ['shpn_pkid' => 'shpn_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdShpDts()
    {
        return $this->hasMany(OrdShpDt::className(), ['pck_dt_pkid' => 'pck_dt_pkid']);
    }
}