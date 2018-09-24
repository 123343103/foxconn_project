<?php
/**
 * Created by PhpStorm.
 * User: F1678688
 * Date: 2017/6/20
 * Time: 上午 10:31
 */
namespace app\modules\crm\models\show;
use app\modules\common\models\BsDistrict;
use app\modules\common\models\BsPubdata;
use app\modules\crm\models\CrmCommunity;
use app\modules\crm\models\CrmCommunityChild;

class CrmCommunityShow extends CrmCommunity{
    public function fields()
    {
        $fields=parent::fields();
        $fields['cmt_id']=function(){
            $data=BsPubdata::findOne($this->cmt_id);
            return isset($data->bsp_svalue)?$data->bsp_svalue:"";
        };
        $fields["commu_plantype"]=function(){
            $data=self::getPlanTypes();
            return isset($data[$this->commu_plantype])?$data[$this->commu_plantype]:"";
        };
        $fields["commu_source"]=function(){
            $data=self::getPlanFroms();
            return isset($data[$this->commu_source])?$data[$this->commu_source]:"";
        };
        $fields["commu_status"]=function(){
            $data=self::getStatus();
            return isset($data[$this->commu_status])?$data[$this->commu_status]:"";
        };
        $fields["countData"]=function(){
            return CrmCommunityChild::find()->where(["commu_ID"=>$this->commu_ID])->orderBy("commu_iid desc")->one();
        };
        $fields["email_send_time"]=function(){
            return date("Y-m-d",strtotime($this->email_send_time));
        };
        $fields["cust_cmp_district"]=function(){
            if(!$this->cust_cmp_district){
                return "";
            }
            $data=BsDistrict::findBySql("select concat_ws('',a.district_name,b.district_name,c.district_name,d.district_name) addr from bs_district a right join bs_district b on a.district_id=b.district_pid right join bs_district c on b.district_id=c.district_pid right join bs_district d on c.district_id=d.district_pid where d.district_id=".$this->cust_cmp_district)->select('addr')->scalar();
            return $data?$data:"";
        };
        $fields["childs"]=function(){
            return CrmCommunityChild::find()->where(["commu_ID"=>$this->commu_ID])->orderBy("commu_iid desc")->all();
        };
        return $fields;
    }
}
?>