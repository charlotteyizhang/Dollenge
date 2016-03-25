/**
 * Created by user on 2016/3/17.
 */
$(document).ready(function() {
    function validate(username){
        //$("#userText").html('<img src="ajax-loader.gif" />');
        $.post('../php/validate.php', {'user_name':username}, function(data) {
            $("#userText").html(data);
        });
    }
});