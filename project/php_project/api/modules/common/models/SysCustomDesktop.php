<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sys_custom_desktop".
 *
 * @property string $scd_id
 * @property string $action_url
 * @property string $uid
 * @property string $scd_status
 * @property string $scd_descr
 */
class SysCustomDesktop extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_custom_desktop';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid'], 'integer'],
            [['action_url', 'scd_descr'], 'string', 'max' => 255],
            [['scd_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scd_id' => 'Scd ID',
            'action_url' => 'Action Url',
            'uid' => 'Uid',
            'scd_status' => 'Scd Status',
            'scd_descr' => 'Scd Descr',
        ];
    }
}
