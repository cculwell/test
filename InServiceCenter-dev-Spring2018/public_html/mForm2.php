<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">
<?php
require "../resources/config.php";
# create connection to database
$mysqli = new mysqli($config['db']['amsti_01']['host']
    , $config['db']['amsti_01']['username']
    , $config['db']['amsti_01']['password']
    , $config['db']['amsti_01']['dbname']);

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
?>
<head>
    <title>New Request</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../resources/library/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="../resources/library/timepicker/jquery.timepicker.css">
    <link rel="stylesheet" href="css/NewRequest.css">

    <script src="../resources/library/jquery-3.2.1.min.js"></script>
    <script src="../resources/library/jquery-ui/jquery-ui.min.js"></script>
    <script src="../resources/library/timepicker/jquery.timepicker.js"></script>
    <script src="../resources/library/jquery-validation/jquery.validate.js"></script>
    <script src="js/NewRequest.js"></script>


</head>

<div class="callout large">
    <div class="row column text-center">
        <img id="logo" src="img/Logo.jpg" width="30%" height="30%" alt="" />
    </div>
</div>

<form class="container" id="RequestForm" name="RequestForm">
        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12 well text-center">
                    <h2> Inservice Request Form</h2>
                    <h3> Select a request type to begin </h3>

                    <form class="container form-horizontal" name="innerRequestForm" id="innerRequestForm">

                        <!-- Request Type Radio Buttons -->
                        <div class="row form-group">
                            <div class="radio-inline">
                                <label><input type="radio" name="RequestType" id="General" value="General"
                                              onclick="selectRequestType();">General Request</label>
                            </div>
                            <div class="radio-inline">
                                <label><input type="radio" name="RequestType" id="BookStudy" value="BookStudy"
                                              onclick="selectRequestType();">Book Study</label>
                            </div>
                        </div>

                        <!-- School and System Input -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">School / System</div>
                            <div class="panel-body">

                                <div class="row" id="school_system_row">

                                    <div class="form-group col-xs-6 pull-left">
                                        <label for="school">School</label>
                                        <input class="form-control" type="text" id="school" name="school" size="35" maxlength="50" minlength="2" required>
<!--                                        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>-->
                                    </div>

                                    <div class="form-group col-xs-6 pull-left">
                                        <label for="system">System</label>
                                        <input class="form-control" type="text" id="system" name="system" size="35" maxlength="50" required>
<!--                                        <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>-->
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="panel panel-primary">
                            <div class="panel-heading">Request Details</div>
                            <div class="panel-body">
                                <!-- Request Description -->
                                <div class="row form-group" id="request_desc_row">
                                    <div class="col-md-12">
                                        <label class="col-md-push-12 pull-left">Request Description</label>
                                        <textarea class="form-control col-md-6" style="width:100%" rows="3"
                                                  id="request_desc" name="request_desc" minlength="10" required></textarea>
                                    </div>
                                </div>

                                <!-- Book Title -->
                                <div class="row form-group" id="book_title_row">
                                    <div class="form-group col-md-4">
                                        <label for="book_title">Book Title</label>
                                        <input type="text" id="book_title" name="book_title" size="35" maxlength="100">
                                    </div>
                                    <!--div class="col-md-4"> </div> -->
                                    <div class="form-group col-md-4 ">
                                        <label for="publisher">Publisher</label>
                                        <input type="text"  id="publisher" name="publisher" size="35" maxlength="50">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="isbn">ISBN</label>
                                        <input type="text"  id="isbn" name="isbn" size="35" maxlength="25">
                                    </div>
                                    <div class="col-md-4"> </div>
                                    <!--<div class="form-group col-md-4 pull-left  ">-->
                                    <!--<label for="cost_per_book">Cost per Book</label>-->
                                    <!--<input type="text"  id="cost_per_book" name="cost_per_book" size="35" maxlength="25">-->
                                    <!--</div>-->

                                </div>

                                <!-- Method of Eval or Format of Study -->
                                <div class="row form-group" id="format_method_row">

                                    <div class="form-group col-md-4 " id="study_format_sec">
                                        <label for="study_format">Study Format</label>
                                        <input type="text"  id="study_format" name="study_format" size="35" maxlength="100">
                                    </div>

                                    <div class="form-group col-md-4" id="eval_method_sec">
                                        <label for="eval_method">Evaluation Method</label>
                                        <input type="text"  id="eval_method" name="eval_method" size="35" maxlength="50">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <div class="input-group input-group-md">
                                            <label for="total_cost">Amt Requested / Total Cost</label>
                                            <input type="number"  id="total_cost" name="total_cost" size="35" maxlength="25">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3" id="cost_per_book_div">
                                        <label for="cost_per_book">Cost per Book</label>
                                        <input type="number"  id="cost_per_book" name="cost_per_book" size="35" maxlength="25">
                                    </div>
                                    <!--<div class="form-group col-md-4">-->
                                    <!--<label for="total_hours">Total Hours</label>-->
                                    <!--<input type="text"  id="total_hours" name="total_hours" size="35" maxlength="25">-->
                                    <!--</div>-->
                                </div>

                                <!-- Request Justification -->
                                <div class="row form-group" id="request_just_row">
                                    <div class="col-md-12">
                                        <label class="col-md-push-12 pull-left">Need / Justification</label>
                                        <textarea class="form-control col-md-6" style="width:100%" rows="3"
                                                  id="request_just" name="request_just" required minlength="10"></textarea>
                                    </div>
                                </div>


                                <!-- Location and Targets -->
                                <div class="row form-group" id="location_participants_row">

                                    <div class="form-group col-md-4" id="location_sec">
                                        <label for="study_format">Request Location</label>
                                        <input type="text"  id="request_location" name="request_location" size="35" maxlength="100">
                                    </div>

                                    <div class="form-group col-md-3" id="target_part_sec">
                                        <label for="eval_method">Target Participants #</label>
                                        <input type="number"  id="target_participants" name="target_participants" size="40">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="total_cost">Enrolled Participants #</label>
                                        <input type="number"  id="enrolled_participants" name="enrolled_participants" size="40">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Date(s) and Time(s) -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">Date(s) and Time(s)</div>
                            <div class="panel-body">
                                <div class="container col-xs-12">
                                    <div class="row clearfix">
                                        <div class="col-md-12 column">
                                            <table class="table table-bordered table-hover table-responsive" id="tab_logic">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Start Time</th>
                                                    <th class="text-center">End Time</th>
                                                    <th class="text-center">Break Time (Hours)</th>
                                                    <!--<th class="text-center">Hours</th>-->

                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <!--<th></th>-->
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                <tr id='addr0'>
                                                    <td>1</td>
                                                    <td><input type="date" name='date0'  placeholder='mm/dd/yyyy' class="form-control datepicker"/></td>
                                                    <td><input type="text" name='sTime0' placeholder='00:00am/pm' class="form-control timepicker"/></td>
                                                    <td><input type="text" name='eTime0' placeholder='00:00am/pm' class="form-control timepicker"/></td>
                                                    <td><input type="number" name='bTime0' placeholder='' class="form-control"/></td>
                                                    <!--<td><input type="number" name='hours' placeholder='' class="form-control"/></td>-->
                                                </tr>
                                                <tr id='addr1'></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <a id="add_date_row" class="btn btn-success pull-left">Add Row</a>
                                    <a id='delete_date_row' class="btn btn-danger pull-right">Delete Row</a>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="form-group col-xs-3">
                                <input class="submit" type="submit" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</form>

<!--<div class="callout large">-->
<!--    <div class="row column text-center">-->
<!--        <img id="logo" src="img/Logo.jpg" width="30%" height="30%" alt="" />-->
<!--    </div>-->
<!--</div>-->
<!---->
<!---->
<!--<form class="container">-->
<!---->
<!--    <div class="row setup-content" id="step-1">-->
<!--        <div class="col-xs-12">-->
<!--            <div class="col-md-12 well text-center">-->
<!--                <h2> Inservice Request Form</h2>-->
<!--                <h3> Select a request type to begin </h3>-->
<!---->
<!--                <form class="container form-horizontal" name="RequestForm" id="RequestForm" novalidate>-->
<!---->
<!--                    <!-- Request Type Radio Buttons -->
<!--                    <div class="row form-group">-->
<!--                        <div class="radio-inline">-->
<!--                            <label><input type="radio" name="RequestType" id="General" value="General"-->
<!--                                          onclick="selectRequestType();">General Request</label>-->
<!--                        </div>-->
<!--                        <div class="radio-inline">-->
<!--                            <label><input type="radio" name="RequestType" id="BookStudy" value="BookStudy"-->
<!--                                          onclick="selectRequestType();">Book Study</label>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
                    <!-- School and System Input -->
<!--                    <div class="panel panel-primary">-->
<!--                        <div class="panel-heading">School / System</div>-->
<!--                        <div class="panel-body">-->
<!---->
<!--                            <div class="row" id="school_system_row">-->
<!---->
<!--                                <div class="form-group col-xs-6 pull-left has-feedback">-->
<!--                                    <label for="school">School</label>-->
<!--                                    <input class="form-control" type="text" id="school" name="school" size="35" maxlength="50">-->
<!--                                    <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>-->
<!--                                </div>-->
<!---->
<!--                                <div class="form-group col-xs-6 pull-left has-feedback">-->
<!--                                    <label for="system">System</label>-->
<!--                                    <input class="form-control" type="text" id="system" name="system" size="35" maxlength="50">-->
<!--                                    <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>-->
<!--                                </div>-->
<!--                            </div>-->
<!---->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <button type="submit">submit</button>-->
<!--                </form>-->








<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</form>-->
<script>

    $(function () {
        $.validator.setDefaults({
            errorClass: 'help-block',
            highlight: function(element){
                $(element)
                    .closest('.form-group')
                    .addClass('has-error');
            },
            unhighlight: function(element) {
                $(element)
                    .closest('.form-group')
                    .removeClass('has-error');
            },
            errorPlacement: function(error, element) {
                if(element.prop('type') == 'checkbox') {
                    error.insertAfter(element.parent());
                } else if (element.prop('type') == 'textarea') {
                    error.insertAfter(element.parent());
                }
                else {
                    error.insertAfter(element);
                }
            }
        });

//        $("#commentForm").validate();

        $("#RequestForm").validate({

            rules: {
                school:{
                    required: true,
                    minLength: 2
                },
                system:{
                    required: true,
                    minLength: 2
                }
            }
        });
    });



</script>