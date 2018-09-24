<?php

namespace app\modules\warehouse\models;

use Yii;

/**
 * This is the model class for table "shp_nt".
 *
 * @property string $note_pkid
 * @property string $note_no
 * @property string $soh_id
 * @property string $status
 * @property string $wh_id
 * @property integer $urg
 * @property string $n_time
 * @property string $yn_cancel
 * @property string $cancel_rs
 * @property string $cancel_date
 * @property string $operator
 *
 * @property BsPck[] $bsPcks
 * @property ShpNtDt[] $shpNtDts
 */
class ShpNt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const STATUS_CANCEL     = '109092';      // 取消出货状态
    const STATUS_DEFAULT    = '109051';      // 默认状态 待处理
    const STATUS_PICKING     ='109091';       //已处理
    const PRI_GENERAL       = '1';      // 一般
    const PRI_URGENT        = '2';      // 急
    const PRI_EXTRA_URGENT  = '3';      // 緊急
    public static function tableName()
    {
        return 'shp_nt';
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
            [['soh_id', 'status', 'urg', 'pickor', 'canclor','trans_mode'], 'integer'],
            [['n_time', 'cancel_date', 'pic_date'], 'safe'],
            [['note_no'], 'string', 'max' => 30],
            [['yn_cancel'], 'string', 'max' => 1],
            [['cancel_rs'], 'string', 'max' => 100],
            [['operator', 'pic_ip'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note_pkid' => 'Note Pkid',
            'note_no' => 'Note No',
            'soh_id' => 'Soh ID',
            'status' => 'Status',
            'urg' => 'Urg',
            'n_time' => 'N Time',
            'yn_cancel' => 'Yn Cancel',
            'cancel_rs' => 'Cancel Rs',
            'cancel_date' => 'Cancel Date',
            'operator' => 'Operator',
            'pickor' => 'Pickor',
            'pic_date' => 'Pic Date',
            'pic_ip' => 'Pic Ip',
            'canclor' => 'Canclor',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPcks()
    {
        return $this->hasMany(BsPck::className(), ['note_pkid' => 'note_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShpNtDts()
    {
        return $this->hasMany(ShpNtDt::className(), ['note_pkid' => 'note_pkid']);
    }
}
