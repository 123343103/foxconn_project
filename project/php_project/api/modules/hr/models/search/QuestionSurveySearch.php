<?php

namespace app\modules\hr\models\Search;

use app\classes\Trans;
use app\modules\hr\models\BsQstInvst;
use app\modules\warehouse\models\show\BsQstInvstShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaff;
use app\modules\hr\models\show\HrStaffShow;

/**
 * StaffSearch represents the model behind the search form about `app\modules\hr\models\Staff`.
 */
class QuestionSurveySearch extends BsQstInvst
{
    /**
     * @inheritdoc
     */
    public $organization;
    public $opp_ip;
    public function rules()
    {
        return [
            [['invst_type', 'invst_dpt', 'invst_nums', 'clo_nums', 'crter', 'opper', 'yn_de', 'deler', 'yn_close', 'closer'], 'integer'],
            [['remarks', 'de_reason', 'clo_reason'], 'string'],
            [['invst_start', 'invst_end', 'crt_date', 'opp_date', 'de_date', 'clo_date'], 'safe'],
            [['invst_subj','invst_pt'], 'string', 'max' => 200],
            [['invst_state'], 'string', 'max' => 1],
            [['crt_ip', 'opp_ip', 'de_ip'], 'string', 'max' => 15],
            [['invst_path'], 'string', 'max' => 50],
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
        $query = BsQstInvstShow::find()->select('invst_id','invst_subj','invst_type','invst_dpt', 'invst_pt', 'invst_nums','opper');
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
//        $query->andFilterWhere(['like', 'invsy_id', $this->invst_id])
//            ->andFilterWhere([
//                'or',
//                ['like', 'hr_organization.organization_name', $this->organization_name],
//                ['like', 'hr_organization.organization_name', $trans->t2c($this->organization_name)],
//                ['like', 'hr_organization.organization_name', $trans->c2t($this->organization_name)]
//            ])
//            ->andFilterWhere(['or',
//                ['like', 'staff_name', $this->staff_name],
//                ['like', 'staff_name', $trans->t2c($this->staff_name)],
//                ['like', 'staff_name', $trans->c2t($this->staff_name)]
//            ])
//            ->andFilterWhere(['=', 'position', $this->position])
//            ->andFilterWhere(['=', 'staff_status', self::STAFF_STATUS_DEFAULT])
//        ;
        return $dataProvider;
    }

    public function export($params)
    {
        $query = BsQstInvstShow::find()->select('invst_id','invst_subj','invst_type','invst_dpt', 'invst_pt', 'invst_nums','opper');
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
        $query->andFilterWhere(['like', 'invst_id', $this->invst_id])
            ->andFilterWhere(['like', 'hr_organization.organization_name',$this->organization_name])
            ->andFilterWhere(['like', 'invst_subj', $this->invst_subj])
            ->andFilterWhere(['=', 'position', $this->position])
            ->andFilterWhere(['=', 'invst_type', $this->invst_type])
        ;
        return $dataProvider;
    }
}
