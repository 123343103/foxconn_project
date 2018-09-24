<?php

namespace app\modules\warehouse\models;

use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\sale\models\OrdDt;
use app\modules\sale\models\OrdInfo;
use app\modules\sale\models\OrdStatus;
use Yii;

/**
 * This is the model class for table "o_whpdt_dt".
 *
 * @property string $o_whdtid
 * @property string $o_whpkid
 * @property string $part_no
 * @property string $req_num
 * @property string $pck_dt_pkid
 * @property string $o_whnum
 * @property string $remarks
 * @property string $invt_id
 *
 * @property OWhpdt $oWhpk
 */
class OWhpdtDt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'o_whpdt_dt';
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
            [['o_whpkid', 'pck_dt_pkid', 'invt_id'], 'integer'],
            [['req_num', 'o_whnum'], 'number'],
            [['part_no'], 'string', 'max' => 20],
            [['remarks'], 'string', 'max' => 255],
            [['o_whpkid'], 'exist', 'skipOnError' => true, 'targetClass' => OWhpdt::className(), 'targetAttribute' => ['o_whpkid' => 'o_whpkid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'o_whdtid' => 'PK_ID',
            'o_whpkid' => '主表关联ID',
            'part_no' => '商品料号',
            'req_num' => '需求出库数量',
            'pck_dt_pkid' => '撿貨單明細PKID',
            'o_whnum' => '出库数量',
            'remarks' => '备注',
            'invt_id' => '出庫儲位，bs_sit_invt(其它出庫專用)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOWhpk()
    {
        return $this->hasOne(OWhpdt::className(), ['o_whpkid' => 'o_whpkid']);
    }


    //关联出货主表
//    public function getOwhpdt()
//    {
//        return $this->hasOne(OWhpdt::className(), ['o_whpkid' => 'o_whpkid']);
//    }

    //关联订单主表
    public function getOrdinfo()
    {
        return $this->hasOne(OrdInfo::className(), ['ord_id' => 'ord_id'])->via('oWhpk');
    }
    //关联订单主表
    public function getOrdStatus()
    {
        return $this->hasOne(OrdStatus::className(), ['os_id' => 'os_id'])->via('ordinfo');
    }
    //关联订单子表
    public function getOrddt()
    {
        return $this->hasOne(OrdDt::className(), ['ord_id' => 'ord_id'])->via('oWhpk');
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
    //获取出货详情表信息
    public static function getOwhpdtdtinfo($partno,$owhdtid)
    {
        return self::find()
            ->select([
                self::tableName().".o_whpkid",
                self::tableName().".o_whdtid",
                self::tableName().".part_no",
                "o_whpdt.ord_id"
            ])->joinWith('oWhpk',$eagerLoading = true, $joinType = 'RIGHT JOIN')
            ->where([
                self::tableName().'.o_whdtid' => $owhdtid
            ]) ->andWhere([
                self::tableName().'.part_no' => $partno
            ])
            ->asArray()
            ->one();
    }
}
