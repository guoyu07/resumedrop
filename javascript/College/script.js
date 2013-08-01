$(window).load(function() {
    college = new College;
    college.init();
});

College = function() {
    this.init = function() {
        if ($('#college-id').val() !== 0) {
            var college_title = 'Update college';
        } else {
            var college_title = 'Create college';
        }
        $('#new-college').click(function() {
            $('#create-college').dialog({
                modal: true,
                title: college_title
            });
        });

        $('#create-college form').submit(function() {
            var college_name = $('input#college-name').val();
            if (college_name.length == 0) {
                return false;
            }
        });
    };
};