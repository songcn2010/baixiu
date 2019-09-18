<?php

include_once "../fn.php";

is_login();

$page = "comments";

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
  <link rel="stylesheet" href="../assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="../assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="../assets/css/admin.css">
  <link rel="stylesheet" href="../assets/vendors/jquery-pagination.1/pagination.css">
  <script src="../assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">

  <?php  include_once "./inc/navbar.php"; ?>
  
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm btn-approveds">批量批准</button>
          <button class="btn btn-danger btn-sm btn-dels">批量删除</button>
        </div>

        <div class="page-box pull-right">
        </div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox" id="th-chk"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>

        <tbody>
          <!-- <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>大大</td>
            <td>楼主好人，顶一个</td>
            <td>《Hello world》</td>
            <td>2016/10/07</td>
            <td>未批准</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>

  <?php include_once "./inc/aside.php" ?>

  <script src="../assets/vendors/jquery/jquery.js"></script>
  <script src="../assets/vendors/jquery-pagination.1/jquery.pagination.js"></script>
  <script src="../assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="../assets/vendors/template/template-web.js"></script>
  <script>NProgress.done()</script>

  <!--模板-->
  <script type="text/html" id="tpl">
  {{ each list v i }}
    <tr>
       <td class="text-center"><input type="checkbox" class="tb-chk" data-id="{{ v.id }}"></td>
       <td>{{ v.author }}</td>
       <td>{{ v.content.substr(0,30) }}······</td>
       <td>《{{ v.title }}》</td>
       <td>{{ v.created }}</td>
       <td>{{ state[ v.status ] }}</td>
       <td class="text-right" data-id="{{ v.id }}">
         <!--因为批准和删除的时候都需要获取当前评论行的id，这里就直接添加属性-->
         {{ if v.status ==="held" }}
         <a href="javascript:;" class="btn btn-info btn-xs btn-approved">批准</a>
         {{ /if }}
         <a href="javascript:;" class="btn btn-danger btn-xs btn-del">删除</a>
       </td>
    </tr>
  {{ /each }}
  </script>


  <script>
  
  $(function(){

    //顶一个当前页默认为第一页
    var currentPage = 1;

    var state = {
      held: "待审核",
      approved: "准许",
      rejected: "拒绝",
      trashed: "回收站"
    }

    //动态渲染
    function render(page,pageSize){
      //发送ajax请求
      $.ajax({
        type: "get",
        url: "./comments/comGet.php",
        dataType: "json",
        data: {
          //先弄第一页试试
          page: page || 1,
          pageSize: pageSize || 10
        },
        success: function(info){
          // console.log(info);
          //现在是一个数组
          //要把数组放入一个对象中，方便模板遍历
          var obj = {
            list: info,
            //因为文章的状态status是因为，需要对应中文
            //在全局申明一个对象来存放对应的状态中文
            state: state
          };
          // console.log(obj);
          //调用模板
          var htmlStr = template( "tpl", obj );
          //放进tbody
          $('tbody').html( htmlStr );
          
        }
      })
      
    }
    render();


    //分页功能
    function setPage(page){
      //由于有个动态获取当前评论总数
      // 所以配置一个获取接口
      //发送ajax请求
      $.ajax({
        url: "./comments/comTotal.php",
        dataType: "json",
        success: function( info ) {
          // console.log(info);
          
            // 初始化分页
            $('.page-box').pagination( info.total,{
              prev_text: "« 上一页",
              next_text: "下一页 »",
              items_per_page: 10,  // 每页 10 条
              num_edge_entries: 1,   // 两侧首尾分页条目数
              num_display_entries: 5,    // 连续分页主体部分分页条目数
              current_page: page - 1 || 0,   // 当前页索引, 控制按钮亮
              load_first_page: false,  // 一进入页面不执行回调

              // 每次页码被点击都会被调用的函数(插件提供的)
              callback: function( index ) {
                // 点击页码时, 应该请求对应页码的数据, 进行渲染

                // 利用封装的render方法, 渲染点击对应的页面数据
                render( index + 1 );
                //小bug，如果前一页全选了没有操作，跳转到后一页，全选框还是选中状态，手动取消
                $('#th-chk').prop('checked',false);
                //如果前一页选中了多个文章，但是没有操作就跳转到其他页面，也会出现批量按钮不隐藏的问题，手动消除
                $('.btn-batch').hide();

                // 记录当前页
                currentPage = index + 1;
              }
            });
          }
      })
    }
    setPage();


    // console.log( $('.btn-approved') );
    //因为tbody的内容是动态渲染的，包括批准和删除按钮
    // 所以这里不能直接给他们注册点击事件，需要委托tbody,因为只有父级元素中只有tbody是原生的
    
    //批准功能
    $('tbody').on("click", ".btn-approved",function(){

      //先获取设置在父元素td中的id属性
      var id = $(this).parent().attr("data-id");
      // console.log(id);

      //发送ajax请求
      $.ajax({
        type: "get",
        url: "./comments/comApproved.php",
        data: {
          id: id
        },
        success: function(info){
          // console.log(info);
          //修改未设置返回值，没报错就是成功
          //需要渲染后才会无刷新效果
          render( currentPage);
          
        }
      })

    } )


    //删除功能
    $('tbody').on('click','.btn-del',function(){

      var id = $(this).parent().attr("data-id");

      //发送请求
      $.ajax({
        type: "get",
        url: "./comments/comDel.php",
        dataType: "json",
        data: {
          id: id
        },
        success: function(info){
          // console.log(info);

          var maxPage = Math.ceil( info.total/10 );
          // console.log(maxPage);
          if( currentPage > maxPage ){
            //如果当前页超出了最大页
            currentPage = maxPage;
          }

          setPage( currentPage );
          render( currentPage );
          
          
        }

      })



    })


    //全选反选功能
    $('#th-chk').on('click',function(){
      //获取当前的选中状态
      var flag = $(this).prop('checked');
      //把当前状态设置给下面的复选框
      $('.tb-chk').prop('checked',flag);
      //当全选框选中时，批量操作按钮出现
      if( flag ){
        $('.btn-batch').show();
      }else{
        $('.btn-batch').hide();
      }
    })


    //复选框同步控制全选以及批量操作(超过两个复选框被选中，就显示批量)
    $('tbody').on('click','.tb-chk',function(){
      // console.log( $(this) );
      var res = $('.tb-chk:checked').length;
      if( res >=2 ){
        $('.btn-batch').show();
      }else{
        $('.btn-batch').hide();
      }

      $('#th-chk').prop('checked', res === 10);
            
    })


    //批量获取id
    function getId(){
        var arr = [];
      //遍历被选中的复选框，获取id放到一个数组中
      $('.tb-chk:checked').each(function(){
        var id = $(this).attr('data-id');
        arr.push( id );      
      })
       return arr.join(',');      
    }
   


    //批量批准
    $('.btn-approveds').on("click",function(){
      //由于需要获取到选中的复选框的id，稍后批量删除也会用，所以做一个封装
      var ids = getId();
      $.ajax({
        url: "./comments/comApproved.php",
        data: {
          id:ids
        },
        success: function(info){
          // console.log(info);
          //批准成功，重新渲染页面
          render( currentPage );
          //批准后,批量操作按钮隐藏
          $('.btn-batch').hide();
          //这里有个小bug，由于全选某页批准后，全选按钮没有取消，需要手动取消
          $('#th-chk').prop('checked',false)
        }
      })
    })


    //批量删除
    $('.btn-dels').on("click",function(){
      //获取选中的id
      var ids = getId();
      //发送删除请求
      $.ajax({
        type: "get",
        url: "./comments/comDel.php",
        dataType: "json",
        data: {
          id: ids
        },
        success: function(info){
          //返回的剩余评论总数，成功
          // console.log(info);
          //重新渲染当前页面
          render( currentPage );
          //让批量按钮隐藏
          $('.btn-batch').hide();
          //和批量批准一样，全选后删除，全选框没有取消，需要手动
          $('#th-chk').prop('checked',false)
        }
      })
    })
    
  })
  
  </script>

  
</body>
</html>
