<?php

include_once "../fn.php";

is_login();

$page = "post-add";

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->


      <!--表单的三大要素  action  method  name-->
      <form class="row" action="./posts/postsAdd.php" method="post" enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">标题</label>
            <div class="box"></div>
            <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容" style="display:none"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail img-box" style="height:100px;width:100px" src="../assets/img/zhanwei.png">
            <input id="feature" class="form-control" name="feature" type="file" accept="image/jpg,image/jpeg,image/png,image/gif,image/bmp">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <!-- <option value="1">未分类</option> -->
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <!-- <option value="drafted">草稿</option>
              <option value="published">已发布</option> -->
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php include_once "./inc/aside.php" ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.min.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/moment/moment.js"></script>
  <script>NProgress.done()</script>

  <script type="text/html" id="tpl">
  <!--分类栏的模板-->
  {{ each list v i }}
    <option value="{{ v.id }}">{{ v.name }}</option>
  {{ /each }}
  </script>

  <script type="text/html" id="tpl-state">
  <!--状态栏的模板-->
  {{ each state v k }}
    <option value="{{ k }}">{{ v }}</option>            
  {{ /each }}
  </script>


  <script>

    $(function(){
      

      //配置富文本编辑器
    var E = window.wangEditor;  // 给 wangEditor 起了个小名
    var editor = new E('.box');  // 进行创建实例

    // 在富文本编辑器被修改时调用, html就是编辑器中的内容
    editor.customConfig.onchange = function (html) {
      // 监控变化，同步更新到 textarea
      document.querySelector('textarea').value = html;
    }
    editor.create();


// =================================================================

    //别名同步
    $('#slug').on("input",function(){
      //获取别名输入框的内容   由于默认是slug，但是输入内容删除后，没有，所以手动设置slug
      var slugCon = $(this).val() || "slug";
      $('strong').text( slugCon );
    })

    //图像选择预览功能
    $('#feature').on("change",function(){
      // console.log($(this));
      var file = this.files[0];
      // console.dir(file);
      var imgUrl = URL.createObjectURL( file );
      // img.src = imgUrl;
      $('.img-box').attr("src", imgUrl).show();

    })

    //动态渲染分类框
    $.ajax({
      //先做一个获取后台获取分类数据的接口
      url: "./categories/catGet.php",
      dataType: "json",
      success: function(info){
        // console.log(info);
        var obj = {
          list: info
        };
        // console.log(obj);
        var htmlStr = template("tpl", obj );
        $('#category').html( htmlStr );
      }
    })


    //动态渲染状态栏
    var state = {
      drafted: "草稿",
      published: "已发布",
      trashed: "回收站"
    }
    var obj = {
      state: state
    }
    // console.log(obj);
    
    var htmlStr = template("tpl-state",obj);
    $('#status').html( htmlStr );


    // ==================================================
    //通过moment插件格式化时间
    $('#created').val( moment().format("YYYY-MM-DDTHH:mm") )


  }) 

  
  </script>
</body>
</html>
