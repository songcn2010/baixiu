<?php

include_once "../fn.php";

is_login();

$page = "categories";

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新分类目录</h2>
            <input type="hidden" name="id" id="id" value="">
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">              
              <input type="button" class="btn btn-primary btn-add" value="添加">
              <input type="button" class="btn btn-primary btn-update" value="修改" style="display:none">
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action" style="height:30px">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm btn-dels" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox" class="th-chk"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <!-- <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td>未分类</td>
                <td>uncategorized</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr> -->
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
        <td class="text-center"><input type="checkbox" class="tb-chk" id="{{ v.id }}"></td>
        <td>{{ v.name }}</td>
        <td>{{ v.slug }}</td>
        <td class="text-center" data-id="{{ v.id }}">
          <a href="javascript:;" class="btn btn-info btn-xs btn-edit">编辑</a>
          <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
        </td>
      </tr>
  {{ /each }}
  </script>

  <script>
  
  $(function(){

    function render(){
      $.ajax({
        url: "./categories/catGet.php",
        dataType: "json",
        success: function(info){
          // console.log(info); 
          var obj = {
            list: info
          }
          // console.log(obj);
          var htmlStr = template( "tpl",obj );    
          $('tbody').html( htmlStr );
        }
      })
    }
    render();

    //全选
    $('.th-chk').on("click",function(){
      //获取当前全选的状态,设置给复选框
      // console.log($(this));
      var res = $(this).prop("checked");
      // console.log(res);     
      $('.tb-chk').prop("checked",res);
      if(res){
        $('.btn-dels').show();
      }else{
        $('.btn-dels').hide()
      }
    })

    //复选框  动态渲染，需要绑定事件委托
    $('tbody').on("click",".tb-chk",function(){
      //判断是否复选框都选中
      var reschk = $('.tb-chk:checked').length;
      var chkleng = $('.tb-chk').length;
      //如果复选框的个数和选中的个数相同
      $('.th-chk').prop("checked", reschk === chkleng );
      //如果被选中的个数超过2个，就显示批量按钮
      if( reschk >= 2 ){
        $('.btn-dels').show();
      }else{
        $('.btn-dels').hide();
      }
    })

    //单个分类删除   由于单个分类的按钮都是动态渲染，需要事件委托
    $('tbody').on("click",'.btn-del',function(){
      //获取到当前分类的id
      var id = $(this).parent().attr("data-id");
      // console.log(id);
      //发送删除请求
      $.ajax({
        url: "./categories/catDel.php",
        data: {
          id: id
        },
        success: function(info){
          render();
        }
      })
    })


    //批量删除
    $('.btn-dels').on("click",function(){
      // console.log(1);
      // 获取复选框中被选中的id
      var arr = [];
      $('.tb-chk:checked').each(function(){
        // console.log($(this));
        var id = $(this).attr("id");
        // console.log(ids);
        //把获取到id放到数组中
        arr.push( id );
        // console.log(arr);      
      })
      //把数组转化成带逗号的字符串
      var idsStr = arr.join(",");
      //发送批量删除请求
      $.ajax({
        url: "./categories/catDel.php",
        data: {
          id: idsStr
        },
        success: function(info){
          //删除成功后，重新渲染页面
          render();
        }
      })

    })
    
    //添加分类功能
    $('.btn-add').on("click",function(){
      var name = $("#name").val();   
      var slug = $("#slug").val();  
      //输入内容不能为空
      if( name === '' || slug === '' ){
        alert("请输入内容");
        return;
      }
      //先获取当前所有分类
      $.ajax({
        url: "./categories/catGet.php",
        dataType: "json",
        success: function(info){
          // console.log(info);
          //返回一个二维数组
          //判断 如果输入的内容重复，则提示
          for(var i = 0; i < info.length; i++ ){
            // console.log( info[i] );
            var oldName = info[i].name;
            var oldSlug = info[i].slug;
            if( name === oldName || slug === oldSlug ){
              alert("分类名或slug重复，请重新输入");
              return;
            }else{
              //发送请求
              $.ajax({
                url: "./categories/catAdd.php",
                dataType: "json",
                data: {
                  name: name,
                  slug: slug
                },
                success: function(info){
                  // console.log(info);
                  //清空输入框
                  $("#name").val('');   
                  $("#slug").val('');  
                  render();               
                }
              })
            }
          }

        }

      })
  
    })

    //编辑功能,点击编辑，将分类数据同步到左边编辑框
    $('tbody').on("click",'.btn-edit',function(){
      //显示修改按钮
      $('.btn-update').show();
      //获取当前分类的id
      var id = $(this).parent().attr("data-id");
      console.log(id);
      //发送请求获取当前分类数据
      $.ajax({
        url: "./categories/catGetOne.php",
        dataType: "json",
        data: {
          id: id
        },
        success: function(info){
          console.log(info);
          //把获得的对应数据复制到输入框的value属性
          $('#name').val( info.name );
          $('#slug').val( info.slug );
           //在输入框那里设置一个隐藏域保存id
          $('#id').val( info.id );
        }
      })
    })

    //修改功能   点击编辑按钮之后出现
    $('.btn-update').on("click",function(){
      //获取输入框的value内容
      var id = $('#id').val();
      var name = $('#name').val();
      var slug = $('#slug').val();
      //发送update请求
      $.ajax({
        url: "./categories/catUpdate.php",
        dataType: "json",
        data: {
          id: id,
          name: name,
          slug: slug
        },
        success: function(info){
          render();
        }
      })
    })


  })
  
  </script>
</body>
</html>
