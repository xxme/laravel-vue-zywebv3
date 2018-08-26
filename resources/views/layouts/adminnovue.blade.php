<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/AdminLTE.min.css?v=3.1.2">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/dist/css/skins/skin-blue.min.css">
  <!-- admin main -->
  <link rel="stylesheet" href="/css/adminmain.css?v=3.1.2">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <script type="text/javascript">
      window.Laravel = window.Laravel || {};
      window.Laravel.csrfToken = "{{csrf_token()}}";
  </script>
  <script src="{{ asset('/js/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('/js/bootstrap/bootstrap.min.js') }}"></script>
</head>
<body class="skin-blue sidebar-collapse sidebar-mini">
    <div class="wrapper">
      @include('admin.public.menu')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <div class="alert alert-success alert-dismissible is-hide">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-check"></i> {{ __('messages.success') }}</h4>
        </div>
        <div class="alert alert-danger alert-dismissible is-hide">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> {{ __('messages.error') }}</h4>
          <span></span>
        </div>
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            @yield('pageTitle')
            <small>@yield('pageSubTitle')</small>
          </h1>
        </section>
        <!-- Main content -->
        <section class="content" id="app">
          <!-- Main row -->
          @yield('content')
          <!-- /.row (main row) -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- /.control-sidebar -->
      <header class="main-header">
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <span class="version">V3.1.3</span>

          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Notifications: style can be found in dropdown.less -->
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  @if($currentUser->profileimg)
                  <img src="{{ asset("uploads/profiles") }}/{{ $currentUser->profileimg }}" class="user-image" alt="User Image">
                  @else
                  <img src="{{ asset("images") }}/no-image-available.jpeg" class="user-image" alt="User Image">
                  @endif
                  <span class="hidden-xs">{{ $currentUser->name }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="{{ url('/admin/user/edit/'.$currentUser->id) }}" class="btn btn-default btn-flat">{{ __('messages.profile') }}</a>
                    </div>
                    <div class="pull-right">
                      <a href="{{ url('/admin/logout') }}" class="btn btn-default btn-flat">{{ __('messages.signOut') }}</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>K</b>YS</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">{{ config('app.name', 'Laravel') }} 工作中心</span>
        </a>
      </header>
    </div>
    <div class="LockOn"><div class="loadingicon"><i class="fa fa-rocket fa-spin fa-3x"></i> <span>Loading</span></div></div>
    <!-- Fullcalendar -->
    <!-- AdminLTE App -->
    <script src="/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- AdminLTE for demo purposes -->
    <script>
      function hidealert(){
        setTimeout(function(){
          $('.alert').hide();
          $('.alert-danger').find('span').html('');
        },5000);
      }
    </script>
  </body>
</html>
