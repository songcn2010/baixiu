<?php

include_once "../../fn.php";

//获取总的文章条数.有效文章，需要联合查询  有作者有分类
$sql = "select count(*) as total from posts 
        join users on posts.user_id=users.id
        join categories on posts.category_id=categories.id";

//执行查询语句
$res = my_query( $sql )[0];

//转成json格式输出
echo json_encode( $res );

?>