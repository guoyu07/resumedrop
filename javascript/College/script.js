var college = new College;
$(window).load(function() {
    college.init();
    Pagers.options({callback: college.initializeRowClick});
});

function College() {
    $this = this;
    this.init = function() {
        $('#create-college').dialog({
            modal: true,
            autoOpen: false
        });

        $('#new-college').click(function() {
            $this.popup();
        });

        $('#create-college form').submit(function() {
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
            var row_id = $(this).data('rowId');
            $('#college-id').val(row_id);
            $('#college-name').val($('.name', this).html());
            college.popup();
        });
    }

    this.popup = function() {
        if ($('#college-id').val() > 0) {
            var college_title = 'Update college';
        } else {
            var college_title = 'Create college';
        }
        $('#create-college').dialog({title: college_title});
        $('#create-college').dialog('open');
    };
};