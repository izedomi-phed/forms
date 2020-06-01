<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PHED FORMS') }}</title>


    <!-- Styles -->
    <link href="{{ asset('assets/css/core.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/thesaas.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">


    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">




    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light elevation-2">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->

      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <small>{{ Auth::user()->email }}</small>
        </a>
      </li>
       <li class="nav-item">

        <a class="text-dark nav-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
            <strong>{{ __('Logout') }}</strong>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar bg-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
      <img src="{{ asset('images/phed-logo.jpg') }}" alt="PHED FORMS" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-bold">PHED STAFF</span>
      <!-- <span class="brand-text font-weight-bold">HCM APPRAISAL</span> -->
    </a>

      <hr>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
         <div class="image">
          <img src="https://d3ayyz93zozlya.cloudfront.net/global-images/global/user-placeholder-image-01.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <small><a href="#" class="d-block text-dark">{{ Auth::user()->name }}</a></small>
        </div>
      </div>
      <hr>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <h3 class="mb-3"><strong class="text-white pl-3"><a href="{{route('home')}}">Dashboard</a></strong></h3>
          </li>

           <li class="nav-item has-treeview">
              <a href="#" class="nav-link text-dark">
                <i class="nav-icon fa fa-book"></i>
                <p>
                  Forms
                </p>
              </a>
            </li>


            @if($isAdmin)
            <li class="nav-item has-treeview">
              <a href="{{route('approval-dashboard')}}" class="nav-link text-dark">
              <i class="nav-icon fa fa-book"></i>
                <p>
                  APPROVER ACCOUNT
                </p>
              </a>
            </li>
             @endif

            @if(Auth::user()->name == "READY")
            <li class="nav-item">
              <a href="{{route('profile')}}" class="nav-link text-dark  @if(Route::current()->getName() == 'profile'){{'bg-white'}}@endif">
                <i class="fa fa-user-circle nav-icon"></i>
                <p>Profile</p>
              </a>
            </li>
            @endif

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('msg')
    <!-- Main content -->
    <section class="contents" id="app">
      <div class="container-fluid" style="padding-top:20px">
             @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href=".">PHED APPRAISAL</a>.</strong>
    All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>


<!-- AdminLTE App -->


<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.js') }}"></script>

<script src="{{ asset('assets/js/core.min.js') }}"></script>
<script src="{{ asset('assets/js/thesaas.min.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>



</body>
</html>
