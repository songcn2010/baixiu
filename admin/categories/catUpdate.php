<?php

include_once "../../fn.php";

$id = $_GET['id'];
$name = $_GET['name'];
$slug = $_GET['slug'];

$sql = "update categories set name='$name',slug='$slug' where id=$id";

$res = my_exec( $sql );
echo json_encode( $res );
?> 