<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "req_addr".
 *
 * @property string $req_id
 * @property string $distinct_id
 * @property string $addr
 * @property string $detail_addr
 * @property integer $addr_type
 *
 * @property ReqInfo $req
 */
class ReqAddr extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_addr';
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
            [['req_id', 'distinct_id', 'addr_type'], 'integer'],
            [['addr'], 'string', 'max' => 100],
            [['detail_addr'], 'string', 'max' => 200],
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
            'distinct_id' => 'Distinct ID',
            'addr' => 'Addr',
            'detail_addr' => 'Detail Addr',
            'addr_type' => 'Addr Type',
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
