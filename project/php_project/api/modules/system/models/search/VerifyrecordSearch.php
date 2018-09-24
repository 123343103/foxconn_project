<?php

namespace app\modules\system\models\search;

use app\modules\system\models\AuthAssignment;
use app\modules\system\models\VerifyrecordChild;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\system\models\Verifyrecord;
use app\modules\system\models\show\VerifyrecordShow;

/**
 * VerifyrecordSearch represents the model behind the search form about `app\modules\system\models\Verifyrecord`.
 */
class VerifyrecordSearch extends Verifyrecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vco_id', 'ver_id', 'bus_code','but_code', 'vco_busid', 'vco_send_acc', 'vco_status'], 'integer'],
            [['vco_senddate', 'vco_send_dept'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$id,$isSupper)
    {
        $res = AuthAssignment::find()->select('item_name')->where(['user_id'=>$id])->all();
        $resArr = [];
        foreach ($res as $k => $v) {
            $resArr[] = $v['item_name'];
        }
        if ($isSupper) {
            $query = VerifyrecordShow::find()->joinWith('verifyChild')->where(['ver_acc_id'=>$id])->andWhere(['vco_status'=>self::STATUS_DEFAULT,'vcoc_status'=>VerifyrecordChild::STATUS_CHECKIND]);
        } else {
            $query = VerifyrecordShow::find()->joinWith('verifyChild')->where(['ver_acc_id'=>$id])->orWhere(['in','ver_acc_rule',$resArr])->orWhere(['acc_code_agent'=>$id])->orWhere(['in','rule_code_agent',$resArr])->andWhere(['vco_status'=>self::STATUS_DEFAULT,'vcoc_status'=>VerifyrecordChild::STATUS_CHECKIND]);
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

        $this->load($params);
        if (!\Yii::$app->request->get('sort')) {
            $query->orderBy("vco_senddate desc");
        }
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'but_code' => $this->but_code,

        ]);
//        $commandQuery = clone $query;
//        echo $commandQuery->createCommand()->getRawSql();exit;
        return $dataProvider;

    }
    public function searchRecord($params,$code)
    {
        $query = VerifyrecordShow::find()->where(['vco_code'=>$code]);

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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        return $dataProvider;
    }
}
