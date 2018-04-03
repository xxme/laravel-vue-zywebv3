@extends('../layouts.adminnovue')

@section('pageTitle', $pageTitle)
@section('pageSubTitle', $subTitle)

@section('content')
<!-- col-md-12 -->
<div>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('messages.create') }}</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="POST" action="{{ url('admin/type/create_do') }}">
            {{ csrf_field() }}
            <input type="hidden" name="group_id" value="{{ $group_id }}" />
            <div class="box-body">
                <div class="form-group">
                    <label for="name">{{ __('messages.name') }}</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter name">
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<!-- /.col-md-12 -->
@endsection
