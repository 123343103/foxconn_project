<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_costtype".
 *
 * @property string $scost_id
 * @property string $scost_code
 * @property string $scost_sname
 * @property string $scost_status
 * @property string $scost_remark
 * @property string $scost_vdef1
 * @property string $scost_vdef2
 */
class SaleCosttype extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_costtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scost_id'], 'required'],
            [['scost_id'], 'integer'],
            [['scost_code', 'scost_sname'], 'string', 'max' => 20],
            [['scost_status'], 'string', 'max' => 2],
            [['scost_remark', 'scost_vdef1', 'scost_vdef2'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scost_id' => 'Scost ID',
            'scost_code' => '類別編碼',
            'scost_sname' => '1跨日出差報銷申請,2按日出差報銷申請,3交際類費用申請',
            'scost_status' => 'Scost Status',
            'scost_remark' => 'Scost Remark',
            'scost_vdef1' => 'Scost Vdef1',
            'scost_vdef2' => 'Scost Vdef2',
        ];
    }
}
