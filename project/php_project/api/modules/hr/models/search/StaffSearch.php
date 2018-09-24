<?php

namespace app\modules\hr\models\search;

use app\classes\Trans;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;

/**
 * StaffSearch represents the model behind the search form about `app\modules\hr\models\Staff`.
 */
class StaffSearch extends HrStaff
{
    /**
     * @inheritdoc
     */
    public $organization_name;
    public $staff_name;
    public function rules()
    {
        return [
            [['staff_married', 'blood_type'], 'string'],
            [['birth_date', 'party_time', 'employment_date', 'salary_date', 'work_time', 'staff_graduation_date'], 'safe'],
            [['staff_age', 'staff_qq', 'annual_leave', 'staff_status', 'staff_seniority'], 'safe'],
            [['staff_code'], 'safe'],
            [['staff_name', 'english_name', 'former_name', 'residence_type', 'staff_email', 'other_contacts', 'residence_address', 'job_level', 'job_task', 'job_title', 'job_title_level', 'administrative_level', 'superior', 'subordinate', 'staff_avatar'], 'safe'],
            [['staff_nation', 'card_id', 'native_place', 'staff_tel', 'staff_mobile', 'computer_level', 'languages_0', 'languages_1', 'languages_2', 'language_level_0', 'language_level_1', 'language_level_2'], 'safe'],
            [['health_condition', 'political_status', 'staff_type', 'attendance_type', 'staff_school', 'staff_major'], 'safe'],
            [['card_address', 'position', 'job_situation', 'insurance_situation', 'remark'], 'safe'],
            [['organization_code', 'specialty','organization_name'], 'safe'],
            [['opening_bank', 'bank_account'], 'safe'],
            [['staff_diploma', 'staff_degree','staff_status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = HrStaffShow::find()->select('staff_id,staff_code,staff_name,hr_staff.organization_code,job_level,position,employment_date,staff_mobile,staff_status');
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            if(isset($params['export'])){
                $pageSize =false;
            }else{
                $pageSize =10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('organization');
        $trans=new Trans();
        $query->andFilterWhere(['like', 'staff_code', $this->staff_code])
            ->andFilterWhere([
                'or',
                ['like', 'hr_organization.organization_name', $this->organization_name],
                ['like', 'hr_organization.organization_name', $trans->t2c($this->organization_name)],
                ['like', 'hr_organization.organization_name', $trans->c2t($this->organization_name)],
                ['like', 'hr_organization.organization_code', $this->organization_name]
            ])
            ->andFilterWhere(['or',
                ['like', 'staff_name', $this->staff_name],
                ['like', 'staff_name', $trans->t2c($this->staff_name)],
                ['like', 'staff_name', $trans->c2t($this->staff_name)]
            ])
            ->andFilterWhere(['=', 'position', $this->position])
            ->andFilterWhere(['=', 'staff_status', $this->staff_status])
            ->andFilterWhere(['<>', 'staff_status', 0])
            ;
        return $dataProvider;
    }

    public function export($params)
    {
        $query = HrStaffShow::find()->select('staff_id,staff_code,staff_name,hr_staff.organization_code,job_task,position,employment_date,staff_mobile');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => false,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('organization');
        $query->andFilterWhere(['like', 'staff_code', $this->staff_code])
            ->andFilterWhere(['like', 'hr_organization.organization_name',$this->organization_name])
            ->andFilterWhere(['like', 'staff_name', $this->staff_name])
            ->andFilterWhere(['=', 'position', $this->position])
            ->andFilterWhere(['=', 'staff_status', self::STAFF_STATUS_DEFAULT])
        ;
        return $dataProvider;
    }
//获取采购员信息
    public function buyinfosearch($params)
    {
        $query = HrStaffShow::find()->select('staff_id,staff_code,staff_name,hr_staff.organization_code,staff_mobile');
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            if(isset($params['export'])){
                $pageSize =false;
            }else{
                $pageSize =10;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('organization');
        $query->andFilterWhere(['=',HrStaff::tableName().'.staff_id',empty($params['id'])?'':$params['id']]);
        return $dataProvider;
    }
}
