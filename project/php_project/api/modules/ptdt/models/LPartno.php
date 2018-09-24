<?php

namespace app\modules\ptdt\models;

use app\models\Common;
use Yii;

/**
 * This is the model class for table "l_partno".
 *
 * @property string $l_prt_pkid
 * @property string $l_pdt_pkid
 * @property string $part_no
 * @property string $pdt_origin
 * @property integer $warranty_period
 * @property string $min_order
 * @property integer $check_status
 * @property integer $part_status
 * @property integer $yn_inquiry
 * @property string $min_inquirynum
 * @property integer $machine_type
 * @property integer $yn_tax
 * @property string $tp_spec
 * @property integer $yn_free_delivery
 * @property integer $yn_discuss
 * @property integer $isselftake
 * @property integer $isactivity
 * @property string $supplier_no
 * @property string $sale_dpt
 * @property string $wh_id
 * @property integer $cm_pos
 * @property string $l/t
 * @property integer $leg_lv
 * @property integer $is_agent
 * @property integer $is_batch
 * @property integer $is_first
 * @property string $marks
 * @property string $crter
 * @property string $crt_date
 * @property string $crt_ip
 * @property string $opper
 * @property string $opp_date
 * @property string $opp_ip
 * @property integer $yn_pa_fjj
 * @property integer $yn_pallet
 * @property integer $yn
 */
class LPartno extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_partno';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('pdt');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['l_pdt_pkid', 'warranty_period', 'check_status', 'part_status', 'yn_inquiry', 'machine_type', 'yn_tax', 'yn_free_delivery', 'yn_discuss', 'isselftake', 'isactivity', 'supplier_no', 'sale_dpt', 'wh_id', 'cm_pos', 'leg_lv', 'is_agent', 'is_batch', 'is_first', 'crter', 'opper', 'yn_pa_fjj', 'yn_pallet', 'yn'], 'integer'],
            [['part_no', 'pdt_origin', 'warranty_period', 'min_order', 'part_status', 'yn_inquiry'], 'required'],
            [['min_order', 'min_inquirynum'], 'number'],
            [['crt_date', 'opp_date'], 'safe'],
            [['part_no', 'pdt_origin', 'l/t'], 'string', 'max' => 20],
            [['tp_spec'], 'string', 'max' => 100],
            [['marks'], 'string', 'max' => 255],
            [['crt_ip', 'opp_ip'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'l_prt_pkid' => 'L Prt Pkid',
            'l_pdt_pkid' => 'L Pdt Pkid',
            'part_no' => 'Part No',
            'pdt_origin' => 'Pdt Origin',
            'warranty_period' => 'Warranty Period',
            'min_order' => 'Min Order',
            'check_status' => 'Check Status',
            'part_status' => 'Part Status',
            'yn_inquiry' => 'Yn Inquiry',
            'min_inquirynum' => 'Min Inquirynum',
            'machine_type' => 'Machine Type',
            'yn_tax' => 'Yn Tax',
            'tp_spec' => 'Tp Spec',
            'yn_free_delivery' => 'Yn Free Delivery',
            'yn_discuss' => 'Yn Discuss',
            'isselftake' => 'Isselftake',
            'isactivity' => 'Isactivity',
            'supplier_no' => 'Supplier No',
            'sale_dpt' => 'Sale Dpt',
            'wh_id' => 'Wh ID',
            'cm_pos' => 'Cm Pos',
            'l/t' => 'L/t',
            'leg_lv' => 'Leg Lv',
            'is_agent' => 'Is Agent',
            'is_batch' => 'Is Batch',
            'is_first' => 'Is First',
            'marks' => 'Marks',
            'crter' => 'Crter',
            'crt_date' => 'Crt Date',
            'crt_ip' => 'Crt Ip',
            'opper' => 'Opper',
            'opp_date' => 'Opp Date',
            'opp_ip' => 'Opp Ip',
            'yn_pa_fjj' => 'Yn Pa Fjj',
            'yn_pallet' => 'Yn Pallet',
            'yn' => 'Yn',
        ];
    }


//    public function afterSave($insert,$changedAttributes){
//        \Yii::$app->pdt->createCommand("call  pdt.p_log_sync_pdt(:l_prt_pkid)",[":l_prt_pkid"=>$this->l_prt_pkid])->execute();
//    }
}
