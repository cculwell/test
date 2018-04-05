var subscribe_box = document.getElementById('subscribe_box');
var unsubscribe_box = document.getElementById('unsubscribe_box');
var success_box = document.getElementById('success_box');
var error_box = document.getElementById('error_box');
var subscribe_button = document.getElementById("subscribe_button");
var subscribe_cancel_button = document.getElementById("subscribe_cancel_button");
var unsubscribe_cancel_button = document.getElementById("unsubscribe_cancel_button");
var unsubscribe_button = document.getElementById('unsubscribe_button');
var ok_button_error = document.getElementById('ok_error');
var ok_button_success = document.getElementById('ok_success');


// When the user clicks on the subscribe button, open the subscribe box 
subscribe_button.onclick = function() {
    subscribe_box.style.display = "block";
}

// When the user clicks on the unsubscribe button, open the unsubscribe box 
unsubscribe_button.onclick = function() {
    subscribe_box.style.display = "none";
    unsubscribe_box.style.display = "block";
}

// When the user clicks the cancel button, exit the subscribe box
subscribe_cancel_button.onclick = function() {
    subscribe_box.style.display = "none";
    document.getElementById('subscribe_email_address').value = "";
    document.getElementById('captcha').value = "";
}

// When the user clicks the cancel button, exit the subscribe box
unsubscribe_cancel_button.onclick = function() {
    unsubscribe_box.style.display = "none";
    document.getElementById('unsubscribe_email_address').value = "";
    document.getElementById('unsubscribe_captcha').value = "";
}

// When the user clicks ok in the error box, close it
ok_button_error.onclick = function(event) {
    error_box.style.display = "none";
}

// When the user clicks ok in the error box, close it
ok_button_success.onclick = function(event) {
    success_box.style.display = "none";
}

// When the user clicks anywhere outside of the subscribe box, close it
window.onclick = function(event) {
    if (event.target == subscribe_box) {
        subscribe_box.style.display = "none";
    }

    if (event.target == unsubscribe_box) {
        unsubscribe_box.style.display = "none";
    }

    if (event.target == success_box) {
        success_box.style.display = "none";
    }
    if (event.target == error_box) {
        error_box.style.display = "none";
    }    
}