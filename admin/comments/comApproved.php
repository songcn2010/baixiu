<?php

  include_once "../../fn.php";
  //修改对应的数据的status
  $id = $_GET['id'];

  // $sql = "update comments set status='approved' where id=$id";
  //由于批量批准，改成批量操作的语句
  $sql = "update comments set status='approved' where id in ($id)";

  //执行sql语句
  my_exec( $sql );

?>