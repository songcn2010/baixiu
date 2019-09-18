<?php

include_once "../../fn.php";

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// echo '<pre>';
// print_r($_FILES);
// echo '</pre>';

$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
$slug = $_POST['slug'];
$category = $_POST['category'];
$created = $_POST['created'];
$status = $_POST['status'];

$file = $_FILES['feature'];

//处理文件
if( $file['error'] === 0 ){
  //获取后缀名
  $ext = strrchr( $file['name'], "." );
  //生成新的文件名
  $newName = time().rand(1000,9999).$ext;
  //临时存放路径
  $temp = $file['tmp_name'];
  //新的存放路径
  $newFileUrl = "../../uploads/" . $newName;
  //转存文件
  move_uploaded_file( $temp, $newFileUrl );
  // 收集新的路径
  $feature = "../uploads/" .$newName;
}

//判断是否重新上传了文件
if( empty( $feature ) ){
  //没有重新上传文件，不更新文件数据
  $sql = "update posts set title='$title',content='$content',slug='$slug',
          created='$created',category_id=$category,status='$status' 
          where id=$id";
}else{
  $sql = "update posts set title='$title',content='$content',slug='$slug',
          created='$created',category_id=$category,status='$status',feature='$feature' 
          where id=$id";
}

$res = my_exec( $sql );

echo json_encode($res);

?>