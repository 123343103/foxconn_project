<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use Yii;

/**
 * This is the model class for table "sale_tripapply_h".
 *
 * @property string $stah_id
 * @property string $stah_code
 * @property string $scost_id
 * @property string $stah_description
 * @property string $stah_date
 * @property string $stah_applyer
 * @property string $position_id
 * @property string $organization_id
 * @property string $stah_costcode
 * @property integer $stah_mil
 * @property string $stah_place
 * @property string $cust_id
 * @property string $stah_btime
 * @property string $stah_etime
 * @property string $stah_workdescription
 * @property string $stah_costcount
 * @property string $stah_partner1
 * @property string $stah_partner2
 * @property string $stah_partner3
 * @property string $stah_agenter
 * @property string $stah_isflag
 * @property string $stah_flag
 * @property string $stah_iscar
 * @property string $stah_nocarprove
 * @property string $stah_status
 * @property string $stah_remark
 * @property string $stah_senddate
 * @property string $stah_sender
 * @property string $stah_checkdate
 * @property string $creater
 * @property string $cdate
 * @property string $editor
 * @property string $edate
 */
class SaleTripapply extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_tripapply_h';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stah_id'], 'required'],
            [['stah_id', 'scost_id', 'stah_applyer', 'position_id', 'organization_id', 'stah_mil', 'cust_id', 'stah_partner1', 'stah_partner2', 'stah_partner3', 'stah_agenter', 'stah_nocarprove', 'stah_sender', 'creater', 'editor','stah_district'], 'integer'],
            [['stah_date', 'stah_senddate', 'stah_checkdate', 'cdate', 'edate','data_id'], 'safe'],
            [['stah_costcount'], 'number'],
            [['stah_code', 'stah_costcode', 'stah_btime', 'stah_etime', 'stah_flag'], 'string', 'max' => 20],
            [['stah_description', 'stah_place', 'stah_workdescription', 'stah_remark'], 'string', 'max' => 200],
            [['stah_isflag', 'stah_iscar', 'stah_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stah_id' => 'Stah ID',
            'stah_code' => '編碼',
            'scost_id' => '出差或日出差申請 關聯銷售費用類別表(出差類別)',
            'stah_description' => '描述',
            'stah_date' => '日期',
            'stah_applyer' => '出差申請人',
            'position_id' => '資位id',
            'organization_id' => '部門ID',
            'stah_costcode' => '費用代碼',
            'stah_mil' => '關聯軍區表ID',
            'stah_place' => '出差地點',
            'cust_id' => '拜訪客戶ID 關聯客戶表ID',
            'stah_btime' => '出差開始時間',
            'stah_etime' => '出差結束時間',
            'stah_workdescription' => '出差任務描述',
            'stah_costcount' => '預支費用',
            'stah_partner1' => '陪同人1',
            'stah_partner2' => '陪同人2',
            'stah_partner3' => '陪同人3',
            'stah_agenter' => '工作代理人',
            'stah_isflag' => '是否誤餐費',
            'stah_flag' => '誤餐',
            'stah_iscar' => '是否派車',
            'stah_nocarprove' => '無派車證明人',
            'stah_status' => '狀態',
            'stah_remark' => '備註',
            'stah_senddate' => '送審日期',
            'stah_sender' => '審核人',
            'stah_checkdate' => '審核日期',
            'creater' => '創建人',
            'cdate' => '創建日期',
            'editor' => '修改人',
            'edate' => '修改日期',
        ];
    }
    /*公司地址*/
    public function getDistrict(){
        $disId = $this->hasOne(BsDistrict::className(),['district_id'=>'stah_district']);
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
    public function getScost(){
        return $this->hasOne(SaleCosttype::className(),['scost_id'=>'scost_id']);
    }
}
