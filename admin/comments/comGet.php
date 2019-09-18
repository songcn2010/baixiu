<?php

  include_once "../../fn.php";

  //指定页数的评论数据的获取接口
  //准备sql查询语句
  $page = $_GET['page'];
  $pageSize = $_GET['pageSize'];

  // 当前页的其实索引
  $start = ( $page - 1 ) * $pageSize;

  //sql查询语句，由于需要找到发表的文章标题，需要和posts联合查询

  $sql = "select comments.*,posts.title from comments 
          join posts on comments.post_id=posts.id 
          limit $start,$pageSize";

  //执行sql查询语句
  $res = my_query( $sql );

  // echo '<pre>';
  // print_r($res);
  // echo '</pre>';

  //返回的是一个二维数组，转化成json输出  
  // json_encode     转化成json字符串
  // json_decode     传参true转化成数组，否则转化成对象
  echo json_encode( $res );

?>