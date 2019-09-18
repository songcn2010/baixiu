<?php

include_once "../../fn.php";

$info['text'] = $_POST['text'];
$info['link'] = $_POST['link'];

$file = $_FILES['image'];
echo '<pre>';
print_r($file);
echo '</pre>';
if( $file['error'] === 0 ){
  //获取后缀名
  $ext = strrchr( $file['name'] , "." );
  //生成新文件名
  $newName = time().rand(1000,9999).$ext;
  //临时存放路径
  $temp = $file['tmp_name'];
  //新的存放路径
  $newFileUrl = "../../uploads/" . $newName;
  //转存
  move_uploaded_file( $temp , $newFileUrl );

  // 收集文件路径
  $info['image'] = "../uploads/" . $newName;
}


if( isset( $info['image'] ) ){
  //说明确实获得了文件路径
  $sqlGet = "select value from options where id=10";
  $resGet = my_query( $sqlGet )[0]['value'];
  // echo '<pre>';
  // print_r($resGet);
  // echo '</pre>';

  //获取到一个字符串,转化成二维数组,参数true转化数组，否则是对象
  $arr = json_decode( $resGet , true );
  
  $arr[] = $info;
 
  // echo '<pre>';
  // print_r($arr);
  // echo '</pre>';

  //将数据添加好的数组转化成字符串
  $str = json_encode( $arr , JSON_UNESCAPED_UNICODE);

  //更新数据库内容
  $sql = "update options set value='$str' where id=10";
  $res = my_exec( $sql );
  echo json_encode( $res );
}

?>