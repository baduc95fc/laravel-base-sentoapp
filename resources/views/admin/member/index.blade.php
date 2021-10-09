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
                    <li><a href="#">Danh sách thành viên</a></li>
                    <li class="active">Quản lý thành viên</li>
                </ul>
            </div>
            <!-- END BREADCRUMB -->

            <div class="page-header title">
                <!-- PAGE TITLE ROW -->
                <h1>Quản lý thành viên <span class="sub-title">Danh sách thành viên</span></h1>
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
                        <h4><i class="fa fa-edit"></i> Quản lý thành viên</h4>
                    </div>
                    <div class="portlet-widgets">
                        <a href="#" class="tooltip-primary" data-placement="left" data-rel="tooltip" title="" data-original-title="" data-toggle="modal" data-target="#"><i class="fa fa-plus"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="portlet-body no-padding-top no-padding-bottom">
                    <!-- Custom Filter -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-filter"></i> Bộ lọc</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-inline" role="form" id="FilterForm">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control selectpicker" name="status">
                                        <option value="*" selected>Tất cả</option>
                                        <option value="1">Đã kích hoạt</option>
                                        <option value="2">Chưa kích hoạt</option>
                                        <option value="0">Khóa</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table id="table-users" class="datatable table table-hover table-striped table-bordered tc-table">
                        <thead>
                        <tr>
                            <th data-hide="phone,tablet">STT</th>
                            <th data-hide="expand">Email</th>
                            <th data-class="phone,tablet">Họ tên</th>
                            <th data-hide="phone,tablet">Ngày sinh</th>
                            <th data-hide="phone,tablet">Giới tính</th>
                            <th data-hide="phone,tablet">Trạng thái</th>
                            <th data-hide="phone,tablet">Actions</th>
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
    <div class="modal fade modal-scroll" id="viewRecordDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="width: 90%">
            <div class="overLayLoading">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Thông tin chi tiết</h4>
                </div>
                <div class="modal-body padding-2x" id="ModalContent">
                    <!-- START YOUR CONTENT HERE -->
                    <!-- code here -->
                    <!-- END YOUR CONTENT HERE -->
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@endsection

@section('scripts')
    <script>
        var baseURL = '{{ \URL::to('/')  }}'
        var responsiveHelper = undefined;
        var breakpointDefinition = {
            tablet: 1024,
            phone : 480
        };
        var tElement = $('#table-users');
        var table = $('#table-users').DataTable({
            "pageLength": 10,
            "processing": true,
            "language": {
                "processing": "Đang xử lý",
                "search": "Tìm kiếm",
                "emptyTable": "Không tìm thấy bản ghi",
                "sLengthMenu":    "Hiển thị _MENU_ bản ghi trên 1 trang",
            },

            "serverSide": true,
            "ajax":{
                "url": "{{ url('admin/getDataUserMember') }}",
                "dataType": "json",
                "type": "post",
                "data": function (data) {
                    data._token = "{{csrf_token()}}";
                    data.searchStatus = $('[name="status"]').val() ? $('[name="status"]').val() : '*';
                }
            },
            "columnDefs": [
                {
                    "targets": 0,
                    "orderable": false,
                },
                {
                    "targets": 1,
                    "orderable": false,
                },
                {
                    "targets": 2,
                    "orderable": false,
                },
                {
                    "targets": 3,
                    "orderable": false,
                },
                {
                    "targets": 4,
                    "orderable": false,
                },
                {
                    "targets": 5,
                    "orderable": false,
                },
                {
                    "targets": 6,
                    "orderable": false,
                }
            ],
            "columns": [
                { "data": "index" },
                { "data": "email" },
                { "data": "name" },
                { "data": "date_of_birth" },
                { "data": "gender" },
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
        $('[name="status"]').change(function(){
            table.draw();
        });

        // view user detail
        $("body").on("click",".viewRecordDetail", function(){
            var id = $(this).data('id');
            var tr = $(this).closest('tr'); //Find DataTables table row
            var rowIndex = table.row(tr).index();
            $('#viewRecordDetail').attr('data-index', rowIndex);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/viewDetailMember/' + id,
                type: "GET",
                beforeSend: function() {
                    $('#viewRecordDetail').find('.overLayLoading').css('display', 'block');
                },
                success: function(response) {
                    if (response.status == 1) {
                        $('#ModalContent').html('<pre>' + JSON.stringify(response.data, null, 4) + '</pre>');
                        console.log(response);
                        //code here
                    } else {
                        console.log(response);
                        $.gritter.add({
                            title: response.message,
                            class_name: "bg-danger",
                            sticky: false
                        });
                    }
                },
                complete: function() {
                    $('#viewRecordDetail').find('.overLayLoading').css('display', 'none');
                },
            });
        });

        //update status of member
        $('body').on('click', '#btnChangeStatus', function (e) {
            e.preventDefault();
            if (!confirm('Bạn chắc chắn về điều này?')) {
                return false;
            }
            var id = $(this).data('id');
            var status = $(this).data('status');
            var rowIndex = $('#viewRecordDetail');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/updateStatusAdministrator',
                type: "POST",
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
                    if (response.error == false) {
                        $('#status').html(response.data.status == 1 ? '<button id="btnChangeStatus" data-id="' + response.data.id + '" data-status="' + response.data.status + '" class="btn btn-success" title="Bấm để bỏ kích hoạt tài khoản"><i class="fa fa-check"></i> Đã kích hoạt</button>' : (response.data.status == 2 ? '<button id="btnChangeStatus" data-id="' + response.data.id + '" data-status="' + response.data.status + '" class="btn btn-warning" title="Click để kích hoạt tài khoản"><i class="fa fa-spinner"></i> Chưa kích hoạt</button>' : '<button id="btnChangeStatus" data-id="' + response.data.id + '" data-status="' + response.data.status + '" class="btn btn-danger" title="Click để kích hoạt tài khoản"><i class="fa fa-ban"></i> Khóa</button>'));
                        if(typeof table !== 'undefined' && table !== null) {
                            table.row(rowIndex).draw(false);
                        }
                    } else {
                        $.gritter.add({
                            title: response.message,
                            class_name: "bg-danger",
                            sticky: false
                        });
                    }
                },
            })
        });
    </script>
@stop
