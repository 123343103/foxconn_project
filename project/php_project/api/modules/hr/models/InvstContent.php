<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "invst_content".
 *
 * @property string $cnt_id
 * @property string $invst_id
 * @property string $cnt_tpc
 * @property integer $cnt_type
 *
 * @property InvstAnsw[] $invstAnsws
 * @property BsQstInvst $invst
 * @property InvstOpt[] $invstOpts
 * @property InvstOptions[] $invstOptions
 */
class InvstContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invst_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invst_id', 'cnt_type'], 'integer'],
            [['cnt_tpc'], 'string'],
            [['invst_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsQstInvst::className(), 'targetAttribute' => ['invst_id' => 'invst_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cnt_id' => '問卷題目pkid',
            'invst_id' => '問卷調查pkid，BS_QST_INVST.invst_id',
            'cnt_tpc' => '題目內容',
            'cnt_type' => '題目類型。1單選，2多選，3文本，4判斷',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvstAnsws()
    {
        return $this->hasMany(InvstAnsw::className(), ['cnt_id' => 'cnt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvstCon()
    {
        return $this->hasOne(BsQstInvst::className(), ['invst_id' => 'invst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvstOpts()
    {
        return $this->hasMany(InvstOpt::className(), ['cnt_id' => 'cnt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvstOpt()
    {
        return $this->hasMany(InvstOptions::className(), ['cnt_id' => 'cnt_id']);
    }
}
