@extends('admin.layout')
@section('content')
    <!-- BEGIN PAGE HEADING ROW -->
    <div class="row">
        <div class="col-lg-12">
            <!-- BEGIN BREADCRUMB -->
            <div class="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li><a href="#">Danh sách nhân viên</a></li>
                    <li class="active">Quản lý nhân viên</li>
                </ul>
            </div>
            <!-- END BREADCRUMB -->

            <div class="page-header title">
                <!-- PAGE TITLE ROW -->
                <h1>Quản lý nhân viên <span class="sub-title">Danh sách nhân viên</span></h1>
            </div>

        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
    <!-- END PAGE HEADING ROW -->
    <div class="row space-2x">
        <div class="col-lg-12">
            <!-- START YOUR CONTENT HERE -->
            <div class="portlet">
                <div class="portlet-heading inverse">
                    <div class="portlet-title">
                        <h4><i class="fa fa-edit"></i> Quản lý nhân viên</h4>
                    </div>
                    <div class="portlet-widgets">
                        <a href="#" class="tooltip-primary" data-placement="left" data-rel="tooltip" title="" data-original-title="Thêm nhân viên" data-toggle="modal" data-target="#add-user"><i class="fa fa-plus"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-body no-padding-top no-padding-bottom">
                    <table id="table-users" class="datatable table table-hover table-striped table-bordered tc-table">
                        <thead>
                        <tr>
                            <th data-hide="phone,tablet">STT</th>
                            <th data-class="expand">Họ tên</th>
                            <th data-hide="phone,tablet">Email</th>
                            <th data-hide="phone,tablet">Trạng thái</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END YOUR CONTENT HERE -->
        </div>
    </div>
    <!-- Add shop Address Modal -->
    <div class="modal fade modal-scroll" id="add-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Thêm quản trị</h4>
                </div>
                <div class="modal-body padding-2x">
                    <form role="form" method="post" id="form_user_add" action="javascript:void(0)" enctype="multipart/form-data">
                        <div id="error_add"></div>
                        <div class="form-group">
                            <label>Họ và Tên </label> (<span class="red">*</span>)
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label>Email </label> (<span class="red">*</span>)
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu </label> (<span class="red">*</span>)
                            <input type="password" class="form-control" name="password" id="password">
                        </div>
                        <div class="form-group">
                            <label>Giới tính</label> (<span class="red">*</span>)
                            <select class="form-control" name="gender" id="gender">
                                <option value="">Chọn giới tính</option>
                                <option value="1">Nam</option>
                                <option value="2">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                        <div class="form-actions no-padding-bottom">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary" id="button_save_add"><i class="fa fa-floppy-o" title="Save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit shop Address Modal -->
    <div class="modal fade modal-scroll" id="edit-Record" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Sửa quản trị</h4>
                </div>
                <div class="modal-body padding-2x">
                    <form role="form" method="post" id="form_user_edit" action="javascript:void(0)" enctype="multipart/form-data">
                        <div id="error_edit"></div>
                        <div class="form-group">
                            <label>Họ và Tên </label> (<span class="red">*</span>)
                            <input type="text" class="form-control" name="name_edit" id="name_edit">
                        </div>
                        <div class="form-group">
                            <label>Email </label> (<span class="red">*</span>)
                            <input type="email" class="form-control" name="email_edit" id="email_edit">
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu </label>
                            <input type="password" class="form-control" name="password_edit">
                        </div>
                        <div class="form-group">
                            <label>Giới tính</label> (<span class="red">*</span>)
                            <select class="form-control" name="gender_edit" id="gender_edit">
                                <option value="">Chọn giới tính</option>
                                <option value="1">Nam</option>
                                <option value="2">Nữ</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="status_edit" id="status_edit">
                                <option value="1">Active</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                        <div class="form-actions no-padding-bottom">
                            <div class="btn-group">
                                <input type="hidden" class="form-control" name="id" id="id_edit">
                                <button type="submit" class="btn btn-primary" id="button_save_edit"><i class="fa fa-floppy-o" title="Save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection

@section('scripts')
    <script>
        var responsiveHelper = undefined;
        var breakpointDefinition = {
            tablet: 1024,
            phone : 480
        };
        var tElement = $('#table-users');
        var table = $('#table-users').DataTable({
            "processing": true,
            "language": {
                "processing": "Đang xử lý",
                "search": "Tìm kiếm",
                "emptyTable": "Không tìm thấy bản ghi",
                "sLengthMenu":    "Hiển thị _MENU_ bản ghi trên 1 trang",
            },

            "serverSide": true,
            "ajax":{
                "url": "{{ url('admin/getDataUserAdministrator') }}",
                "dataType": "json",
                "type": "post",
                "data":{ _token: "{{csrf_token()}}" }
            },
            "columnDefs": [
                {
                    "targets": 0,
                    "orderable": false,//disable column 6 sorting
                },
                {
                    "targets": 3,
                    "orderable": false,//disable column 4 sorting
                },
                {
                    "targets": 4,
                    "orderable": false,//disable column 5 sorting
                }
            ],
            "columns": [
                { "data": "index" },
                { "data": "name" },
                { "data": "email" },
                { "data" : "status" },
                { "data" : "options" }
            ],
            "autoWidth": false,
            preDrawCallback: function () {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper) {
                    responsiveHelper = new ResponsiveDatatablesHelper(tElement, breakpointDefinition);
                }
            },
            rowCallback: function (nRow) {
                responsiveHelper.createExpandIcon(nRow);
            },
            drawCallback: function (oSettings) {
                responsiveHelper.respond();
            }
        });
    </script>

    <script>
        // Thêm mới user
        $('#button_save_add').on('click', function () {
            var table = $('#table-users').DataTable();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#button_save_add').html('Đang xử lý..');
            $.ajax({
                url: '/admin/saveAdministrator' ,
                type: "POST",
                data: $('#form_user_add').serialize(),
                success: function(data) {
                    if (data.errors) {
                        $('#button_save_add').html('<i class="fa fa-floppy-o"></i> Save');
                        $.each(data.errors, function(key, value){
                            $('#error_add').html('<div class="alert alert-danger forgotPasswordError"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + value + '</div>');
                        });
                    } else {
                        $('#button_save_add').attr("disabled", true);
                        $("#form_user_add").trigger("reset");
                        $('#button_save_add').attr("disabled", false);
                        $('#button_save_add').html('<i class="fa fa-floppy-o"></i> Save');
                        $('#add-user').modal('hide');
                        table.draw();
                        $.gritter.add({
                            title: data.message,
                            class_name: "bg-success",
                            sticky: false
                        })
                    }
                }
            });
        });
        // Get data modal when edit
        $("body").on("click",".editRecord",function(){

            var id_edit = $(this).data('id');
            var url_edit = $(this).data('url');
            var method_edit = $(this).data('method');
            console.log(id_edit)
            $.ajax({
                type : method_edit,
                url  : url_edit,
                data : {id: id_edit},
                success:function(data){
                    console.log(data);
                    $('#name_edit').val(data.data.name);
                    $('#email_edit').val(data.data.email);
                    $('[name="status_edit"] > option[value="' + data.data.status + '"]').prop("selected", true);
                    $('[name="gender_edit"] > option[value="' + data.data.gender + '"]').prop("selected", true);
                    $('#id_edit').val(data.data.id);
                }
            });
        });

        // update data for user
        $('#button_save_edit').on('click', function () {
            var table = $('#table-users').DataTable();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#button_save_edit').html('Đang xử lý..');
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/updateAdministrator/' + id ,
                type: "POST",
                data: $('#form_user_edit').serialize(),
                success: function(data) {
                    console.log(data);
                    if (data.errors_edit) {
                        $('#button_save_edit').html('Save');
                        $.each(data.errors_edit, function(key, value){
                            $('#error_edit').html('<div class="alert alert-danger forgotPasswordError"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + value + '</div>');
                        });
                    } else {
                        $('#button_save_edit').attr("disabled", true);
                        $("#form_user_edit").trigger("reset");
                        $('#button_save_edit').attr("disabled", false);
                        $('#button_save_edit').html('Save');
                        $('#edit-Record').modal('hide');
                        table.draw();
                        $.gritter.add({
                            title: data.message,
                            class_name: "bg-success",
                            sticky: false
                        })
                    }
                }
            });
        });

    </script>
@stop
