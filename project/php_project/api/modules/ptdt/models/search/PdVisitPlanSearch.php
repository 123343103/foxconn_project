<?php

namespace app\modules\ptdt\models\Search;

use app\classes\Trans;
use app\modules\ptdt\models\show\PdVisitPlanShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdVisitPlan;
use app\modules\ptdt\models\PdFirm;
use app\modules\ptdt\models\show\PdFirmShow;
use app\modules\common\models\BsCompany;
/**
 * VisitPlanSearch represents the model behind the search form about `app\modules\ptdt\models\VisitPlan`.
 */
class PdVisitPlanSearch extends PdVisitPlan
{
    public $firmMessage;
    public $startDate;
    public $endDate;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bus_code', 'pvp_type', 'firm_id', 'pvp_staff_id', 'pvp_verifyter'], 'integer'],
            [['plan_date', 'pvp_senddate', 'pvp_verifydate','plan_status','create_by','create_at','startDate','endDate'], 'safe'],
            [['firmMessage', 'plan_time', 'pvp_contact_man'], 'string', 'max' => 20],
            [['plan_place', 'purpose_write','pvp_plancode'], 'string', 'max' => 100],
            [['pvp_contact_position', 'purpose'], 'string', 'max' => 10],
            [['pvp_contact_tel', 'pvp_contact_mobile'], 'string', 'max' => 14],
            [['note'], 'string', 'max' => 200],

        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$companyId)
    {
        $trans = new Trans();
        $query = PdVisitPlanShow::find()->where(['!=','pvp_status',self::STATUS_DELETE])
            ->andWhere(['in','company_id',BsCompany::getIdsArr($companyId)]);

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

        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);

        if (!\Yii::$app->request->get('sort')){
            $query->orderBy("create_at desc");
        }

        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('creatorStaff');
        $query->andFilterWhere([     //根据字段搜索
            'pvp_type' => $this->pvp_type,
            'plan_status' => $this->plan_status,
        ]);
            //根据字段模糊查询
        $query->andFilterWhere(['or',['like', 'pvp_plancode', $trans->c2t(trim($this->pvp_plancode))],['like', 'pvp_plancode', $trans->t2c(trim($this->pvp_plancode))]])
            ->andFilterWhere(['or',['like', 'plan_date', $trans->c2t(trim($this->plan_date))],['like', 'plan_date', $trans->t2c(trim($this->plan_date))]])
            ->andFilterWhere(['or',['like', 'staff_name', $trans->c2t(trim($this->create_by))],['like', 'staff_name', $trans->t2c(trim($this->create_by))]]);
        if ($this->startDate && !$this->endDate) {
        $query->andFilterWhere([">=", "create_at", $this->startDate]);
        }
        if ($this->endDate && !$this->startDate) {
            $query->andFilterWhere(["<=", "create_at", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        if ($this->endDate && $this->startDate) {
            $query->andFilterWhere(["between", "create_at", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        return $dataProvider;
    }

    public function searchQuery($params)   //搜索方法
    {
        $trans = new Trans();
        $query = PdFirmShow::find();
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

        // 从参数的数据中加载过滤条件，并验证
        $this->load($params);
        if (!\Yii::$app->request->get('sort')){
            $query->orderBy("firm_id desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('firmType');

        //根据字段模糊查询
        $query->andFilterWhere(['or',
            ['like', 'firm_sname', $trans->t2c(trim($this->firmMessage))],
            ['like', 'firm_sname', $trans->c2t(trim($this->firmMessage))],
            ['like', 'firm_shortname', $trans->t2c(trim($this->firmMessage))],
            ['like', 'firm_shortname', $trans->c2t(trim($this->firmMessage))],
            ['like', 'firm_compaddress', $trans->t2c(trim($this->firmMessage))],
            ['like', 'firm_compaddress', $trans->c2t(trim($this->firmMessage))],
            ['=', 'bsp_svalue', trim($this->firmMessage)],
            ])
        ;
        return $dataProvider;
    }
}
