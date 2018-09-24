<?php

namespace app\modules\system\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sys_parameter".
 *
 * @property string $par_id
 * @property string $par_fun
 * @property string $par_sname
 * @property string $par_stype
 * @property string $par_syscode
 * @property string $par_value
 * @property string $par_value_decimal
 * @property string $par_status
 * @property string $par_creator
 * @property string $par_cdate
 * @property string $par_editor
 * @property string $par_edate
 * @property string $vdef1
 * @property string $vdef2
 * @property string $vdef3
 */
class SysParameter extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_parameter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['par_value_decimal'], 'number'],
            [['par_creator', 'par_editor'], 'integer'],
            [['par_cdate', 'par_edate'], 'safe'],
            [['par_fun', 'par_sname', 'vdef1', 'vdef2', 'vdef3'], 'string', 'max' => 60],
            [['par_stype', 'par_syscode', 'par_value'], 'string', 'max' => 20],
            [['par_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'par_id' => 'ID或代碼',
            'par_fun' => '所屬模塊(子系统)',
            'par_sname' => '參數名稱',
            'par_stype' => '參數類型',
            'par_syscode' => '參數编码',
            'par_value' => '值',
            'par_value_decimal' => '浮点型参数值',
            'par_status' => '狀態,1??效0無效',
            'par_creator' => '狀態0無效1有效',
            'par_cdate' => 'Par Cdate',
            'par_editor' => 'Par Editor',
            'par_edate' => 'Par Edate',
            'vdef1' => 'Vdef1',
            'vdef2' => 'Vdef2',
            'vdef3' => 'Vdef3',
        ];
    }
}
