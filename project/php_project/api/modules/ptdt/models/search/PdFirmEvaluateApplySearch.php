<?php
namespace app\modules\ptdt\models\search;
use app\modules\common\models\BsCompany;
use app\modules\common\models\BsProduct;
use app\modules\ptdt\models\PdFirmReport;
use app\modules\ptdt\models\show\PdFirmEvaluateApplyShow;
use app\modules\ptdt\models\show\PdFirmReportShow;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirmEvaluateApply;
/**
 * 厂商评鉴申请搜索模型
 */
class PdFirmEvaluateApplySearch extends PdFirmEvaluateApply
{
    //列表页搜索属性
    public $firmName;
    public $startDate;
    public $endDate;
    public $firmPosition;
    public $evaluateApplyType;
    public $evaluateApplyStatus;
    //厂商搜索关键字
    public $searchKeyword;

    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['firmName','startDate','endDate','firmPosition','evaluateApplyType','evaluateApplyStatus','searchKeyword'],'safe'],
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
     * 搜索厂商评鉴申请
     */
    public function searchEvaluateApply($params)
    {
        $query = PdFirmEvaluateApplyShow::find()->where(['!=','apply_status',self::STATUS_DELETE])
            ->andWhere(['in','pd_firm_evaluate_apply.company_id',BsCompany::getIdsArr($params['companyId'])]);
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
            'pd_firm.firm_position' => $this->firmPosition,
            'pd_firm_evaluate_apply.apply_type' => $this->evaluateApplyType,
            'pd_firm_evaluate_apply.apply_status' => $this->evaluateApplyStatus,
        ]);
        $query->andFilterWhere(['like', "pd_firm.firm_sname", $this->firmName]);
        if ($this->startDate && !$this->endDate) {
            $query->andFilterWhere([">=", "pd_firm_evaluate_apply.create_at", $this->startDate]);
        }
        if ($this->endDate && !$this->startDate) {
            $query->andFilterWhere(["<=", "pd_firm_evaluate_apply.create_at", date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        if ($this->endDate && $this->startDate) {
            $query->andFilterWhere(["between", "pd_firm_evaluate_apply.create_at", $this->startDate, date("Y-m-d H:i:s", strtotime($this->endDate . '+1 day'))]);
        }
        return $dataProvider;
    }

    /**
     * 搜索呈报通过的厂商
     */
    public function searchFirmReport($params)
    {
        $query = PdFirmReportShow::find()->where(['report_status' => PdFirmReport::REPORT_COMPLETE]);
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
        $query->joinWith('firmMessage');
        $query->joinWith('firmType');
        $query->andFilterWhere(['like', 'firm_sname', $this->searchKeyword])
            ->orFilterWhere(['like', 'firm_shortname', $this->searchKeyword])
            ->orFilterWhere(['like', 'firm_compaddress', $this->searchKeyword])
            ->orFilterWhere(['like', 'bsp_svalue', $this->searchKeyword]);
        return $dataProvider;
    }

    /**
     * 搜索商品
     */
    public function searchProduct($params)
    {
        $query = BsProduct::find();
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
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
}
