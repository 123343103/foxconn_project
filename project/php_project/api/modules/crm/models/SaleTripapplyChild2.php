<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "sale_tripapply_l2".
 *
 * @property string $stal_id
 * @property string $stah_id
 * @property string $stah_bdt
 * @property string $stah_place
 * @property string $stah_edt
 * @property string $stah_arrpalce
 * @property string $stah_transportation
 * @property string $stah_transcost
 * @property string $stah_foodcost
 * @property string $stah_staycost
 * @property string $stah_othercost
 * @property string $stah_ocdescription
 * @property string $stah_notmeatcost
 * @property string $stah_cost1
 * @property string $stah_cost2
 * @property string $stah_cost3
 * @property string $stah_cost4
 * @property string $stah_remark
 */
class SaleTripapplyChild2 extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_tripapply_l2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stal_id'], 'required'],
            [['stal_id', 'stah_id'], 'integer'],
            [['stah_bdt', 'stah_edt'], 'safe'],
            [['stah_transcost', 'stah_foodcost', 'stah_staycost', 'stah_othercost', 'stah_notmeatcost', 'stah_cost1', 'stah_cost2', 'stah_cost3', 'stah_cost4'], 'number'],
            [['stah_place', 'stah_arrpalce', 'stah_transportation'], 'string', 'max' => 20],
            [['stah_ocdescription', 'stah_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stal_id' => 'Stal ID',
            'stah_id' => '關聯銷售出差費用支出申請主表',
            'stah_bdt' => '出發日期時間',
            'stah_place' => '出發地',
            'stah_edt' => '到達日期時間',
            'stah_arrpalce' => '到達地',
            'stah_transportation' => '交通工具',
            'stah_transcost' => '交通費',
            'stah_foodcost' => '膳食費',
            'stah_staycost' => '住宿費',
            'stah_othercost' => '其他費用 報銷報告反寫',
            'stah_ocdescription' => '其他費用說明',
            'stah_notmeatcost' => '誤餐費',
            'stah_cost1' => '費用1',
            'stah_cost2' => '費用2',
            'stah_cost3' => '費用3',
            'stah_cost4' => '費用4',
            'stah_remark' => '備註',
        ];
    }
}
