<?php

include_once "../../fn.php";

//获取全部分类数据，不用传data
$sql = "select * from categories order by id";

//运行sql
$res = my_query( $sql );

// echo '<pre>';
// print_r($res);
// echo '</pre>';
echo json_encode( $res );

?>