$(document).ready(function() {

    var subscribe_box = document.getElementById('subscribe_box');
    var unsubscribe_box = document.getElementById('unsubscribe_box');
    var success_box = document.getElementById('success_box');
    var error_box = document.getElementById('error_box');
    var success_text = document.getElementById('success_text');
    var error_text = document.getElementById('error_text');

    // Ajax call to pass e-mail address to php
    $('#subscribe_confirm_button').click(function() {
        var email = document.getElementById('subscribe_email_address').value;
        var captcha_entered = document.getElementById('captcha').value;
        var captcha_hash = jQuery("#captcha").realperson('getHash');
        var url = "php/email/subscribe.php";

        $.ajax({
            type: "POST",
            url: url,
            data:
            {
                email: email,
                captcha_entered: captcha_entered,
                captcha_hash: captcha_hash
            },
            success: function(data)
            {
                if (data == "Successfully Subscribed!") {
                    subscribe_box.style.display = "none";
                    success_text.innerHTML = data;
                    success_box.style.display = "block";

                    document.getElementById('subscribe_email_address').value = "";
                    document.getElementById('captcha').value = "";
                }
                if (data == "There was a problem subscribing. Please try again or contact the administrator." || 
                    data == "The E-mail address provided is already being used." ||
                    data == "Please provide an E-mail address." ||
                    data == "Please enter a valid E-mail adress." ||
                    data == "Captcha response is incorrect.") {
                    subscribe_box.style.display = "none";
                    error_text.innerHTML = data;
                    error_box.style.display = "block";

                    // Reset input fields
                    document.getElementById('subscribe_email_address').value = "";
                    document.getElementById('captcha').value = "";
                }
            },
            error: function(jqXHR, exception) {
                alert('ERROR: (' + jqXHR + ')' + " " + exception);
            }
        });

        event.preventDefault(); // avoid to execute the actual submit of the form.
    });

    $('#unsubscribe_confirm_button').click(function() {
        var email = document.getElementById('unsubscribe_email_address').value;
        var captcha_entered = document.getElementById('unsubscribe_captcha').value;
        var captcha_hash = jQuery("#unsubscribe_captcha").realperson('getHash');
        var url = "php/email/unsubscribe.php";

        $.ajax({
            type: "POST",
            url: url,
            data:
            {
                email: email,
                captcha_entered: captcha_entered,
                captcha_hash: captcha_hash
            },
            success: function(data)
            {
                if (data == "Successfully Unsubscribed!") {
                    unsubscribe_box.style.display = "none";
                    success_text.innerHTML = data;
                    success_box.style.display = "block";

                    // Reset input fields
                    document.getElementById('unsubscribe_email_address').value = "";
                    document.getElementById('unsubscribe_captcha').value = "";
                }
                if (data == "There was a problem unsubscribing. Please try again or contact the administrator." || 
                    data == "This E-mail is not subscribed to the newsletter. Check the spelling and try again." ||
                    data == "Please provide an E-mail address." ||
                    data == "Please enter a valid E-mail adress." ||
                    data == "Captcha response is incorrect.") {
                    unsubscribe_box.style.display = "none";
                    error_text.innerHTML = data;
                    error_box.style.display = "block";

                    // Reset input fields
                    document.getElementById('unsubscribe_email_address').value = "";
                    document.getElementById('unsubscribe_captcha').value = "";
                }
            },
            error: function(jqXHR, exception) {
                alert('ERROR: (' + jqXHR + ')' + " " + exception);
            }
        });

        event.preventDefault(); // avoid to execute the actual submit of the form.
    });
});