<?php

include_once "../fn.php";

is_login();

$page = "nav-menus";

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
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
      <div class="page-title">
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="href" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <input class="btn btn-primary btn-add" type="button" value="添加">
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>               
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>               
                <td><i class="fa fa-glass"></i>奇趣事</td>
                <td>奇趣事</td>
                <td>#</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>             -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

 <?php include_once "./inc/aside.php" ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script>NProgress.done()</script>

  <script type="text/html" id="tpl">
  {{ each list v i }}
    <tr>               
      <td><i class="{{ v.icon }}"></i>{{ v.text }}</td>
      <td>{{ v.title }}</td>
      <td><a href="{{ v.link }}">{{ v.link }}</a></td>
      <td class="text-center">
        <a href="javascript:;" class="btn btn-danger btn-xs btn-del" index="{{ i }}">删除</a>
      </td>
    </tr>   
  {{ /each }}  
  </script>

  <script>
  
  $(function(){

    function render(){
      //发送请求获取分类数据
      $.ajax({
        url: "./navMenus/navMenusGet.php",
        dataType: "json",
        success: function(info){
          // console.log(info);
          //把返回的数组包装到对象
          var obj = {
            list: info 
          }
          console.log(obj);         
          //调用模板方法
          var htmlStr = template( "tpl", obj );
          //添加到tbody
          $('tbody').html( htmlStr );
        }
      })
    }
    render();

    //删除功能
    $('tbody').on("click",".btn-del",function(){
      var index = $(this).attr("index");
      // console.log(index);
      //发送请求
      $.ajax({
        url: "./navMenus/navMenusDel.php",
        data: {
          index: index
        },
        success: function(info){
          render();
        }
      })
    })

    
    // //添加功能
    $('.btn-add').on("click",function(){
      var text = $('#text').val();
      var title = $('#title').val();
      var link = $('#href').val();
      //发送请求
      $.ajax({
        url: "./navMenus/navMenusAdd.php",
        data: {
          text: text,
          title: title,
          link: link
        },
        success: function(info){
          $('#text').val('');
          $('#title').val('');
          $('#href').val('');
          render();
        }
      })
    })


  })
  
  </script>
</body>
</html>
