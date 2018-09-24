<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/19
 * Time: 上午 08:35
 */
namespace app\modules\crm\models\search;

use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCarrier;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\crm\models\CrmCommunity;
class CrmCommunitySearch extends CrmCommunity{
    public function fields()
    {
        $fields=parent::fields();
        $fields["commu_type"]=function(){
            $data=BsPubdata::findOne($this->commu_type);
            return isset($data->bsp_svalue)?$data->bsp_svalue:"";
        };
        $fields["cmt_id"]=function(){
            $data=BsPubdata::findOne($this->cmt_id);
            return isset($data->bsp_svalue)?$data->bsp_svalue:"";
        };
        $fields["commu_plantype"]=function(){
            switch ($this->commu_type){
                case 100855:
                    $data= self::getPlanTypes();
                    return isset($data[$this->commu_plantype])?$data[$this->commu_plantype]:"";
                    break;
                case 100856:
                    $data = self::getActType();
                    return isset($data[$this->act_type])?$data[$this->act_type]:"";
                        break;
                default:
                    return "/";
                    break;
            }
        };
        $fields["commu_status"]=function(){
            $status=self::getStatus();
            return isset($status[$this->commu_status])?$status[$this->commu_status]:"";
        };
        $fields["act_type"]=function(){
            $data=self::getActType();
            return  isset($data[$this->act_type])?$data[$this->act_type]:"";
        };
        $fields["commu_postime"]=function(){
            switch($this->commu_type){
                case 100856:
                    return $this->act_start_time."~".$this->act_end_time;
                    break;
                case 100858:
                    return $this->email_send_time;
                default:
                    return $this->commu_postime;
                    break;
            }
        };
        return $fields;
    }

    public static function search($params=""){
        $query=self::find()->orderBy("commu_ID desc");
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);
        if(isset($params["commu_type"])){
            $query->andFilterWhere(["commu_type"=>$params["commu_type"]]);
        }
        if(isset($params["cmt_id"])){
            $query->andFilterWhere(["cmt_id"=>$params["cmt_id"]]);
        }
        if(isset($params["cmt_intor"])){
            $query->andFilterWhere(["cmt_intor"=>$params["cmt_intor"]]);
        }
        if(isset($params["start_time"])){
            $query->andFilterWhere([
                "or",
                [">=","commu_postime",$params["start_time"]],
                [">=","act_start_time",$params["start_time"]],
                [">=","email_send_time",$params["start_time"]]
            ]);
        }
        if(isset($params["end_time"])){
            $query->andFilterWhere([
                "or",
                ["<=","commu_postime",$params["end_time"]],
                ["<=","act_end_time",$params["end_time"]],
                ["<=","email_send_time",$params["end_time"]],
            ]);
        }
        return $dataProvider;
    }

}
?>