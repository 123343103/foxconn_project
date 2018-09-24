<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/23
 * Time: 11:10
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdFirmReportCompared;

class PdFirmReportComparedShow extends PdFirmReportCompared{
    public $analysis;
    public $firm;

    public function fields(){
        $fields = parent::fields();

        $fields['firm'] = function(){
            return [
                "name" => $this->firmMessage['firm_sname'],
                "shortname" => $this->firmMessage['firm_shortname'],
                "brand"=>$this->firmMessage['firm_brand'],
                "issupplier"=>$this->firmMessage['firm_issupplier'],
                "ename" => $this->firmMessage['firm_ename'],
                "compaddress" => $this->firmMessage['firm_compaddress'],
            ];
        };
    }
}