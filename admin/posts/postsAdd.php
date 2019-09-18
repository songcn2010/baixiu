<?php

echo '<pre>';
print_r($_POST);
echo '</pre>';

echo '<pre>';
print_r($_FILES);
echo '</pre>';

include_once "../../fn.php";

$title = $_POST['title'];
$content = $_POST['content'];
$slug = $_POST['slug'];
$category = $_POST['category'];
$created = $_POST['created'];
$status = $_POST['status'];

//获取当前登录用户的id
//通过session中获取
session_start();
$userid = $_SESSION['user_id'];
echo $userid;

//处理上传文件
$file = $_FILES['feature'];
if( $file['error'] === 0 ){
  //上传文件成功
  //获取后缀名
  $ext = strrchr( $file['name'],"." );
  //生成新的文件名
  $newName = time().rand(1000,9999).$ext;
  //临时文件路径
  $temp = $file['tmp_name'];
  //新的存放路径
  $newFileUrl = "../../uploads/" . $newName;
  //转存文件
  move_uploaded_file( $temp, $newFileUrl );

  //手机文件路径
  $feature = "../uploads/".$newName;
}


//编写添加sql语句  insert into
$sql = "insert into posts (slug,title,feature,created,content,status,user_id,category_id)               value ( '$slug','$title','$feature','$created','$content','$status',$userid,$category)";

//执行sql语句
$res = my_exec( $sql );

if ( $res ) {
  echo "添加成功";
  // 添加成功, 跳转到文章列表页
  header("location: ../posts.php");
}
else {
  echo "添加失败";
}
?>