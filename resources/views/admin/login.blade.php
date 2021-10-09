<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Đăng Nhập Quản Trị - {{config('app.name')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta id="token" name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{\URL::asset('assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{\URL::asset('assets/css/fonts.css')}}">
    <link rel="stylesheet" href="{{\URL::asset('assets/font-awesome/css/font-awesome.min.css')}}">
    <!-- PAGE LEVEL PLUGINS STYLES -->
    <!-- REQUIRE FOR SPEECH COMMANDS -->
    <link rel="stylesheet" type="text/css" href="{{\URL::asset('assets/css/plugins/gritter/jquery.gritter.css')}}" />  
    <!-- Tc core CSS -->
    <link id="qstyle" rel="stylesheet" href="{{\URL::asset('assets/css/themes/style.css')}}">
    <link rel="stylesheet" type="text/css" href="{{\URL::asset('assets/css/custom.css')}}" />  
    <!-- Add custom CSS here -->
    <!-- End custom CSS here -->
    <!--[if lt IE 9]>
    <script src="{{\URL::asset('assets/js/html5shiv.js')}}"></script>
    <script src="{{\URL::asset('assets/js/respond.min.js')}}"></script>
    <![endif]-->
  </head>
  <body class="login">
    <div id="wrapper">
        <!-- BEGIN MAIN PAGE CONTENT -->
        <div class="login-container">
            <h2>
                <a href="#"><img src="{{\URL::asset('assets/images/logo.png')}}" alt="logo" class="img-responsive"></a><!-- can use your logo-->
            </h2>
            <!-- BEGIN LOGIN BOX -->
            <div id="login-box" class="login-box visible">                  
                <p class="bigger-110">
                    <i class="fa fa-key"></i> Vui lòng nhập thông tin đăng nhập
                </p>
                <div class="hr hr-8 hr-double dotted"></div>
                @include('errors.errorlist')
                <div class="_alert_wrapper-login"></div>
                <form method="post" action="{{url('/admin/checkLogin')}}">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <div class="input-icon right">
                            <span class="fa fa-envelope text-gray"></span>
                            <input type="text" name="email" class="form-control" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-icon right">
                            <span class="fa fa-lock text-gray"></span>
                            <input type="password" name="password" class="form-control" placeholder="Mật khẩu">
                        </div>
                    </div>
                    <div class="tcb">
                        <label>
                            <input type="checkbox" name="remember" class="tc">
                            <span class="labels"> Nhớ mật khẩu</span>
                        </label>
                        <button type="submit" class="pull-right btn btn-primary">Đăng nhập<i class="fa fa-key icon-on-right"></i></button>
                        <div class="clearfix"></div>
                    </div>              
                    <div class="footer-wrap">
                        <span class="pull-left">
                            <a href="#" onclick="show_box('forgot-box'); return false;"><i class="fa fa-angle-double-left"></i> Quên mật khẩu?</a>
                        </span>
                        <div class="clearfix"></div>
                    </div>                          
                </form>
            </div>
            <!-- END LOGIN BOX -->
            
            <!-- BEGIN FORGOT BOX -->
            <div id="forgot-box" class="login-box">             
                <p class="bigger-110">
                    <i class="fa fa-key"></i> Lấy Lại Mật Khẩu
                </p>
                
                <div class="hr hr-8 hr-double dotted"></div>
                <div class="_alert_wrapper"></div>
                <form method="post" action="#">
                    <div class="form-group">
                        <div class="input-icon right">
                            <span class="fa fa-envelope text-gray"></span>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                            <span class="help-block">Nhập Email cần khôi phục mật khẩu</span>
                        </div>
                    </div>
                    <button type="button" id="btnSendForgot" class="btn pull-right btn-danger">Gửi đi</button>
                    
                    <div class="clearfix"></div>
                    
                    <div class="footer-wrap">
                        <a href="#" onclick="show_box('login-box'); return false;">Quay lại đăng nhập <i class="fa fa-angle-double-right"></i></a>
                    </div>                          
                </form>                 
            </div>
            <!-- END FORGOT BOX -->
        </div>
        <!-- END MAIN PAGE CONTENT --> 
    </div> 
    <!-- core JavaScript -->
    <script src="{{\URL::asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/pace/pace.min.js')}}"></script>
    <!-- PAGE LEVEL PLUGINS JS -->
    <!-- Themes Core Scripts -->    
    <script src="{{\URL::asset('assets/js/main.js')}}"></script>
    <!-- REQUIRE FOR SPEECH COMMANDS -->
    <script src="{{\URL::asset('assets/js/speech-commands.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/gritter/jquery.gritter.min.js')}}"></script> 
    <!-- initial page level scripts for examples -->    
    <script type="text/javascript">
        function show_box(id) {
            jQuery('.login-box.visible').removeClass('visible');
            jQuery('#'+id).addClass('visible');
        }
        $('body').on('click', '#btnSendForgot', function(e){
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
            var email = form.find('[name="email"]').val();
            btn.html('<i class="fa fa-spinner fa-spin"></i> Đang gửi');
            btn.attr('disabled', true);
            $.ajax({
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: '{{url('/admin/sendForgot')}}',
                data: {
                    email: email
                },

                dataType: 'json',
                success: function(response) {
                    setTimeout(function() {
                        btn.attr('disabled', false);
                        btn.find('.fa-spinner').remove();
                        form.find('[name="email"]').val('');
                        btn.text('Gửi đi');
                        if (response.status == 0) {
                            $('._alert_wrapper').html('<div class="alert bg-danger">\n<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n<p>'+response.message+'</p>\n</div>');
                        }
                        if (response.status == 200) {
                            $('#login-box').find('.alert').remove();
                            $('._alert_wrapper-login').html('<div class="alert bg-success">\n<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n<p>'+response.message+'</p>\n</div>');
                            $('#login-box').addClass('visible');
                            $('#forgot-box').removeClass('visible');
                        }
                        
                    }, 500);
                }
            });
        });
    </script>
  </body>
</html>