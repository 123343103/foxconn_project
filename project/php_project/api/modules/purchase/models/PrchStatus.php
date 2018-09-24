<?php

namespace app\modules\purchase\models;

use Yii;

/**
 * This is the model class for table "prch_status".
 *
 * @property integer $prch_id
 * @property string $prch_name
 * @property integer $sorts
 * @property integer $yn
 * @property string $opper
 * @property string $opp_date
 * @property string $remarks
 * @property integer $prch_type
 */
class PrchStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prch_status';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('prch');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prch_id', 'prch_name'], 'required'],
            [['prch_id', 'sorts', 'yn', 'prch_type'], 'integer'],
            [['opp_date'], 'safe'],
            [['prch_name'], 'string', 'max' => 50],
            [['opper'], 'string', 'max' => 30],
            [['remarks'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prch_id' => 'Prch ID',
            'prch_name' => 'Prch Name',
            'sorts' => 'Sorts',
            'yn' => 'Yn',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'remarks' => 'Remarks',
            'prch_type' => 'Prch Type',
        ];
    }
}
