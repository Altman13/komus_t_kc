function show() {
    if ($('#dialog').dialog('isOpen') != true) {
        $.ajax({
            url: "calls.php",
            cache: false,
            success: function(text) {
                if (text != '') {
                    $('#phone').text(text);
                    $('#dialog').dialog({
                        modal: true
                    });
                }
            }
        });
    }
}

$(document).ready(function() {
    $('#open_win').click(function() {
        
    });
    show();
    setInterval('show()', 3000);
});
