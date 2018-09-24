<?php
/**
 * Created by PhpStorm.
 * User: F3860965
 * Date: 2017/6/7
 * Time: 上午 09:45
 */

namespace app\commands;

use Codeception\Test\Feature\ErrorLogger;
use Yii;
use  app\classes\Trans;
use app\models\BsBankInfo;
use yii\data\ActiveDataProvider;
use yii\test\InitDbFixture;


class BankInfo
{
    //工商银行
    public function AddICBKBankInfo($date)
    {
        $transaction = Yii::$app->oms->beginTransaction();
        try {
            $bank=0;
            //dumpE("fdsafsa");
            $list['BnkCode'] = "ICBK";
            $list['Account'] = "4000026619202686718";
            $list['CurCde'] = "RMB";
            if ($date == null || $date == "") {
                $list['Date'] = date("Y-m-d");//当天的日期
            } else {
                $list['Date'] = $date;
            }
            $list['Reserve'] = null;
            $key = "98256725";
            $clients = new \SoapClient('http://10.151.18.208:8000/UpBankInfoService/UpBankInfoService.svc?wsdl');//连接wcf服务调用加密解密方法
            $JosnStr = json_encode($list);//将数组转化为josn格式的数据
            $params = array('encryptString' => $JosnStr, 'sKey' => $key);//将参数封装成为数组
            $result = $clients->ToDESEncrypt($params);//调用.net wcf中加密方法
            $pjosn = $result->ToDESEncryptResult;//将stdClass对象的类转换为字符串
            $client = new \SoapClient('http://10.191.43.16/B2BSTMT_SHELL.asmx?WSDL');//连接webservice服务调用抓取银行流水的方法
            $userID = "TZWTEST";//用户账户
            $param = array('userID' => $userID, 'pJson' => $pjosn);//将参数封装成为数组
            $pJsonDisplay = $client->__Call("QSODOA", array($param));//调用.net webservice中的方法，参数必须是二维数组
            $qsodoaResult = $pJsonDisplay->QSODOAResult;//获取结果字符串
            $DetialList = explode("$$$$$$", $qsodoaResult);//将字符串分割成数组（分隔符为$$$$$$）
            $Detialcount = count($DetialList);
            if ($Detialcount > 1) {
                $Display = $DetialList[1];
                $paramRtn = array('decryptString' => $Display, 'sKey' => $key);//将参数封装成为数组
                $pJsonRtn = $clients->ToDESDecrypt($paramRtn);//调用.net wcf中解密方法
                $DecryptResult = $pJsonRtn->ToDESDecryptResult;//获取结果数据字符串
                $DecryptArray = json_decode($DecryptResult, true);//反序列化字符串为数组
                $tr = new Trans();
                foreach ($DecryptArray as $decry) {
                    $Bankinfo = new BsBankInfo();
                    $DrcrfCn = $tr->t2c($decry['Drcrf_CN']);//借贷标示//繁体转换为简体
                    $Summery = $this->Sbc2Dbc($decry['Summary']);//全角转半角
                    $Summery = preg_replace('/([\x80-\xff]*:())/i', '', $Summery);//过滤掉中文冒号括号
                    $blsu = preg_match_all('/([\x80-\xff]*)/i', '', $Summery);//
                    $PostScript = $this->Sbc2Dbc($decry['PostScript']);//全角转半角
                    $PostScript = preg_replace('/([\x80-\xff]*:())/i', '', $PostScript);//过滤掉中文冒号括号
                    $blop = preg_match_all('/([\x80-\xff]*)/i', '', $PostScript);//
                    $Reserved4 = $decry['RepReserved4'];
                    //判断摘要获取总金额
//                    if (($Summery != null || $Summery != "") && $blsu == false && count($Summery) >= 10) {
//                        $SM = explode(",", str_replace(array(";"), ',', $Summery));//如果分隔符为分号先转换为逗号再进行分割
//                        //如果为合并付款的获取多个订单号的金额并相加
//                        if (count($SM) > 1) {
//                            for ($m = 0; $m < count($SM); $m++) {
//                                $Summmery = $SM[$m];
//                            }
//                        } //单笔订单付款获取单个订单号的金额
//                        else {
//                            if (count($SM) < 1) {
//                                $Summmery = "";
//                            } else {
//                                $Summmery = $SM[0];
//                            }
//                        }
//                    }
//                    //判断附言获取总金额
//                    if (($PostScript != null || $PostScript != "") && $blop == false && count($PostScript) >= 10) {
//                        $PS = explode(",", str_replace(array(";"), ',', $PostScript));//如果分隔符为分号先转换为逗号再进行分割
//                        //如果为合并付款的获取多个订单号的金额并相加
//                        if (count($PS) > 1) {
//                            for ($p = 0; $p < count($PS); $p++) {
//                                $Post = $PS[$p];
//                            }
//                        } //单笔订单付款获取单个订单号的金额
//                        else {
//                            if (count($PS) < 1) {
//                                $Post = "";
//                            } else {
//                                $Post = $PS[0];
//                            }
//                        }
//                    }
                    //根据流水号查询该流水号在表中是否存在
                    $id = $this->getIsExist($Reserved4);
                    if ($DrcrfCn == "贷" && count($id) == 0) //借贷标示为贷，数据表中没有此笔数据才执行以下语句
                    {
                        $Bankinfo->SYS_CDE = $decry['SYS_CDE'];
                        $Bankinfo->FIN_CDE = $decry['FIN_CDE'];
                        $Bankinfo->FIN_SUBAREA_CDE = $tr->t2c($decry['FIN_SUBAREA_CDE']);
                        $Bankinfo->BNK_CDE = $decry['BNK_CDE'];
                        $Bankinfo->SYS_CN_DESC = $tr->t2c($decry['SYS_CN_DESC']);
                        $Bankinfo->FIN_NME = $tr->t2c($decry['FIN_NME']);
                        $Bankinfo->FIN_SUBAREA = $tr->t2c($decry['FIN_SUBAREA']);
                        $Bankinfo->CORP_DESC = $tr->t2c($decry['CORP_DESC']);
                        $Bankinfo->BNK_NME = $tr->t2c($decry['BNK_NME']);
                        $Bankinfo->BRANCH_NME = $tr->t2c($decry['BRANCH_NME']);
                        $Bankinfo->ACCOUNTS = $decry['ACCOUNT'];
                        $Bankinfo->CUR_CDE = $decry['CUR_CDE'];
                        $Bankinfo->TXNAMT = $decry['CreditAmount'];
                        $Bankinfo->TRDATE = $decry['Date'];
                        $Bankinfo->TRANSID = $decry['RepReserved4'];
                        $Bankinfo->OPPACCNO = $decry['RecipAccNo'];
                        $Bankinfo->OPPNAME = $tr->t2c($decry['RecipName']);
                        $Bankinfo->INTERINFO = $tr->t2c($this->Sbc2Dbc($decry['Summary']));
                        $Bankinfo->POSTSCRIPT = $tr->t2c($this->Sbc2Dbc($decry['PostScript']));
                        $Bankinfo->IN_DATE = date("Y-m-d H:i:s");
                        if (!$Bankinfo->save()) {
                            throw new \Exception("发生错误");
                        }
                        $bank=1;
                    }
                }
            }
            $transaction->commit();
            if($bank==1) {
                return "1";
            }
            else{
                return "0";
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }

    }

    //农业银行
    public function AddABOCBankInfo($date)
    {
        $transaction = Yii::$app->oms->beginTransaction();
        try {
            $bank=0;
            $list['BnkCode'] = "ABOC";
            $list['Account'] = "41028900040053117";
            $list['CurCde'] = "RMB";
            if ($date == null || $date == "") {
                $list['Date'] = date("Y-m-d");//当天的日期
            } else {
                $list['Date'] = $date;
            }
            $list['Reserve'] = null;
            $key = "98256725";
            $clients = new \SoapClient('http://10.151.18.208:8000/UpBankInfoService/UpBankInfoService.svc?wsdl');//连接wcf服务调用加密解密方法
            $JosnStr = json_encode($list);//将数组转化为josn格式的数据
            $params = array('encryptString' => $JosnStr, 'sKey' => $key);//将参数封装成为数组
            $result = $clients->ToDESEncrypt($params);//调用.net wcf中加密方法
            $pjosn = $result->ToDESEncryptResult;//将stdClass对象的类转换为字符串
            $client = new \SoapClient('http://10.191.43.16/B2BSTMT_SHELL.asmx?WSDL');//连接webservice服务调用抓取银行流水的方法
            $userID = "TZWTEST";//用户账户
            $param = array('userID' => $userID, 'pJson' => $pjosn);//将参数封装成为数组
            $pJsonDisplay = $client->__Call("QSODOA", array($param));//调用.net webservice中的方法，参数必须是二维数组
            $qsodoaResult = $pJsonDisplay->QSODOAResult;//获取结果字符串
            $DetialList = explode("$$$$$$", $qsodoaResult);//将字符串分割成数组（分隔符为$$$$$$）
            $Detialcount = count($DetialList);
            if ($Detialcount > 1) {
                $Display = $DetialList[1];
                $paramRtn = array('decryptString' => $Display, 'sKey' => $key);//将参数封装成为数组
                $pJsonRtn = $clients->ToDESDecrypt($paramRtn);//调用.net wcf中解密方法
                $DecryptResult = $pJsonRtn->ToDESDecryptResult;//获取结果数据字符串
                $DecryptArray = json_decode($DecryptResult, true);//反序列化字符串为数组
                //dumpE($DecryptArray);
                $tr = new Trans();
                foreach ($DecryptArray as $decry) {
                    $Bankinfo = new BsBankInfo();
                    $Summery = $this->Sbc2Dbc($decry['Abs']);//全角转半角
                    $Summery = preg_replace('/([\x80-\xff]*:())/i', '', $Summery);//过滤掉中文冒号括号
                    $blsu = preg_match_all('/([\x80-\xff]*)/i', '', $Summery);//
                    $PostScript = $this->Sbc2Dbc($decry['PostScript']);//全角转半角
                    $PostScript = preg_replace('/([\x80-\xff]*:())/i', '', $PostScript);//过滤掉中文冒号括号
                    $blop = preg_match_all('/([\x80-\xff]*)/i', '', $PostScript);//
                    $TrJrn = $decry['TrJrn'];
                    //判断摘要获取总金额
//                    if (($Summery != null || $Summery != "") && $blsu == false && count($Summery) >= 10) {
//                        $SM = explode(",", str_replace(array(";"), ',', $Summery));//如果分隔符为分号先转换为逗号再进行分割
//                        //如果为合并付款的获取多个订单号的金额并相加
//                        if (count($SM) > 1) {
//                            for ($m = 0; $m < count($SM); $m++) {
//                                $Summmery = $SM[$m];
//                            }
//                        } //单笔订单付款获取单个订单号的金额
//                        else {
//                            if (count($SM) < 1) {
//                                $Summmery = "";
//                            } else {
//                                $Summmery = $SM[0];
//                            }
//                        }
//                    }
//                    //判断附言获取总金额
//                    if (($PostScript != null || $PostScript != "") && $blop == false && count($PostScript) >= 10) {
//                        $PS = explode(",", str_replace(array(";"), ',', $PostScript));//如果分隔符为分号先转换为逗号再进行分割
//                        //如果为合并付款的获取多个订单号的金额并相加
//                        if (count($PS) > 1) {
//                            for ($p = 0; $p < count($PS); $p++) {
//                                $Post = $PS[$p];
//                            }
//                        } //单笔订单付款获取单个订单号的金额
//                        else {
//                            if (count($PS) < 1) {
//                                $Post = "";
//                            } else {
//                                $Post = $PS[0];
//                            }
//                        }
//                    }
                    //根据流水号查询该流水号在表中是否存在
                    $id = $this->getIsExist($TrJrn);
                    if ($decry['Amt'] > 0 && count($id) == 0) //金额大于0并且数据表中没有此笔数据才执行以下语句
                    {
                        $Bankinfo->SYS_CDE = $decry['SYS_CDE'];
                        $Bankinfo->FIN_CDE = $decry['FIN_CDE'];
                        $Bankinfo->FIN_SUBAREA_CDE = $decry['FIN_SUBAREA_CDE'];
                        $Bankinfo->BNK_CDE = $decry['BNK_CDE'];
                        $Bankinfo->SYS_CN_DESC = $tr->t2c($decry['SYS_CN_DESC']);
                        $Bankinfo->FIN_NME = $tr->t2c($decry['FIN_NME']);
                        $Bankinfo->FIN_SUBAREA = $tr->t2c($decry['FIN_SUBAREA']);
                        $Bankinfo->CORP_DESC = $tr->t2c($decry['CORP_DESC']);
                        $Bankinfo->BNK_NME = $tr->t2c($decry['BNK_NME']);
                        $Bankinfo->BRANCH_NME = $tr->t2c($decry['BRANCH_NME']);
                        $Bankinfo->ACCOUNTS = $decry['ACCOUNT'];
                        $Bankinfo->CUR_CDE = $decry['CUR_CDE'];
                        $Bankinfo->TXNAMT = $decry['Amt'];
                        $Bankinfo->TRDATE = $decry['TrDate'];
                        $Bankinfo->TRANSID = $decry['TrJrn'];
                        $Bankinfo->OPPACCNO = $decry['OppAccNo'];
                        $Bankinfo->OPPNAME = $tr->t2c($decry['OppName']);
                        $Bankinfo->INTERINFO = $tr->t2c($this->Sbc2Dbc($decry['Abs']));
                        $Bankinfo->POSTSCRIPT = $tr->t2c($this->Sbc2Dbc($decry['PostScript']));
                        $Bankinfo->IN_DATE = date("Y-m-d H:i:s");
                        if (!$Bankinfo->save()) {
                            throw new \Exception("发生错误");
                        }
                        $bank=0;
                    }
                }
            }
            $transaction->commit();
            if($bank==1) {
                return "1";
            }
            else{
                return "0";
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //中国银行
    public function AddBKCHBankInfo($date)
    {
        $transaction = Yii::$app->oms->beginTransaction();
        try {
           $bank=0;
            $list['BnkCode'] = "BKCH";
            $list['Account'] = "254621880150";
            $list['CurCde'] = "RMB";
            if ($date == null || $date == "") {
                $list['Date'] = date("Y-m-d");//当天的日期
            } else {
                $list['Date'] = $date;
            }
            $list['Reserve'] = null;
            $key = "98256725";
            $clients = new \SoapClient('http://10.151.18.208:8000/UpBankInfoService/UpBankInfoService.svc?wsdl');//连接wcf服务调用加密解密方法
            $JosnStr = json_encode($list);//将数组转化为josn格式的数据
            $params = array('encryptString' => $JosnStr, 'sKey' => $key);//将参数封装成为数组
            $result = $clients->ToDESEncrypt($params);//调用.net wcf中加密方法
            $pjosn = $result->ToDESEncryptResult;//将stdClass对象的类转换为字符串
            $client = new \SoapClient('http://10.191.43.16/B2BSTMT_SHELL.asmx?WSDL');//连接webservice服务调用抓取银行流水的方法
            $userID = "TZWTEST";//用户账户
            $param = array('userID' => $userID, 'pJson' => $pjosn);//将参数封装成为数组
            $pJsonDisplay = $client->__Call("QSODOA", array($param));//调用.net webservice中的方法，参数必须是二维数组
            $qsodoaResult = $pJsonDisplay->QSODOAResult;//获取结果字符串
            $DetialList = explode("$$$$$$", $qsodoaResult);//将字符串分割成数组（分隔符为$$$$$$）
            $Detialcount = count($DetialList);
            if ($Detialcount > 1) {
                $Display = $DetialList[1];
                $paramRtn = array('decryptString' => $Display, 'sKey' => $key);//将参数封装成为数组
                $pJsonRtn = $clients->ToDESDecrypt($paramRtn);//调用.net wcf中解密方法
                $DecryptResult = $pJsonRtn->ToDESDecryptResult;//获取结果数据字符串
                $DecryptArray = json_decode($DecryptResult, true);//反序列化字符串为数组
                //dumpE($DecryptArray);
                $tr = new Trans();
                foreach ($DecryptArray as $decry) {
                    $Bankinfo = new BsBankInfo();
                    $Direction = $tr->t2c($decry['DIRECTION']);
                    //获取摘要和附言的内容
                    $Script = $tr->t2c($decry['INTERINFO']);//摘要整合信息
                    $PScript = explode("//", $Script);
                    for ($j = 0; $j < count($PScript); $j++) {
                        $Str = substr($PScript[$j], 0, 1);//截取字符串中的第一个字符
                        //获取摘要内容
                        if ($Str == 'A') {
                            $MScript = explode(":", $PScript[$j]);
                            if (count($MScript) > 1) {
//                                dumpE(preg_replace('/([\x80-\xff]*:())/i', '', $tr->t2c($this->Sbc2Dbc($MScript[1]))));
                                $InterInfo = $tr->t2c($this->Sbc2Dbc($MScript[1]));
                                $InterInfo = preg_replace('/([\x80-\xff]*:())/i', '', $InterInfo);//将全角转换为半角后过滤掉中文冒号括号
//                                $bl = preg_match_all('/([\x80-\xff]*)/i', '', $InterInfo);//
//                                if ($InterInfo != "" && $bl == false && $InterInfo != null && count($InterInfo) >= 10) {
//                                    $IN = explode(",", str_replace(array(";"), ',', $InterInfo));//如果分隔符为分号先转换为逗号再进行分割
//                                    if (count($IN) > 1) {
//                                        for ($n = 0; $n < count($IN); $n++) {
//                                            $InScript = $IN[$n];
//                                            if (count($InScript) == 10)//判断是否为10位数的订单号
//                                            {
//
//                                            }
//                                        }
//                                    } else {
//                                        if (count($IN) < 1) {
//                                            $InScript = "";
//                                        } else {
//                                            $InScript = $IN[0];
//                                            if (count($InScript) == 10) {
//
//                                            }
//                                        }
//                                    }
//                                }
                            }
                        }
                        //获取附言内容
                        if ($Str == "F") {
                            $MScripts = explode(":", $PScript[$j]);
                            if (count($MScripts) > 1) {
                                $FScript = $tr->t2c($this->Sbc2Dbc($MScripts[1]));
                                $FScript = preg_replace('/([\x80-\xff]*:())/i', '', $FScript);//将全角转换为半角后过滤掉中文冒号括号
//                                $blfs = preg_match_all('/([\x80-\xff]*)/i', '', $FScript);//
//                                if ($FScript != null && $FScript != "" && $blfs == false && count($FScript) >= 10) {
//                                    $FS = explode(",", str_replace(array(";"), ',', $InterInfo));//如果分隔符为分号先转换为逗号再进行分割
//                                    if (count($FS) > 1) {
//                                        for ($f = 0; $f < count($FS); $f++) {
//                                            $FsScript = $FS[$f];
//                                            if (count($FsScript) == 10) {
//
//                                            }
//                                        }
//                                    }
//                                    else{
//                                        if(count($FS)<1)
//                                        {
//                                            $FsScript="";
//
//                                        }
//                                        else{
//                                            $FsScript=$FS[0];
//                                        }
//                                    }
//                                }
                            }
                        }
                    }
                    //$blsu = preg_match_all('/([\x80-\xff]*)/i', '', $Summery);//
                    $TRANSID = $decry['TRANSID'];
                    //根据流水号查询该流水号在表中是否存在
                    $id = $this->getIsExist($TRANSID);
                    if (count($id) == 0 && $Direction == "来账") //借贷标识为来账并且数据表中没有此笔数据才执行以下语句
                    {
                        $Bankinfo->SYS_CDE = $decry['SYS_CDE'];
                        $Bankinfo->FIN_CDE = $decry['FIN_CDE'];
                        $Bankinfo->FIN_SUBAREA_CDE = $decry['FIN_SUBAREA_CDE'];
                        $Bankinfo->BNK_CDE = $decry['BNK_CDE'];
                        $Bankinfo->SYS_CN_DESC = $tr->t2c($decry['SYS_CN_DESC']);
                        $Bankinfo->FIN_NME = $tr->t2c($decry['FIN_NME']);
                        $Bankinfo->FIN_SUBAREA = $tr->t2c($decry['FIN_SUBAREA']);
                        $Bankinfo->CORP_DESC = $tr->t2c($decry['CORP_DESC']);
                        $Bankinfo->BNK_NME = $tr->t2c($decry['BNK_NME']);
                        $Bankinfo->BRANCH_NME = $tr->t2c($decry['BRANCH_NME']);
                        $Bankinfo->ACCOUNTS = $decry['ACCOUNT'];
                        $Bankinfo->CUR_CDE = $decry['CUR_CDE'];
                        $Bankinfo->TXNAMT = $decry['TXNAMT'];
                        $Bankinfo->TRDATE = $decry['TXNDATE'];
                        $Bankinfo->TRANSID = $decry['TRANSID'];
                        $Bankinfo->OPPACCNO = $decry['ACTACN'];
                        $Bankinfo->OPPNAME = $tr->t2c($decry['ACNTNAME']);
                        $Bankinfo->INTERINFO = $InterInfo;
                        $Bankinfo->POSTSCRIPT = $FScript;
                        $Bankinfo->IN_DATE = date("Y-m-d H:i:s");
                        if (!$Bankinfo->save()) {
                            throw new \Exception("发生错误");
                        }
                        $bank=1;
                    }
                }
            }
            $transaction->commit();
            if($bank==1)
            {
                return "1";
            }
            else{
                return "0";
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return $this->error($e->getMessage());
        }
    }

    //全角转换成半角
    public function Sbc2Dbc($str)
    {
        $arr = array(
            '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '(', '〕' => ')', '【' => '[', '】' => ']', '〖' => '[', '〗' => ']', '“' => '"', '”' => '"',
            '‘' => '\'', '’' => '\'', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>', '％' => '%', '＋' => '+', '—' => '-', '－' => '-',
            '～' => '~', '：' => ':', '。' => '.', '、' => ',', '，' => ',', '、' => ',', '；' => ';', '？' => '?', '！' => '!', '…' => '-',
            '‖' => '|', '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"', '　' => ' ', '×' => '*', '￣' => '~', '．' => '.', '＊' => '*',
            '＆' => '&', '＜' => '<', '＞' => '>', '＄' => '$', '＠' => '@', '＾' => '^', '＿' => '_', '＂' => '"', '￥' => '$', '＝' => '=',
            '＼' => '\\', '／' => '/'
        );
        return strtr($str, $arr);
    }

    //判断该流水号是否存在
    public function getIsExist($Transid)
    {
        $query = BsBankInfo::find()->select('TRANSID');
        $dataProvider = new ActiveDataProvider([
            'query' => $query]);
        $query->andFilterWhere(['TRANSID' => $Transid]);
        $model = $dataProvider->getModels();
        return $model;
    }
}