
//<!-- Toggle password visibility -->

    $(document).ready(function() {
        $("#show-password").change(function(){
            $(this).prop("checked") ?  $("#inputPassword").prop("type", "text") : $("#inputPassword").prop("type", "password");    
        });
    });
