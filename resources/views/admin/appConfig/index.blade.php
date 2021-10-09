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
                    <li class="active">Cài đặt App</li>
                </ul>

            </div>
            <!-- END BREADCRUMB -->

            <div class="page-header title">
                <!-- PAGE TITLE ROW -->
                <h1>Quản lý cài đặt <span class="sub-title">Cài đặt App</span></h1>
            </div>


        </div><!-- /.col-lg-12 -->
    </div><!-- /.row -->
    <!-- END PAGE HEADING ROW -->
    <div class="row space-2x">
        <div class="col-lg-12">
            <div class="portlet">
                <div class="portlet-heading dark">
                    <div class="portlet-title">
                        <h4>CÀI ĐẶT TỔNG QUAN</h4>
                    </div>
                    <div class="portlet-widgets">
                        <a data-toggle="collapse" data-parent="#accordion" href="#ft-9"><i class="fa fa-chevron-down"></i></a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="ft-9" class="panel-collapse collapse in">
                    <div class="portlet-body">
                        <form action="{{ url('/admin/updateAppConfig') }}" role="form" method="post">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="form-field-mask-2">Điều khoản sử dụng</label>
                                        <textarea rows="10" name="use_of_terms" id="use_of_terms" placeholder="" class="form-control">{{$use_of_terms != '' ? $use_of_terms : ''}}</textarea>
                                    </div>
                                </div>
                                <hr class="separator">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label>demo (<span class="text-danger">*</span>)</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label>demo</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="form-group">
                                        <label>demo</label>
                                        <input class="form-control" type="text">
                                    </div>
                                </div>
                                <hr class="separator">
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-success" type="submit" title="Cập nhật"><i class="fa fa-floppy-o"></i> CẬP NHẬT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
{{--                <div id="ft-9" class="panel-collapse collapse in">--}}
{{--                    <div class="portlet-body">--}}
{{--                        <form class="form-horizontal" action="{{url('/admin/updateAppConfig')}}" role="form" method="post">--}}
{{--                            {!! csrf_field() !!}--}}
{{--                            <div class="form-group">--}}
{{--                                <label class="col-sm-2 control-label text-left">Điều khoản sử dụng</label>--}}
{{--                                <div class="col-sm-10">--}}
{{--                                    <textarea rows="10" name="use_of_terms" id="use_of_terms" placeholder="" class="form-control">{{$use_of_terms != '' ? $use_of_terms : ''}}</textarea>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <hr class="separator">--}}
{{--                            <div class="form-group">--}}
{{--                                <div class="col-sm-2">--}}
{{--                                </div>--}}
{{--                                <div class="col-sm-10 text-right">--}}
{{--                                    <button class="btn btn-success" type="submit" title="Lưu lại"><i class="fa fa-floppy-o"></i> LƯU LẠI</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{\URL::asset('assets/ckeditor4/ckeditor.js')}}"></script>
    <script src="{{\URL::asset('vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script src="{{ \URL::asset('assets/js/simple.money.format.js')}}"></script>
    <script>
        var domain = "{{ url('/admin/FileManager')  }}";
        $('#btnAddBrowser').filemanager('image', {prefix: domain});
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
            //image: "assets/images/user-profile-1.jpg",
            class_name: "bg-danger",
            sticky: false
        });
        @endif
        CKEDITOR.replace('use_of_terms', {
            filebrowserImageBrowseUrl: domain+'?type=Images',
            filebrowserBrowseUrl: domain+'?type=Files'
        });
    </script>
@stop
