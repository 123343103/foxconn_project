<?php

namespace app\modules\purchase\models;

use app\models\Common;
use app\modules\common\models\BsPubdata;
use app\modules\ptdt\models\BsBrand;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\spp\models\BsSupplier;
use Yii;

/**
 * This is the model class for table "bs_req_dt".
 *
 * @property string $req_dt_id
 * @property string $req_id
 * @property string $prt_pkid
 * @property string $req_nums
 * @property string $req_price
 * @property string $spp_id
 * @property string $total_amount
 * @property string $exp_account
 * @property string $req_date
 * @property string $prj_no
 * @property string $org_price
 * @property string $rebat_rate
 * @property string $remarks
 *
 * @property BsReq $req
 * @property RReqPrch[] $rReqPrches
 * @property BsPrchDt[] $prchDts
 */
class BsReqDt extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_req_dt';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('prch');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['req_id', 'spp_id', 'exp_account'], 'integer'],
            [['req_nums', 'req_price', 'total_amount', 'org_price', 'rebat_rate'], 'number'],
            [['req_date'], 'safe'],
            [['prj_no'], 'string', 'max' => 30],
            [['part_no'], 'string', 'max' => 20],
            [['remarks'], 'string', 'max' => 250],
            [['req_id'], 'exist', 'skipOnError' => true, 'targetClass' => BsReq::className(), 'targetAttribute' => ['req_id' => 'req_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_dt_id' => 'Req Dt ID',
            'req_id' => 'Req ID',
            'part_no' => 'part_no',
            'req_nums' => 'Req Nums',
            'req_price' => 'Req Price',
            'spp_id' => 'Spp ID',
            'total_amount' => 'Total Amount',
            'exp_account' => 'Exp Account',
            'req_date' => 'Req Date',
            'prj_no' => 'Prj No',
            'org_price' => 'Org Price',
            'rebat_rate' => 'Rebat Rate',
            'remarks' => 'Remarks',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReq()
    {
        return $this->hasOne(BsReq::className(), ['req_id' => 'req_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRReqPrches()
    {
        return $this->hasMany(RReqPrch::className(), ['req_dt_id' => 'req_dt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrchDts()
    {
        return $this->hasMany(BsPrchDt::className(), ['prch_dt_id' => 'prch_dt_id'])->viaTable('r_req_prch', ['req_dt_id' => 'req_dt_id']);
    }
    public static function getBsReqDtInfoOne($id)
    {
        return self::find()->where(['req_dt_id' => $id])->one();
    }
    //关联料号表
//    public function getBsPartno()
//    {
//        return $this->hasOne(BsPartno::className(), ['prt_pkid' => 'prt_pkid']);
//    }
    //关联供应商表
    public function getBsSupplier()
    {
        return $this->hasOne(BsSupplier::className(), ['spp_id' => 'spp_id']);
    }
    //关联商品表
    public function getBsProduct()
    {
        return $this->hasOne(BsProduct::className(),['pdt_pkid'=>'pdt_pkid'])->via('bsPartno');
    }
    //关联品牌表
    public function getBsBrank()
    {
        return $this->hasOne(BsBrand::className(),['brand_id'=>'brand_id'])->via('bsProduct');
    }
    //关联基本信息表（查询商品的单位）
    public function getBsPubdata()
    {
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'unit'])->via('bsProduct');
    }
}
