<?php

include_once "../../fn.php";

$info['text'] = $_GET['text'];
$info['title'] = $_GET['title'];
$info['link'] = $_GET['link'];

//先从数据库获取
$sqlGet = "select value from options where id=9";
$res = my_query( $sqlGet )[0]['value'];

//得到一个字符串，转化为数组
$arr = json_decode( $res , true);
// echo '<pre>';
// print_r($res);
// echo '</pre>';


// array_splice( $arr, 从哪开始删, 删几个 )
$arr[] = $info;

$str = json_encode( $arr );

//编写修改sql,数据库存的是字符串，删除，本质上也是修改
$sql = "update options set value='$str' where id=9";

my_exec( $sql );

?>