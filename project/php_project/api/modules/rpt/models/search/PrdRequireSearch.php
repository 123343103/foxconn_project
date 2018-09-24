<?php
namespace app\modules\rpt\models\search;
use app\modules\ptdt\models\PdRequirement;
use app\modules\rpt\models\show\PrdRequireShow;
use app\modules\rpt\models\show\TemplateShow;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * 商品需求报表
 */
class PrdRequireSearch extends PrdRequireShow
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
     * 报表内置模板列表
     */
    public function requireSearch($params)
    {
        $query = PrdRequireShow::find()->select(['if(pdq_source_type=100020,\'類型一\',if(pdq_source_type=100020,\'類型二\',\'類型三\')) AS  name','COUNT(commodity) AS aa',  'COUNT(pdq_id) AS shuliang']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $query->where('pdq_date>=:pdq_date_start',[':pdq_date_start'=>$params['pdq_date_start']])
        ->andWhere('pdq_date<=:pdq_date_end',[':pdq_date_end'=>$params['pdq_date_end']])
        ->GroupBy('pdq_source_type');
        return $dataProvider;
    }
}
