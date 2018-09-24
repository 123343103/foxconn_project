<?php
/**
 * Created by PhpStorm.
 * User: F1675634
 * Date: 2016/10/10
 * Time: 上午 08:54
 */
namespace app\modules\hr\models\search;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\hr\models\HrStaffTitle;
class HrStaffTitleSearch extends HrStaffTitle{

    public function rules(){
        return [
            [['title_id'],'integer'],
            [['title_name','title_level','title_description','create_by','create_at','update_by','update_at'],'safe'],
        ];
    }
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params){
        $query = HrStaffTitle::find();
        $dataProvider = new ActiveDataProvider([
            'query'=>$query,
        ]);

        $this->load($params);
        if(!$this->validate()){
            return $dataProvider;
        }
        $query->andFilterWhere([
            'title_id' => $this->title_id,
            'create_at'=>$this->create_at,
            'update_at'=>$this->update_at,
        ]);
        $query->andFilterWhere(['like', 'title_name',$this->title_name])
            ->andFilterWhere(['like','title_description',$this->title_description])
            ->andFilterWhere(['like','create_by',$this->create_by])
            ->andFilterWhere(['like','update_by',$this->update_by])
            ->andFilterWhere(['like','title_level',$this->title_level]);
        return $dataProvider;
    }
}