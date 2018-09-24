<?php
//$conn=mysqli_connect("mysqld","root","root","sys"); //连接数据库
////连接数据库错误提示
//if (mysqli_connect_errno($conn)) {
//    die("连接 MySQL 失败: " . mysqli_connect_error());
//}else{
//	echo "conn success;";
//}




$servername = "mysqld";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;", $username, $password);
    echo "连接成功";
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
