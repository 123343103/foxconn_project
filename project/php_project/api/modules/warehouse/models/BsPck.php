<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "bs_pck".
 *
 * @property string $pck_pkid
 * @property string $note_pkid
 * @property string $pck_no
 * @property string $status
 * @property string $wh_id
 * @property integer $urg
 * @property string $pck_time
 * @property string $pck_man
 * @property string $pck_IP
 * @property string $cancle_date
 * @property string $cancle_reason
 *
 * @property ShpNt $notePk
 * @property BsPckDt[] $bsPckDts
 * @property OrdShp[] $ordShps
 */
class BsPck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_pck';
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
            [['note_pkid', 'status', 'wh_id', 'urg', 'pck_man'], 'integer'],
            [['pck_time', 'cancle_date'], 'safe'],
            [['pck_no'], 'string', 'max' => 30],
            [['pck_IP'], 'string', 'max' => 20],
            [['cancle_reason'], 'string', 'max' => 200],
            [['note_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => ShpNt::className(), 'targetAttribute' => ['note_pkid' => 'note_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pck_pkid' => 'Pck Pkid',
            'note_pkid' => 'Note Pkid',
            'pck_no' => 'Pck No',
            'status' => 'Status',
            'wh_id' => 'Wh ID',
            'urg' => 'Urg',
            'pck_time' => 'Pck Time',
            'pck_man' => 'Pck Man',
            'pck_IP' => 'Pck  Ip',
            'cancle_date' => 'Cancle Date',
            'cancle_reason' => 'Cancle Reason',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotePk()
    {
        return $this->hasOne(ShpNt::className(), ['note_pkid' => 'note_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPckDts()
    {
        return $this->hasMany(BsPckDt::className(), ['pck_pkid' => 'pck_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrdShps()
    {
        return $this->hasMany(OrdShp::className(), ['pck_pkid' => 'pck_pkid']);
    }
}

