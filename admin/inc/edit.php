<style>
  .edit-box{
      position:fixed;
      left:0;
      top:0;
      width:100%;
      height:100%;
      background:rgba(0,0,0,0.7);
      z-index:10;
      padding:50px 50px;
      display:none;
  }
  .container-fluid{
      background: #eee;
      border-radius:10px;
      padding-bottom:20px;
  }
</style>

<div class="edit-box">
    <div class="container-fluid my-in">
      <div class="page-title">
        <h1>修改文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" id="editForm">
        <!-- 隐藏域 :就是一个普通表单可以向后台传值，只是看不到而已 -->
        <input type="hidden"  id="id" name="id" value="">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">正文</label>
            <textarea id="content" class="form-control input-lg"
               name="content" cols="30" rows="10" placeholder="内容" style="display:none"></textarea>
            <!-- 生成富文本编辑器容器 -->
            <div id="content-box"></div> 
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
            <p class="help-block">https://zce.me/post/<strong id="strong">slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" id="img" style="width:100px;" src="../assets/img/zhanwei.png">
            <!--  accept="image/jpeg,image/gif,image/png" 限制上传文件格式 -->
            <input id="feature" class="form-control" name="feature" type="file" accept="image/*">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control sel-cate sel-cate1" name="category">     
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control sel-state sel-state1" name="status">
            </select>
          </div>
          <div class="form-group">
            <!-- <button class="btn btn-primary" >修改</button> -->
            <input  id="btn-update" type="button" value="修改"  class="btn btn-primary btn-update" >
            <input  id="btn-cancel" type="button" value="放弃"  class="btn btn-danger btn-cancel" >
          </div>
        </div>
      </form>
    </div>
</div>

