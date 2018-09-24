<?php
//库存明细表
namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\ptdt\models\BsMaterial;
use Yii;
use app\modules\common\models\BsProduct;
use app\modules\warehouse\models\BsWh;
use app\modules\warehouse\models\BsSt;

/**
 * This is the model class for table "l_bs_invt_list".
 *
 * @property string $invt_iid
 * @property integer $comp_id
 * @property string $pdt_id
 * @property integer $whs_id
 * @property integer $st_id
 * @property string $L_invt_bach
 * @property string $L_invt_barcode
 * @property integer $brandid
 * @property string $L_invt_num
 * @property integer $sale_id
 * @property string $update_date
 * @property string $part_no
 * @property string $io_type
 * @property integer $opp_id
 * @property string $opp_date
 * @property string $opp_ip
 * @property string $total_num
 */
class LBsInvtList extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_bs_invt_list';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wms');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comp_id', 'pdt_id', 'whs_id', 'st_id', 'brandid', 'sale_id', 'opp_id'], 'integer'],
            [['L_invt_num', 'total_num'], 'number'],
            [['update_date', 'opp_date'], 'safe'],
            [['L_invt_bach', 'L_invt_barcode'], 'string', 'max' => 30],
            [['part_no', 'opp_ip'], 'string', 'max' => 20],
            [['io_type'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invt_iid' => 'Invt Iid',
            'comp_id' => '公司ID 關聯公司表(bs_comp)',
            'pdt_id' => '商品id   關聯商品基本信息表',
            'whs_id' => '倉庫ID 關聯倉庫信息表(bs_warehouse)',
            'st_id' => '储位ID 關聯储位信息表',
            'L_invt_bach' => '批次',
            'L_invt_barcode' => '條碼',
            'brandid' => '品牌??品牌表ID',
            'L_invt_num' => '可用庫存數量',
            'sale_id' => '根据??采?入??回???ID',
            'update_date' => '操作日期',
            'part_no' => '料號（bs_material.part_no）',
            'io_type' => '出入庫類型（1:入庫；0：出庫）',
            'opp_id' => '操作人',
            'opp_date' => '操作時間',
            'opp_ip' => 'IP',
            'total_num' => '出入庫數量（出庫存負數）',
        ];
    }

    //关联商品表
    public function getProduct()
    {
        return $this->hasOne(BsProduct::className(), ['pdt_id' => 'pdt_id']);
    }

    //关联仓库表
    public function getWarehouse()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'whs_id']);
    }

    //关联储位表
    public function getStore()
    {
        return $this->hasOne(BsSt::className(), ['st_id' => 'st_id']);
    }

    //关联商品表
    public function getMaterial()
    {
        return $this->hasOne(BsMaterial::className(), ['part_no' => 'part_no']);
    }
}
