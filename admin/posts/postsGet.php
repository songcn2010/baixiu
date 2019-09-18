<?php

include_once "../../fn.php";

$page = $_GET['page']; 
$pageSize = $_GET['pageSize']; 

//其实索引
$start = ( $page - 1 ) * $pageSize;

//查询文章数据sql  ,需要联合查询
$sql = "select posts.*,users.nickname,categories.name from posts 
        join users on posts.user_id=users.id 
        join categories on posts.category_id=categories.id 
        order by id desc 
        limit $start,$pageSize";

//执行查询sql语句
$res = my_query( $sql );

// echo '<pre>';
// print_r($res);
// echo '</pre>';
// 返回的是一个二维数组  转化成json格式输出

echo json_encode( $res );


?>