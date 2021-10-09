<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>{{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta id="token" name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{\URL::asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{\URL::asset('assets/css/fonts.css')}}">
    <link rel="stylesheet" href="{{\URL::asset('assets/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{\URL::asset('assets/css/plugins/colorBox/colorbox.css')}}" />
    <link rel="stylesheet" href="{{\URL::asset('assets/css/plugins/footable/footable.min.css')}}" />
    <link href="{{\URL::asset('assets/css/plugins/select2/select2.css')}}" rel="stylesheet">
    <link href="{{\URL::asset('assets/css/plugins/bootstrap-select/bootstrap-select.min.css')}}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png')}}">
    <link href="{{\URL::asset('assets/css/datetimepicker.min.css')}}" rel="stylesheet">
    <link href="{{\URL::asset('assets/css/plugins/daterangepicker/daterangepicker-bs3.css')}}" rel="stylesheet">
    <link href="{{\URL::asset('assets/css/plugins/morris/morris.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{\URL::asset('assets/css/plugins/bootstrap-datepicker/datepicker.css')}}">
    <!-- REQUIRE FOR SPEECH COMMANDS -->
    <link rel="stylesheet" type="text/css" href="{{\URL::asset('assets/css/plugins/gritter/jquery.gritter.css')}}" />
    <!-- Tc core CSS -->
    <link id="qstyle" rel="stylesheet" href="{{\URL::asset('assets/css/themes/style.css')}}">
    <!-- Add custom CSS here -->
    <link rel="stylesheet" href="{{\URL::asset('assets/css/only-for-demos.css')}}">
    <link rel="stylesheet" type="text/css" href="{{\URL::asset('assets/css/custom.css')}}" />
    <!-- End custom CSS here -->
    <!--[if lt IE 9]>
    <script src="{{\URL::asset('assets/js/html5shiv.js')}}"></script>
    <script src="{{\URL::asset('assets/js/respond.min.js')}}"></script>
    <![endif]-->
    <!--[if lte IE 8]>
    <script src="{{\URL::asset('assets/js/plugins/easypiechart/easypiechart.ie-fix.js')}}"></script>
    <![endif]-->
  </head>

  <body>
    <div id="wrapper">
        <div id="main-container">
            <!-- BEGIN TOP NAVIGATION -->
                <nav class="navbar-top" role="navigation">
                    <!-- BEGIN BRAND HEADING -->
                    <div class="navbar-header">
{{--                        <button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".top-collapse">--}}
{{--                            <i class="fa fa-bars"></i>--}}
{{--                        </button>--}}
                        <div class="navbar-brand">
                            <a href="{{url('/admin/dashboard')}}">
                                <img src="{{\URL::asset('assets/images/logo.png')}}" alt="logo" class="img-responsive">
                            </a>
                        </div>
                    </div>
                    <!-- END BRAND HEADING -->
                    <div class="nav-top">
                        <!-- BEGIN RIGHT SIDE DROPDOWN BUTTONS -->
                            <ul class="nav navbar-right">
                                <li class="dropdown">
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                </li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bell"></i> <span class="badge up badge-primary">1</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-scroll dropdown-messages">
                                        <li class="dropdown-header">
                                            <i class="fa fa-bell"></i> 1 Đơn hàng mới
                                        </li>
                                        <li id="messageScroll">
                                            <ul class="list-unstyled">

                                                <li>
                                                    <a href="#">
                                                        <div class="row">
                                                            <div class="col-xs-2">
                                                                <img class="img-circle" src="#" width="35" height="35" style="border-radius: 0;" alt="">
                                                            </div>
                                                            <div class="col-xs-10">
                                                                <p>
                                                                    #MDH5452363
                                                                </p>
                                                                <p class="small">
                                                                    <i class="fa fa-clock-o"></i> 12 giờ trước
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>
                                        <li class="dropdown-footer">
                                            <a href="#">
                                                Tất cả đơn hàng
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="dropdown user-box">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <img class="img-circle" src="{{ asset('images/default/user.jpg') }}" alt=""> <span class="user-info"><?php echo isset(Auth::user()->email) ? Auth::user()->email : '';?></span> <b class="caret"></b>
                                    </a>
                                        <ul class="dropdown-menu dropdown-user">
                                            <li>
                                                <a href="{{ url('admin/profile') }}">
                                                    <i class="fa fa-user"></i> My Profile
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{url('/admin/appConfig')}}">
                                                    <i class="fa fa-gear"></i> Settings
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{url('/admin/logout')}}">
                                                    <i class="fa fa-power-off"></i> Logout
                                                </a>
                                            </li>
                                        </ul>
                                </li>
                            </ul>
                        <!-- END RIGHT SIDE DROPDOWN BUTTONS -->

                        <!-- BEGIN TOP MENU -->
                            <div class="collapse navbar-collapse top-collapse">
                                <!-- .nav -->
                                <ul class="nav navbar-left navbar-nav">
                                    <li><a href="{{url('/admin/dashboard')}}">Dashboard</a></li>
                                </ul><!-- /.nav -->
                            </div>
                        <!-- END TOP MENU -->

                    </div><!-- /.nav-top -->
                </nav><!-- /.navbar-top -->
                <!-- END TOP NAVIGATION -->
