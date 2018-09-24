<?php

namespace app\modules\sale\models;

use app\models\Common;
use app\modules\warehouse\models\BsPckDt;
use Yii;

/**
 * This is the model class for table "shp_nt_dt".
 *
 * @property string $shpn_pkid
 * @property string $note_pkid
 * @property string $sol_id
 * @property string $nums
 * @property string $marks
 * @property string $part_no
 *
 * @property BsPckDt[] $bsPckDts
 * @property OmsShpNt $notePk
 */
class OmsShpNtDt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wms.shp_nt_dt';
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
            [['note_pkid', 'sol_id','wh_id'], 'integer'],
            [['nums'], 'number'],
            [['marks'], 'string', 'max' => 2000],
            [['part_no'], 'string', 'max' => 20],
            [['note_pkid'], 'exist', 'skipOnError' => true, 'targetClass' => OmsShpNt::className(), 'targetAttribute' => ['note_pkid' => 'note_pkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'shpn_pkid' => 'Shpn Pkid',
            'note_pkid' => 'Note Pkid',
            'sol_id' => 'Sol ID',
            'wh_id' => 'Wh ID',
            'nums' => 'Nums',
            'marks' => 'Marks',
            'part_no' => 'Part No',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsPckDts()
    {
        return $this->hasMany(BsPckDt::className(), ['shpn_pkid' => 'shpn_pkid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotePk()
    {
        return $this->hasOne(OmsShpNt::className(), ['note_pkid' => 'note_pkid']);
    }
}
