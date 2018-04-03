@extends('../layouts.adminnovue')

@section('pageTitle', $pageTitle)
@section('pageSubTitle', $subTitle)

@section('content')
<!-- col-md-12 -->
<div>
  <div class="box">
    <div class="box-header">
      <button class="btn btn-primary btn-xs" onclick="formsubmit()">Update</button>
      <div class="box-tools">
        <a href="{{ url('admin/type/create/'.$group_id) }}" class=""><i class="fa fa-plus-square fa-2x pull-right"></i></a>
      </div>
    </div>
    <div class="box-body">
      <form method="POST" id="form">
      {{ csrf_field() }}
      <input type="hidden" name="group_id" value="{{ $group_id }}" />
      <table class="table table-bordered">
        <tbody><tr>
          <th style="width: 10px">ID</th>
          <th style="width: 65px">{{__('messages.listorder')}}</th>
          <th>{{__('messages.name')}}</th>
          <th style="width: 40px">{{__('messages.status')}}</th>
        </tr>
        @if($types)
          @foreach ($types as $type)
            <tr>
              <td>{{ $type->id }}</td>
              <td>
                <input type="text" class="form-control" name="listorder[{{ $type->id }}]" value="{{ $type->listorder }}" />
              </td>
              <td>
                <input type="text" class="form-control" name="name[{{ $type->id }}]" value="{{ $type->name }}" />
              </td>
              <td>@if($type->status == 1)<span class="label label-success" onclick="changestatus('{{ $type->id }}')">Available @else <span class="label label-danger" onclick="'{{ $type->id }}'">Unavailable @endif</span></td>
            </tr>
          @endforeach
        @endif
      </tbody></table>
      </form>
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
<script>
  function changestatus(id) {
    
  }
  function formsubmit(){
    var formdata = $('form').serialize();
    $.ajax({
      type: 'post',
      datatype: 'json',
      data: formdata,
      url: "{{ url('admin/type/updatetypes') }}"
    })
    .done(function(data){
      if(data.success){
        $('.alert-success').show();
      } else {
        $('.alert-danger').find('span').html(data.message);
        $('.alert-danger').show();
      }
      hidealert();
    })
    .fail(function(data){
      alert("error!");
    });
  }
</script>
<!-- /.col-md-12 -->
@endsection
