$('.counselors').chosen({
    disable_search_threshold: 10,
    no_results_text: "Oops, nothing found!",
    width: '400px'
}
);

var college = new College;
$(window).load(function() {
    college.init();
    Pagers.options({callback: college.initializeRowClick});
});

function College() {
    $this = this;
    this.college_id = 0;

    this.init = function() {
        $('#college-options').dialog({
            modal: true,
            autoOpen: false,
            width: 500,
            height: 300
        });

        $('#new-college').click(function() {
            $this.college_id = 0;
            $this.popup();
        });

        $('#college-options form').submit(function() {
            var college_name = $('input#college-name').val();
            if (college_name.length == 0) {
                return false;
            }
        });

        this.initializeRowClick();
    };

    this.initializeRowClick = function()
    {
        $('.pager-row').click(function() {
            $this.college_id = $(this).data('rowId');
            $('#college-name').val($('.name', this).html());
            college.popup();
        });
    }

    this.popup = function() {
        $('.college-id').val(this.college_id);
        var college_title = 'College options';
        if (this.college_id > 0) {
            $('#save-button').val('Update name');
            $('#other-options').show();
        } else {
            $('#save-button').val('Create college');
            $('#other-options').hide();
        }
        $('#college-options').dialog({title: college_title});
        $('#college-options').dialog('open');
    };

    this.delete = function() {

    };
}
;