
// update data for user
$('#admin-change-password').on('click', function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('#admin-change-password').html('Đang xử lý..');
    var id = $(this).data('id');
    $.ajax({
        url: '/admin/changePasswordAdmin/' + id ,
        type: "POST",
        data: $('#form-change-password').serialize(),
        success: function(data) {
            console.log(data);
            if (data.errors_old_pass) {
                $('#admin-change-password').html('<i class="fa fa-floppy-o" title="Save"></i>Save');
                $('#error').html('<div class="alert alert-danger forgotPasswordError"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.errors_old_pass + '</div>');
                return false;
            }
            if (data.errors_confirm_pass) {
                $('#admin-change-password').html('<i class="fa fa-floppy-o" title="Save"></i>Save');
                $('#error').html('<div class="alert alert-danger forgotPasswordError"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data.errors_confirm_pass + '</div>');
                return false;
            }
            if (data.errors) {
                $('#admin-change-password').html('<i class="fa fa-floppy-o" title="Save"></i>Save');
                $.each(data.errors, function(key, value){
                    $('#error').html('<div class="alert alert-danger forgotPasswordError"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + value + '</div>');
                });
            } else {
                $('#admin-change-password').html('<i class="fa fa-floppy-o" title="Save"></i>Save');
                $('#error').hide();
                $('#message-success').fadeIn(500);
                setTimeout(function(){
                    $('#message-success').fadeOut(500, function(){
                        location.reload(true);
                    });
                }, 500);
            }
        }
    });
});
