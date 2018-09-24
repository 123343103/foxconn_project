<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\sale\models\SaleOrderh;
use app\modules\sale\models\SaleOrderl;
use Yii;

/**
 * This is the model class for table "ic_invl".
 *
 * @property string $invl_id
 * @property string $invh_id
 * @property integer $invl_status
 * @property string $origin_type
 * @property string $origin_id
 * @property string $p_origin_type
 * @property string $p_bill_id
 * @property string $origin_quantity
 * @property string $p_origin_quantity
 * @property string $category_id
 * @property string $part_no
 * @property integer $comp_pdtid
 * @property string $out_quantity
 * @property string $real_oquantity
 * @property string $in_quantity
 * @property string $real_quantity
 * @property string $in_warehouse_quantity
 * @property string $brand
 * @property string $batch_no
 * @property string $bar_code
 * @property string $is_largess
 * @property string $invoice_quantity
 * @property string $lor_id
 * @property string $pack_quantity
 * @property string $pack_type
 * @property string $suttle
 * @property string $origin_code
 * @property string $p_bill_code
 * @property string $gross_weight
 * @property string $inout_time
 * @property integer $abnormal_id
 * @property string $subitem_remark
 * @property string $logistics_no
 * @property integer $chl_id
 * @property integer $wh_id2
 */
class IcInvl extends Common
{
    //状态
    const DELETE_STATUS=0;//删除
    const DEFAULT_STATUS=10;//默认

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ic_invl';
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
            [['invh_id', 'invl_status', 'origin_id', 'p_bill_id', 'comp_pdtid', 'lor_id', 'wh_id2', 'abnormal_id', 'chl_id'], 'integer'],
            [['origin_quantity', 'p_origin_quantity', 'out_quantity', 'real_oquantity', 'in_quantity', 'real_quantity', 'in_warehouse_quantity', 'invoice_quantity', 'pack_quantity', 'suttle', 'gross_weight'], 'number'],
            [['inout_time'], 'safe'],
            [['origin_type', 'p_origin_type', 'category_id', 'part_no', 'batch_no', 'bar_code', 'pack_type', 'logistics_no'], 'string', 'max' => 20],
            [['brand'], 'string', 'max' => 60],
            [['origin_code','p_bill_code'], 'string', 'max' => 40],
            [['is_largess'], 'string', 'max' => 4],
            [['subitem_remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invl_id' => 'Invl ID',
            'invh_id' => '出入庫主表id',
            'invl_status' => '状态',
            'origin_type' => '源單類型',
            'origin_id' => '源單ID',
            'p_origin_type' => '上級單據類型',
            'p_bill_id' => '上級單據ID',
            'origin_quantity' => '源單數量',
            'p_origin_quantity' => '上級單據數據',
            'category_id' => '商品分類id',
            'part_no' => '商品料号',
            'comp_pdtid' => '公司商品ID',
            'out_quantity' => '應出庫數量',
            'real_oquantity' => '實出庫數量',
            'in_quantity' => '應入庫數量',
            'real_quantity' => '實入庫數量',
            'in_warehouse_quantity' => '入仓数量',
            'brand' => '商品品牌',
            'batch_no' => '(收货批次)商品批號',
            'bar_code' => '條碼',
            'is_largess' => '是否贈品',
            'invoice_quantity' => '已開票數',
            'lor_id' => '收發貨庫位 關聯倉庫貨位信息表bs_locator',
            'pack_quantity' => '包裝件數',
            'pack_type' => '包裝方式',
            'suttle' => '淨重',
            'gross_weight' => '毛重',
            'inout_time' => '出入庫時間',
            'abnormal_id' => '出入庫異動明細ID 出入?明?表回?',
            'subitem_remark' => '子項備註',
            'logistics_no' => '物流單號',
            'chl_id' => '單據確認后由異動表回寫',
        ];
    }

    //关联订单主表
    public function getSaleOrderh(){
        return $this->hasOne(SaleOrderh::className(),['soh_id'=>'origin_id']);
    }
    //关联订单子表
    public function getSaleOrderl(){
        return $this->hasOne(SaleOrderl::className(),['soh_id'=>'origin_id']);
    }
    //关联料号表
    public function getPartno()
    {
        return $this->hasOne(BsPartno::className(),['part_no'=>'part_no']);
    }
    //关联商品表
    public function getProduct(){
        return $this->hasOne(BsProduct::className(),['pdt_pkid'=>'pdt_pkid'])->via('partno');
    }
    //关联出入库主表
    public function getIcInvh(){
        return $this->hasOne(IcInvh::className(),['invh_id'=>'invh_id']);
    }
    //关联运输方式表
    public function getBsTr()
    {
        return $this->hasOne(BsTr::className(), ['tran_code' => 'trans_type'])->via('icInvh');
    }
//获取出货详情表信息
    public static function getIcInvlinfo($partno,$invlid)
    {
        return self::find()
            ->select([
                self::tableName().".invh_id",
                self::tableName().".invl_id",
                self::tableName().".part_no",
                self::tableName().".origin_id",
                self::tableName().".logistics_no",
            ])->joinWith('icInvh',$eagerLoading = true, $joinType = 'RIGHT JOIN')
            ->where([
                self::tableName().'.invl_id' => $invlid
            ]) ->andWhere([
                self::tableName().'.part_no' => $partno
            ])
            ->asArray()
            ->one();
    }
    //获取订单id
    public static function getIcInvlid($inchcode)
    {
        return self::find()
            ->select([
                self::tableName().".origin_id"
            ])->where([
                self::tableName().'.invh_id' => $inchcode
            ])
            ->asArray()
            ->one();

    }
}
