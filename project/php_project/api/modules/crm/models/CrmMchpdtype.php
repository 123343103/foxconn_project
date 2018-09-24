<?php

namespace app\modules\crm\models;

use app\models\Common;
use app\modules\ptdt\models\BsCategory;
use app\modules\hr\models\HrStaff;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "crm_bs_mchpdtype".
 *
 * @property integer $id
 * @property string $staff_code
 * @property integer $category_id
 * @property string $mpdt_flag
 * @property string $mpdt_status
 * @property string $mpdt_remark
 * @property string $create_by
 * @property string $create_at
 * @property string $update_by
 * @property string $update_at
 */
class CrmMchpdtype extends Common
{

    const STATUS_DELETE = 0;          //删除
    const STATUS_DEFAULT = 10;        //正常
    const STATUS_DISABLED = 11;        //禁用

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crm_bs_mchpdtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_at', 'update_at', 'category_id', 'mpdt_status'], 'safe'],
            [['staff_code', 'create_by', 'update_by'], 'string', 'max' => 20],
            [['mpdt_flag'], 'string', 'max' => 2],
            [['mpdt_remark'], 'string', 'max' => 200],
            [['mpdt_status'], 'default', 'value' => self::STATUS_DEFAULT]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'staff_code' => 'Staff Code',
            'category_id' => 'Category ID',
            'mpdt_flag' => 'Mpdt Flag',
            'mpdt_status' => 'Mpdt Status',
            'mpdt_remark' => 'Mpdt Remark',
            'create_by' => 'Create By',
            'create_at' => 'Create At',
            'update_by' => 'Update By',
            'update_at' => 'Update At',
        ];
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

    public function fields()
    {
        $fields = parent::fields();
        $fields['status'] = function () {
            return $this->mpdt_status;
        };
        $fields['staff_id'] = function () {
            return HrStaff::getStaffByIdCode($this->staff_code)['staff_id'];
        };
        $fields['staff_name'] = function () {
            return HrStaff::getStaffByIdCode($this->staff_code)['staff_name'];
        };
        $fields['mpdt_status'] = function () {
            switch ($this->mpdt_status) {
                case self::STATUS_DEFAULT :
                    return '启用';
                    break;
                case self::STATUS_DISABLED :
                    return '禁用';
                    break;
                default :
                    break;
            }
        };
        $fields['create_by'] = function () {
            return HrStaff::getStaffById($this->create_by)['staff_name'];
        };
        $fields['update_by'] = function () {
            return HrStaff::getStaffById($this->update_by)['staff_name'];
        };
        //商品分类
        $fields['typeName'] = function () {
            $catArr = explode(",", $this->category_id);
            $cats = BsCategory::find()->where(["in", "catg_id", $catArr])->asArray()->all();
            $catNameArr = array_filter(array_column($cats, "catg_name"));
            if (!count($catNameArr)) {
                return "/";
            }
            return implode(",", $catNameArr);
        };
        return $fields;
    }

    public static function getStatus()
    {
        return [
            self::STATUS_DEFAULT => '启用',
            self::STATUS_DISABLED => '禁用',
        ];
    }
}