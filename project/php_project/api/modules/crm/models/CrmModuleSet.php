<?php

namespace app\modules\crm\models;
use app\models\Common;
use Yii;

/**
 * This is the model class for table "crm_module_set".
 *
 * @property integer $id
 * @property string $uid
 * @property string $module
 * @property string $display
 * @property string $status
 */
class CrmModuleSet extends Common
{
    const STATUS_DEFAULT = '10';
    const DISPLAY_NONE = '0';//不显示
    const DISPLAY_SHOW = '10';//显示
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_module_set';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'module'], 'string', 'max' => 20],
            [['display', 'status'], 'string', 'max' => 2],
            [['status'],'default','value'=>self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '登陆用户',
            'module' => '模块',
            'display' => '是否显示',
            'status' => '状态',
        ];
    }
}
