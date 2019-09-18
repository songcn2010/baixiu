<?php

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
$id = $_SESSION['user_id'];
//准备sql查询语句
$sql = "select * from users where id=$id";
//执行sql查询语句
$res = my_query( $sql )[0]; //因为结果是二维数组，所以直接0

// echo '<pre>';
// print_r($res);
// echo '</pre>';
//把对应的内容动态渲染到页面

//判断是否是文章模块
$isPosts = in_array( $page, [ 'categories','posts','post-add' ] );

//判断是否是设置模块
$isSetting = in_array( $page, [ 'settings','nav-menus','slides' ] );

?>

<div class="aside">
    <div class="profile">
      <img class="avatar" src="<?php echo $res['avatar'] ?>">
      <h3 class="name"><?php echo $res['nickname'] ?></h3>
    </div>

    <ul class="nav">
      <li class="<?php echo $page === 'index1' ? 'active':'' ?>">
        <a href="index1.php"><i class="fa fa-dashboard"></i>仪表盘</a>
      </li>

      <li class="<?php echo $isPosts ? 'active' : '' ?>">

        <!-- collapsed有就是向右，没有向下-->
        <a href="#menu-posts" data-toggle="collapse" class="<?php echo $isPosts ? '' : 'collapsed' ?>">
         
          <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
        </a>

        <!-- in类控制的是当前模块是否展开 -->
        <ul id="menu-posts" class="collapse <?php echo $isPosts ? 'in' : '' ?>">

          <li class="<?php echo $page === 'posts' ? 'active':'' ?>">
          <a href="posts.php">所有文章</a></li>

          <li class="<?php echo $page === 'post-add' ? 'active':'' ?>">
          <a href="post-add.php">写文章</a></li>

          <li class="<?php echo $page === 'categories' ? 'active':'' ?>">
          <a href="categories.php">分类目录</a></li>
        </ul>
      </li>


      <li class="<?php echo $page === 'comments' ? 'active':'' ?>">
        <a href="comments.php"><i class="fa fa-comments"></i>评论</a>
      </li>

      <li class="<?php echo $page === 'users' ? 'active':'' ?>">
        <a href="users.php"><i class="fa fa-users"></i>用户</a>
      </li>

      <li class="<?php echo $isSetting ? 'avtive':'' ?>">
        <a href="#menu-settings" data-toggle="collapse" class="<?php echo $isSetting ? '':'collapsed' ?>">
          <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
        </a>
        <ul id="menu-settings" class="collapse <?php echo $isSetting ? 'in' : '' ?>">
          <li class="<?php echo $page === 'nav-menus' ? 'active':'' ?>">
          <a href="nav-menus.php">导航菜单</a></li>

          <li class="<?php echo $page === 'slides' ? 'active':'' ?>">
          <a href="slides.php">图片轮播</a></li>

          <li class="<?php echo $page === 'settings' ? 'active':'' ?>">
          <a href="settings.php">网站设置</a></li>
        </ul>
      </li>

    </ul>
  </div>