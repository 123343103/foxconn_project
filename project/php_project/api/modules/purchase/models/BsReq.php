<?php

namespace app\modules\purchase\models;

use app\models\Common;
use app\modules\ptdt\models\BsBrand;
use app\modules\ptdt\models\BsPartno;
use app\modules\ptdt\models\BsProduct;
use app\modules\spp\models\BsSupplier;
use Yii;

/**
 * This is the model class for table "bs_req".
 *
 * @property string $req_id
 * @property string $req_no
 * @property string $req_dct
 * @property string $req_rqf
 * @property string $leg_id
 * @property string $spp_dpt_id
 * @property string $contact
 * @property string $area_id
 * @property string $req_dpt_id
 * @property string $addr
 * @property string $cst_type
 * @property string $cur_id
 * @property string $urg_id
 * @property string $prj_code
 * @property string $agr_code
 * @property string $req_type
 * @property string $e_dpt_id
 * @property string $scrce
 * @property string $mtr_ass
 * @property integer $yn_lead
 * @property integer $yn_mul_dpt
 * @property integer $yn_aff
 * @property integer $yn_three
 * @property integer $yn_eqp_budget
 * @property integer $yn_low_value
 * @property integer $yn_fix_cntrl
 * @property integer $yn_req
 * @property string $remarks
 * @property string $total_amount
 * @property string $t_amount_addfax
 * @property integer $req_status
 * @property string $app_id
 * @property string $app_date
 * @property string $app_ip
 * @property integer $yn_rec
 * @property string $recer
 * @property string $rec_cont
 * @property integer $yn_can
 * @property string $can_rsn
 *
 * @property BsReqDt[] $bsReqDts
 */
class BsReq extends Common
{
    const REQUEST_STATUS_CLOSE=1;
    const REQUEST_STATUS_STATUS=35;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bs_req';
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
            [['req_dct', 'req_rqf', 'leg_id', 'spp_dpt_id', 'area_id', 'req_dpt_id', 'cst_type', 'cur_id', 'urg_id', 'req_type', 'e_dpt_id', 'mtr_ass', 'yn_lead', 'yn_mul_dpt', 'yn_aff', 'yn_three', 'yn_eqp_budget', 'yn_low_value', 'yn_fix_cntrl', 'yn_req', 'req_status', 'app_id', 'yn_rec', 'recer', 'yn_can'], 'integer'],
            [['total_amount', 't_amount_addfax'], 'number'],
            [['app_date'], 'safe'],
            [['req_no', 'contact', 'scrce'], 'string', 'max' => 30],
            [['addr', 'remarks', 'can_rsn'], 'string', 'max' => 200],
            [['prj_code', 'agr_code'], 'string', 'max' => 50],
            [['app_ip'], 'string', 'max' => 16],
            [['rec_cont'], 'string', 'max' => 20],
            [['req_no'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'req_id' => 'Req ID',
            'req_no' => 'Req No',
            'req_dct' => 'Req Dct',
            'req_rqf' => 'Req Rqf',
            'leg_id' => 'Leg ID',
            'spp_dpt_id' => 'Spp Dpt ID',
            'contact' => 'Contact',
            'area_id' => 'Area ID',
            'req_dpt_id' => 'Req Dpt ID',
            'addr' => 'Addr',
            'cst_type' => 'Cst Type',
            'cur_id' => 'Cur ID',
            'urg_id' => 'Urg ID',
            'prj_code' => 'Prj Code',
            'agr_code' => 'Agr Code',
            'req_type' => 'Req Type',
            'e_dpt_id' => 'E Dpt ID',
            'scrce' => 'Scrce',
            'mtr_ass' => 'Mtr Ass',
            'yn_lead' => 'Yn Lead',
            'yn_mul_dpt' => 'Yn Mul Dpt',
            'yn_aff' => 'Yn Aff',
            'yn_three' => 'Yn Three',
            'yn_eqp_budget' => 'Yn Eqp Budget',
            'yn_low_value' => 'Yn Low Value',
            'yn_fix_cntrl' => 'Yn Fix Cntrl',
            'yn_req' => 'Yn Req',
            'remarks' => 'Remarks',
            'total_amount' => 'Total Amount',
            't_amount_addfax' => 'T Amount Addfax',
            'req_status' => 'Req Status',
            'app_id' => 'App ID',
            'app_date' => 'App Date',
            'app_ip' => 'App Ip',
            'yn_rec' => 'Yn Rec',
            'recer' => 'Recer',
            'rec_cont' => 'Rec Cont',
            'yn_can' => 'Yn Can',
            'can_rsn' => 'Can Rsn',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    //关联主表
    public function getBsReqDts()
    {
        return $this->hasOne(BsReqDt::className(), ['req_id' => 'req_id']);
    }
    public static function getBsReqInfoOne($id)
    {
        return self::find()->where(['req_id' => $id])->one();
    }
}

