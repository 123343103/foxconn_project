<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/5/31
 * Time: 15:58
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmStoresinfo;

class CrmStoresinfoShow extends CrmStoresinfo
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['sz'] = function(){
            return $this->sz['staff_name'];
        };

        $fields['dz'] = function(){
            return $this->dz['staff_name'];
        };
        return $fields;
    }
}