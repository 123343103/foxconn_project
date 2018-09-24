<?php
namespace app\modules\rpt\models\search;
use app\modules\rpt\models\show\TemplateShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 搜索报表模板
 */
class TemplateSearch extends TemplateShow
{
    public $rptt_code;
    public $rptt_name;
    /**
     * 规则
     */
    public function rules()
    {
        return [
            [['rptt_code', 'rptt_name', 'rptt_title'], 'safe'],
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
     * 报表内置模板列表
     */
    public function search()
    {
        $query = TemplateShow::find()->where(['rptt_type'=>10]);

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
        ]);

        return $dataProvider;
    }

    // 搜索用户/角色分配到的模板
    public function searchAssigned($params)
    {
        $query = TemplateShow::find()->where(['in', 'rptt_id', $params['idArr']]);
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
        ]);
        $this->load($params);
        $query->andFilterWhere(['like', "rptt_code", $this->rptt_code])
            ->andFilterWhere(['or', ['like', "rptt_name", $this->rptt_name],['like', 'rptt_title', $this->rptt_name]]);
        return $dataProvider;
    }
}
