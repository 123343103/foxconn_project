<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/1/4
 * Time: 9:45
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\SaleTripapply;

class SaleTripapplyShow extends SaleTripapply
{
    public $data_id;
    public function fields()
    {
        $fields = parent::fields();
        $fields['district1'] = function(){
            return [
                'id'=>$this->district->district_id,
                'name'=>$this->district->district_name,
            ];
        };
        $fields['district2'] = function(){
            return [
                'id'=>$this->district2->district_id,
                'name'=>$this->district2->district_name,
            ];
        };
        $fields['district3'] = function(){
            return [
                'id'=>$this->district3->district_id,
                'name'=>$this->district3->district_name,
            ];
        };
        $fields['district4'] = function(){
            return [
                'id'=>$this->district4->district_id,
                'name'=>$this->district4->district_name,
            ];
        };
        $fields['costType'] = function(){
            return $this->scost['scost_sname'];
        };

        return $fields;
    }
}