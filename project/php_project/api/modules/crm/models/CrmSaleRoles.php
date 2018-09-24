<?php

namespace app\modules\crm\models;

use app\models\Common;
use Yii;
use app\modules\hr\models\HrStaff;
use app\modules\common\models\BsPubdata;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "crm_sale_roles".
 *
 * @property string $sarole_id
 * @property string $sarole_code
 * @property string $sarole_sname
 * @property string $sarole_type
 * @property string $isdeduct_salary
 * @property string $other_amount
 * @property string $sarole_qty
 * @property string $sarole_status
 * @property string $sarole_desription
 * @property string $create_by
 * @property string $cdate
 * @property string $update_by
 * @property string $udate
 * @property string $sarole_remark
 * @property string $vdef1
 * @property string $vdef2
 * @property string $vdef3
 * @property string $vdef4
 * @property string $vdef5
 */
class CrmSaleRoles extends Common
{
    const STATUS_DEL= '30';//删除
    const STATUS_DEFAULT = '20';//启用
    const STATUS_FORBIDDEN = '10';//禁用
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_sale_roles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sarole_code'], 'required', 'message' => '{attribute}必填'],
            [['sarole_code'], 'unique', 'filter' => ['!=', 'sarole_status', CrmSaleRoles::STATUS_DEL], 'targetAttribute' => 'sarole_code', 'message' => '{attribute}已经存在'],
            [['other_amount', 'sarole_qty'], 'number'],
            [['create_by', 'update_by'], 'integer'],
            [['cdate', 'udate','sarole_code', 'sarole_sname', 'sarole_type', 'sarole_status','sarole_remark'], 'safe'],
//            [['sarole_code', 'sarole_sname', 'sarole_type', 'sarole_status'], 'string', 'max' => 20],
            [['isdeduct_salary'], 'string', 'max' => 4],
            [['sarole_desription', 'vdef1', 'vdef2', 'vdef3', 'vdef4', 'vdef5'], 'string', 'max' => 120],
        ];
    }

    /*建档人*/
    public function getBuildStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'create_by'])->from(['u2' => HrStaff::tableName()]);
    }

    /*修改人*/
    public function getUpdateStaff()
    {
        return $this->hasOne(HrStaff::className(), ['staff_id' => 'update_by']);
    }

    /*人力类型*/
    public function getRoleType()
    {
        return $this->hasOne(BsPubdata::className(), ['bsp_id' => 'sarole_type']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sarole_id' => 'ID',
            'sarole_code' => '销售角色编码',
            'sarole_sname' => '銷售角色名稱',
            'sarole_type' => '1直接人力2間接人力',
            'isdeduct_salary' => '是否扣減個人工資',
            'other_amount' => '其他金額',
            'sarole_qty' => '角色提成係數',
            'sarole_status' => '狀態',
            'sarole_desription' => '描述',
            'create_by' => '創建人',
            'cdate' => '創建時間',
            'update_by' => '修改人',
            'udate' => '修改時間',
            'sarole_remark' => '備註',
            'vdef1' => '是否主管',
            'vdef2' => '備用2',
            'vdef3' => '備用3',
            'vdef4' => '備用4',
            'vdef5' => '備??5',
        ];
    }
    public function behaviors()
    {
        return [
//            "StaffBehavior" => [                           //為字段自動賦值（登錄用戶）
//                "class" => StaffBehavior::className(),
//                'attributes'=>[
//                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['creator'],   //插入時自動賦值字段
//                    BaseActiveRecord::EVENT_BEFORE_UPDATE =>  ['editor']  //更新時自動賦值字段
//                ],
//                'value'=>function(){
//                    return Yii::$app->user->identity->staff->staff_id;  // 測試出錯
//                    return 694; // 測試ok
//                }
//            ],
            'timeStamp' => [
                'class' => TimestampBehavior::className(),    //時間字段自動賦值
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['cdate'],  //插入
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['udate']            //更新
                ],
                'value' => function () {
                    return date("Y-m-d H:i:s", time());          //賦值的值來源,如不同複寫
                }
            ]
        ];
    }
}
