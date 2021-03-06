@extends('../layouts.adminnovue')

@section('pageTitle', $pageTitle)
@section('pageSubTitle', $subTitle)

@section('content')
<!-- col-md-12 -->
<div>
  <div class="box box-primary">
    <!-- form start -->
    <form role="form" method="post" action="{{ url('admin/user/create') }}">
      {{ csrf_field() }}
      <div class="box-body">
        <div class="form-group @if(!empty($errors->first('name'))) has-error @endif">
          <label for="inputName">Name</label>
          <input type="text" class="form-control" name="name" placeholder="Name" value="@if(!empty(old('name'))){{ old('name') }}@else @endif" required>
          <span class="help-block">{{ $errors->first('name') }}</span>
        </div>
        <div class="form-group @if(!empty($errors->first('email'))) has-error @endif">
          <label for="inputEmail1">Email address</label>
          <input type="email" class="form-control" name="email" placeholder="Enter email" value="@if(!empty(old('email'))){{ old('email') }}@else @endif" required>
          <span class="help-block">{{ $errors->first('email') }}</span>
        </div>
        <div class="form-group @if(!empty($errors->first('password'))) has-error @endif">
          <label for="inputPassword1">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" id="inputPassword1" name="password" placeholder="Password">
          </div>
          <span class="help-block">{{ $errors->first('password') }}</span>
        </div>
        @if($currentUser->group_id == 1)
          <div class="form-group">
            <label>Group</label><br />
            @if($usergroups)
            @foreach ($usergroups as $usergroup)
              @if($usergroup->id == 2 || $usergroup->id == 5)
              <label>
                @if($usergroup->id == 2)
                <input type="radio" name="groupid" value="{{ $usergroup->id }}" checked="checked">
                @else
                <input type="radio" name="groupid" value="{{ $usergroup->id }}">
                @endif
                {{ $usergroup->name }} 
              </label>
              @endif
            @endforeach
            @endif
          </div>
        @endif
        <div class="form-group is-hide newprofile">
          <label>New profile photo</label><br />
          <img src="" id="newprofile" />
        </div>
        <div class="form-group">
          <button type="button" class="btn btn-primary btn-sm" onclick="changeProfile()">
            <i class="fa fa-smile-o"></i> Upload profile photo
          </button>

          <p class="help-block">you can upload the .jpg, .jpeg, .png or .gif file.</p>
          <input type="file" id="file" style="display: none" />
          <input type="hidden" name="profileimg" value="" />
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<input type="hidden" name="group_id" value="{{ $group_id }}" />
        <a href="{{ url('admin') }}"><button type="button" class="btn btn-default">{{ __('messages.cancel') }}</button></a>
        <button type="submit" class="btn btn-primary pull-right">{{ __('messages.submit') }}</button>
      </div>
    </form>
  </div>
</div>
<!-- /.col-md-12 -->
<script>
  $(function () {
    $('.eye').on('click', function(){
      var dom = $(this).children('i');
      if(dom.hasClass("fa-eye")){
        dom.removeClass("fa-eye");
        dom.addClass("fa-eye-slash");
        changeInputType("text");
      } else {
        dom.removeClass("fa-eye-slash");
        dom.addClass("fa-eye");
        changeInputType("password");
      }
    });

    @if(old('profileimg'))
        $('#newprofile').attr('src', "{{asset('uploads/profiles/'.old('profileimg'))}}");
        $('input[name="profileimg"]').val("{{ old('profileimg') }}");
        $(".newprofile").show();
    @endif
  });
  function changeInputType(type) {
    var input = $("#inputPassword1");
    $(input).replaceWith($("<input />").val(input.val())
      .attr("placeholder", input.attr("placeholder"))
      .attr("id", "inputPassword1")
      .attr("class", "form-control").attr("type", type));
  }
  
  function changeProfile() {
      $('#file').click();
  }
  $('#file').change(function () {
      if ($(this).val() != '') {
          upload(this);
      }
  });
  function upload(img) {
    var form_data = new FormData();
    form_data.append('file', img.files[0]);
    form_data.append('_token', '{{csrf_token()}}');
    $(".LockOn").show();
    $.ajax({
        url: "{{url('admin/user/uploadprofile')}}",
        data: form_data,
        type: 'POST',
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.fail) {
                alert(data.errors['file']);
            }
            else {
                $('input[name="profileimg"]').val(data);
                $('#newprofile').attr('src', '{{asset('uploads/profiles')}}/' + data);
                $('.newprofile').show();
            }
            $(".LockOn").hide();
        },
        error: function (xhr, status, error) {
            alert(xhr.responseText);
            $(".LockOn").hide();
        }
    });
  }
</script>
@endsection
