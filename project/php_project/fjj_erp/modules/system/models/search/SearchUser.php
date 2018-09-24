<?php

namespace app\modules\system\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * SearchUser represents the model behind the search form about `app\models\User`.
 */
class SearchUser extends User
{
    /**
     * @inheritdoc
     */
    public $staff_name;
    public $organization_name;
    public $staff_code;
    public function rules()
    {
        return [
            [['staff_name','staff_code','organization_name'], 'safe'],
            [['user_id', 'staff_id', 'user_status', 'is_login'], 'integer'],
            [['user_account', 'user_pwd', 'security_level', 'start_at', 'end_at', 'user_type', 'create_by', 'create_at', 'update_by', 'update_at'], 'safe'],
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
        $query = User::find();

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
        $query->joinWith('staff')->joinWith('staff.organization');
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'staff_id' => $this->staff_id,
            'user_status' => $this->user_status,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'is_login' => $this->is_login,
        ]);

        $query->andFilterWhere(['like', 'user_account', $this->user_account])
            ->andFilterWhere([
                'like','hr_staff.staff_name', $this->staff_name,
            ])
            ->andFilterWhere([
                'like','hr_staff.staff_code', $this->staff_code,
            ])
            ->andFilterWhere([
                'like','organization_name', $this->organization_name,
            ])
            ->andFilterWhere(['like', 'user_pwd', $this->user_pwd])
            ->andFilterWhere(['>=', 'user_status', self::STATUS_ACTIVE])
            ->andFilterWhere(['like', 'security_level', $this->security_level])
            ->andFilterWhere(['like', 'user_type', $this->user_type])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'update_by', $this->update_by]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'user_account'=>'賬號',
            'staff_name'=>'姓名',
            'staff_code'=>'工號',
            'organization_name'=>'部門',
        ];
    }
}
