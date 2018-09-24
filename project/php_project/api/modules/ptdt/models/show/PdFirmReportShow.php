<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2016/11/17
 * Time: 17:16
 */
namespace app\modules\ptdt\models\show;

use app\modules\ptdt\models\PdFirmReport;
use yii\helpers\Html;

class PdFirmReportShow extends PdFirmReport{
    public function fields(){
        $fields = parent::fields();
        $fields['firm_message']= function(){
            return $this->firmMessage;
        };

        $fields['firmName'] = function(){
            return $this->firmMessage['firm_sname'];
        };
        $fields['firm_shortname'] = function(){
            return $this->firmMessage['firm_shortname'];
        };
        $fields['firm_brand'] = function(){
            return $this->firmMessage['firm_brand'];
        };
        $fields['firm_source'] = function(){
            return $this->firmSource['bsp_svalue']?$this->firmSource['bsp_svalue']:'';
        };
        $fields['firm_type'] = function(){
            return $this->firmType['bsp_svalue']?$this->firmType['bsp_svalue']:'';
        };
        $fields['firmSupplier'] = function(){
            switch ($this->firmMessage['firm_issupplier']){
                case '1':
                    return "是";
                    break;
                case '0':
                    return "否";
                    break;
                default:
                    return "/";
            }
        };
        $fields['createBy'] = function(){
            return [
                "name" => $this->buildStaff['staff_name'],
                "code" => $this->buildStaff['staff_code'],
            ];
        };
        $fields['create_code'] = function(){
            return $this->buildStaff['staff_code'];
        };
        $fields['create_name'] = function(){
            return $this->buildStaff['staff_name'];
        };
        $fields['bsPubdata'] = function (){
          return [

                "agentsType" => $this->agentsType['bsp_svalue'],
                "developType" => $this->developType['bsp_svalue'],
                "urgencyDegree" => $this->urgencyDegree['bsp_svalue'],
//                "cooperateDegree" => $this->cooperateDegree['bsp_svalue'],
          ];
        };
        $fields['reportStatus'] = function(){
            return $this->status;
        };
        $fields['firmCategory'] = function() {
            return $this->firmMessage->categoryName;
        };
        $fields['firmCompared'] = function(){
            return $this->firmCompared;
        };
        return $fields;
    }
}