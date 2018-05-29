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
    <div class="box-body">
      <div class="pull-right">
        <a href="{{ url('admin/logs/'.$prevmonth) }}"><button class="btn btn-xs"><i class="fa fa-chevron-left"></i></button></a>
        <span>{{ $subTitle }}</span>
        <a href="{{ url('admin/logs/'.$nextmonth) }}"><button class="btn btn-xs"><i class="fa fa-chevron-right"></i></button></a>
      </div>
      <table class="table table-bordered">
        <tbody><tr>
          <th style="width: 150px">{{__('messages.actiondatetime')}}</th>
          <th style="width: 80px"><i class="fa fa-user-o"></i></th>
          <th style="width: 70px">
            <select name="action" onchange="changeaction(this)">
              <option value="0">{{__('messages.action')}}</option>
              <option value="1" @if(Route::input('action') == '1')selected="true"@endif>{{__('messages.actionadd')}}</option>
              <option value="2" @if(Route::input('action') == '2')selected="true"@endif>{{__('messages.actionupdate')}}</option>
              <option value="3" @if(Route::input('action') == '3')selected="true"@endif>{{__('messages.actiondel')}}</option>
            </select>
          </th>
          <th></th>
        </tr>
        @if($logs)
          @foreach ($logs as $log)
            <tr>
              <td align="center">
                {{ $log->created_at }}
              </td>
              <td align="center">
                {{ $log->user->name }}
              </td>
              <td align="center">
                @if($log->log_type == 1)
                {{ __('messages.actionadd') }}
                @elseif($log->log_type == 2)
                {{ __('messages.actionupdate') }}
                @elseif($log->log_type == 3)
                {{ __('messages.actiondel') }}
                @endif
              </td>
              <td>
                @if($log->type == 1)
                  {{ $log->content }} {{ __('messages.event') }}
                @elseif($log->type == 2)
                  {{ __('messages.typeofwork') }}
                @elseif($log->type == 3)
                  {{ __('messages.comment') }}
                @elseif($log->type == 7)
                  {{ __('messages.menushoppinglist') }}
                @elseif($log->type == 8)
                  {{ __('messages.estimateslist') }}
                @endif
                @if($log->type == 1 || $log->type == 7 || $log->type == 8)
                  #{{ $log->obj_id }}
                @endif
              </td>
            </tr>
          @endforeach
        @endif
      </tbody></table>
      <div class="pull-right">
        {{ $logs->links() }}
      </div>
    </div>
    <!-- /.box-body -->
  </div>
</div>
<!-- /.col-md-12 -->
<script>
  function changeaction(e) {
    window.location.href = "{{ url('/admin/logs/'.$subTitle) }}"+'/'+$(e).val();
  }
</script>
@endsection
