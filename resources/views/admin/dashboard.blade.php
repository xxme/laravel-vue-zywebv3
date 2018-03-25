@extends('../layouts.admin')

@section('pageTitle', $pageTitle)
@section('pageSubTitle', $subTitle)

@section('content')
<!-- col-md-12 -->
<div class="col-md-12">
  <div class="box-header with-border">
    <a name="2018-03-06"></a>
    <h4>03-01 星期四(木)<small class="pull-right">日程: 3</small></h4>
  </div>
  
  <!-- box -->
  <div class="box box-widget">
    <!-- box-header -->
    <div class="box-header with-border">
      <div class="user-block">
        <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Image">
        <span class="username">Jonathan Burke Jr.</span>
        <span class="description">7:30 PM Today</span>
      </div>
      <!-- /.user-block -->
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
      </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <h5>Aコース / 送货</h5>
      <img class="img-responsive pad" src="../dist/img/photo2.png" alt="Photo">

      <p>I took this photo this morning. What do you guys think?</p>
      <button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Share</button>
      <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
      <span class="pull-right text-muted">127 likes - 3 comments</span>
    </div>
    <!-- /.box-body -->
    <div class="box-footer box-comments">
      <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="../dist/img/user3-128x128.jpg" alt="User Image">

        <div class="comment-text">
          <span class="username">
            Maria Gonzales
            <span class="text-muted pull-right">8:03 PM Today</span>
          </span><!-- /.username -->
            It is a long established fact that a reader will be distracted
            by the readable content of a page when looking at its layout.
        </div>
          <!-- /.comment-text -->
      </div>
      <!-- /.box-comment -->
      <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="User Image">

        <div class="comment-text">
          <span class="username">
            Luna Stark
            <span class="text-muted pull-right">8:03 PM Today</span>
          </span><!-- /.username -->
            It is a long established fact that a reader will be distracted
            by the readable content of a page when looking at its layout.
        </div>
          <!-- /.comment-text -->
      </div>
        <!-- /.box-comment -->
    </div>
    <!-- /.box-footer -->
    <div class="box-footer">
      <form action="#" method="post">
        <img class="img-responsive img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="Alt Text">
        <!-- .img-push is used to add margin to elements next to floating images -->
        <div class="img-push input-group">
          <input type="text" class="form-control" placeholder="Press enter to post comment">
          <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat">Send!</button>
          </span>
        </div>
      </form>
    </div>
    <!-- /.box-footer -->
  </div>
  <!-- /.box -->
  <!-- box -->
  <div class="box box-widget">
    <!-- box-header -->
    <div class="box-header with-border">
      <div class="user-block">
        <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Image">
        <span class="username">Jonathan Burke Jr.</span>
        <span class="description">7:30 PM Today</span>
      </div>
      <!-- /.user-block -->
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
      </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <h5>Aコース / 送货</h5>
      <img class="img-responsive pad" src="../dist/img/photo2.png" alt="Photo">

      <p>I took this photo this morning. What do you guys think?</p>
      <button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Share</button>
      <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
      <span class="pull-right text-muted">127 likes - 3 comments</span>
    </div>
    <!-- /.box-body -->
    <div class="box-footer box-comments">
      <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="../dist/img/user3-128x128.jpg" alt="User Image">

        <div class="comment-text">
          <span class="username">
            Maria Gonzales
            <span class="text-muted pull-right">8:03 PM Today</span>
          </span><!-- /.username -->
            It is a long established fact that a reader will be distracted
            by the readable content of a page when looking at its layout.
        </div>
          <!-- /.comment-text -->
      </div>
      <!-- /.box-comment -->
      <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="User Image">

        <div class="comment-text">
          <span class="username">
            Luna Stark
            <span class="text-muted pull-right">8:03 PM Today</span>
          </span><!-- /.username -->
            It is a long established fact that a reader will be distracted
            by the readable content of a page when looking at its layout.
        </div>
          <!-- /.comment-text -->
      </div>
        <!-- /.box-comment -->
    </div>
    <!-- /.box-footer -->
    <div class="box-footer">
      <form action="#" method="post">
        <img class="img-responsive img-circle img-sm" src="../dist/img/user4-128x128.jpg" alt="Alt Text">
        <!-- .img-push is used to add margin to elements next to floating images -->
        <div class="img-push input-group">
          <input type="text" class="form-control" placeholder="Press enter to post comment">
          <span class="input-group-btn">
            <button type="button" class="btn btn-info btn-flat">Send!</button>
          </span>
        </div>
      </form>
    </div>
    <!-- /.box-footer -->
  </div>
  <!-- /.box -->
</div>
<!-- /.col-md-12 -->
      
@endsection
