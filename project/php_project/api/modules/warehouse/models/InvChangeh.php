<?php

namespace app\modules\warehouse\models;

use app\models\Common;
use app\modules\common\models\BsBusinessType;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaff;
use Yii;
use app\behaviors\FormCodeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "inv_changeh".
 *
 * @property integer $chh_id
 * @property string $chh_code
 * @property integer $chh_type
 * @property integer $comp_id
 * @property string $chh_date
 * @property integer $wh_id
 * @property integer $wh_id2
 * @property string $chh_status
 * @property string $chh_description
 * @property integer $whp_id
 * @property string $chh_remark
 * @property string $create_at
 * @property integer $create_by
 * @property integer $review_by
 * @property string $review_at
 * @property string $o_status
 * @property string $in_status
 */
class InvChangeh extends Common
{
    const STATUS_DELETE = 0;  //删除
    const STATUS_ADD = 10;    //待提交
    const STATUS_WAIT = 20; //审核中
    const STATUS_PENDING = 30;//审核完成
    const STATUS_PREPARE = 40; //驳回
    const STATUS_OUTWAIT = 1;  //待出库（调出仓库）
    const STATUS_OUTACTION = 2;  //已出库（调出仓库）
    const STATUS_INWAIT = 3;   //待入库（调入仓库）
    const STATUS_INACTION = 4;  //已入库（调入仓库）
    const STATUS_INMOVE = 5;    //移仓处理（已生成移仓通知）
    const STATUS_MOVE = 50;     //报废作废


    //入库单出库单编码标志
    public $codeType;
    const CODE_TYPE_DB = 20;
    const CODE_TYPE_YD = 30;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inv_changeh';
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
            [['chh_id', 'chh_type', 'comp_id', 'wh_id', 'wh_id2', 'whp_id', 'create_by', 'review_by','depart_id'], 'integer'],
            [['chh_date', 'create_at', 'review_at'], 'safe'],
            [['chh_code'], 'string', 'max' => 40],
            [['chh_status','o_status','in_status'], 'safe'],
            [['chh_description', 'chh_remark'], 'string', 'max' => 200],
        ];
    }

    //报废类别
    public function getChhType()
    {
        return
            $this->hasOne(BsBusinessType::className(), ['business_type_id' => 'chh_type'])
                ->select('business_value');
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     * 获取单条信息
     */
    public static function getInvChange($id, $select)
    {
        return self::find()->where(['chh_id' => $id])->select($select)->one();
    }

    public function getProduct()
    {

    }

    /**
     * @return $this
     * 关联仓库表获取仓库名
     */
    public function getWh()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'wh_id'])->select('wh_name,wh_code,wh_attr');
    }

    /**
     * @return $this
     * 关联部门表获取仓库名
     */
    public function getOrganizationss()
    {
        return $this->hasOne(HrOrganization::className(), ['organization_id' => 'depart_id'])->select('organization_id,organization_name');
    }

    /**
     * @return $this
     * 入库仓库名
     */
    public function getWh2()
    {
        return $this->hasOne(BsWh::className(), ['wh_id' => 'wh_id2'])->select('wh_name,wh_code,wh_attr');
    }

    /*申请人*/
    public function getStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by'])->select('staff_name');
    }

    /*制单人*/
    public function getStaffss()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'review_by'])->select('staff_name');
    }


    /**
     * @return $this
     * 申请人工号
     */
    public function getStaffCode()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by'])->select('staff_code');
    }

    /**
     * @return \yii\db\ActiveQuery
     * 关联报废子表信息
     */
    public function getInvChangeLInfo()
    {
        return $this->hasMany(InvChangel::className(), ['chh_id' => 'chh_id']);
    }

    /*
     * 获取部门信息
     */
    public function getOrganization()
    {
        $org = HrStaff::find()->select([
            HrStaff::tableName() . ".staff_id",
            HrOrganization::tableName() . ".organization_name organization"
        ])
            ->joinWith("organization", false)
            ->where([HrStaff::tableName() . '.staff_id' => $this->create_by])
            ->asArray()
            ->one();
        return $org;

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'chh_id' => '单据ID',
            'chh_code' => '单据编码',
            'chh_type' => '异动类型',//（仓库转移，调拨，仓位异动，料号转换等等异动单）
            'comp_id' => '公司ID',
            'chh_date' => '单据日期',
            'wh_id' => '异动仓库,料号异动仓或转仓为转出仓',
            'wh_id2' => '对应异动仓库',
            'chh_status' => '状态',
            'chh_description' => '异动描述',
            'whp_id' => '关联仓库费用表(wh_price)',
            'chh_remark' => '备注',
            'create_at' => '创建时间',
            'create_by' => '创建人',
            'review_by' => '确认人',
            'review_at' => '确认日期',
            'depart_id'=>'调拨部门',
            'o_status'=>'出库状态',
            'in_status'=>'入库状态',
        ];
    }

    public function behaviors()
    {
        return [
            "formCode" => [
                "class" => FormCodeBehavior::className(),
                "codeField" => 'chh_code',
                "formName" => self::tableName(),
                "model" => $this,
            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['create_at'],            //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['review_at']            //更新
                ]
            ],
        ];
    }

}
