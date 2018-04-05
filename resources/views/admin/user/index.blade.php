@extends('../layouts.adminnovue')

@section('pageTitle', $pageTitle)
@section('pageSubTitle', $subTitle)

@section('content')
<!-- col-md-12 -->
<div>
  <div class="box">
    <div class="box-header">
      <div class="box-tools">
        <a href="{{ url('/user/create/'.$group_id) }}" class=""><i class="fa fa-plus-square fa-2x pull-right"></i></a>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered">
        <tbody><tr>
          <th style="width: 10px">{{__('messages.listorder')}}</th>
          <th>{{__('messages.name')}}</th>
          <th style="width: 40px">{{__('messages.status')}}</th>
        </tr>
        
      </tbody></table>
    </div>
    <!-- /.box-body -->
    {{-- <div class="box-footer clearfix">
      <ul class="pagination pagination-sm no-margin pull-right">
        <li><a href="#">«</a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">»</a></li>
      </ul>
    </div> --}}
  </div>
</div>
<!-- /.col-md-12 -->
@endsection
