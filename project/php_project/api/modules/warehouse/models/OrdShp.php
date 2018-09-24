<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "ord_shp".
 *
 * @property string $ord_shp_PKID
 * @property string $pck_pkid
 * @property string $wh_id
 * @property string $ord_shp_no
 * @property string $status
 * @property string $OPPER
 * @property string $OPP_DATE
 * @property string $OPP_IP
 *
 * @property OrdLgst[] $ordLgsts
 * @property BsPck $pckPk
 * @property OrdShpDt[] $ordShpDts
 */
class OrdShp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_shp';
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
            [['pck_pkid', 'wh_id', 'status', 'OPPER'], 'integer'],
            [['OPP_DATE'], 'safe'],
            [['ord_shp_no'], 'string', 'max' => 30],
            [['OPP_IP'], 'string', 'max' => 20],
            [['pck_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsPck::className(), 'targetAttribute' => ['pck_pkid' => 'pck_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ord_shp_PKID' => '出庫單PKID',
            'pck_pkid' => '撿貨單PKID',
            'wh_id' => '倉庫PKID',
            'ord_shp_no' => '出庫單號',
            'status' => '出庫狀態',
            'OPPER' => '操作人',
            'OPP_DATE' => '操作時間',
            'OPP_IP' => '操作IP',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdLgsts()
    {
        return $this->hasMany(OrdLgst::className(), ['ord_shp_PKID' => 'ord_shp_PKID']);
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
    public function getOrdShpDts()
    {
        return $this->hasMany(OrdShpDt::className(), ['ord_shp_PKID' => 'ord_shp_PKID']);
    }
}
