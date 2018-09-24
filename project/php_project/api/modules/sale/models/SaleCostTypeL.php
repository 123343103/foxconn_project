<?php

namespace app\modules\sale\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_costtypel".
 *
 * @property string $scost_id
 * @property string $scostl_id
 * @property string $stcl_id
 * @property string $stcl_code
 * @property string $stcl_sname
 * @property string $scostl_status
 * @property string $scostl_remark
 * @property string $scostl_vdef1
 * @property string $scostl_vdef2
 */
class SaleCostTypeL extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_costtypel';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scost_id', 'scostl_id'], 'integer'],
            [['scostl_id'], 'required'],
            [['stcl_id', 'stcl_code', 'stcl_sname'], 'string', 'max' => 20],
            [['scostl_status'], 'string', 'max' => 2],
            [['scostl_remark', 'scostl_vdef1', 'scostl_vdef2'], 'string', 'max' => 120],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'scost_id' => 'Scost ID',
            'scostl_id' => 'Scostl ID',
            'stcl_id' => 'Stcl ID',
            'stcl_code' => 'Stcl Code',
            'stcl_sname' => 'Stcl Sname',
            'scostl_status' => 'Scostl Status',
            'scostl_remark' => 'Scostl Remark',
            'scostl_vdef1' => 'Scostl Vdef1',
            'scostl_vdef2' => 'Scostl Vdef2',
        ];
    }

    /**
     * @inheritdoc
     * @return \app\modules\sale\models\search\SaleCostTypeLSearch the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\sale\models\search\SaleCostTypeLSearch(get_called_class());
    }
}
