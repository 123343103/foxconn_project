<?php
namespace app\modules\rpt\models\search;
use app\modules\rpt\models\show\RptTreeShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 搜索报表树
 */
class RptTreeSearch extends RptTreeShow
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
     * 模糊查询报表列表
     */
    public function search()
    {
        $query = RptTreeShow::find();
//        $query->joinWith("template");
//        $query->where(['rptt_type'=>10]);
        $query->orderBy('rptc_sort DESC');

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

    public function getCatsTree($arr)
    {
        foreach ($arr as $k=>$v) {

        }
    }
}
