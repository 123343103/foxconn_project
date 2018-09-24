<?php
namespace app\modules\ptdt\models\search;
use app\classes\Trans;
use app\modules\hr\models\HrStaff;
use app\modules\ptdt\models\PdProductManager;
use app\modules\ptdt\models\show\PdProductManagerShow;
use yii\data\ActiveDataProvider;
/**
 * 日志搜索模型
 *  F3858995
 *  2016/10/20
 */
class PdProductManagerSearch extends PdProductManager
{
    public function fields(){
        $fields=parent::fields();
        $fields["staff_id"]=function(){
            return $this->staff->staff_id;
        };
        $fields["staff_name"]=function(){
            return $this->staff->staff_name;
        };
        return $fields;
    }
    public function rules()
    {
        return [
            ['searchPara','string',"max"=>20],
        ];
    }
    public function search($params)
    {
        $trans=new Trans();
        $query = PdProductManagerShow::find()->where([self::tableName().".pm_level"=>2]);
        if (!isset($params['sort']) || empty($params['sort'])){
            $query->orderBy("create_at desc");
        }
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
        if(isset($params['pm_name'])){
            $query->joinWith('staff');
            $query->andFilterWhere([
                "or",
                ['like',HrStaff::tableName().".staff_name",$params["pm_name"]],
                ['like',HrStaff::tableName().".staff_name",$trans->t2c($params["pm_name"])],
                ['like',HrStaff::tableName().".staff_name",$trans->c2t($params["pm_name"])]
            ]);
        };
        if(isset($params["category_id"])){
            $query->andFilterWhere(["like",self::tableName().'.category_id',$params['category_id']]);
        }
        if(isset($params['leader_name'])){
            $query->innerJoin(self::tableName()." p",self::tableName().".parent_id=p.pm_id");
            $query->leftJoin(HrStaff::tableName()." h2","h2.staff_code=p.staff_code");
            $query->andFilterWhere([
                "or",
                ['like',"h2.staff_name",$params["leader_name"]],
                ['like',"h2.staff_name",$trans->c2t($params["leader_name"])],
                ['like',"h2.staff_name",$trans->t2c($params["leader_name"])]
            ]);
        };
        return $dataProvider;
    }
}