<?php
namespace app\modules\rpt\models\search;
use app\modules\rpt\models\show\TemplateParamShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 搜索报表模板
 */
class TemplateParamsSearch extends TemplateParamShow
{

    /**
     * 规则
     */
    public function rules()
    {
        return [
//            [['staff_code', 'staff_name', 'csarea_name'], 'safe'],
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
     * 报表模板
     */
    public function search($tpId)
    {
        $query = TemplateParamShow::find()->where(['rptt_id'=>$tpId]);

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
}
