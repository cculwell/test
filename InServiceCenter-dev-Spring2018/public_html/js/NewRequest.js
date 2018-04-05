


window.onload = initPage();

function initPage() {
    // do noting placeholder
};


// Function to manipulate dom after content is loaded.
window.addEventListener("load", function(){
    document.getElementById('General').checked = true;
    selectRequestType();
});


function selectRequestType() {
    if (document.getElementById('General').checked) {
        $("#school_system_row").show();
        $("#request_desc_row").show();
        $("#book_title_row").hide();
        $("#format_method_row").show();
        $("#study_format_sec").hide();
        $("#eval_method_sec").show();
        $("#company_panel").show();
        $("#faciliator_panel").hide();
        $("#cost_per_book_div").hide();
    }
    else if (document.getElementById('BookStudy').checked) {
        $("#school_system_row").show();
        $("#request_desc_row").hide();
        $("#book_title_row").show();
        $("#format_method_row").show();
        $("#study_format_sec").show();
        $("#eval_method_sec").hide();
        $("#company_panel").hide();
        $("#faciliator_panel").show();
        $("#cost_per_book_div").show();
    }
    else{
        // do nothing at the moment
    }
};


$(document).ready(function() {

    $('.realperson-challenge').trigger("click");

    $('.datepicker').datepicker();
    $('.timepicker').timepicker({
        disableTimeRanges: [['12:00am', '8:00am'], ['9:30pm', '11:59pm']],
        disableTextInput: true
    });

    function validate() {
        var valid = true;
        $('#RequestForm input, #RequestForm select, #RequestForm textarea').each(function(key, value) {
            var t = $( 'input[name=RequestType]:checked' ).val();
            // var flag = 'valid';
            var ele = $(this);
            var n = ele.attr("name");
            var v = ele.val();


            if (n=='RequestType') {
                // console.log("skip");
            } else {
                if(v==''){
                    if(t=='General'){
                        if(n=='book_title'){}
                        else if(n=='publisher'){}
                        else if(n=='isbn'){}
                        else if(n=='study_format'){}
                        else if(n=='cost_per_book'){}
                        else if(n=='facilitator_name'){}
                        else if(n=='facilitator_phn_nbr'){}
                        else if(n=='facilitator_email'){}
                        else {
                            // console.log(n + "=" + v);
                            valid = false;
                            return valid;
                        }
                    } else {
                        if(n=='request_desc'){}
                        else if(n=='eval_method'){}
                        else if(n=='company_name'){}
                        else if(n=='company_phn_nbr'){}
                        else if(n=='company_email'){}
                        else {
                            // console.log(n + "=" + v);
                            valid = false;
                            return valid;
                        }
                    }
                }
            }
        });
        return valid;
    }

    // Ajax call to pass form to php
    $('#submitRequest').click(function () {
        event.preventDefault(); // avoid to execute the actual submit of the form.
        // $('#submitRequest').checkValidity();

        var form = $('form');
        var url = "php/add_request.php"; // the script where you handle the form input.

        var captcha_hash = $("#captcha").realperson('getHash');
        var form_data = form.serialize();

        form_data = form_data + "&captcha_hash=" + captcha_hash;

        // console.log(form_data);
        // $('#debug').html(form_data);


        var valid = validate();
        console.log("Valid = " + valid);

        if(valid) {
            $.ajax({
                type: "POST",
                url: url,
                data: form_data,
                dataType: "json",
                success: function (data) {
                    if (data == "captcha failed") {
                        alert("Incorrect Captcha");
                        $('.realperson-challenge').trigger("click");
                    }
                    // $('#debug').html(data);
                    console.log("Ajax Success");
                    var test = JSON.stringify(data, null, '\t');
                    console.log(test);
                    alert("Your Request ID is " + test + " please save this for your record!!!");
                    // $('#RequestForm').trigger("reset");
                    location.reload();
                },
                error: function (data) {
                    alert("Error: Check all Fields and resubmit!");
                },
                complete: function (data) {
                    // alert("complete");
                    // location.reload();

                }
            });
        } else {
            alert("Please feel out all the fields!!!");
        }
    });
});


// jQuery Code to Add , Delete rows

$(document).ready(function(){
    var i=1;
    $("#add_date_row").click(function(){
        $('#addr'+i).html("<td>"+ (i+1) + "</td>" +
            "<td><input  name='date"+i+"'  type='text' placeholder='mm/dd/yyyy' class='form-control input-md datepicker'/></td>"+
            "<td><input  name='sTime"+i+"' type='text' placeholder='00:00am/pm' class='form-control input-md timepicker'/></td>" +
            "<td><input  name='eTime"+i+"' type='text' placeholder='00:00am/pm' class='form-control input-md timepicker'/></td>" +
            "<td><input  name='bTime"+i+"' type='number' placeholder='' class='form-control input-md' value='0'/></td>" //+
            // "<td><input  name='note"+i+"' type='text' placeholder=''  class='form-control input-md'></td>"
        );

        $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
        i++;

        $('.datepicker').datepicker();
        $('.timepicker').timepicker({
            disableTimeRanges: [['12:00am', '8:00am'], ['9:30pm', '11:59pm']],
            disableTextInput: true
        });
    });

    $("#delete_date_row").click(function(){
        if(i>1){
            $("#addr"+(i-1)).html('');
            i--;
        }
    });

});