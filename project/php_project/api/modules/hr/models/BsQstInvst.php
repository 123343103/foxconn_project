<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "{{%bs_qst_invst}}".
 *
 * @property string $invst_id
 * @property string $invst_type
 * @property string $invst_subj
 * @property string $invst_dpt
 * @property string $remarks
 * @property string $invst_start
 * @property string $invst_end
 * @property integer $invst_nums
 * @property integer $clo_nums
 * @property string $invst_state
 * @property string $crter
 * @property string $crt_date
 * @property string $crt_ip
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 * @property integer $yn_de
 * @property string $deler
 * @property string $de_date
 * @property string $de_ip
 * @property string $de_reason
 * @property integer $yn_close
 * @property string $closer
 * @property string $clo_date
 * @property string $clo_reason
 * @property string $invst_path
 * @property integer $is_send
 * @property string $send_date
 *
 * @property BsQstAnsw[] $bsQstAnsws
 * @property InvstContent[] $invstContents
 * @property InvstDpt[] $invstDpts
 */
class BsQstInvst extends \yii\db\ActiveRecord
{
    const QUESTION_STATUS_DEL=1;
    const QUESTION_STATUS_CLOSE=1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_qst_invst';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invst_type', 'invst_dpt', 'invst_nums', 'clo_nums', 'crter', 'opper', 'yn_de', 'deler', 'yn_close', 'closer', 'is_send'], 'integer'],
            [['remarks', 'de_reason', 'clo_reason'], 'string'],
            [['invst_start', 'invst_end', 'crt_date', 'opp_date', 'de_date', 'clo_date', 'send_date'], 'safe'],
            [['invst_subj'], 'string', 'max' => 200],
            [['invst_state'], 'string', 'max' => 1],
            [['crt_ip', 'opp_ip', 'de_ip'], 'string', 'max' => 15],
            [['invst_path'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invst_id' => 'Invst ID',
            'invst_type' => 'Invst Type',
            'invst_subj' => 'Invst Subj',
            'invst_dpt' => 'Invst Dpt',
            'remarks' => 'Remarks',
            'invst_start' => 'Invst Start',
            'invst_end' => 'Invst End',
            'invst_nums' => 'Invst Nums',
            'clo_nums' => 'Clo Nums',
            'invst_state' => 'Invst State',
            'crter' => 'Crter',
            'crt_date' => 'Crt Date',
            'crt_ip' => 'Crt Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
            'yn_de' => 'Yn De',
            'deler' => 'Deler',
            'de_date' => 'De Date',
            'de_ip' => 'De Ip',
            'de_reason' => 'De Reason',
            'yn_close' => 'Yn Close',
            'closer' => 'Closer',
            'clo_date' => 'Clo Date',
            'clo_reason' => 'Clo Reason',
            'invst_path' => 'Invst Path',
            'is_send' => 'Is Send',
            'send_date' => 'Send Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBsQstAnsws()
    {
        return $this->hasMany(BsQstAnsw::className(), ['invst_id' => 'invst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvstContents()
    {
        return $this->hasMany(InvstContent::className(), ['invst_id' => 'invst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvstDpts()
    {
        return $this->hasMany(InvstDpt::className(), ['invst_id' => 'invst_id']);
    }
    //获取BsQstInvst一条信息
    public static function getBsQstInvstInfoOne($id)
    {
        return self::find()->where(['invst_id' => $id])->one();
    }
}
