
                <!-- BEGIN SIDE NAVIGATION -->
                <nav class="navbar-side" role="navigation">
                    <div class="navbar-collapse sidebar-collapse collapse">
                        <ul id="side" class="nav navbar-nav side-nav">
                            <!-- BEGIN SIDE NAV MENU -->
                            <li>
                                <a href="{{url('/admin/dashboard')}}">
                                    <i class="fa fa-dashboard"></i> Dashboard
                                </a>
                            </li>
                            <!-- BEGIN COMPONENTS DROPDOWN -->
                            <li class="panel">
                                <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#productManagement">
                                    <i class="fa fa-users"></i> Quản lý người dùng <span class="fa arrow"></span>
                                </a>
                                <ul class="collapse nav" id="">
                                    <li>
                                        <a href="{{url('/admin/administrator')}}">
                                            <i class="fa fa-angle-double-right"></i> Quản trị
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{url('/admin/member')}}">
                                            <i class="fa fa-angle-double-right"></i> Thành viên
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="panel">
                                <a href="javascript:;" data-parent="#side" data-toggle="collapse" class="accordion-toggle" data-target="#something">
                                    <i class="fa fa-file-excel-o"></i> Quản lý đơn hàng <span class="fa arrow"></span>
                                </a>
                                <ul class="collapse nav">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-angle-double-right"></i> Đơn hàng
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul><!-- /.side-nav -->
                        <div class="sidebar-labels">
                            <h4>Khác</h4>
                            <ul>
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#change-password"><i class="fa fa-lock text-success" data-id="{{Auth::guard('admin')->user()->id}}"></i> Đổi mật khẩu</a></li>

                                <li><a href="{{url('/admin/appConfig')}}"><i class="fa fa-gear fa-spin text-primary"></i> Cài đặt App</a></li>
                            </ul>
                        </div>
                    </div><!-- /.navbar-collapse -->
                </nav><!-- /.navbar-side -->
                <div class="modal fade modal-scroll" id="change-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Đổi mật khẩu</h4>
                            </div>
                            <div class="modal-body padding-2x">
                                <form role="form" method="post" id="form-change-password" action="javascript:void(0)" enctype="multipart/form-data">
                                    <div id="error"></div>
                                    <div class="alert alert-class bg-success" id="message-success" style="display: none">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <p>Đổi mật khẩu thành công</p>
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu cũ </label> (<span class="red">*</span>)
                                        <input type="password" class="form-control" name="current-password" id="current-password" placeholder="Nhập mật khẩu cũ">
                                    </div>
                                    <div class="form-group">
                                        <label>Mật khẩu mới </label> (<span class="red">*</span>)
                                        <input type="password" class="form-control" name="new-password" id="new-password" placeholder="Nhập mật khẩu mới">
                                    </div>
                                    <div class="form-group">
                                        <label>Xác nhận mật khẩu </label> (<span class="red">*</span>)
                                        <input type="password" class="form-control" name="confirm-password" id="confirm-password" placeholder="Xác nhận mật khẩu">
                                    </div>
                                    <div class="form-actions no-padding-bottom">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary" id="admin-change-password" data-id="{{Auth::guard('admin')->user()->id}}"><i class="fa fa-floppy-o" title="Save"></i> Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <!-- END SIDE NAVIGATION -->
                <!-- BEGIN MAIN PAGE CONTENT -->
                <div id="page-wrapper">
