<?php

  header('content-type:text/html;charset=utf-8');

  //判断$_POST是否为空，不为空进行校验   empty(),判断是否为空，空true，非空false
  if( !empty( $_POST ) ){

    include_once "../fn.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    if( empty( $email ) || empty( $password ) ){
      $msg = "用户名或密码为空";
    }else{
      //用户名和密码都有内容
      //准备sql语句进行查询
      $sql = "select * from users where email='$email'";

      //调用方法执行sql查询语句
      $res = my_query( $sql );

      // echo '<pre>';
      // print_r($res);
      // echo '</pre>';

      //判断查询结果
      if( !$res ){
        $msg = "用户名不存在";
      }else{
        //用户名在数据控中存在
        //返回的是一个二维数组
        $data = $res[0];

        //进行密码校验，输入密码是否和数据库中一致
        if( $password === $data['password'] ){
          // 用户名密码正确, 跳转到首页
          // 需要记录用户信息 user_id
          //session_start
          session_start();
          $_SESSION['user_id'] = $data['id'];
          header("location: ./index1.php");

        }else{
          //密码不正确
          $msg = "密码错误";
        }

      }

    }


  }
?>


<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
  <div class="login">
    <!--表单三大元素 action method name-->
    <form class="login-wrap" action="" method="post">
      <img class="avatar" src="../assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if( isset($msg) ){ ?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $msg ?>
      </div>
      <?php } ?>
      
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input name="email"
               id="email" 
               type="text" 
               class="form-control" 
               placeholder="邮箱" 
               autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password"
               name="password"
               type="password"
               class="form-control" 
               placeholder="密码">
      </div>     
      <input  class="btn btn-primary btn-block" type="submit" value="登录">
    </form>
  </div>
</body>
</html>
