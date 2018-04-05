    $(function() {
        $('#captcha').realperson({chars: $.realperson.alphanumeric, length: 7});
        $('#unsubscribe_captcha').realperson({chars: $.realperson.alphanumeric, length: 7});
    });