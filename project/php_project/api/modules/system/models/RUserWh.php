<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_user_wh".
 *
 * @property integer $user_id
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 *
 * @property RUserWhDt[] $rUserWhDts
 */
class RUserWh extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_user_wh';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'opper'], 'integer'],
            [['opp_date'], 'safe'],
            [['opp_ip'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用戶pkid',
            'opper' => '修改人',
            'opp_date' => '修改時間',
            'opp_ip' => '修改人IP',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRUserWhDts()
    {
        return $this->hasMany(RUserWhDt::className(), ['user_id' => 'user_id']);
    }
}
