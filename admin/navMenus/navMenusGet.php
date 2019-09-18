<?php

include_once "../../fn.php";

$sql = "select value from options where id=9";

$res = my_query( $sql )[0]['value'];
// echo '<pre>';
// print_r($res);
// echo '</pre>';

echo $res;

?>