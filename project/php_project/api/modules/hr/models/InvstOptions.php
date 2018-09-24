<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "invst_options".
 *
 * @property string $cnt_id
 * @property string $opt_code
 * @property string $opt_name
 * @property integer $opt_nums
 * @property string $opt_rate
 *
 * @property InvstContent $cnt
 */
class InvstOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invst_options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cnt_id', 'opt_nums'], 'integer'],
            [['opt_rate'], 'number'],
            [['opt_code'], 'string', 'max' => 1],
            [['opt_name'], 'string', 'max' => 20],
            [['cnt_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvstContent::className(), 'targetAttribute' => ['cnt_id' => 'cnt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cnt_id' => '問卷內容pkid，INVST_CONTENT.cnt_id',
            'opt_code' => '選項代碼，從參數表抓取(單選/多選/判斷)',
            'opt_name' => '選項值',
            'opt_nums' => '小計數目',
            'opt_rate' => '小計比例(保留兩位小數)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCnt()
    {
        return $this->hasOne(InvstContent::className(), ['cnt_id' => 'cnt_id']);
    }
}
