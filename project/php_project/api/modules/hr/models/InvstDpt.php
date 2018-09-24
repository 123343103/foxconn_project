<?php

namespace app\modules\hr\models;

use Yii;

/**
 * This is the model class for table "invst_dpt".
 *
 * @property string $invst_id
 * @property string $dpt_id
 */
class InvstDpt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invst_dpt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invst_id', 'dpt_id'], 'integer'],
        ];
    }


    public static function getBsQstInvstInfoOne($id)
    {
        return self::find()->where(['invst_id' => $id])->one();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invst_id' => '调查对象pkid',
            'dpt_id'=>'对象id'
        ];
    }
}
