<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "req_sale".
 *
 * @property string $req_id
 * @property integer $user_id
 * @property integer $user_mana
 * @property integer $org_id
 * @property integer $csarea_id
 * @property integer $sts_id
 *
 * @property ReqInfo $req
 */
class ReqSale extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_sale';
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
            [['req_id', 'user_id', 'user_mana', 'csarea_id', 'sts_id','org_id'], 'integer'],
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
            'user_id' => 'User ID',
            'user_mana' => 'User Mana',
            'org_id' => 'Org ID',
            'csarea_id' => 'Csarea ID',
            'sts_id' => 'Sts ID',
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
