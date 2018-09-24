<?php

namespace app\modules\system\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "auth_title".
 *
 * @property string $action_url
 * @property string $action_title
 * @property string $action_parent
 * @property string $action_icon
 */
class AuthTitle extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_title';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_url'], 'required'],
            [['action_url', 'action_title', 'action_parent'], 'string', 'max' => 255],
            [['action_icon'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'action_url' => '操作URL',
            'action_title' => '操作描述',
            'action_parent' => '所属功能',
            'action_icon' => '对应的桌面图标',
        ];
    }
}
