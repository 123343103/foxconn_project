<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_ord_file".
 *
 * @property string $l_ord_id
 * @property string $file_old
 * @property string $file_new
 * @property integer $sorts
 */
class LOrdFile extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oms.l_ord_file';
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
            [['l_ord_id', 'sorts'], 'integer'],
            [['file_old'], 'string', 'max' => 100],
            [['file_new'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_ord_id' => 'L Ord ID',
            'file_old' => 'File Old',
            'file_new' => 'File New',
            'sorts' => 'Sorts',
        ];
    }
}
