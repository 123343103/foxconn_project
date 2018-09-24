<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/23
 * Time: 上午 11:00
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\IcInvh;

class IcInvhShow extends IcInvh
{
    public function fields(){
        $fields=parent::fields();
        $fields["invh_status"]=function(){
            $data=self::getStatus();
            return isset($data[$this->invh_status])?$data[$this->invh_status]:"/";
        };
        $fields["inout_type"]=function(){
            $data=self::getInOutType();
            return isset($data[$this->inout_type])?$data[$this->inout_type]:"/";
        };
        $fields["organization_id"]=function(){
            $data=self::getOrganization();
            return isset($data[$this->organization_id])?$data[$this->organization_id]:"/";
        };
        $fields["whs_id"]=function(){
            $data=self::getWareHouse();
            return isset($data[$this->whs_id])?$data[$this->whs_id]:"/";
        };
    }
}