<?php

namespace app\modules\rpt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "rpt_param".
 *
 * @property integer $rptp_id
 * @property integer $rptp_tp
 * @property string $rptp_type
 * @property string $rptp_name
 * @property string $rptp_key
 * @property string $rptp_logic
 * @property string $rptp_val
 */
class RptParam extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rpt_param';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rptp_tp'], 'integer'],
            [['rptp_type'], 'string', 'max' => 2],
            [['rptp_name', 'rptp_key', 'rptp_logic', 'rptp_val'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rptp_id' => 'Rptp ID',
            'rptp_tp' => 'Rptp Tp',
            'rptp_type' => 'Rptp Type',
            'rptp_name' => 'Rptp Name',
            'rptp_key' => 'Rptp Key',
            'rptp_logic' => 'Rptp Logic',
            'rptp_val' => 'Rptp Val',
        ];
    }
}
