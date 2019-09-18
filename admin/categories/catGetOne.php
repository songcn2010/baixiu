<?php

include_once "../../fn.php";

$id = $_GET['id'];

$sql = "select * from categories where id=$id ";

//运行sql
$res = my_query( $sql )[0];

// echo '<pre>';
// print_r($res);
// echo '</pre>';
echo json_encode( $res );

?>