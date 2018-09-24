<?php

namespace app\modules\system\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sys_display_child".
 *
 * @property integer $field_id
 * @property integer $ddi_sid
 * @property string $field_title
 * @property string $field_name
 * @property integer $field_index
 * @property integer $field_width
 * @property integer $field_display
 */
class SysDisplayListChild extends Common
{

    const STATUS_DEFAULT = 10;       //默认
    const STATUS_DELETE=0;           //删除
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_display_list_child';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ddi_sid', 'field_index', 'field_width', 'field_display'], 'integer'],
            [['field_title'], 'string', 'max' => 255],
            [['field_field'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'field_id' => 'Field ID',
            'ddi_sid' => 'Ddi Sid',
            'field_title' => 'Field Title',
            'field_field' => 'Field field',
            'field_index' => 'Field Index',
            'field_width' => 'Field Width',
            'field_display' => 'Field Display',
        ];
    }
}
