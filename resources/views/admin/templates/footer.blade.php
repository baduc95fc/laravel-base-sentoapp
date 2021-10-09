                    <!-- BEGIN FOOTER CONTENT -->
                        <div class="footer">
                            <div class="footer-inner">
                                <!-- basics/footer -->
                                <div class="footer-content">
                                    &copy; 2019 <a href="http://sentora.vn">Sentora</a>, All Rights Reserved.
                                </div>
                                <!-- /basics/footer -->
                            </div>
                        </div>
                        <button type="button" id="back-to-top" class="btn btn-primary btn-sm back-to-top">
                            <i class="fa fa-angle-double-up icon-only bigger-110"></i>
                        </button>
                    <!-- END FOOTER CONTENT -->

                </div><!-- /#page-wrapper -->
            <!-- END MAIN PAGE CONTENT -->
        </div>
    </div>
    <!-- core JavaScript -->
    <script src="{{\URL::asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{\URL::asset('assets/js/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/pace/pace.min.js')}}"></script>

    <!-- PAGE LEVEL PLUGINS JS -->
    <script src="{{\URL::asset('assets/js/plugins/footable/footable.min.js')}}"></script>

    <script src="{{\URL::asset('assets/js/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/datatables/datatables.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/datatables/datatables.responsive.js')}}"></script>
    <!-- Themes Core Scripts -->
    <script src="{{\URL::asset('assets/js/main.js')}}"></script>
    <!-- REQUIRE FOR SPEECH COMMANDS -->
    <script src="{{\URL::asset('assets/js/speech-commands.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/gritter/jquery.gritter.min.js')}}"></script>
    <!-- initial page level scripts for examples -->
    <script src="{{\URL::asset('assets/js/plugins/slimscroll/jquery.slimscroll.init.js')}}"></script>
    <script src="{{\URL::asset('assets/js/plugins/datatables/datatables.init.js')}}"></script>
    <script src="{{\URL::asset('assets/js/change_password.js')}}"></script>
    <script src="{{\URL::asset('assets/js/custom.js')}}"></script>
    @yield('scripts')
  </body>
</html>
