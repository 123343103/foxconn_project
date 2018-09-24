<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/9
 * Time: 上午 09:24
 */

namespace app\modules\warehouse\models\show;


use app\modules\common\models\BsDistrict;
use app\modules\warehouse\models\LqtHead;

class LqtHeadShow extends LqtHead
{
    public function fields()
    {
        $fields = parent::fields();
        //报价单号
        $fields['lqt_no'] = function () {
            return $this->lqt_no;
        };
        //报价单id
        $fields['lqt_id'] = function () {
            return $this->lqt_id;
        };
        //物流公司代码
        $fields['logqt_cmp'] = function () {
            return $this->logqt_cmp;
        };
        //单据状态
        $fields['STATUS'] = function () {

               if( $this->STATUS=='0')
               {
                   return "无效";
               }
               else
               {
                return "有效";
               }
        };
        //起运地
        $fields['Frcity'] = function () {
            $dist=  BsDistrict::find()->select('district_name')->where(['district_id' => $this->FR_DIST])->one();
           $city=  BsDistrict::find()->select('district_name')->where(['district_id' => $this->FR_CITY])->one();
            return $dist['district_name']."-".$city['district_name'];
        };

        //目的地
        $fields['Tocity'] = function () {
            $dist=  BsDistrict::find()->select('district_name')->where(['district_id' => $this->TO_DIST])->one();
            $city= BsDistrict::find()->select('district_name')->where(['district_id' => $this->TO_CITY])->one();
            return $dist['district_name']."-".$city['district_name'];
        };
        //运输模式
        $fields['TRANSMODE'] = function () {
        if ($this->TRANSMODE == '5') {
            return "陆运";
        }
        else{
            return "快递";
        }
    };
    //运输模式id
        $fields['tans'] = function () {
            return $this->TRANSMODE;
        };
        //运输类型
        $fields['TRANSTYPE'] = function () {
            if ($this->TRANSTYPE == '201') {
                return "标准快递";
            } else if ($this->TRANSTYPE == '202') {
                return "经济快递";
            } else if ($this->TRANSTYPE == '203') {
                return "优速快递";
            } else if ($this->TRANSTYPE == '501') {
                return "空运普件";
            } else if ($this->TRANSTYPE == '502') {
                return "空运急件";
            } else {
                return "精品快线";
            }
        };
        //生效日期
        $fields['EFFECTDATE'] = function () {
            return $this->EFFECTDATE;
        };
        //截止日期
        $fields['EXPIREDATE'] = function () {
            return $this->EXPIREDATE;
        };
        return $fields;
    }
}