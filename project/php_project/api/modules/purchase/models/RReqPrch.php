<?php

namespace app\modules\purchase\models;

use Yii;

/**
 * This is the model class for table "r_req_prch".
 *
 * @property string $r_rq_prch_id
 * @property string $req_dt_id
 * @property string $prch_dt_id
 *
 * @property BsReqDt $reqDt
 * @property BsPrchDt $prchDt
 */
class RReqPrch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'r_req_prch';
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
            [['req_dt_id', 'prch_dt_id'], 'integer'],
            [['req_dt_id', 'prch_dt_id'], 'unique', 'targetAttribute' => ['req_dt_id', 'prch_dt_id'], 'message' => 'The combination of Req Dt ID and Prch Dt ID has already been taken.'],
            [['req_dt_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsReqDt::className(), 'targetAttribute' => ['req_dt_id' => 'req_dt_id']],
            [['prch_dt_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsPrchDt::className(), 'targetAttribute' => ['prch_dt_id' => 'prch_dt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'r_rq_prch_id' => 'R Rq Prch ID',
            'req_dt_id' => 'Req Dt ID',
            'prch_dt_id' => 'Prch Dt ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReqDt()
    {
        return $this->hasOne(BsReqDt::className(), ['req_dt_id' => 'req_dt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrchDt()
    {
        return $this->hasOne(BsPrchDt::className(), ['prch_dt_id' => 'prch_dt_id']);
    }
}
