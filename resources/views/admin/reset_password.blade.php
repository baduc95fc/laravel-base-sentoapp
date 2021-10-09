<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Đặt Lại Mật Khẩu - {{config('app.name')}}</title>
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
                    <i class="fa fa-key"></i> Đặt lại mật khẩu
                </p>
                <div class="hr hr-8 hr-double dotted"></div>
                @include('errors.errorlist')
                <div class="_alert_wrapper"></div>
                <form method="post" action="{{url('/admin/resetPassword')}}">
                    {!!csrf_field()!!}
                    <div class="form-group">
                        <div class="input-icon right">
                            <span class="fa fa-lock text-gray"></span>
                            <input type="password" name="resetPassword" class="form-control" placeholder="Mật khẩu mới">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-icon right">
                            <span class="fa fa-lock text-gray"></span>
                            <input type="password" name="confirmResetPassword" class="form-control" placeholder="Xác nhận mật khẩu mới">
                        </div>
                    </div>   
                    <div class="tcb">
                        <button type="submit" id="btnResetPassword" class="pull-right btn btn-primary">Xác nhận</button>
                        <div class="clearfix"></div>
                    </div>               
                          
                </form>
            </div>
            <!-- END LOGIN BOX -->
            
            
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
        var GetURLParameter = function(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++) {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam) {
                    return sParameterName[1];
                }
            }
        }
        $('body').on('click', '#btnResetPassword', function(e){
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
            var resetPassword = form.find('[name="resetPassword"]').val();
            var confirmResetPassword = form.find('[name="confirmResetPassword"]').val();
            var token_password = GetURLParameter('token');
            var email = GetURLParameter('email');
            btn.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý');
            btn.attr('disabled', true);
            $.ajax({
                headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "POST",
                url: '{{url('/admin/resetPassword')}}'+'?email='+email+'&token='+token_password,
                data: {
                    resetPassword: resetPassword,
                    confirmResetPassword: confirmResetPassword
                },
                dataType: 'json',
                success: function(response) {
                    setTimeout(function() {
                        btn.attr('disabled', false);
                        btn.find('.fa-spinner').remove();
                        form.find('[name="resetPassword"]').val('');
                        form.find('[name="confirmResetPassword"]').val('');
                        btn.text('Xác nhận');
                        if (response.status == 0) {
                            $('._alert_wrapper').html('<div class="alert bg-danger">\n<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>\n<p>'+response.message+'</p>\n</div>');
                        }
                        if (response.status == 200) {
                            window.location.href = response.redirect_link;
                        }
                        
                    }, 500);
                }
            });
        });
    </script>
  </body>
</html>