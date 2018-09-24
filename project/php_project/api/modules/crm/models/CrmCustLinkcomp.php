<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use Yii;

/**
 * This is the model class for table "crm_cust_linkcomp".
 *
 * @property integer $linc_id
 * @property string $linc_isp
 * @property integer $cust_id
 * @property string $linc_code
 * @property string $linc_name
 * @property string $linc_shortname
 * @property string $linc_type
 * @property string $linc_date
 * @property string $linc_incpeople
 * @property string $linc_tel
 * @property integer $linc_district
 * @property string $linc_address
 * @property string $linc_status
 * @property string $linc_remark
 */
class CrmCustLinkcomp extends Common
{
    const STATUS_DELETE = '0';
    const STATUS_DEFAULT = '10';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_cust_linkcomp';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cust_id', 'linc_district'], 'integer'],
            [['linc_date','shareholding_ratio','total_investment','total_investment_cur'], 'safe'],
            [['linc_isp','linc_status'], 'string', 'max' => 2],
            [['linc_code', 'linc_name', 'linc_shortname', 'linc_type', 'linc_incpeople', 'linc_tel', 'linc_address', 'linc_remark','relational_character'], 'string', 'max' => 60],
            ['linc_status', 'default', 'value' => self::STATUS_DEFAULT ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'linc_id' => 'Linc ID',
            'linc_isp' => '是否母公司',
            'cust_id' => '关联客户id',
            'linc_code' => '编码',
            'linc_name' => '公司名称',
            'linc_shortname' => '公司简称',
            'linc_type' => '经营类型',
            'linc_date' => '注册时间',
            'linc_incpeople' => '公司负责人',
            'linc_tel' => '联系电话',
            'linc_district' => '公司地址五级id',
            'linc_address' => '公司地址',
            'relational_character' => '关联性质',
            'linc_status' => '状态',
            'linc_remark' => '备注',
        ];
    }
    /*经营类型*/
    public function getLincType(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'linc_type']);
    }

    /*币别*/
    public function getCurrency(){
        return $this->hasOne(BsPubdata::className(),['bsp_id'=>'total_investment_cur']);
    }

    /*公司地址*/
    public function getDistrict(){
        $disId = $this->hasOne(BsDistrict::className(),['district_id'=>'linc_district']);
        return $disId;
    }
    public function getDistrict2(){
        $a = $this->hasOne(BsDistrict::className(),['district_id'=>'district_pid'])->via('district');
        return $a;
    }
    public function getDistrict3(){
        $a = $this->hasOne(BsDistrict::className(),['district_id'=>'district_pid'])->via('district2');
        return $a;
    }
    public function getDistrict4(){
        $a = $this->hasOne(BsDistrict::className(),['district_id'=>'district_pid'])->via('district3');
        return $a;
    }
}
