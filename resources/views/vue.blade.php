@extends('../layouts.public')

@section('content')
<link rel="stylesheet" href="/dist/css/AdminLTE.min.css">
<div id="app">
    <router-view></router-view>
</div>
<script src="{{ mix('js/app.js') }}"></script>
@endsection
