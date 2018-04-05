$(document).ready(function () {
    $('#captcha').realperson({chars: $.realperson.alphanumeric, length: 5});
    $('.realperson-challenge').trigger("click");


    var index = 1;
    $('.datepicker').datepicker({minDate: -0});
    $('.timepicker').timepicker({'minTime': '8:00am',
        'maxTime': '11:30pm',
        disableTextInput: true
    });

    $("#add_date_btn").click(function(){

        $('#add_row' + index).html("<td>" + (index + 1)+ "</td>" +
            "<td><input type='text' name='requesteddatefrom" + index + "' class='form-control datepicker' placeholder='MM/DD/YYYY' required/></td>" +
            "<td><input type='text' class='timepicker form-control' name='starttime" + index + "' placeholder='HH:MM AM/PM' required/></td>" +
            "<td><input type='text' class='timepicker form-control' name='endtime" + index + "' placeholder='HH:MM AM/PM' required></td>" +
            "<td><input type='text' class='timepicker form-control' name='preeventsetup" + index + "' placeholder='HH:MM AM/PM' required></td>"

        );
        $('.datepicker').datepicker({minDate: -0});
        $('.timepicker').timepicker({'minTime': '8:00am',
            'maxTime': '11:30pm',
            disableText:true
        });
        index+=1;
        $('#table_body').append("<tr id='add_row"+index+"'></tr>");
    });
    $('#delete_date_btn').click(function(){
        if(index===1)
        {
            index=1;
        }
        else
        {
            index-=1;
            $("#add_row"+index).html('');
        }
    });
    //Dialog box for user to know if successful
    $('#Show_Success').dialog({
        autoOpen: false,
        buttons: {
            "Ok": function () {
                location.reload();
                $(this).dialog("close");
            }
        }
    });
    var form = $("#form_reservation");
    form.validate();
    $('#form_submit').click(function(){

        jQuery.validator.setDefaults({
            success: "valid"
        });

        var captcha_hash = $("#captcha").realperson('getHash');

        if(form.valid())
        {
            // event.preventDefault();
            $.ajax({
                url: "php/ReservationRequest.php",
                type: 'POST',
                data: form.serialize() + '&captchaHash=' + captcha_hash,
                success:function(results)
                {
                    if(results==="captcha failed") {
                        alert("Incorrect Captcha");
                        $('.realperson-challenge').trigger("click");
                    }
                    else
                    {
                        $('#Show_Success').dialog("open")
                            .dialog("option", "width", 500);
                    }

                },
                error:function () {
                    alert("ERROR FORM NOT SUBMITTED")
                }
            });
        }


        event.preventDefault();
        });

});
