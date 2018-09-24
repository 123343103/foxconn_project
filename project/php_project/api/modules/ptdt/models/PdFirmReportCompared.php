<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "pd_firm_report_compared".
 *
 * @property integer $prc_id
 * @property integer $pfr_id
 * @property string $pfr_code
 * @property integer $firm_id
 * @property string $prc_remark
 */
class PdFirmReportCompared extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pd_firm_report_compared';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['prc_id'], 'required'],
            [['prc_id', 'pfr_id', 'firm_id'], 'integer'],
            [['pfr_code'], 'string', 'max' => 20],
            [['prc_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prc_id' => '厂商呈报分析对比表ID',
            'pfr_id' => '呈报主表ID',
            'pfr_code' => '呈报主表编码',
            'firm_id' => '关联厂商ID',
            'prc_remark' => '备注',
        ];
    }
}
