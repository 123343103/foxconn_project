<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "ord_file".
 *
 * @property string $ord_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $sorts
 *
 * @property OrdInfo $ord
 */
class OrdFile extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.ord_file';
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
            [['ord_id', 'sorts'], 'integer'],
            [['file_old'], 'string', 'max' => 100],
            [['file_new'], 'string', 'max' => 50],
            [['ord_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdInfo::className(), 'targetAttribute' => ['ord_id' => 'ord_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ord_id' => 'Ord ID',
            'file_old' => 'File Old',
            'file_new' => 'File New',
            'sorts' => 'Sorts',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrd()
    {
        return $this->hasOne(OrdInfo::className(), ['ord_id' => 'ord_id']);
    }
}
