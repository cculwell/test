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


<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <title>New Request</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../resources/library/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="../resources/library/timepicker/jquery.timepicker.css">
    <link rel="stylesheet" type="text/css" href="../resources/library/realperson-2.0.1/jquery.realperson.css">
    <link rel="stylesheet" href="css/NewRequest.css">

    <script src="../resources/library/jquery-3.2.1.min.js"></script>
    <script src="../resources/library/jquery-ui/jquery-ui.min.js"></script>
    <script src="../resources/library/timepicker/jquery.timepicker.js"></script>
    <script src="../resources/library/jquery_chained/jquery.chained.js"></script>
<!--    <script src="../resources/library/jquery-validation/jquery.validate.js"></script>-->
    <script type="text/javascript" src="../resources/library/realperson-2.0.1/jquery.plugin.js"></script>
    <script type="text/javascript" src="../resources/library/realperson-2.0.1/jquery.realperson.js"></script>
    <script src="js/NewRequest.js"></script>
</head>

<div class="callout large">
    <div class="row column text-center">
        <img id="logo" src="img/Logo.jpg" width="25%" height="25%" alt="" />
    </div>
</div>


<form class="container">

    <div class="row setup-content" id="RequestForm" name="RequestForm">
        <div class="col-xs-12">
            <div class="col-md-12 well text-center">
                <h2> Inservice Request Form</h2>
                <h3> Select a request type to begin </h3>

                <!-- <form> -->
                <form class="container form-horizontal" name="innerRequestForm" id="innerRequestForm" novalidate>

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
                        <!--
                        <div class="radio-inline">
                            <label><input type="radio" name="RequestType" id="Workshop" value="Workshop"
                                    onclick="javascript:selectRequestType();">Workshop</label>
                        </div>
                        -->
                    </div>

                    <!-- School and System Input -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">School / System</div>
                        <div class="panel-body">
                            <div class="row form-group" id="school_system_row">

                                <div class="col-md-6 pull-left">
                                    <label for="system" hidden>System</label>


                                    <select id="system" name="system">
                                        <option value="">Select System</option>
                                        <?php
                                        $system_results = $mysqli->query("select distinct system from systems_schools order by system") or die($mysqli->error);
                                        while ($row = mysqli_fetch_array($system_results)) {
                                            $system_val = str_replace(' ','_',$row['system']);
                                            echo "<option value='" . $system_val . "'>" . $row['system'] . "</option>";
                                        }
                                        ?>
                                    </select>

                                </div>


                                <div class="col-md-6 pull-left">
                                    <label for="system" hidden>School</label>

                                    <select multiple id="school" name="school">
                                        <option value="">Select School</option>
                                        <?php
                                        $school_results = $mysqli->query("select school, system from systems_schools order by system, school") or die($mysqli->error);
                                        while ($row = mysqli_fetch_array($school_results)) {
                                            $system_val = str_replace(' ','_',$row['system']);
                                            $school_val = str_replace(' ','_',$row['school']);
                                            echo "<option value='" . $school_val . "' data-chained='" . $system_val . "'>" . $row['school'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <script>
                                    $("#school").chained("#system");
                                </script>



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
                                    <input type="text" id="book_title" name="book_title" size="35" maxlength="100"
                                           style="text-align: center">
                                </div>
                                <!--div class="col-md-4"> </div> -->
                                <div class="form-group col-md-4 ">
                                    <label for="publisher">Publisher</label>
                                    <input type="text"  id="publisher" name="publisher" size="35" maxlength="50"
                                           style="text-align: center">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="isbn">ISBN</label>
                                    <input type="text"  id="isbn" name="isbn" size="35" maxlength="25"
                                           style="text-align: center">
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
                                    <input type="text"  id="study_format" name="study_format" size="35" maxlength="100"
                                           style="text-align: center">
                                </div>

                                <div class="form-group col-md-4" id="eval_method_sec">
                                    <label for="eval_method">Evaluation Method</label>
                                    <input type="text"  id="eval_method" name="eval_method" size="35" maxlength="50"
                                           style="text-align: center">
                                </div>

                                <div class="form-group col-md-3">
                                    <div class="input-group input-group-md">
                                        <label for="total_cost">Amt Requested / Total Cost</label>
                                        <input type="number"  id="total_cost" name="total_cost" size="35" maxlength="25" value="0"
                                               style="text-align: center">
                                    </div>
                                </div>

                                <div class="form-group col-md-3" id="cost_per_book_div">
                                    <label for="cost_per_book">Cost per Book</label>
                                    <input type="number"  id="cost_per_book" name="cost_per_book" size="35" maxlength="25" value="0"
                                           style="text-align: center">
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
                                               id="request_just" name="request_just"></textarea>
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
                                    <input type="number"  id="target_participants" name="target_participants" size="40" value="0"
                                           style="text-align: center">
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="total_cost">Enrolled Participants #</label>
                                    <input type="number"  id="enrolled_participants" name="enrolled_participants" size="40" value="0"
                                           style="text-align: center">
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
                                                <td><input type="text" name='date0'  placeholder='mm/dd/yyyy' class="form-control datepicker"/></td>
                                                <td><input type="text" name='sTime0' placeholder='00:00am/pm' class="form-control timepicker"/></td>
                                                <td><input type="text" name='eTime0' placeholder='00:00am/pm' class="form-control timepicker"/></td>
                                                <td><input type="number" name='bTime0' placeholder='' class="form-control" value="0"/></td>
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

                    <!-- Company / Org / Publisher -->
                    <div class="panel panel-primary" id="company_panel">
                        <div class="panel-heading">Company / Organization / Publisher</div>
                        <div class="panel-body">
                            <div class="row form-group" id="company_row">
                                <div class="form-group col-md-4">
                                    <label for="company_name">Name </label>
                                    <input type="text"  id="company_name" name="company_name" size="35" maxlength="50">
                                </div>
                                <!--div class="col-md-4"> </div> -->
                                <div class="form-group col-md-4">
                                    <label for="company_phn_nbr">Phone</label>
                                    <input type="tel"  id="company_phn_nbr" name="company_phn_nbr" size="35" maxlength="50"
                                           class="phone_number">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="company_email">Email </label>
                                    <input type="email"  id="company_email" name="company_email" size="35" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Facilitator -->
                    <div class="panel panel-primary" id="faciliator_panel">
                        <div class="panel-heading">Facilitator</div>
                        <div class="panel-body">
                            <div class="row form-group" id="facilitator_row">
                                <div class="form-group col-md-4">
                                    <label for="company_name">Name </label>
                                    <input type="text"  id="facilitator_name" name="facilitator_name" size="35" maxlength="50">
                                </div>
                                <!--div class="col-md-4"> </div> -->
                                <div class="form-group col-md-4">
                                    <label for="company_phn_nbr">Phone</label>
                                    <input type="tel" pattern="^\d{4}-\d{3}-\d{4}$" id="facilitator_phn_nbr" name="facilitator_phn_nbr" size="35" maxlength="50">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="company_email">Email </label>
                                    <input type="email"  id="facilitator_email" name="facilitator_email" size="35" maxlength="50">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="panel panel-primary">
                        <div class="panel-heading">Contact</div>
                        <div class="panel-body">
                            <div class="row form-group" id="contact_row">
                                <div class="form-group col-md-4">
                                    <label for="contact_name">Name </label>
                                    <input type="text"  id="contact_name" name="contact_name" size="35" maxlength="50">
                                </div>
                                <!--div class="col-md-4"> </div> -->
                                <div class="form-group col-md-4">
                                    <label for="contact_phn_nbr">Phone</label>
                                    <input type="text"  id="contact_phn_nbr" name="contact_phn_nbr" size="35" maxlength="50">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="contact_email">Email </label>
                                    <input type="email"  id="contact_email" name="contact_email" size="35" maxlength="50">
                                </div>
                                <!--div class="col-md-4"> </div>
                                <div class="col-md-4">
                                    <label for="contact_addr">Address</label>
                                    <input type="text"  id="contact_addr" size="35" maxlength="50">
                                </div> -->
                            </div>
                        </div>
                        <!--div class="panel-footer">Panel footer</div> -->
                    </div>

                    <div class="captcha_container">
                        <input  type="text" id="captcha" name="captcha">
                    </div>

                    <div class="btn-group">
                        <button id="submitRequest" type="button" class="btn btn-primary">Submit Form</button>
                    </div>


                </form>
                <script>
                    $('#captcha').realperson({chars: $.realperson.alphanumeric, length: 5});
                </script>
                <!--<button id="submitForm" class="btn btn-primary btn-md" type="submit">Submit Form</button>-->


    <!--                <div class="errorTxt">-->
    <!--                    <span id="errNm2"></span>-->
    <!--                    <span id="errNm1"></span>-->
    <!--                </div>-->
            </div>
        </div>
    </div>
</form>
</html>

