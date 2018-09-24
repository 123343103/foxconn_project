<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use Yii;

/**
 * This is the model class for table "sale_interapply_h".
 *
 * @property integer $siah_id
 * @property string $siah_code
 * @property string $scost_id
 * @property string $siah_description
 * @property integer $siah_dirstrict
 * @property string $siah_address
 * @property string $siah_appdate
 * @property integer $siah_appdepid
 * @property integer $siah_appman
 * @property string $siah_objtype
 * @property integer $siah_objid
 * @property string $siah_shape
 * @property string $siah_standard
 * @property string $siah_cost
 * @property integer $cur_id
 * @property integer $siah_peopleqyt
 * @property string $siah_remark
 * @property string $siah_status
 * @property integer $siah_sender
 * @property string $siah_senddate
 * @property integer $siah_creator
 * @property string $siah_cdate
 * @property integer $siah_editor
 * @property string $siah_edate
 */
class SaleInterapply extends Common
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale_interapply_h';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['siah_id'], 'required'],
            [['siah_id', 'scost_id', 'siah_dirstrict', 'siah_appdepid', 'siah_appman', 'siah_objid', 'cur_id', 'siah_peopleqyt', 'siah_sender', 'siah_creator', 'siah_editor'], 'integer'],
            [['siah_appdate', 'siah_senddate', 'siah_cdate', 'siah_edate','data_id'], 'safe'],
            [['siah_cost'], 'number'],
            [['siah_code', 'siah_objtype', 'siah_shape', 'siah_standard'], 'string', 'max' => 20],
            [['siah_description'], 'string', 'max' => 40],
            [['siah_address'], 'string', 'max' => 255],
            [['siah_remark'], 'string', 'max' => 60],
            [['siah_status'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'siah_id' => 'Siah ID',
            'siah_code' => '編碼',
            'scost_id' => '??sale_costtype',
            'siah_description' => '描述',
            'siah_dirstrict' => '地点五级ID',
            'siah_address' => '地点',
            'siah_appdate' => '申請日期',
            'siah_appdepid' => '申請部門 ??部?',
            'siah_appman' => '申請人',
            'siah_objtype' => '交際對象分類',
            'siah_objid' => '交際對象(客戶ID)',
            'siah_shape' => '交際形式',
            'siah_standard' => '費用標準',
            'siah_cost' => '申請???金額',
            'cur_id' => '金額幣別 ????表',
            'siah_peopleqyt' => '預計人數',
            'siah_remark' => '備註',
            'siah_status' => '狀態',
            'siah_sender' => '送審人',
            'siah_senddate' => '送審日期',
            'siah_creator' => '創建人',
            'siah_cdate' => '創建日期',
            'siah_editor' => '修改人',
            'siah_edate' => '修改日期',
        ];
    }
    /*公司地址*/
    public function getDistrict(){
        $disId = $this->hasOne(BsDistrict::className(),['district_id'=>'siah_dirstrict']);
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
