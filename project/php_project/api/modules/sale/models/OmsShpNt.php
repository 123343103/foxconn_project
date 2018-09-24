<?php

namespace app\modules\sale\models;

use app\behaviors\FormCodeBehavior;
use app\models\Common;
use app\modules\warehouse\models\BsPck;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

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
 * @property integer $pickor
 * @property string $pic_date
 * @property string $pic_ip
 * @property integer $canclor
 * @property string $trans_mode
 *
 * @property BsPck[] $bsPcks
 * @property OmsShpNtDt[] $shpNtDts
 */
class OmsShpNt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.shp_nt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('oms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['soh_id', 'status', 'urg', 'pickor', 'canclor', 'trans_mode'], 'integer'],
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
            'trans_mode' => 'Trans Mode',
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
        return $this->hasMany(OmsShpNtDt::className(), ['note_pkid' => 'note_pkid']);
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                'codeField' => 'note_no',
                "formName" => 'shp_nt',
                "model" => $this
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['n_time']
                ]
            ],
        ];
    }
}
