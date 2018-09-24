<?php
/**
 * Created by PhpStorm.
 * User: F1678086
 * Date: 2017/2/28
 * Time: 16:53
 */
/**
 * @return array
 * 邮件发送
 */
namespace app\modules\common\tools;
class SendMail
{
    public function sendmail($select,$canSelect,$subject,$content)
    {

        $arr1 = explode(',',$select);
        foreach ($arr1 as $key => $val) {
            $results1[] = $val;
//            if($key != count($arr1)-1){
//                $results1[] = $val;
//            }
        }
        if (!empty($canSelect)) {
            $arr2 = explode(',', $canSelect);
            foreach ($arr2 as $key => $val) {
                $results2[] = $val;
//                if($key != count($arr2)-1){
//                    $results2[] = $val;
//                }
            }
            $array = array_merge($results1, $results2);
        } else {
            $array = array_merge($results1);
        }
        /*应用接口*/
        $client = new \SoapClient('http://imes.foxconn.com/mailintoface.asmx?WSDL');
        /*发送邮件*/
        $result = $client->send(array(
            'from' => 'service@foxconnmall.com',
            'toLst' => $array,
//            'ccLst'=>array('hzlh-ec-erp@mail.foxconn.com'),
//            'bccLst'=>array('hzlh-ec-erp@mail.foxconn.com'),
            'subject' => $subject,
            'body' => $content,
            'MessageType' => '邮件',
            'isHtml' => 'true',
            'strEncoding' => 'utf-8',
        ));
        return $result;
//        return $array;
    }
}
