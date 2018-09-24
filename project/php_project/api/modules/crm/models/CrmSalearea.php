<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\common\models\BsDistrict;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use Yii;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsPubdata;

/**
 * This is the model class for table "crm_bs_salearea".
 *
 * @property integer $csarea_id
 * @property string $csarea_code
 * @property string $csarea_name
 * @property string $csarea_status
 * @property string $csarea_remark
 */
class CrmSalearea extends Common
{
    const STATUS_STOP = '10';//禁用
    const STATUS_DEFAULT = '20';//默认
    const STATUS_DELETE= '30';//删除
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bs_salearea';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['csarea_code'], 'required', 'message' => '{attribute}必填'],
            [['csarea_code'], 'unique', 'filter' => ['!=', 'csarea_status', CrmSalearea::STATUS_DELETE],'targetAttribute' => 'csarea_code', 'message' => '{attribute}已经存在'],
            [['csarea_id'], 'integer'],
//            [['csarea_code', 'csarea_name', 'csarea_status'], 'string', 'max' => 20],
            [['csarea_code', 'csarea_name', 'csarea_status'], 'safe'],
            [['csarea_remark'], 'string', 'max' => 60],
            [['create_at', 'update_at', 'create_by', 'update_by','csarea_children'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'csarea_id' => '军区ID',
            'csarea_code' => '代号',
            'csarea_name' => '名称',
            'csarea_status' => '状态',
            'csarea_remark' => '备注',
            'csarea_children' => '包含地区',
        ];
    }

    public static function getSalearea($where = [], $asArray = true)
    {
        return static::find()->asArray($asArray)->where($where)->andWhere(['csarea_status' => 20])->all();
    }

    /*建档人*/
    public function getBuildStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by']);
    }
    /*更新人*/
    public function getUpdateStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'update_by']);
    }
    /*状态*/
    public function getStatus()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'csarea_status']);
    }
    /*包含地区*/
    public function getIncludeDistrict(){
        return $this->hasMany(CrmDistrictSalearea::className(),['csarea_id'=>'csarea_id']);
    }
    public function getDis(){
      return  $this->hasMany(CrmDistrictSalearea::className(),['csarea_id'=>'csarea_id']);

    }
    /*省*/
    public function getDisProvice(){
        return $this->hasMany(BsDistrict::className(),['district_id'=>'district_id'])->via('includeDistrict');
    }
    /*市*/
    public function getDisCity(){
        return $this->hasMany(BsDistrict::className(),['district_id'=>'city_id'])->via('includeDistrict');
    }
    public function behaviors()
    {
        return [
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['update_at']            //更新
                ]
            ],
        ];
    }
}
