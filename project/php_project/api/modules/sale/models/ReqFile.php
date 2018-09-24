<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "req_file".
 *
 * @property string $req_id
 * @property string $file_old
 * @property string $file_new
 *
 * @property ReqInfo $req
 */
class ReqFile extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.req_file';
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
            [['req_id'], 'integer'],
            [['file_old'], 'string', 'max' => 100],
            [['file_new'], 'string', 'max' => 50],
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
            'file_old' => 'File Old',
            'file_new' => 'File New',
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
