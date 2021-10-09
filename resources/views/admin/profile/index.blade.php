@extends('admin.layout')
@section('content')
    <!-- BEGIN PAGE HEADING ROW -->
    <div class="row">
        <div class="col-lg-12">
            <!-- BEGIN BREADCRUMB -->
            <div class="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{url('/admin/dashboard')}}">Dashboard</a>
                    </li>
                    <li class="active">My profile</li>
                </ul>

            </div>
            <!-- END BREADCRUMB -->

            <div class="page-header title">
                <!-- PAGE TITLE ROW -->
                <h1>Trang cá nhân <span class="sub-title"></span></h1>
            </div>


        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
    <!-- END PAGE HEADING ROW -->
    <div class="row space-2x">
        <div class="col-lg-12">
            <div class="portlet">
                <div class="portlet-heading dark">
                    <div class="portlet-title">
                        <h4>Cập nhật thông tin cá nhân</h4>
                    </div>
                    <div class="portlet-widgets">
                        <a data-toggle="collapse" data-parent="#accordion" href="#ft-9"><i class="fa fa-chevron-down"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div id="ft-9" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <form action="{{url('/admin/updateProfile')}}" role="form" method="post">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="form-field-mask-2">Họ tên (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input name="name" type="text" placeholder="Họ tên" class="form-control" value="{{ $info->name }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="form-field-mask-2">Email (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                            <input name="email" type="text" placeholder="Email" class="form-control" value="{{ $info->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label>Giới tính</label>
                                        <select name="gender" class="form-control selectpicker">
                                            <option value="1" {{ $info->gender == 1 ? 'selected' : '' }}>Nam</option>
                                            <option value="2" {{ $info->gender == 2 ? 'selected' : '' }}>Nữ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-success" type="submit" title="Cập nhật"><i class="fa fa-floppy-o"></i> CẬP NHẬT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    @if(\Session::has('message'))
    $.gritter.add({
        title: "{{\Session::get('message')}}",
        //text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //image: "assets/images/user-profile-1.jpg",
        class_name: "bg-success",
        sticky: false
    });
    @endif
    @if(\Session::has('error'))
    $.gritter.add({
        title: "{{\Session::get('error')}}",
        //text: "Lorem ipsum dolor sit amet, consectetur adipiscing elit.",
        //image: "assets/images/user-profile-1.jpg",
        class_name: "bg-danger",
        sticky: false
    });
    @endif
</script>
@stop
