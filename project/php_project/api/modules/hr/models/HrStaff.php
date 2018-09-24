<?php
/**
 * 员工模型类
 * @author F3858995
 * 2016.9.8
 */

namespace app\modules\hr\models;

use app\models\Common;
use app\modules\crm\models\CrmEmployee;
use Yii;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaffTitle;

/**
 * This is the model class for table "hr_staff".
 *
 * @property integer $staff_id
 * @property string $staff_name
 * @property string $english_name
 * @property string $former_name
 * @property string $staff_sex
 * @property string $staff_nation
 * @property string $staff_married
 * @property string $card_id
 * @property string $birth_date
 * @property integer $staff_age
 * @property string $residence_type
 * @property string $native_place
 * @property string $blood_type
 * @property string $health_condition
 * @property string $political_status
 * @property string $party_time
 * @property string $staff_tel
 * @property string $staff_mobile
 * @property string $staff_email
 * @property integer $staff_qq
 * @property string $other_contacts
 * @property string $card_address
 * @property string $residence_address
 * @property string $staff_code
 * @property string $organization_code
 * @property string $job_level
 * @property string $position
 * @property string $employment_date
 * @property string $job_task
 * @property string $job_title
 * @property string $job_title_level
 * @property string $administrative_level
 * @property string $staff_type
 * @property string $attendance_type
 * @property string $salary_date
 * @property integer $annual_leave
 * @property integer $staff_status
 * @property integer $staff_seniority
 * @property string $work_time
 * @property string $superior
 * @property string $subordinate
 * @property string $opening_bank
 * @property string $bank_account
 * @property string $staff_diploma
 * @property string $staff_degree
 * @property string $staff_school
 * @property string $staff_graduation_date
 * @property string $staff_major
 * @property string $computer_level
 * @property string $languages_0
 * @property string $languages_1
 * @property string $languages_2
 * @property string $language_level_0
 * @property string $language_level_1
 * @property string $language_level_2
 * @property string $specialty
 * @property string $job_situation
 * @property string $insurance_situation
 * @property string $remark
 * @property string $staff_avatar
 * @property integer $app_list_top
 */
class HrStaff extends Common
{

    const STAFF_STATUS_DEL = 0;

    const STAFF_STATUS_DEFAULT = 10;

    const STAFF_STATUS_LEAVE = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'erp.hr_staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['staff_id', 'staff_age', 'annual_leave', 'staff_seniority', 'num_seniority', 'staff_qq'], 'integer'],
//            [['staff_code'],'unique'],
//            [['staff_code', 'organization_code', 'staff_name', 'former_name', 'english_name', 'staff_sex', 'card_id', 'birth_date', 'native_place', 'blood_type', 'staff_nation', 'staff_married', 'health_condition', 'political_status', 'party_time', 'residence_type', 'residence_address', 'job_level', 'administrative_level', 'staff_type', 'job_task', 'job_title', 'employment_date', 'job_title_level', 'position', 'attendance_type', 'salary_date', 'staff_status', 'work_time', 'staff_tel', 'staff_mobile', 'staff_email', 'card_address', 'other_contacts', 'superior', 'subordinate', 'opening_bank', 'bank_account', 'staff_diploma', 'staff_degree', 'staff_graduation_date', 'staff_school', 'staff_major', 'computer_level', 'languages_0', 'languages_1', 'languages_2', 'language_level_0', 'language_level_1', 'language_level_2', 'specialty', 'job_situation', 'insurance_situation', 'remark', 'staff_avatar', 'staff_manage'], 'safe'],
//            [['staff_status'],'default','value'=>self::STAFF_STATUS_DEFAULT]
            [['staff_married', 'blood_type'], 'string'],
            [['birth_date', 'party_time', 'employment_date', 'salary_date', 'work_time', 'staff_graduation_date'], 'safe'],
            [['staff_age', 'staff_qq', 'annual_leave', 'staff_status', 'staff_seniority'], 'safe'],
            [['staff_code'], 'unique'],
            [['staff_name', 'english_name', 'former_name', 'residence_type', 'staff_email', 'other_contacts', 'residence_address', 'job_level', 'job_task', 'job_title', 'job_title_level', 'administrative_level', 'superior', 'subordinate', 'staff_avatar'], 'safe'],
            [['staff_nation', 'card_id', 'native_place', 'staff_tel', 'staff_mobile', 'computer_level', 'languages_0', 'languages_1', 'languages_2', 'language_level_0', 'language_level_1', 'language_level_2'], 'safe'],
            [['health_condition', 'political_status', 'staff_type', 'attendance_type', 'staff_school', 'staff_major'], 'safe'],
            [['card_address', 'position', 'job_situation', 'insurance_situation', 'remark'], 'safe'],
            [['organization_code', 'specialty'], 'safe'],
            [['opening_bank', 'bank_account'], 'safe'],
            [['staff_diploma', 'staff_degree'], 'safe'],
            [['sts_id', 'csarea_id', 'sale_area', 'data_from', 'app_list_top'], 'safe'],
            [['staff_status'], 'default', 'value' => self::STAFF_STATUS_DEFAULT],
            [['staff_avatar'], 'default', 'value' => "/avatar/avatar.png"]
        ];

    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => 'Staff ID',
            'staff_name' => 'Staff Name',
            'english_name' => 'English Name',
            'former_name' => 'Former Name',
            'staff_sex' => 'Staff Sex',
            'staff_nation' => 'Staff Nation',
            'staff_married' => 'Staff Married',
            'card_id' => 'Card ID',
            'birth_date' => 'Birth Date',
            'staff_age' => 'Staff Age',
            'residence_type' => 'Residence Type',
            'native_place' => 'Native Place',
            'blood_type' => 'Blood Type',
            'health_condition' => 'Health Condition',
            'political_status' => 'Political Status',
            'party_time' => 'Party Time',
            'staff_tel' => 'Staff Tel',
            'staff_mobile' => 'Staff Mobile',
            'staff_email' => 'Staff Email',
            'staff_qq' => 'Staff Qq',
            'other_contacts' => 'Other Contacts',
            'card_address' => 'Card Address',
            'residence_address' => 'Residence Address',
            'staff_code' => 'Staff Code',
            'organization_code' => 'Organization Code',
            'job_level' => 'Job Level',
            'position' => 'Job Management',
            'employment_date' => 'Employment Date',
            'job_task' => 'Job Task',
            'job_title' => 'Job Title',
            'job_title_level' => 'Job Title Level',
            'administrative_level' => 'Administrative Level',
            'staff_type' => 'Staff Type',
            'attendance_type' => 'Attendance Type',
            'salary_date' => 'Salary Date',
            'annual_leave' => 'Annual Leave',
            'staff_status' => 'Staff Status',
            'staff_seniority' => 'Staff Seniority',
            'work_time' => 'Work Time',
            'superior' => 'Superior',
            'subordinate' => 'Subordinate',
            'opening_bank' => 'Opening Bank',
            'bank_account' => 'Bank Account',
            'staff_diploma' => 'Staff Diploma',
            'staff_degree' => 'Staff Degree',
            'staff_school' => 'Staff School',
            'staff_graduation_date' => 'Staff Graduation Date',
            'staff_major' => 'Staff Major',
            'computer_level' => 'Computer Level',
            'languages_0' => 'Languages 0',
            'languages_1' => 'Languages 1',
            'languages_2' => 'Languages 2',
            'language_level_0' => 'Language Level 0',
            'language_level_1' => 'Language Level 1',
            'language_level_2' => 'Language Level 2',
            'specialty' => 'Specialty',
            'job_situation' => 'Job Situation',
            'insurance_situation' => 'Insurance Situation',
            'remark' => 'Remark',
            'staff_avatar' => 'Staff Avatar',
        ];
    }

    /**
     * 获取该组织机构下的人员
     * @param $code
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getStaffByOrgCode($code)
    {
        return self::find()->where(['organization_code' => $code])->select('staff_id,staff_name')->asArray()->all();
    }

    /**
     * 通过工号获取员工数据
     * @param $code
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getStaffByIdCode($code)
    {
        return self::find()
            ->select([
                self::tableName().".staff_id",
                self::tableName().".staff_code",
                self::tableName().".staff_name",
                self::tableName().".staff_mobile",
                self::tableName().".staff_email",
                self::tableName().".other_contacts",
                HrStaffTitle::tableName().".title_name position",
                HrOrganization::tableName().".organization_id organizationid",
                HrOrganization::tableName().".organization_name organization"
            ])
            ->joinWith("title",false)
            ->joinWith("organization",false)
            ->where([
                self::tableName().'.staff_code' => $code
            ])
            ->asArray()
            ->one();
    }

    /**
     * 通过员工ID获取员工数据
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getStaffById($id)
    {
        return self::find()->where(['staff_id' => $id])->select('staff_id,staff_name,job_task,staff_tel,staff_mobile,staff_code,organization_code')->one();
    }

    public static function getStaffInfoById($id)
    {
        return self::find()
            ->select([
                self::tableName().".staff_id",
                self::tableName().".staff_code",
                self::tableName().".staff_name",
                self::tableName().".staff_mobile",
                self::tableName().".staff_email",
                self::tableName().".other_contacts",
                self::tableName().".position",
                HrOrganization::tableName().".organization_name organization"
            ])
            ->joinWith("title",false)
            ->joinWith("organization",false)
            ->where([
                self::tableName().'.staff_id' => $id
            ])
            ->asArray()
            ->one();
    }

    /** 获取审核人单位代码
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getOrgCode($id)
    {
        return self::find()->where(['staff_id' => $id])->select('organization_code')->one();
    }

    public static function getStaffOption($code)
    {

        $staffList = self::find()->where(['organization_code' => $code])->select('staff_id,staff_name')
            ->asArray()->all();

        $option = [];
        foreach ($staffList as $key => $val) {
            $option[$val['staff_id']] = $val['staff_name'];
        }
        return $option;
    }

    public function getOrganization()
    {
        return $this->hasOne(HrOrganization::className(), ['organization_code' => 'organization_code']);
    }

    /*关联管理职*/
    public function getTitle()
    {
        return $this->hasOne(HrStaffTitle::className(), ['title_id' => 'position']);
    }

    public static function getStaffAll($select)
    {
        return self::find()->where(['staff_status' => self::STAFF_STATUS_DEFAULT])->select($select)->asArray()->all();
    }

    /*
     * 根据姓名查询管理员staffCode add by jh 20170620
     */
    public static function getStaffByName($name)
    {
        return self::find()->where(['staff_name' => $name])->select('staff_id,staff_name,job_task,staff_mobile,staff_code')->one();
    }

    public function getEmployee(){
        return $this->hasOne(CrmEmployee::className(),['staff_code'=>'staff_code']);
    }
}
