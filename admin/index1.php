<?php

include_once "../fn.php";

is_login();

$page = "index1";

//主页需要渲染的有，文章总数  草稿数   分类数   评论数   待审核评论数

//文章总数
$postsSql = "select count(*) as total from posts";
//草稿总数
$draSql = "select count(*) as total from posts where status='drafted'";
//分类个数
$catSql = "select count(*) as total from categories";
//评论总数
$comSql = "select count(*) as total from comments";
//待审核评论数
$heldSql = "select count(*) as total from comments where status='held'";

//执行sql查询语句
$postsTotal = my_query( $postsSql )[0]['total'];
$draTotal = my_query( $draSql )[0]['total'];
$catTotal = my_query( $catSql )[0]['total'];
$comTotal = my_query( $comSql )[0]['total'];
$heldTotal = my_query( $heldSql )[0]['total'];

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    
  <?php  include_once "./inc/navbar.php"; ?>
  
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><a href="./posts.php"><strong><?php echo $postsTotal ?></strong>篇文章（<strong><?php echo $draTotal ?></strong>篇草稿）</a></li>
              <li class="list-group-item"><a href="./categories.php"><strong><?php echo $catTotal ?></strong>个分类</a></li>
              <li class="list-group-item"><a href="./comments.php"><strong><?php echo $comTotal ?></strong>条评论（<strong><?php echo $heldTotal ?></strong>条待审核）</a></li>
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>

  <?php include_once "./inc/aside.php" ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
</body>
</html>
