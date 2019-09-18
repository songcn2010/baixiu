<?php

  include_once "../../fn.php";

  //由于需要对应post的标题，所以要联合查询
  $sql = "select count(*) as total from comments join posts 
          on comments.post_id=posts.id";

  //执行sql语句
  $res = my_query( $sql )[0];

  // echo '<pre>';
  // print_r($res);
  // echo '</pre>';

  //转化成json输出
  echo json_encode( $res );

?>