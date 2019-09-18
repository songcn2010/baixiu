<?php

include_once "../../fn.php";

$index = $_GET['index'];

$sqlGet = "select value from options where id=10";
$resGet = my_query( $sqlGet )[0]['value'];

// echo '<pre>';
// print_r($resGet);
// echo '</pre>';
//得到的是字符串，转化成二维数组
$arr = json_decode( $resGet , true);
// echo '<pre>';
// print_r($arr);
// echo '</pre>';

array_splice( $arr , $index , 1 );
//删除完毕,将数组转化成json字符串
$str = json_encode( $arr );

//把删除后的数据重新更新的数据库
$sql = "update options set value='$str' where id=10";
my_exec( $sql );


?>