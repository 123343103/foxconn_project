<?php

namespace app\modules\common\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "bs_exchange_rate".
 *
 * @property string $ber_id
 * @property string $ber_origin
 * @property string $ber_current
 * @property string $ber_year
 * @property string $ber_month
 * @property string $ber_day
 * @property string $ber_rate
 * @property string $ber_status
 * @property string $create_by
 * @property string $cdate
 * @property string $update_by
 * @property string $udate
 * @property string $ber_remark
 */
class BsExchangeRate extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_exchange_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ber_rate'], 'number'],
            [['create_by', 'update_by'], 'integer'],
            [['cdate', 'udate'], 'safe'],
            [['ber_origin', 'ber_current'], 'string', 'max' => 20],
            [['ber_year', 'ber_month', 'ber_day', 'ber_status'], 'string', 'max' => 4],
            [['ber_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ber_id' => '匯率ID',
            'ber_origin' => '原幣',   // 关联币别表cur_code
            'ber_current' => '本幣',  // 关联币别表cur_code
            'ber_year' => '年',
            'ber_month' => '月',
            'ber_day' => '日',
            'ber_rate' => '匯率',
            'ber_status' => '狀態',
            'create_by' => '創建人',
            'cdate' => '創建時間',
            'update_by' => '修改人',
            'udate' => '修改時間',
            'ber_remark' => '備註',
        ];
    }
}
