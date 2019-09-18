<?php

include_once "../../fn.php";

$id = $_GET['id'];

$sql = "delete from comments where id in ($id)";
my_exec( $sql );

//删除后，获取剩余的总数，便于重新分页
$com_total = "select count(*) as total from comments 
              join posts on comments.post_id=posts.id";

$res = my_query( $com_total )[0];

// echo '<pre>';
// print_r($res);
// echo '</pre>';\

echo json_encode($res);

?>