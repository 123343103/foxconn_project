<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/9
 * Time: 上午 09:22
 */

namespace app\modules\warehouse\models\show;


use app\modules\warehouse\models\LqtExpress;

class LqtExpressShow extends LqtExpress
{
    public function fields()
    {
        $fields= parent::fields();

        //报价单id
        $fields['lqt_id']=function (){
            return $this->lqt_id;
        };
        //报价单号
        $fields['lqt_no']=function (){
          return $this->lqtno['lqt_no'];
        };
        //计费单位
        $fields['uom']=function (){
            return $this->uom;
        };
        //首重重量
        $fields['weightmin']=function (){
          return $this->weightmin;
        };
        //首重价格
        $fields['firstprice']=function (){
            return $this->firstprice;
        };
        //续重重量
        $fields['nextweight']=function (){
            return $this->nextweight;
        };
        //续重价格
        $fields['next_rate']=function (){
            return $this->next_rate;
        };
        //计费区间
        $fields['cost_range']=function (){
            return $this->min_value."-".$this->max_value;
        };
        //状态
        $fields['status']=function (){
            return $this->STATUS;
        };
        return $fields;
    }
}