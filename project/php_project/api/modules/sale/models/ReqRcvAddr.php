<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "req_rcv_addr".
 *
 * @property string $req_id
 * @property string $addr_man
 * @property integer $addr_pho
 * @property string $addr_tel
 * @property string $addr_post
 * @property string $distinct_id
 * @property string $rcv_addr
 * @property string $detail_addr
 *
 * @property ReqInfo $req
 */
class ReqRcvAddr extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_rcv_addr';
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
            [['req_id', 'ba_id', 'addr_pho', 'distinct_id'], 'integer'],
            [['addr_man', 'addr_tel'], 'string', 'max' => 20],
            [['addr_post'], 'string', 'max' => 10],
            [['rcv_addr'], 'string', 'max' => 100],
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
            'ba_id' => 'Ba ID',
            'addr_man' => 'Addr Man',
            'addr_pho' => 'Addr Pho',
            'addr_tel' => 'Addr Tel',
            'addr_post' => 'Addr Post',
            'distinct_id' => 'Distinct ID',
            'rcv_addr' => 'Rcv Addr',
            'detail_addr' => 'Detail Addr',
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
