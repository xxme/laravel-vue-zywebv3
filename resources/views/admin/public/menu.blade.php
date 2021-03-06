<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li>
        <a><span>Powered by wjx</span></a>
      </li>
      <li @if(Request::is('admin') || Request::is('admin/event/*')) class="active" @endif><a href="{{ url('/admin') }}"><i class="fa fa-calendar"></i> <span>{{ __('messages.events') }}</span></a></li>
      <li class="treeview @if(Request::is('admin/type/*')) active @endif">
        <a href="#">
          <i class="fa fa-book"></i> 
          <span>{{ __('messages.typemanage') }}</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @if($typegroups)
          @foreach ($typegroups as $typegroup)
            <li @if(Request::is('admin/type/index/'.$typegroup->id) || Request::is('admin/type/create/'.$typegroup->id)) class="active" @endif><a href="{{ url('admin/type/index/'.$typegroup->id) }}"><i class="fa fa-angle-double-right"></i> {{ $typegroup->name }}</a></li>
          @endforeach
          @endif
        </ul>
      </li>
      <li class="treeview @if(Request::is('admin/user/*')) active @endif">
        <a href="#">
          <i class="fa fa-users"></i> 
          <span>{{ __('messages.usermanage') }}</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          @if($usergroups)
          @foreach ($usergroups as $usergroup)
            <li @if(Request::is('admin/user/index/'.$usergroup->id) || Request::is('admin/user/create/'.$usergroup->id)) class="active" @endif><a href="{{ url('admin/user/index/'.$usergroup->id) }}"><i class="fa fa-angle-double-right"></i> {{ $usergroup->name }}</a></li>
          @endforeach
          @endif
        </ul>
      </li>
      <li>
        <a href="{{ url('admin/productlist') }}">
          <i class="fa fa-shopping-cart"></i> 
          <span>{{ __('messages.menushoppinglist') }}</span>
          <span class="pull-right-container">
            <small class="label pull-right bg-green">{{ $countproductlist }}</small>
          </span>
        </a>
      </li>
      <li>
        <a href="{{ url('admin/estimate') }}">
          <i class="fa fa-comments-o"></i> 
          <span>{{ __('messages.estimateslist') }}</span>
          <span class="pull-right-container">
            <small class="label pull-right label-primary">{{ $countoffers }}</small>
          </span>
        </a>
      </li>
      <li>
        <a href="{{ url('admin/logs') }}">
          <i class="fa fa-globe"></i> 
          <span>{{ __('messages.timeline') }}</span>
        </a>
      </li>
      <li>
        <a href="https://shop.koyoshieki.com/wp-admin/edit.php?post_type=product" target="_blank">
          <i class="fa fa-heart"></i> 
          <span>{{ __('messages.onlineshop') }}</span>
        </a>
      </li>
      <!-- <li class="header">LABELS</li>
      <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
      <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li> -->
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>