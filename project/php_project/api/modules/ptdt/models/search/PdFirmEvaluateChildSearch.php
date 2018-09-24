<?php
namespace app\modules\ptdt\models\search;
use app\modules\ptdt\models\show\PdFirmEvaluateChildShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ptdt\models\PdFirmEvaluateChild;
/**
 * 厂商评鉴子表搜索模型
 */
class PdFirmEvaluateChildSearch extends PdFirmEvaluateChild
{
    /**
     * 验证规则
     */
    public function rules()
    {
        return [
            [['evaluate_child_id', 'evaluate_child_status', 'evaluate_id', 'report_id', 'create_by', 'update_by'], 'integer'],
            [['report_child_id', 'evaluate_child_code', 'evaluate_date', 'evaluate_reason', 'passage_server_score', 'passage_server_decide', 'price_delivery_score', 'price_delivery_decide', 'operate_finance_score', 'operate_finance_decide', 'manage_innovate_score', 'manage_innovate_decide', 'evaluate_synthesis_score', 'evaluate_level', 'create_at', 'update_at'], 'safe'],
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
     * 搜索
     */
    public function searchEvaluateChild($params)
    {
        $query = PdFirmEvaluateChildShow::find()->where(['and',['evaluate_id'=>$params['id']],['!=','evaluate_child_status',PdFirmEvaluateChild::STATUS_DELETE]]);
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
        return $dataProvider;
    }
}
