<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "ord_shp_dt".
 *
 * @property string $shp_dt_pkid
 * @property string $ord_shp_PKID
 * @property string $pck_dt_pkid
 * @property string $st_id
 * @property string $sol_id
 * @property string $shp_nums
 * @property string $marks
 *
 * @property OrdLgstDt[] $ordLgstDts
 * @property OrdShp $ordShpPK
 * @property BsPckDt $pckDtPk
 */
class OrdShpDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ord_shp_dt';
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
            [['ord_shp_PKID', 'pck_dt_pkid', 'st_id', 'sol_id'], 'integer'],
            [['shp_nums'], 'number'],
            [['marks'], 'string', 'max' => 2000],
            [['ord_shp_PKID'], 'exist', 'skipOnError' => true, 'targetClass' => OrdShp::className(), 'targetAttribute' => ['ord_shp_PKID' => 'ord_shp_PKID']],
            [['pck_dt_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => BsPckDt::className(), 'targetAttribute' => ['pck_dt_pkid' => 'pck_dt_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shp_dt_pkid' => '出貨單明細PKID',
            'ord_shp_PKID' => '出庫單PKID',
            'pck_dt_pkid' => '撿貨單明細PKID',
            'st_id' => '儲位PKID',
            'sol_id' => '訂單明細PKID',
            'shp_nums' => '出庫數量',
            'marks' => '說明',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdLgstDts()
    {
        return $this->hasMany(OrdLgstDt::className(), ['shp_dt_pkid' => 'shp_dt_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdShpPK()
    {
        return $this->hasOne(OrdShp::className(), ['ord_shp_PKID' => 'ord_shp_PKID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPckDtPk()
    {
        return $this->hasOne(BsPckDt::className(), ['pck_dt_pkid' => 'pck_dt_pkid']);
    }
}
