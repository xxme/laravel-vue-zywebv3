@extends('../layouts.adminnovue')

@section('pageTitle', $pageTitle)
@section('pageSubTitle', $subTitle)

@section('content')
<!-- col-md-12 -->
<div>
  <div class="box">
    @if(Session::has('message'))
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> {{ session('message') }}</h4>
      </div>
    @endif
    @if($currentUser->group_id == 1)
    <div class="box-header">
      <div class="box-tools">
        <a href="{{ url('admin/user/create/'.$group_id) }}" class=""><i class="fa fa-plus-square fa-2x pull-right"></i></a>
      </div>
    </div>
    @endif
    <div class="box-body">
      <table class="table table-bordered">
        <tbody><tr>
          <th style="width: 80px">{{__('messages.profile')}}</th>
          <th style="width: 150px">{{__('messages.name')}}</th>
          <th>{{__('messages.email')}}</th>
          <th style="width: 50px">{{__('messages.action')}}</th>
        </tr>
        @if($users)
          @foreach ($users as $user)
            <tr>
              <td align="center">
                @if($user->profileimg)
                <img src="{{ asset("uploads/profiles") }}/{{ $user->profileimg }}" class="user-listimage" alt="User Image">
                @else
                <img src="{{ asset("images") }}/no-image-available.jpeg" class="user-listimage" alt="User Image">
                @endif
              </td>
              <td>
                {{ $user->name }}
              </td>
              <td>
                {{ $user->email }}
              </td>
              <td>
                @if($currentUser->group_id == 1)
                <a href="{{ url('admin/user/edit/'.$user->id) }}"><button class="btn btn-block btn-primary btn-xs">{{ __('messages.edit') }}</button></a>
                @endif
              </td>
            </tr>
          @endforeach
        @endif
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
