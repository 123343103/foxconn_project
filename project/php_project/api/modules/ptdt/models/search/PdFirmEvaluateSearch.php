<?php
namespace app\modules\ptdt\models\search;
use app\modules\common\models\BsCompany;
use app\modules\ptdt\models\show\PdFirmEvaluateApplyShow;
use app\modules\ptdt\models\show\PdFirmEvaluateShow;
use app\modules\ptdt\models\show\PdFirmReportProductShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirmEvaluate;
/**
 * 厂商评鉴主表搜索模型
 */
class PdFirmEvaluateSearch extends PdFirmEvaluate
{
    //列表页属性
    public $firmName;
    public $groupSupplier;
    public $startDate;
    public $endDate;
    public $productType;
    public $firmType;
    public $evaluateStatus;
    //厂商和商品属性
    public $searchKeyword;

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['firmName','groupSupplier','startDate','endDate','productType','firmType','evaluateStatus','searchKeyword'],'safe'],
        ];
    }

    /**
     * 场景
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 列表页搜索
     */
    public function searchEvaluateMain($params)
    {
        $query = PdFirmEvaluateShow::find()->where(['!=','evaluate_status',self::EVALUATE_DELETE])
            ->andWhere(['in','pd_firm_evaluate.company_id',BsCompany::getIdsArr($params['companyId'])]);
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('firm');
        $query->andFilterWhere([
            'pd_firm.firm_issupplier' => $this->groupSupplier,
            'pd_firm.firm_type' => $this->firmType,
            'evaluate_status' => $this->evaluateStatus,
        ]);
        $query->andFilterWhere(['like', "pd_firm.firm_sname", $this->firmName])
            ->andFilterWhere(['like', "pd_firm.firm_category_id", $this->productType]);
        if ($this->startDate && !$this->endDate) {
            $query->andFilterWhere([">=", "pd_firm_evaluate.create_at", $this->startDate]);
        }
        if ($this->endDate && !$this->startDate) {
            $query->andFilterWhere(["<=", "pd_firm_evaluate.create_at", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        if ($this->endDate && $this->startDate) {
            $query->andFilterWhere(["between", "pd_firm_evaluate.create_at", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        return $dataProvider;
    }

    /**
     * 搜索评鉴申请通过厂商
     */
    public function searchEvaluateApply($params)
    {
        $evaluateModel = self::find()->where(['in', 'evaluate_status', [self::EVALUATE_ING,self::EVALUATE_PASS]])->all();
        $firmId = array();
        if (!empty($evaluateModel)) {
            foreach ($evaluateModel as $val) {
                $firmId[] = $val->firm_id;
            }
        }
        $query = PdFirmEvaluateApplyShow::find();
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
            $pageSize = 8;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->joinWith('firm');
        $query->joinWith('firmType');
        //根据字段模糊查询
        $query->orFilterWhere(['like', 'pd_firm.firm_sname', $this->searchKeyword])
            ->orFilterWhere(['like', 'pd_firm.firm_shortname', $this->searchKeyword])
            ->orFilterWhere(['like', 'pd_firm.firm_compaddress', $this->searchKeyword])
            ->orFilterWhere(['like', 'bs_pubdata.bsp_svalue', $this->searchKeyword])
            ->andFilterWhere(['not in','pd_firm_evaluate_apply.firm_id',$firmId]);
        return $dataProvider;
    }
}
