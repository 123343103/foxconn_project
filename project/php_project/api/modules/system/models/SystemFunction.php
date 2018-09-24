<?php

namespace app\modules\system\models;

use app\models\Common;
use Yii;

/**
 * F3858995
 * 2016.10.10
 * 系統功能模型
 *
 * @property integer $function_id
 * @property string $function_name
 * @property string $system_module
 * @property integer $page_id
 * @property string $page_name
 * @property string $url
 * @property integer $parent_id
 * @property integer $index
 * @property integer $status
 * @property string $create_at
 * @property integer $create_by
 * @property string $update_at
 * @property integer $update_by
 */
class SystemFunction extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'system_function';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['function_id'], 'required'],
            [['function_id', 'page_id', 'parent_id', 'index', 'status', 'create_by', 'update_by'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['function_name', 'system_module', 'page_name'], 'string', 'max' => 64],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'function_id' => '主鍵ID',
            'function_name' => '功能名詞',
            'system_module' => '模塊代碼',
            'page_id' => '頁面ID，該功能沒有單獨頁面的這個為空',
            'page_name' => '頁面名稱',
            'url' => '地址，對應權限',
            'parent_id' => '復功能ID',
            'index' => 'Index',
            'status' => 'Status',
            'create_at' => 'Create At',
            'create_by' => 'Create By',
            'update_at' => 'Update At',
            'update_by' => 'Update By',
        ];
    }
}
