<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "crm_bs_cust_personinch".
 *
 * @property string $ccpich_id
 * @property string $cust_id
 * @property string $ccpich_personid
 * @property string $ccpich_personid2
 * @property integer $csarea_id
 * @property integer $sts_id
 * @property string $ccpich_date
 * @property string $ccpich_status
 * @property string $ccpich_remark
 */
class CrmCustPersoninch extends Common
{
    /**
     * @inheritdoc
     */
    const STATUS_DELETE=0;     //删除
    const STATUS_DEFAULT=10;  //正常
    const PERSONINCH_SALES = 1;//销售
    const PERSONINCH_DEFAULT= 0;//默认
    const PERSONINCH_INVEST = 2;//招商
    public static function tableName()
    {
        return 'crm_bs_customer_personinch';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'csarea_id', 'sts_id'], 'integer'],
            [['ccpich_date','ccpich_stype','ccpich_status'], 'safe'],
            [['ccpich_personid', 'ccpich_personid2'],'safe'],
            [['ccpich_remark'], 'string', 'max' => 200],
            ['ccpich_status','default','value' => self::STATUS_DEFAULT],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ccpich_id' => 'ID',
            'cust_id' => '客户关系表ID',
            'ccpich_personid' => '客户负责人表ID',
            'ccpich_personid2' => '客户业务人员ID',
            'csarea_id' => '所在销售区域ID',
            'sts_id' => '销售点id',
            'ccpich_date' => '认领日期',
            'ccpich_status' => '状态',
            'ccpich_remark' => '备注',
        ];
    }
    /*客户经理人*/
    public function getManager(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'ccpich_personid']);
    }

    /*客户业务人员*/
    public function getSale(){
        return $this->hasOne(HrStaff::className(),['staff_id'=>'ccpich_personid2']);
    }

    /*所属军区*/
    public function getSaleArea(){
        return $this->hasOne(CrmSalearea::className(),['csarea_id'=>'csarea_id']);
    }
    /*销售点*/
    public function getStoreInfo(){
        return $this->hasOne(CrmStoresinfo::className(),['sts_id'=>'sts_id']);
    }
    /*公司地址*/
    public function getDistrict(){
        $disId = $this->hasOne(BsDistrict::className(),['district_id'=>'district_id'])->via('storeInfo');
        return $disId;
    }
    public function getDistrict2(){
        $disId2 = $this->hasOne(BsDistrict::className(),['district_id'=>'district_pid'])->via('district');
        return $disId2;
    }
    public function getDistrict3(){
        $disId3 = $this->hasOne(BsDistrict::className(),['district_id'=>'district_pid'])->via('district2');
        return $disId3;
    }
    public function getDistrict4(){
        $disId4 = $this->hasOne(BsDistrict::className(),['district_id'=>'district_pid'])->via('district3');
        return $disId4;
    }

    /**
     * 招商关联
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getByOne($id){
       return self::find()->where(['cust_id'=>$id])->andWhere(['ccpich_stype'=>self::PERSONINCH_INVEST])->andWhere(['ccpich_status'=>self::STATUS_DEFAULT])->one();
    }

}
