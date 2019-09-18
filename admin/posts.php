<?php

include_once "../fn.php";

is_login();

$page = "posts";

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="../assets/vendors/jquery-pagination.1/pagination.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body class="body">
  <script>NProgress.start()</script>

  <div class="main">
    
  <?php  include_once "./inc/navbar.php"; ?>
  
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm btn-dels" href="javascript:;" style="display: none">批量删除</a>
       
      <!--分页容器-->
      <div class="page-box pull-right"></div>

      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" class="th-chk"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>随便一个名称</td>
            <td>小小</td>
            <td>潮科技</td>
            <td class="text-center">2016/10/07</td>
            <td class="text-center">已发布</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>

  <?php include_once "./inc/aside.php" ?>
  <?php include_once "./inc/edit.php" ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script src="../assets/vendors/jquery-pagination.1/jquery.pagination.js"></script>
  <script src="../assets/vendors/wangEditor/wangEditor.min.js"></script>
  <script src="../assets/vendors/moment/moment.js"></script>
  <script>NProgress.done()</script>

  <script type="text/html" id="tpl-cat">
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
  
  <script type="text/html" id="tpl">
  <!--准备模板-->
  {{ each list v i }}
    <tr>
      <td class="text-center"><input type="checkbox" class="tb-chk" id="{{ v.id }}"></td>
      <td>{{ v.title }}</td>
      <td>{{ v.nickname }}</td>
      <td>{{ v.name }}</td>
      <td class="text-center">{{ v.created }}</td>
      <td class="text-center">{{ state[v.status] }}</td>
      <td class="text-center" data-id="{{ v.id }}">
        <!--这里把id设置给td，便于后面编辑和删除的时候提取试用-->
        <a href="javascript:;" class="btn btn-default btn-xs btn-edit">编辑</a>
        <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
      </td>
    </tr>
  {{ /each }}
  </script>

  <script>
    $(function(){

      var currentPage = 1;


      // 草稿（drafted）/ 已发布（published）/ 回收站（trashed）
      var state = {
        drafted: "草稿",
        published: "已发布",
        trashed: "回收站"
      };


      //渲染
      function render(page,pageSize){
        //发送请求
        $.ajax({
          url: "./posts/postsGet.php",
          dataType: "json",
          data: {
            page: page || 1,
            pageSize: pageSize || 10
          },
          success: function(info){
            // console.log(info); 

            var obj = {
              list: info,
              state: state
            }
            // console.log(obj);
            
            // 调用模板方法
            var htmlStr = template( "tpl", obj );
            $('tbody').html( htmlStr );         
          }
        })
      }
      render();

      //分页
      function setPage(page){
        //进页面先发送请求获取数据
        $.ajax({
          url: "./posts/postsTotal.php",
          dataType: "json",
          success: function(info){
            // console.log(info);
            //返回有效文章总数
              $('.page-box').pagination( info.total, {
              // 各种配置
              prev_text: "上一页",
              next_text: "下一页",
              items_per_page: 10, // 每页显示多少条
              num_edge_entries: 1, // 配置首尾按钮显示的个数
              num_display_entries: 5, // 配置连续主体显示的条目数
              current_page: page - 1 || 0,  // 当前页索引, 从0开始
              load_first_page: false, // 表示一进入页面不需要直接调用一次回调函数
              callback: function( index ) {
                // console.log(index + 1);
                //分页模块初始化成功,重新渲染当前页面
                //如果先选中了全选，再点击其他页面，全选状态还在，需要手动消除
                $('.th-chk').prop("checked", false);
                //如果前一页选中了多个文章，但是没有操作就跳转到其他页面，也会出现批量按钮不隐藏的问题，手动消除
                $('.btn-dels').hide();
                render( index + 1 );
                //把当前页面的值赋给currentPage
                currentPage = index + 1;
              }
            })
            
          }
        })
      }
      setPage();

      //全选
      $('.th-chk').on("click",function(){
        //点击全选，同步checked状态到复选框
        //获取全选框状态
        var chkAll = $(this).prop("checked");
        $('.tb-chk').prop("checked", chkAll );
        if( chkAll ){
          $('.btn-dels').show();
        }else{
          $('.btn-dels').hide();
        };
      })

      //复选框的操作   由于是动态渲染的，所以需要注册事件委托
      $('tbody').on("click", ".tb-chk",function(){
        //判断，如果复选框都选中了，全选框同步选中，否则全选框不选中
        var res = $('.tb-chk:checked').length;
        if( res >= 2 ){
          $('.btn-dels').show();
        }else{
          $('.btn-dels').hide();
        };
        $('.th-chk').prop("checked", res === 10);
      })


      //单个删除功能  由于删除按钮是动态渲染，需要注册事件委托
      $('tbody').on("click",".btn-del",function(){
        //点击成功，获取当前文章的id
        // console.log( $(this) );
        var id = $(this).parent().attr("data-id");
        // console.log(id);
        //获取id成功，发送请求
        $.ajax({
          url: "./posts/postsDel.php",
          dataType: "json",
          data: {
            id: id
          },
          success: function(info){
            console.log(info);
            // 判断当前页的值是否超过最大页
            var maxPage = Math.ceil( info.total/10 );
            if( currentPage > maxPage ){
              currentPage = maxPage
            }
            render( currentPage );
            setPage( currentPage );
          }
        })
      })

      //批量删除文章功能  批量删除按钮  .btnDels
      //由于需要获取复选框选中文章的id，文章页只有一个批量操作功能
      //这里就不单独封装获取复选框id的方法
      $('.btn-dels').on("click",function(){
        //获取复选框被选中的id
       var arr = [];
       $('.tb-chk:checked').each(function(index,value){
         var id = $(this).attr("id");
         arr.push( id );
        //  console.log(arr); 
        //把数组转化成字符串，方便传参
         var ids = arr.join(',');
        //  console.log(ids);     
        //发送请求
        $.ajax({
          url: "./posts/postsDel.php",
          dataType: "json",
          data: {
            id: ids
          },
          success: function(info){
            // console.log(info);
            //删除成功后，需要重新渲染当前页，并重新判断渲染分页模块
            var maxPage = Math.ceil(info.total/10);
            if( currentPage > maxPage ){
              currentPage = maxPage
            };
            render( currentPage );
            setPage( currentPage );
          }
        })        
       })
      })


     
      // =============================================================================
      //模态框的初始化
      
      //配置富文本编辑器
      var E = window.wangEditor;  // 给 wangEditor 起了个小名
      var editor = new E('#content-box');  // 进行创建实例

      // 在富文本编辑器被修改时调用, html就是编辑器中的内容
      editor.customConfig.onchange = function (html) {
        // 监控变化，同步更新到 textarea
        $('#content').val( html );
        
      }
      editor.create();  // 调用实例方法, 创建一个富文本编辑器

      // ===================================================================================
      //模态框别名同步
      $('#slug').on("input",function(){
        // console.log($(this));
        var res = $(this).val();
        $('#strong').text( res );
      })

      //模态框图像预览
      $('#feature').on("change",function(){
        // console.dir(this);
        var file = this.files[0];
        // console.log(file);
        var imgUrl = URL.createObjectURL( file );
        // console.log(imgUrl);
        $('#img').attr("src", imgUrl);
      })

      //时间
      $('#created').val( moment().format( "YYYY-MM-DDTHH:mm" ) );

      //动态渲染分类栏
      $.ajax({
        url: "./categories/catGet.php",
        dataType: "json",
        success: function(info){
          // console.log(info);
          var obj = {
            list: info
          }
          var htmlStr = template( "tpl-cat", obj);
          $('#category').html( htmlStr );
        }
      })

      //状态栏的动态渲染
      var state = {
        drafted: "草稿",
        published: "已发布",
        trashed: "回收站"
      };
      var obj = {
        state: state
      }
      var stateStr = template( "tpl-state", obj);
      $('#status').html( stateStr );



       //文章编辑功能    由于是动态渲染,而且是批量绑定，事件委托
       $('tbody').on("click",".btn-edit",function(){
        //点击编辑，首先弹出模态框
        $('.edit-box').show();
        //同时获取到点击的对应id文章的数据
        //id可以在对应的复选框获取，之前已经在模板渲染是添加了id属性
        // console.log($(this));
        var id = $(this).parent().attr("data-id");
        // console.log(id);
        //将id同步到模态框的隐藏域
        $('#id').val( id );
        //获取到id，发送请求获取对应文章的数据
        $.ajax({
          url: "./posts/postsGetOne.php",
          dataType: "json",
          data: {
            id: id
          },
          success: function(info){
            // console.log(info);
            //获取到对应id的文章数据，然后初始化
            //标题
            $('#title').val( info.title );
            //富文本
            editor.txt.html(info.content);
            $('#content').val( info.content );
            //别名
            $('#slug').val( info.slug );
            $('#strong').text( info.slug );
            //图片
            $('#img').attr("src", info.feature );
            //分类
            $('#category').val( info.category_id );
            //状态
            $('#status').val( info.status );
            }
          })
        });
      

      //模态框选择放弃编辑
      $('#btn-cancel').on("click",function(){
        $('.edit-box').hide();
      })

      //修改功能
      $('#btn-update').on("click",function(){
        var formdata = new FormData( $('#editForm')[0] );
        $.ajax({
          type: "post",
          url: "./posts/postsUpdate.php",
          dataType: "json",
          data: formdata,
          contentType: false,
          processData: false,
          success: function( info ) {
            // console.log(info);
            //修改完成，隐藏模态框，并刷新当前页
            $('.edit-box').hide();
            // 2. 重新渲染
            render( currentPage );
          }
        })
      })


// ===========================================================================================
      //自己新增的功能
      //试验   点击模态框内容区意外的地方，关闭模态框
      $('.edit-box').on("click",function(e){
        // console.log( $(e.target).parent() );    //点击位置的父元素
        //给body添加了一个body的类名，用于判断，有类名为true
        var res = $(e.target).parent().hasClass( "body" );
        // console.log(res);
        if(res){
          $(this).hide();
        }
      })



  })
  </script>
</body>
</html>
