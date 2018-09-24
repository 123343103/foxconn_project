<?php
namespace app\modules\hr\models\search;
use app\classes\Trans;
use app\modules\hr\models\BsQstAnsw;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii;
class AnswSearch extends BsQstAnsw {
    public $title_name;

    public function rules()
    {
        return [
            [['staff_code','staff_name','answ_datetime','dpt_id','dpt_no','dpt_name','yn_log'], 'integer'],
            [['answ_datetime'], 'string'],
            [['answ_datetime'], 'safe'],
            [['answ_in'], 'string', 'max' => 1],
            [['staff_id', 'invst_id', 'answ_id','dpt_id'], 'string', 'max' => 15],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params){
        $query = BsQstAnsw::find()->select('answ_id,answ_datetime,staff_name,staff_code,');
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
                ['like', 'staff_name',$this->title_name],
                ['like', 'answ_datetime',$trans->t2c($this->title_name)],
                ['like', 'staff_code',$trans->c2t($this->title_name)]
            ]) /*like相似查询*/
        ;
        return $dataProvider;
    }
    public function export($params)
    {   $res2=[];
        $sql="SELECT t.cnt_id FROM erp.invst_content t WHERE t.invst_id=".$params['id']." ";
        $res=Yii::$app->db->createCommand($sql)->queryAll();
        $res=array_column($res,'cnt_id');
        //        $query = BsQstAnsw::find()->select('staff_code,staff_name,answ_datetime')->where('invst_id=60');
        $sql2="SELECT
                    t.answ_id,
                    t.staff_code,
                    t.staff_name,
                    t.answ_datetime
                    FROM
                        erp.bs_qst_answ t
                    WHERE
                        t.invst_id =".$params['id']."
                     ";
//        $res2=Yii::$app->db->createCommand($sql2)->queryAll();
//        $a = 'SELECT  * FROM erp.invst_content  WHERE invst_id ='.$params['id'];
        $dataProvider = new SqlDataProvider([
            'sql' => $sql2,
//            'pagination' => [
//                'pageSize' => false,
//            ]
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }
}