//single button filemanager
(function( $ ){

  $.fn.filemanager = function(type, options) {
    type = type || 'file';

    this.on('click', function(e) {
        var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';

        localStorage.setItem('target_input', $(this).data('input'));
        localStorage.setItem('target_preview', $(this).data('preview'));
        localStorage.setItem('target_filename', $(this).data('filename'));
        localStorage.setItem('target_file_link', $(this).data('file-link'));
        localStorage.setItem('target_file_link_e2', $(this).data('file-link-e2'));
        window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
        window.SetUrl = function (url, file_path) {

            //set the value of the desired input to image url
            var target_input = $('#' + localStorage.getItem('target_input'));
            target_input.val(file_path).trigger('change');

            //set or change the preview image src
            var target_preview = $('#' + localStorage.getItem('target_preview'));
            target_preview.attr('src', url).trigger('change');
            target_preview.attr('style','display:block');

            //get file name
            var filename = file_path.split("/").slice(-1).pop();

            //set or change the filename input
            var target_filename = $('#' + localStorage.getItem('target_filename'));
            target_filename.val(filename).trigger('change');

            //set or change the file_link input
            var target_file_link = $('#' + localStorage.getItem('target_file_link'));
            target_file_link.val(url).trigger('change');

            //set or change the file_link input
            var target_file_link_e2 = $('.' + localStorage.getItem('target_file_link_e2'));
            target_file_link_e2.val(url).trigger('change');
      };
      return false;
    });
  }

})(jQuery);
$(document).ready(function(){
    $('body').on('click', '.removeRecord', function (event) {
        event.preventDefault();
        var deleteTitle = $(this).data('delete-title');
        if (confirm('Bạn chắc chắn muốn xóa ' + deleteTitle)) {
            var deleteMethod = $(this).data('method');// request method POST or DELETE ,...
            var urlDelete = $(this).data('url');// request URL
            var idDelete = $(this).data('id'); // id need remove
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: urlDelete+'/'+idDelete,
                type: deleteMethod,
                success: function(response) {
                    console.log(response);
                    if (response.status !== 1) {
                        $.gritter.add({
                            title: response.message,
                            class_name: "bg-danger",
                            sticky: false
                        });
                    } else {
                        $.gritter.add({
                            title: response.message,
                            class_name: "bg-success",
                            sticky: false
                        });
                        if(typeof table !== 'undefined' && table !== null) {
                            table.draw();
                        }
                    }
                }
            });
        }
    });
});
///active menu item by current url
$(function() {
    var CurrentUrl= document.URL;
    var CurrentUrlEnd = CurrentUrl.split('/').filter(Boolean).pop();
    $("#side li a" ).each(function() {
        var ThisUrl = $(this).attr('href');
        var ThisUrlEnd = ThisUrl.split('/').filter(Boolean).pop();
        if(ThisUrlEnd === CurrentUrlEnd) {
            $(this).addClass('active');
            $(this).parents('li.panel').addClass('open');
        }
    });
});
// active, disable status
$("body").on("click",".btnChangeStatus",function(e){
    e.preventDefault();
    if (!confirm('Bạn chắc chắn về điều này?')) {
        return false;
    }
    var rowIndex = $(this).data('index');
    var table_name = $(this).data('template');
    var table = $(table_name).DataTable();
    var url = $(this).data('url');
    var id = $(this).data("id");
    var status = $(this).data("status");
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: url,
        type: 'POST',
        dataType:'json',
        data: {id: id, status: status},
        success: function (data) {
            table.row(rowIndex).draw(false);
            if ($('#status .btnChangeStatus').length) {
                if (data.status == 1) {

                }
            }
        }
    });
});
function formatMoney(amount, decimalCount = 0, decimal = ".", thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }
}
$('.selectpicker').selectpicker('show');
