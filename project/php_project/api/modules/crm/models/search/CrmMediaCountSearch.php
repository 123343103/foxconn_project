<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/7/3
 * Time: 下午 05:20
 */
namespace app\modules\crm\models\search;

use app\modules\common\models\BsDistrict;
use app\modules\crm\models\CrmMediaCount;
use app\modules\crm\models\CrmMediaType;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
class CrmMediaCountSearch extends CrmMediaCount {
    public function fields(){
        $fields=parent::fields();
        $fields["cmt_type"]=function(){
            $data=CrmMediaType::find()->select(["cmt_type"])->indexBy("cmt_id")->column();
            return isset($data[$this->cmt_type])?$data[$this->cmt_type]:"";
        };
        $fields["medic_issupilse"]=function(){
            $data=self::isSupplier;
            return isset($data[$this->medic_issupilse])?$data[$this->medic_issupilse]:"";
        };
        $fields["medic_level"]=function(){
            $data=self::serviceLevel;
            return isset($data[$this->medic_level])?$data[$this->medic_level]:"";
        };
        $fields["district_info"]=function(){
            if(!$this->district_id){
                return "";
            }
            $data=BsDistrict::findBySql("select concat_ws('',a.district_name,b.district_name,c.district_name,d.district_name) addr from bs_district a right join bs_district b on a.district_id=b.district_pid right join bs_district c on b.district_id=c.district_pid right join bs_district d on c.district_id=d.district_pid where d.district_id=".$this->district_id)->select('addr')->scalar();
            return $data?$data:"";
        };
        return $fields;
    }
    public static function search($params=""){
        $query=self::find()->where(["cmt_status"=>1])->orderBy("medic_id desc");
        $dataProvider=new ActiveDataProvider([
            "query"=>$query,
            "pagination"=>[
                "pageSize"=>isset($params["rows"])?$params["rows"]:10
            ]
        ]);
        if(isset($params["medic_code"])){
            $query->andFilterWhere(["like","medic_code",$params["medic_code"]]);
        }
        if(isset($params["cmp_name"])){
            $query->andFilterWhere(["like","medic_compname",$params["cmp_name"]]);
        }
        if(isset($params["service_level"])){
            $query->andFilterWhere(["medic_level"=>$params["service_level"]]);
        }
        if(isset($params["media_type"])){
            $query->andFilterWhere(["cmt_type"=>$params["media_type"]]);
        }
        if(isset($params["is_supplier"])){
            $query->andFilterWhere(["medic_issupilse"=>$params["is_supplier"]]);
        }
        if(isset($params["start_time"])){
            $query->andFilterWhere([">","create_at",$params["start_time"]]);
        }
        if(isset($params["end_time"])){
            $query->andFilterWhere(["<","create_at",$params["end_time"]]);
        }
        return $dataProvider;
    }
}