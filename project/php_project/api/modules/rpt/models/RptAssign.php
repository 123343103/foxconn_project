<?php

namespace app\modules\rpt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "rpt_assign".
 *
 * @property string $rpta_id
 * @property string $rpt_id
 * @property string $roru
 * @property string $rpta_type
 * @property string $rpta_deadline
 * @property string $rpta_status
 * @property string $rpta_descr
 * @property string $create_by
 * @property string $cdate
 * @property string $update_by
 * @property string $udate
 */
class RptAssign extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rpt_assign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rpt_id', 'create_by', 'update_by'], 'integer'],
            [['rpta_deadline', 'cdate', 'udate'], 'safe'],
            [['roru'], 'string', 'max' => 64],
            [['rpta_type', 'rpta_status'], 'string', 'max' => 2],
            [['rpta_descr'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rpta_id' => 'Rpta ID',
            'rpt_id' => 'Rpt ID',
            'roru' => 'Roru',
            'rpta_type' => 'Rpta Type',
            'rpta_deadline' => 'Rpta Deadline',
            'rpta_status' => 'Rpta Status',
            'rpta_descr' => 'Rpta Descr',
            'create_by' => 'Create By',
            'cdate' => 'Cdate',
            'update_by' => 'Update By',
            'udate' => 'Udate',
        ];
    }
}
