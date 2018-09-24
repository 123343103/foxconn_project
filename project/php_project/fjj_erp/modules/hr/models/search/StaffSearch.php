<?php

namespace app\modules\hr\models\Search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaff;

/**
 * StaffSearch represents the model behind the search form about `app\modules\hr\models\Staff`.
 */
class StaffSearch extends HrStaff
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'staff_age', 'annual_leave', 'staff_seniority', 'num_seniority', 'staff_qq'], 'integer'],
            [['staff_code', 'organization_code', 'staff_name', 'former_name', 'english_name', 'staff_sex', 'card_id', 'birth_date', 'native_place', 'blood_type', 'staff_nation', 'staff_married', 'health_condition', 'political_status', 'party_time', 'residence_type', 'residence_address', 'job_level', 'administrative_level', 'staff_type', 'job_task', 'job_title', 'employment_date', 'job_title_level', 'position', 'attendance_type', 'salary_date', 'staff_status', 'work_time', 'staff_tel', 'staff_mobile', 'staff_email', 'card_address', 'other_contacts', 'superior', 'subordinate', 'opening_bank', 'bank_account', 'staff_diploma', 'staff_degree', 'staff_graduation_date', 'staff_school', 'staff_major', 'computer_level', 'languages_0', 'languages_1', 'languages_2', 'language_level_0', 'language_level_1', 'language_level_2', 'specialty', 'job_situation', 'insurance_situation', 'remark', 'staff_avatar', 'staff_manage'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = HrStaff::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'staff_id' => $this->staff_id,
            'birth_date' => $this->birth_date,
            'staff_age' => $this->staff_age,
            'annual_leave' => $this->annual_leave,
            'party_time' => $this->party_time,
            'employment_date' => $this->employment_date,
            'staff_seniority' => $this->staff_seniority,
            'salary_date' => $this->salary_date,
            'num_seniority' => $this->num_seniority,
            'work_time' => $this->work_time,
            'staff_qq' => $this->staff_qq,
            'staff_graduation_date' => $this->staff_graduation_date,
        ]);

        $query->andFilterWhere(['like', 'staff_code', $this->staff_code])
            ->andFilterWhere(['like', 'organization_code', $this->organization_code])
            ->andFilterWhere(['like', 'staff_name', $this->staff_name])
            ->andFilterWhere(['like', 'former_name', $this->former_name])
            ->andFilterWhere(['like', 'english_name', $this->english_name])
            ->andFilterWhere(['like', 'staff_sex', $this->staff_sex])
            ->andFilterWhere(['like', 'card_id', $this->card_id])
            ->andFilterWhere(['like', 'native_place', $this->native_place])
            ->andFilterWhere(['like', 'blood_type', $this->blood_type])
            ->andFilterWhere(['like', 'staff_nation', $this->staff_nation])
            ->andFilterWhere(['like', 'staff_married', $this->staff_married])
            ->andFilterWhere(['like', 'health_condition', $this->health_condition])
            ->andFilterWhere(['like', 'political_status', $this->political_status])
            ->andFilterWhere(['like', 'residence_type', $this->residence_type])
            ->andFilterWhere(['like', 'residence_address', $this->residence_address])
            ->andFilterWhere(['like', 'job_level', $this->job_level])
            ->andFilterWhere(['like', 'administrative_level', $this->administrative_level])
            ->andFilterWhere(['like', 'staff_type', $this->staff_type])
            ->andFilterWhere(['like', 'job_task', $this->job_task])
            ->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'job_title_level', $this->job_title_level])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'attendance_type', $this->attendance_type])
            ->andFilterWhere(['like', 'staff_status', $this->staff_status])
            ->andFilterWhere(['=', 'staff_status', self::STAFF_STATUS_DEFAULT])
            ->andFilterWhere(['like', 'staff_tel', $this->staff_tel])
            ->andFilterWhere(['like', 'staff_mobile', $this->staff_mobile])
            ->andFilterWhere(['like', 'staff_email', $this->staff_email])
            ->andFilterWhere(['like', 'card_address', $this->card_address])
            ->andFilterWhere(['like', 'other_contacts', $this->other_contacts])
            ->andFilterWhere(['like', 'superior', $this->superior])
            ->andFilterWhere(['like', 'subordinate', $this->subordinate])
            ->andFilterWhere(['like', 'opening_bank', $this->opening_bank])
            ->andFilterWhere(['like', 'bank_account', $this->bank_account])
            ->andFilterWhere(['like', 'staff_diploma', $this->staff_diploma])
            ->andFilterWhere(['like', 'staff_degree', $this->staff_degree])
            ->andFilterWhere(['like', 'staff_school', $this->staff_school])
            ->andFilterWhere(['like', 'staff_major', $this->staff_major])
            ->andFilterWhere(['like', 'computer_level', $this->computer_level])
            ->andFilterWhere(['like', 'languages_0', $this->languages_0])
            ->andFilterWhere(['like', 'languages_1', $this->languages_1])
            ->andFilterWhere(['like', 'languages_2', $this->languages_2])
            ->andFilterWhere(['like', 'language_level_0', $this->language_level_0])
            ->andFilterWhere(['like', 'language_level_1', $this->language_level_1])
            ->andFilterWhere(['like', 'language_level_2', $this->language_level_2])
            ->andFilterWhere(['like', 'specialty', $this->specialty])
            ->andFilterWhere(['like', 'job_situation', $this->job_situation])
            ->andFilterWhere(['like', 'insurance_situation', $this->insurance_situation])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'staff_avatar', $this->staff_avatar])
            ->andFilterWhere(['like', 'staff_manage', $this->staff_manage]);

        return $dataProvider;
    }

    public function export($params)
    {
        $query = HrStaff::find();


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $query;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'staff_id' => $this->staff_id,
            'birth_date' => $this->birth_date,
            'staff_age' => $this->staff_age,
            'annual_leave' => $this->annual_leave,
            'party_time' => $this->party_time,
            'employment_date' => $this->employment_date,
            'staff_seniority' => $this->staff_seniority,
            'salary_date' => $this->salary_date,
            'num_seniority' => $this->num_seniority,
            'work_time' => $this->work_time,
            'staff_qq' => $this->staff_qq,
            'staff_graduation_date' => $this->staff_graduation_date,
        ]);

        $query->andFilterWhere(['like', 'staff_code', $this->staff_code])
            ->andFilterWhere(['like', 'organization_code', $this->organization_code])
            ->andFilterWhere(['like', 'staff_name', $this->staff_name])
            ->andFilterWhere(['like', 'former_name', $this->former_name])
            ->andFilterWhere(['like', 'english_name', $this->english_name])
            ->andFilterWhere(['like', 'staff_sex', $this->staff_sex])
            ->andFilterWhere(['like', 'card_id', $this->card_id])
            ->andFilterWhere(['like', 'native_place', $this->native_place])
            ->andFilterWhere(['like', 'blood_type', $this->blood_type])
            ->andFilterWhere(['like', 'staff_nation', $this->staff_nation])
            ->andFilterWhere(['like', 'staff_married', $this->staff_married])
            ->andFilterWhere(['like', 'health_condition', $this->health_condition])
            ->andFilterWhere(['like', 'political_status', $this->political_status])
            ->andFilterWhere(['like', 'residence_type', $this->residence_type])
            ->andFilterWhere(['like', 'residence_address', $this->residence_address])
            ->andFilterWhere(['like', 'job_level', $this->job_level])
            ->andFilterWhere(['like', 'administrative_level', $this->administrative_level])
            ->andFilterWhere(['like', 'staff_type', $this->staff_type])
            ->andFilterWhere(['like', 'job_task', $this->job_task])
            ->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'job_title_level', $this->job_title_level])
            ->andFilterWhere(['like', 'position', $this->position])
            ->andFilterWhere(['like', 'attendance_type', $this->attendance_type])
            ->andFilterWhere(['like', 'staff_status', $this->staff_status])
            ->andFilterWhere(['=', 'staff_status', self::STAFF_STATUS_DEFAULT])
            ->andFilterWhere(['like', 'staff_tel', $this->staff_tel])
            ->andFilterWhere(['like', 'staff_mobile', $this->staff_mobile])
            ->andFilterWhere(['like', 'staff_email', $this->staff_email])
            ->andFilterWhere(['like', 'card_address', $this->card_address])
            ->andFilterWhere(['like', 'other_contacts', $this->other_contacts])
            ->andFilterWhere(['like', 'superior', $this->superior])
            ->andFilterWhere(['like', 'subordinate', $this->subordinate])
            ->andFilterWhere(['like', 'opening_bank', $this->opening_bank])
            ->andFilterWhere(['like', 'bank_account', $this->bank_account])
            ->andFilterWhere(['like', 'staff_diploma', $this->staff_diploma])
            ->andFilterWhere(['like', 'staff_degree', $this->staff_degree])
            ->andFilterWhere(['like', 'staff_school', $this->staff_school])
            ->andFilterWhere(['like', 'staff_major', $this->staff_major])
            ->andFilterWhere(['like', 'computer_level', $this->computer_level])
            ->andFilterWhere(['like', 'languages_0', $this->languages_0])
            ->andFilterWhere(['like', 'languages_1', $this->languages_1])
            ->andFilterWhere(['like', 'languages_2', $this->languages_2])
            ->andFilterWhere(['like', 'language_level_0', $this->language_level_0])
            ->andFilterWhere(['like', 'language_level_1', $this->language_level_1])
            ->andFilterWhere(['like', 'language_level_2', $this->language_level_2])
            ->andFilterWhere(['like', 'specialty', $this->specialty])
            ->andFilterWhere(['like', 'job_situation', $this->job_situation])
            ->andFilterWhere(['like', 'insurance_situation', $this->insurance_situation])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'staff_avatar', $this->staff_avatar])
            ->andFilterWhere(['like', 'staff_manage', $this->staff_manage]);

        return $query;
    }
}
