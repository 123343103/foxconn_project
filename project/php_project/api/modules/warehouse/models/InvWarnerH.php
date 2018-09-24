<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "inv_warner_h".
 *
 * @property string $LIW_PKID
 * @property string $inv_warn_PKID
 */
class InvWarnerH extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_warner_h';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['LIW_PKID', 'inv_warn_PKID'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'LIW_PKID' => 'Liw  Pkid',
            'inv_warn_PKID' => 'Inv Warn  Pkid',
        ];
    }
}
