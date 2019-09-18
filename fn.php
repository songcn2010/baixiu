<?php

 header('content-type:text/html;charset=utf-8');

 //定义敞亮

 define("HOST","127.0.0.1");    //ip地址
 define("UNAME","root");       //用户名
 define("PWD","root");        //密码
 define("DB","z_baixiu");     //数据库名
 define("PORT",3306);         //端口号


 //非查询操作
 function my_exec( $sql ){

  //建立连接
  $link = mysqli_connect( HOST , UNAME , PWD , DB , PORT );
  if( !$link ){
    echo "连接失败";
    return false;
  }

  //准备sql语句，这里sql语句是通过传参，所以不用设置

  //执行sql语句,非查询返回true或false
  $res = mysqli_query( $link, $sql );

  if( $res ){
    mysqli_close( $link );
    return true;
  }else{
    echo mysqli_error();
    mysqli_close( $link );
    return false;
  }
  
 }

 //测试
//  my_exec( "delete from comments where id=500" );
//  确实删除了comments表格的id=500内容,封装无误


 //查询操作
 function my_query( $sql ){

  //建立连接
  $link = mysqli_connect( HOST , UNAME , PWD , DB , PORT );
  if( !$link ){
    echo "连接失败";
  }

  //准备sql语句

  //执行sql语句
  $res = mysqli_query( $link , $sql );

  if( !$res ){
    echo mysqli_error();
    mysqli_close( $link );
    return false;
  }

  $arr = [];

  while( $row = mysqli_fetch_assoc( $res ) ){
    $arr[] = $row;
  }

  mysqli_close( $link );
  return $arr;

 }


 //登录拦截
 function is_login(){

  if( isset( $_COOKIE['PHPSESSID'] ) ){
    //如果cookie里面有sessionId
    session_start();
    //尝试获取用户信息
    if( isset( $_SESSION['user_id'] ) ){
      //找到对应id什么都不做
    }else{
      //找不到就拦截到登录页面
      header("location: ./login.php");
    }

  }else{
    //没有sessionid
    header("location: ./login.php");
  }

 }

?>