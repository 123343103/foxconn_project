<?php

namespace app\modules\sync\models\ptdt;

use Yii;

/**
 * This is the model class for table "bs_partno".
 *
 * @property string $prt_pkid
 * @property string $pdt_PKID
 * @property string $part_no
 * @property string $pdt_origin
 * @property integer $warranty_period
 * @property string $min_order
 * @property integer $part_status
 * @property integer $yn_inquiry
 * @property string $min_inquirynum
 * @property integer $machine_type
 * @property integer $yn_tax
 * @property string $tp_spec
 * @property integer $yn_free_delivery
 * @property integer $yn_discuss
 * @property integer $isselftake
 * @property string $isactivity
 * @property string $supplier_no
 * @property string $sale_dpt
 * @property string $wh_id
 * @property integer $cm_pos
 * @property string $L/T
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
 * @property integer $YN_PA_FJJ
 * @property integer $YN_PALLET
 *
 * @property BsProduct $pdtPK
 */
class BsPartno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_partno';
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
            [['pdt_PKID', 'part_no', 'pdt_origin', 'warranty_period', 'min_order', 'part_status', 'yn_inquiry'], 'required'],
            [['pdt_PKID', 'warranty_period', 'part_status', 'yn_inquiry', 'machine_type', 'yn_tax', 'yn_free_delivery', 'yn_discuss', 'isselftake', 'supplier_no', 'sale_dpt', 'wh_id', 'cm_pos', 'leg_lv', 'is_agent', 'is_batch', 'is_first', 'crter', 'opper', 'YN_PA_FJJ', 'YN_PALLET'], 'integer'],
            [['min_order', 'min_inquirynum'], 'number'],
            [['crt_date', 'opp_date'], 'safe'],
            [['part_no', 'pdt_origin', 'L/T'], 'string', 'max' => 20],
            [['tp_spec'], 'string', 'max' => 100],
            [['isactivity'], 'string', 'max' => 1],
            [['marks'], 'string', 'max' => 255],
            [['crt_ip', 'opp_ip'], 'string', 'max' => 16],
            [['part_no'], 'unique'],
            [['pdt_PKID'], 'exist', 'skipOnError' => true, 'targetClass' => BsProduct::className(), 'targetAttribute' => ['pdt_PKID' => 'pdt_PKID']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'prt_pkid' => 'Prt Pkid',
            'pdt_PKID' => 'Pdt  Pkid',
            'part_no' => 'Part No',
            'pdt_origin' => 'Pdt Origin',
            'warranty_period' => 'Warranty Period',
            'min_order' => 'Min Order',
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
            'L/T' => 'L/ T',
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
            'YN_PA_FJJ' => 'Yn  Pa  Fjj',
            'YN_PALLET' => 'Yn  Pallet',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPdtPK()
    {
        return $this->hasOne(BsProduct::className(), ['pdt_PKID' => 'pdt_PKID']);
    }
}
