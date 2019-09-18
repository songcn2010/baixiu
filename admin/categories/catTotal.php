<?php

include_once "../../fn.php";

//获取全部分类的个数，没有接收数据
$sql = "select count(*) as total from categories";

//执行sql
$res = my_query( $sql )[0];

// echo '<pre>';
// print_r($res);
// echo '</pre>';

echo json_encode( $res );

?>