<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 上午 08:54
 */
namespace app\modules\hr\models\search;

use app\classes\Trans;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaffTitle;
class HrStaffTitleSearch extends HrStaffTitle{
    public $title_name;

    public function rules(){
        return [
            [['title_id'],'integer'],
            [['title_name','title_level','title_code','title_description','create_by','create_at','update_by','update_at'],'safe'],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = HrStaffTitle::find()->select('title_id,title_name,title_code,title_description,');
        if(isset($params['rows'])){
            $pageSize = $params['rows'];
        }else{
            $pageSize =10;
        }
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);

        $this->load($params);
        if(!$this->validate()){
            return $dataProvider;
        }
        $trans = new Trans();
        $query->andFilterWhere(['like', 'title_code', $this->title_code])
            ->andFilterWhere([
                'or',
                ['like', 'title_name',$this->title_name],
                ['like', 'title_name',$trans->t2c($this->title_name)],
                ['like', 'title_name',$trans->c2t($this->title_name)]
            ]) /*like相似查询*/
        ;
        return $dataProvider;
    }
}