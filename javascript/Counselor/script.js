var counselor = new Counselor;

$(window).load(function() {
    counselor.initialize();
});

function Counselor() {

    this.initialize = function() {
        $('#assign-form').dialog({
            autoOpen: false,
            title: 'Assign counselor',
            width : 500,
            height: 150
        });

        $('#user-id').select2({
            width: 'copy'
        });

        $('#assign-counselor').click(function() {
            $('#assign-form').dialog('open');
        });
    };

}