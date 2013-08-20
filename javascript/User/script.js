$(window).load(function() {
    $('#success').dialog(
            {
                autoOpen: false,
                modal: true,
                width : 400,
                title: 'Thank you',
                buttons: [{text: "Ok", click: function() {
                            $(this).dialog("close");
                            location.href = location.href.replace(/resumedrop\/?.*/, '');
                        }}]
            });
});

$(function() {
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = 'mod/resumedrop/javascript/fileupload/server/php/';
    $('#fileupload').fileupload({
        url: url,
        dataType: 'json',
        done: function(e, data) {
            if (data.result.files[0].error) {
                alert(data.result.files[0].error);
            } else {
                $('#success').dialog('open');
            }
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                    'width',
                    progress + '%'
                    );
        }
    }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
});