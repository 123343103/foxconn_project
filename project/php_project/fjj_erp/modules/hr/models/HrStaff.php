<?php
/**
 * 员工模型类
 * @author F3858995
 * 2016.9.8
 */

namespace app\modules\hr\models;

use Yii;
use app\modules\hr\models\HrOrganization;
use app\modules\hr\models\HrStaffTitle;
/**
 * This is the model class for table "staff".
 *
 * @property integer $staff_id
 * @property string $staff_code
 * @property string $organization_code
 * @property string $staff_name
 * @property string $former_name
 * @property string $english_name
 * @property string $staff_sex
 * @property string $card_id
 * @property string $birth_date
 * @property integer $staff_age
 * @property integer $annual_leave
 * @property string $native_place
 * @property string $blood_type
 * @property string $staff_nation
 * @property string $staff_married
 * @property string $health_condition
 * @property string $political_status
 * @property string $party_time
 * @property string $residence_type
 * @property string $residence_address
 * @property string $job_level
 * @property string $administrative_level
 * @property string $staff_type
 * @property string $job_task
 * @property string $job_title
 * @property string $employment_date
 * @property string $job_title_level
 * @property string $position
 * @property string $attendance_type
 * @property integer $staff_seniority
 * @property string $salary_date
 * @property string $staff_state
 * @property integer $num_seniority
 * @property string $work_time
 * @property string $staff_tel
 * @property string $staff_mobile
 * @property string $staff_email
 * @property string $card_address
 * @property integer $staff_qq
 * @property string $other_contacts
 * @property string $superior
 * @property string $subordinate
 * @property string $opening_bank
 * @property string $bank_account
 * @property string $staff_diploma
 * @property string $staff_degree
 * @property string $staff_graduation_date
 * @property string $staff_school
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
 * @property string $staff_manage
 */
class HrStaff extends \yii\db\ActiveRecord
{

    const STAFF_STATUS_DEL = 0;

    const STAFF_STATUS_DEFAULT  = 10;

    const STAFF_STATUS_LEAVE = 20;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hr_staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_code','organization_code','staff_name','position'], 'required'],
            [['staff_code'], 'unique'],
            [['birth_date', 'party_time', 'employment_date', 'salary_date', 'work_time', 'staff_graduation_date'], 'safe'],
            [['staff_age', 'annual_leave', 'staff_seniority', 'num_seniority', 'staff_qq'], 'integer'],
            [['blood_type', 'staff_married', 'staff_status','data_from'], 'safe'],
            [['staff_code'], 'string', 'max' => 11],
            [['organization_code', 'specialty'], 'string', 'max' => 100],
            [['staff_name', 'former_name', 'english_name', 'residence_type', 'residence_address', 'job_level', 'administrative_level', 'job_task', 'job_title', 'job_title_level', 'staff_email', 'other_contacts', 'superior', 'subordinate', 'staff_avatar'], 'string', 'max' => 50],
            [['staff_sex'], 'string', 'max' => 1],
            [['card_id', 'native_place', 'staff_nation', 'staff_tel', 'staff_mobile', 'computer_level', 'languages_0', 'languages_1', 'languages_2', 'language_level_0', 'language_level_1', 'language_level_2'], 'string', 'max' => 20],
            [['health_condition', 'political_status', 'staff_type', 'attendance_type', 'staff_school', 'staff_major'], 'string', 'max' => 30],
            [['position', 'card_address', 'job_situation', 'insurance_situation', 'remark', 'staff_manage'], 'string', 'max' => 255],
            [['opening_bank', 'bank_account'], 'string', 'max' => 40],
            [['staff_diploma', 'staff_degree'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => '序号,ID',
            'staff_code' => '员工工号',
            'organization_code' => '部門',
            'staff_name' => '姓名',
            'former_name' => '曾用名',
            'english_name' => '英文名',
            'staff_sex' => '性别',
            'card_id' => '身份证号码',
            'birth_date' => '出生年月日',
            'staff_age' => '年齡',
            'annual_leave' => '年休假',
            'native_place' => '籍貫',
            'blood_type' => '血型',
            'staff_nation' => '民族',
            'staff_married' => '婚否',
            'health_condition' => '健康狀況',
            'political_status' => '政治面貌',
            'party_time' => '入黨時間',
            'residence_type' => '户口类型',
            'residence_address' => '戶口所在地',
            'job_level' => '職位級別',
            'administrative_level' => '行政級別',
            'staff_type' => '員工類型',
            'job_task' => '職務',
            'job_title' => '職稱',
            'employment_date' => '入職時間',
            'job_title_level' => '職稱級別',
            'position' => '崗位',
            'attendance_type' => '考勤類型',
            'staff_seniority' => '工齡',
            'salary_date' => '起薪時間',
            'staff_status' => '員工狀態',
            'num_seniority' => '總工齡',
            'work_time' => '產加工作時間',
            'staff_tel' => '聯繫電話',
            'staff_mobile' => '手機號碼',
            'staff_email' => '電子郵件',
            'card_address' => '家庭地址',
            'staff_qq' => 'QQ',
            'other_contacts' => '其他聯繫方式',
            'superior' => '直属上级',
            'subordinate' => '直属下级',
            'opening_bank' => '开户行',
            'bank_account' => '银行账户',
            'staff_diploma' => '學歷',
            'staff_degree' => '學位',
            'staff_graduation_date' => '毕业日期',
            'staff_school' => '毕业学校',
            'staff_major' => '专业',
            'computer_level' => '計算機水平',
            'languages_0' => '外語語種1',
            'languages_1' => '外語語種2',
            'languages_2' => '外語語種3',
            'language_level_0' => '外语水平1',
            'language_level_1' => '外语水平2',
            'language_level_2' => '外语水平3',
            'specialty' => '特長',
            'job_situation' => '職務情況',
            'insurance_situation' => '社保情況',
            'remark' => '備註',
            'staff_avatar' => '頭像',
            'staff_manage' => '备注',
        ];
    }
    public static function getStaffByOrgCode($code){
        return self::find()->where(['organization_code'=>$code])->select('staff_id,staff_name')
            ->asArray()->all();
    }

    public static function getStaffByIdCode($code){
        return self::find()->where(['staff_code'=>$code])->select('staff_id,staff_name,job_task,staff_mobile')->asArray()->one();
    }

    public  static function getStaffOption($code){
        $staffList = self::find()->where(['organization_code'=>$code])->select('staff_id,staff_name')
            ->asArray()->all();

        $option=[];
        foreach($staffList as $key => $val){
            $option[$val['staff_id']] =$val['staff_name'];
        }
        return $option;
    }

    public function getOrganization()
    {
        return $this->hasOne(HrOrganization::className(),['organization_code'=>'organization_code']);
    }

    /*关联岗位*/
    public function getTitle(){
        return $this->hasOne(HrStaffTitle::className(),['title_id'=>'position']);
    }
}
