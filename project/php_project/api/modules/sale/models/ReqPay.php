<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "req_pay".
 *
 * @property string $req_id
 * @property string $stag_type
 * @property integer $stag_times
 * @property string $stag_date
 * @property string $stag_cost
 * @property string $stag_desrition
 *
 * @property ReqInfo $req
 */
class ReqPay extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_pay';
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
            [['req_id'], 'required'],
            [['req_id', 'stag_type', 'stag_times'], 'integer'],
            [['stag_date'], 'safe'],
            [['stag_cost'], 'number'],
            [['stag_desrition'], 'string', 'max' => 200],
            [['req_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReqInfo::className(), 'targetAttribute' => ['req_id' => 'req_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_id' => 'Req ID',
            'stag_type' => 'Stag Type',
            'stag_times' => 'Stag Times',
            'stag_date' => 'Stag Date',
            'stag_cost' => 'Stag Cost',
            'stag_desrition' => 'Stag Desrition',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReq()
    {
        return $this->hasOne(ReqInfo::className(), ['req_id' => 'req_id']);
    }
}
