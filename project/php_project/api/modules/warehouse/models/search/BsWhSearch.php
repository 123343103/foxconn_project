<?php
/**
 * Created by PhpStorm.
 * User: F1669561
 * Date: 2017/12/16
 * Time: 下午 01:40
 */
namespace app\modules\warehouse\models\search;
use app\classes\Trans;
use app\modules\warehouse\models\BsWh;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class BsWhSearch extends BsWh
{
    public $wh_name;
    public $wh_code;
    public $wh_lev;
    public $wh_attr;
    public $wh_yn;
    public $yn_deliv;
    public $wh_type;
    public $wh_state;
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function rules()
    {
        return [
            [['wh_code', 'wh_name', 'people','company','wh_state', 'wh_type', 'wh_lev', 'wh_yn', 'yn_deliv','wh_nature'], 'safe'],
            [['district_id','wh_id'], 'safe'],
            [['opp_date', 'nw_date'], 'safe'],
            [['wh_code', 'opper', 'nwer','company'], 'string', 'max' => 30],
            [['wh_name', 'wh_attr', 'remarks','people'], 'string', 'max' => 200],
            [['wh_addr', 'opp_ip'], 'string', 'max' => 20],
//            [['wh_type', 'wh_lev', 'wh_yn','wh_nature'], 'int', 'max' => 20],
        ];
    }
    //仓库主列表
    public function search($params)
    {
        $query = BsWh::find()->orderBy("opp_date desc");
        if (isset($params['rows'])) {
            $pageSize = $params['rows'];
        } else {
                $pageSize = 10;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $pageSize,
            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $trans=new Trans();
        $query->andFilterWhere(['like',BsWh::tableName().'.wh_code',$this->wh_code])
            ->andFilterWhere(['=',BsWh::tableName().'.wh_lev',$this->wh_lev])
            ->andFilterWhere(['=',BsWh::tableName().'.wh_attr',$this->wh_attr])
            ->andFilterWhere(['=',BsWh::tableName().'.wh_yn',$this->wh_yn])
            ->andFilterWhere(['=',BsWh::tableName().'.yn_deliv',$this->yn_deliv])
            ->andFilterWhere(['=',BsWh::tableName().'.wh_type',$this->wh_type])
            ->andFilterWhere(['=',BsWh::tableName().'.wh_state',$this->wh_state])
            ->andFilterWhere(['or',['like',BsWh::tableName().'.wh_name',$this->wh_name],
                ['like',BsWh::tableName().'.wh_name',$trans->t2c($this->wh_name)],
                ['like',BsWh::tableName().'.wh_name',$trans->c2t($this->wh_name)]]);

        return $dataProvider;
    }
}