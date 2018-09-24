<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/12/24
 * Time: 15:08
 */
namespace app\modules\crm\models\show;

use app\modules\crm\models\CrmCustOddsitem;

class CrmCustOddsitemShow extends CrmCustOddsitem
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['typeName'] = function(){
            return $this->productType['catg_name'];
        };

        return $fields;
    }
}

