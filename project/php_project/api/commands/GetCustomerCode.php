<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/8/31
 * Time: 下午 02:09
 */

namespace app\commands;


use app\modules\crm\models\CrmCreditApply;
use app\modules\crm\models\CrmCustomerApply;
use app\modules\crm\models\CrmCustomerInfo;
use yii\data\ActiveDataProvider;

class GetCustomerCode
{
    public function getCustCode($threetoone, $custtaxcode, $currency)
    {
        $codeB = "000000000";//调整好的9位字符 使用九个零解决截取到的九位税藉编码都为零的情况
        $codeC = "";//最终生成的客户代码
        $curr = "";
        if ($currency == 'RMB' || $currency == 'rmb') {
            $curr = 0;
        } else {
            $curr = 1;
        }
        $codeleng = strlen($custtaxcode);
        if ($threetoone == 1) {
            if ($codeleng >= 17) {
                $codeA = substr($custtaxcode, 8, 9);//从第九位开始到17位获取9位数
                for ($i = 0; $i < strlen($codeA); $i++) {
                    if ($codeA[$i] != '0') //当不为0时执行
                    {
                        $codeS = substr($codeA, 0, $i);//将前面的0截取出来
                        $codeB = substr($codeA, $i);//从不为0的位置开始截取直到结尾
                        $codeB = $codeB . $codeS;//将截取的0加到字符串结尾
                        break;//退出循环
                    }
                }
            }
            $codeC = $codeB . $curr;//将截取的字符调整顺序之后加上币别代码

        } else {
            if ($codeleng >= 9) {
                $codeA = substr($custtaxcode, -9);//获取最后九位字符
                for ($i = 0; $i < strlen($codeA); $i++) {
                    if ($codeA[$i] != '0') //当不为0时执行
                    {
                        $codeS = substr($codeA, 0, $i);//将前面的0截取出来
                        $codeB = substr($codeA, $i);//从不为0的位置开始截取直到结尾
                        $codeB = $codeB . $codeS;//将截取的0加到字符串结尾
                        break;//退出循环
                    }
                }
            }
            $codeC = $codeB . $curr;//将截取的字符调整顺序之后加上币别代码
        }
//        $query = CrmCustomerApply::find()->select('applyno');
//        $dataProvider = new ActiveDataProvider([
//        'query' => $query]);
//        $query->andFilterWhere(['applyno' => $codeC]);
//        $isExist = $dataProvider->getModels();
        $isExist = CrmCustomerInfo::find()->select(['cust_code'])->where(['cust_code'=>$codeC])->all();
        if (count($isExist) >0) {
            $codeC = substr_replace($codeC, 'X', 8, 1);//如果有重复的客户代码，将第九位替换为X生成新的客户代码
        }
        return $codeC;
    }

}