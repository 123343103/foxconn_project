<?php
/**
 * Created by PhpStorm.
 * User: F1640717
 * Date: 2017/9/4
 * Time: 上午 08:30
 * ftp上傳文件的路徑。隻配置中固定部分。
 * 全部全部路徑為：上傳  $ftpIP+$XX+數據庫路徑
 *                                 訪問  $httpIP+$XX+數據庫路徑
 */
//$FtpPath = require(__DIR__ . '/FtpPath.php');
$ftpPth = [
    #FTP基本配 
    'ftpIP'=>"10.134.100.101",
    'port'=>"21",
   'httpIP' => 'http://10.134.100.101:81',
    'ftpUser' => "erp",
    'ftpPwd' => "erp2017",

#公司相關信息配
    'CMP' => [
        'father'=>'/cmp/',
        #公司营业执照/三证合一
        'BsLcn' => 'bslcns',
        #税务登记证
        'TaxReg' => 'txrg',
        #纳税人资格证
        'TaxQlf' => 'txqlf'
    ],
#商品相關配
    'PDT' => [
        'father'=>'/pdt/',
        #商品圖片800*800
        'PdtImg' => 'Img',
        #商品圖片60*60
        'Img60'=>'Img60',
        #3D圖片
        'Pdt3D' => '3D',
        #詳情圖片
        'PdtMrk' => 'mrk',
        /*商品下架附件*/
        'Off'=>'off'
    ],
    /*問卷調查*/
    'QST'=>[
        'father'=>'/qst/'
    ],
    /*訂單附件*/
    'ORD'=>[
        'father'=>'/ord/',
        /*從需求單上傳的附件*/
        'Req'=>'req'
    ],
    /*账信相关附件*/
    'CCA'=>[
        'father'=>'/cca/',
        'Credit'=>'credit',
    ]
];
return $ftpPth;
