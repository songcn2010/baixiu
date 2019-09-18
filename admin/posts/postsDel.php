<?php

include_once "../../fn.php";

$id = $_GET['id'];

//删除文章的sql语句
$sql = "delete from posts where id in ($id)";

//执行
my_exec( $sql );

// 删除后，文章页需要重新渲染,重置分页模块，所以需要返回一个删除后的有效文章总数
$sqlTotal = "select count(*) as total from posts 
             join users on posts.user_id=users.id
             join categories on posts.category_id=categories.id";

$res = my_query( $sqlTotal )[0];
// echo '<pre>';
// print_r($res);
// echo '</pre>';
//转化成json返回
echo json_encode( $res );
?>