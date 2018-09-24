<?php

namespace app\modules\system\models;

use Yii;

/**
 * This is the model class for table "r_user_ctg_dt".
 *
 * @property integer $user_id
 * @property string $ctg_pkid
 *
 * @property RUserCtg $user
 */
class RUserCtgDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_user_ctg_dt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ctg_pkid'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => RUserCtg::className(), 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用戶pkid',
            'ctg_pkid' => '商品類別pkid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RUserCtg::className(), ['user_id' => 'user_id']);
    }
}
