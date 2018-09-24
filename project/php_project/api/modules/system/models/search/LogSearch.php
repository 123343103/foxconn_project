<?php
namespace app\modules\system\models\search;
use app\modules\hr\models\HrStaff;
use app\modules\system\models\SystemLog;
use yii\data\ActiveDataProvider;
/**
 * 日志搜索模型
 *  F3858995
 *  2016/10/20
 */
class LogSearch extends SystemLog
{

    public $searchPara;
    public $startTime;
    public $endTime;

    public function rules()
    {
        return [
            ['searchPara','string',"max"=>20],
            [['startTime','endTime'],'safe']
        ];
    }
    public function search($params)
    {
        $query = SystemLog::find();


        if (!isset($params['sort']) || empty($params['sort'])){
            $query->orderBy("time desc");
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith('staff');
        $query->orFilterWhere(['like',self::tableName().".staff_code",$this->searchPara]);
        $query->orFilterWhere(['like',"user_name",$this->searchPara]);
        $query->orFilterWhere(['like',"description",$this->searchPara]);
        $query->orFilterWhere(['like',HrStaff::tableName().".staff_name",$this->searchPara]);
        if( $this->startTime && !$this->endTime){
            $query->andFilterWhere([">=","time",$this->startTime]);
        }
        if( $this->endTime && !$this->startTime){
            $query->andFilterWhere(["<=","time",date("Y-m-d H:i:s",strtotime($this->endTime.'+1 day'))]);
        }
        if( $this->endTime && $this->startTime){
            $query->andFilterWhere(["between","time",$this->startTime,date("Y-m-d H:i:s",strtotime($this->endTime.'+1 day'))]);
        }


        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'user_account'=>'賬號',
            'staff_name'=>'姓名',
            'staff_code'=>'工號',
            'organization_name'=>'部門',
            'startTime' =>"时间",
            "endTime"=>"~",
            "searchPara"=>"关键字",

        ];
    }
}