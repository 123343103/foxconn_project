<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_commission_rate".
 *
 * @property integer $scommi_id
 * @property string $scommi_code
 * @property string $scommi_description
 * @property string $scommi_begin
 * @property string $scommi_end
 * @property string $scommi_values
 * @property string $scommi_rate
 * @property integer $sarole_id
 * @property string $scommi_status
 * @property string $sommmi_remark
 * @property string $create_by
 * @property string $cdate
 * @property string $update_by
 * @property string $udate
 */
class SaleCommissionRate extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_commission_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scommi_begin', 'scommi_end', 'scommi_values', 'scommi_rate'], 'number'],
            [['sarole_id', 'create_by', 'update_by'], 'integer'],
            [['cdate', 'udate'], 'safe'],
            [['scommi_code'], 'string', 'max' => 20],
            [['scommi_description'], 'string', 'max' => 40],
            [['scommi_status'], 'string', 'max' => 2],
            [['sommmi_remark'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scommi_id' => '提成係數ID',
            'scommi_code' => '代碼',
            'scommi_description' => '描述',
            'scommi_begin' => '條件開始值',
            'scommi_end' => '條件結束值',
            'scommi_values' => '計算參考值',
            'scommi_rate' => '係數比率',
            'sarole_id' => '???售角色表',
            'scommi_status' => '狀態',
            'sommmi_remark' => '備註',
            'create_by' => '創建人',
            'cdate' => '創建時間',
            'update_by' => '修改人',
            'udate' => '修改時間',
        ];
    }
}
