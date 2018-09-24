<?php

namespace app\modules\system\models\search;

use app\classes\Trans;
use app\modules\common\models\BsReviewRuleChild;
use app\modules\system\models\show\UserShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
use yii\db\Query;

/**
 * SearchUser represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public $staff_name;
    public $staff_code;
    public $company_name;
    public $searchKeyword;

    public function rules()
    {
        return [
            [['staff_name','staff_code','company_name','searchKeyword'], 'safe'],
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
        $query = UserShow::find();

        // add conditions that should always apply here

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->joinWith('staff');
        $query->joinWith('company');
        $query->andFilterWhere([
            'staff_id' => $this->staff_id,
            'user_status' => $this->user_status,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ]);

        $trans = new Trans();
        $query->andFilterWhere(['like', 'user_account', $this->user_account])
            ->andFilterWhere([
                'or',
                ['like','hr_staff.staff_name', $this->staff_code],
                ['like','hr_staff.staff_name', $trans->c2t($this->staff_code)],
                ['like','hr_staff.staff_name', $trans->t2c($this->staff_code)]
            ])
            ->orFilterWhere([
                'like','hr_staff.staff_code', $this->staff_code,
            ])
            ->andFilterWhere([
                'or',
                ['like','bs_company.company_name', $this->company_name],
                ['like','bs_company.company_name', $trans->t2c($this->company_name)],
                ['like','bs_company.company_name', $trans->c2t($this->company_name)]
            ])
            ->andFilterWhere(['>=', 'user_status', self::STATUS_ACTIVE]);
        return $dataProvider;
    }
    public function searchs($params)
    {
        $query = UserShow::find();

        // add conditions that should always apply here

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->joinWith('staff');
        $query->joinWith('company');
//        $query->andFilterWhere([
//            'staff_id' => $this->staff_id,
//            'user_status' => $this->user_status,
//            'start_at' => $this->start_at,
//            'end_at' => $this->end_at,
//        ]);

        if($this->user_type!="1")
        {
            $query->andFilterWhere([
                'user_type'=>$this->user_type
            ]);
        }
        if($this->user_status!="1")
        {
            $query->andFilterWhere([
                'user_status'=>$this->user_status
            ]);
        }
        $trans = new Trans();
        $query->andFilterWhere(['like', 'user_account', $this->user_account])
            ->andFilterWhere([
                'or',
                ['like','hr_staff.staff_name', $this->staff_code],
                ['like','hr_staff.staff_name', $trans->c2t($this->staff_code)],
                ['like','hr_staff.staff_name', $trans->t2c($this->staff_code)]
            ])
            ->orFilterWhere([
                'like','hr_staff.staff_code', $this->staff_code,
            ]);
//            ->andFilterWhere(['>=', 'user_status', self::STATUS_ACTIVE]);
        $query->orderBy("create_at desc");
        return $dataProvider;
    }

    // 模糊查询
    public function searchLike($params)
    {
        $query = UserShow::find();
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =5;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->joinWith('staff');
        $query->Where(['>=', 'user_status', self::STATUS_ACTIVE]);
        if (!empty($this->user_account)) {
            $query->andFilterWhere(['or',['like', 'user_account', $this->user_account],['like','hr_staff.staff_name', $this->user_account],['like','hr_staff.staff_code', $this->user_account]]);
        }
        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'user_account'=>'賬號',
            'staff_name'=>'姓名',
            'staff_code'=>'工號',
            'company_name'=>'部門',
        ];
    }

    public function selectUser($params)
    {
        $query = (new Query())
            ->select(['user_id', 'user_account', 'hs.staff_code', 'hs.staff_name', 'ho.organization_name'])
            ->from(['us'=>User::tableName()])
            ->leftJoin('hr_staff hs', 'us.staff_id=hs.staff_id')
            ->leftJoin('hr_organization ho', 'hs.organization_code=ho.organization_code')
            ->where(['us.user_status'=>User::STATUS_ACTIVE]);

        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
//        return $params;
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans = new Trans();
        $query->andFilterWhere([
            'or',
            ['like', 'user_account', $this->searchKeyword],
            ['like', 'user_account', $trans->c2t($this->searchKeyword)],
            ['like', 'user_account', $trans->t2c($this->searchKeyword)],
            ['like', 'hs.staff_code', $this->searchKeyword],
            ['like', 'hs.staff_code', $trans->c2t($this->searchKeyword)],
            ['like', 'hs.staff_code', $trans->t2c($this->searchKeyword)],
            ['like', 'hs.staff_name', $this->searchKeyword],
            ['like', 'hs.staff_name', $trans->c2t($this->searchKeyword)],
            ['like', 'hs.staff_name', $trans->t2c($this->searchKeyword)],
            ['like', 'ho.organization_name', $this->searchKeyword],
            ['like', 'ho.organization_name', $trans->c2t($this->searchKeyword)],
            ['like', 'ho.organization_name', $trans->t2c($this->searchKeyword)],
        ]);

//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }

    // 获取审核规则数据
    public function selectReviewData($params)
    {
        $query = (new Query())
            ->select('rule_child_id,review_rule_id,rule_child_index,review_user_id,review_role_id,agent_one_id,agent_two_id,hs1.staff_name u_name,hs2.staff_name agent1_name,hs3.staff_name agent2_name')
            ->from(['rrc'=>BsReviewRuleChild::tableName()])
            ->leftJoin('user us1', 'rrc.review_user_id=us1.user_id')
            ->leftJoin('user us2', 'rrc.agent_one_id=us2.user_id')
            ->leftJoin('user us3', 'rrc.agent_two_id=us3.user_id')
            ->leftJoin('hr_staff hs1', 'us1.staff_id=hs1.staff_id')
            ->leftJoin('hr_staff hs2', 'us2.staff_id=hs2.staff_id')
            ->leftJoin('hr_staff hs3', 'us3.staff_id=hs3.staff_id')
            ->where(['review_rule_id'=>$params]);

//        if(isset($params['rows'])){
//            $pageSize = $params['rows'];
//        }else{
//            $pageSize =10;
//        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'pagination' => [
//                'pageSize' => $pageSize,
//            ]
        ]);
//        return $params;
//        $this->load($params);
//        if (!$this->validate()) {
//            return $dataProvider;
//        }
//        $trans = new Trans();
//        $query->andFilterWhere([
//            'or',
//            ['like', 'user_account', $this->searchKeyword],
//            ['like', 'user_account', $trans->c2t($this->searchKeyword)],
//            ['like', 'user_account', $trans->t2c($this->searchKeyword)],
//            ['like', 'hs.staff_code', $this->searchKeyword],
//            ['like', 'hs.staff_code', $trans->c2t($this->searchKeyword)],
//            ['like', 'hs.staff_code', $trans->t2c($this->searchKeyword)],
//            ['like', 'hs.staff_name', $this->searchKeyword],
//            ['like', 'hs.staff_name', $trans->c2t($this->searchKeyword)],
//            ['like', 'hs.staff_name', $trans->t2c($this->searchKeyword)],
//            ['like', 'ho.organization_name', $this->searchKeyword],
//            ['like', 'ho.organization_name', $trans->c2t($this->searchKeyword)],
//            ['like', 'ho.organization_name', $trans->t2c($this->searchKeyword)],
//        ]);

//        return $query->createCommand()->getRawSql();
        return $dataProvider;
    }
}
