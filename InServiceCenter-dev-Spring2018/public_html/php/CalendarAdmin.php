<?php
/**
 * Created by PhpStorm.
 * User: jtwyn
 * Date: 10/19/2017
 * Time: 2:40 PM
 */
session_start();
include_once "Common.php";

function CheckIfFinished($ReservationID, $conn)
{
    $CheckDate_SQL = "SELECT StartDate, reservationDateTime_ID  from reservationDate_Time WHERE reservationID = '". $ReservationID . "'";
    $result = $conn->query($CheckDate_SQL);


    while($row = $result->fetch_assoc())
    {
        $ReservationDate = $row['StartDate'];
        $TodaysDate = date('Y-m-d');
        if(strtotime($ReservationDate) < strtotime($TodaysDate))
        {
            $CheckDate_SQL = "UPDATE reservationDate_Time SET status = 'finished' WHERE reservationDateTime_ID = '". $row['reservationDateTime_ID'] . "'";
            $conn->query($CheckDate_SQL);
        }
    }
}


$conn = new mysqli($config['db']['amsti_01']['host']
    , $config['db']['amsti_01']['username']
    , $config['db']['amsti_01']['password']
    , $config['db']['amsti_01']['dbname']);

?>

<?php
if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin' || $_SESSION['valid_status']=='User'))
{?>
<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#reservation_queue">Pending Reservations</a></li>
    <li><a data-toggle="tab" href="#booked_reservation">Booked Reservations</a></li>
    <li><a data-toggle="tab" href="#canceled_Reservations">Canceled Reservations</a></li>
    <li><a data-toggle="tab" href="#create_Reservation">Create Reservation</a></li>
</ul>
<div class="tab-content">
    <div id="reservation_queue" class="tab-pane fade in active">
        <h4 class="text-center">Pending Reservations</h4>
        <table class="text-center table table-striped table-bordered" id="datatable">
            <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Program Name</th>
                <th>Program Sponsor</th>
                <th>Room Reserving</th>
                <th>Person In Charge</th>
            </tr>

            </thead>
            <tbody>
            <?php

            $query = "SELECT * FROM reservations WHERE bookedStatus = 'pending'";
            $result = mysqli_query($conn, $query);

            while($row = mysqli_fetch_row($result))
            {
                echo <<<End
            <tr>
            <td>$row[0]</td>
            <td>$row[1]</td>
            <td>$row[3]</td>
            <td>$row[5]</td>
            <td>$row[2]</td>
            
            </tr>
            

End;
            }
            mysqli_free_result($result);
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Reservation ID</th>
                <th>Program Name</th>
                <th>Program Sponsor</th>
                <th>Room Reserving</th>
                <th>Person In Charge</th>
            </tr>
            </tfoot>
        </table>
        <script>
            $(document).ready(function () {
                var table = $('#datatable').DataTable({
                    select:{
                        style: 'single'
                    }
                });

                $('#datatable tbody').on('click', 'tr',  function(){
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    if(row.child.isShown())
                    {
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else
                    {
                        var record = row.data();
                        $.ajax({
                            type:"POST",
                            url:"php/Edit_Form.php",
                            data: {reserveID:  record[0]},
                            success:function(response)
                            {
                                row.child(response).show();
                            },
                            error:function(thrownError)
                            {
                                row.child("Error loading content " + thrownError).show();
                            }
                        });
                        tr.addClass('shown');
                    }
                });
            });
        </script>
    </div>
    <div id="booked_reservation" class="tab-pane fade">
        <h4 class="text-center">Booked Reservations</h4>
        <table class="text-center table table-striped table-bordered" id="datatable_booked">
            <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Program Name</th>
                <th>Program Sponsor</th>
                <th>Room Reserving</th>
                <th>Person In Charge</th>
            </tr>

            </thead>
            <tbody>
            <?php

            $query = "SELECT * FROM reservations WHERE bookedStatus = 'booked'";
            $result = mysqli_query($conn, $query);


            while($row = mysqli_fetch_row($result))
            {
                CheckIfFinished($row[0], $conn);
                echo <<<End
            <tr>
            <td>$row[0]</td>
            <td>$row[1]</td>
            <td>$row[3]</td>
            <td>$row[5]</td>
            <td>$row[2]</td>
            
            </tr>
            

End;
            }
            mysqli_free_result($result);
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Reservation ID</th>
                <th>Program Name</th>
                <th>Program Sponsor</th>
                <th>Room Reserving</th>
                <th>Person In Charge</th>
            </tr>
            </tfoot>
        </table>
        <script>
            $(document).ready(function () {
                var table = $('#datatable_booked').DataTable({
                    select:{
                        style: 'single'
                    }
                });

                $('#datatable_booked tbody').on('click', 'tr',  function(){
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    if(row.child.isShown())
                    {
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else
                    {
                        var record = row.data();
                        $.ajax({
                            type:"POST",
                            url:"php/Edit_Form.php",
                            data: {reserveID:  record[0]},
                            success:function(response)
                            {
                                row.child(response).show();
                            },
                            error:function(thrownError)
                            {
                                row.child("Error loading content " + thrownError).show();
                            }
                        });
                        tr.addClass('shown');
                    }
                });
            });
        </script>
    </div>
    <div id="canceled_Reservations" class="tab-pane fade">
        <h4 class="text-center">Canceled Reservations</h4>
        <table class="text-center table table-striped table-bordered" id="datatable_canceled">
            <thead>
            <tr>
                <th>Reservation ID</th>
                <th>Program Name</th>
                <th>Program Sponsor</th>
                <th>Room Reserving</th>
                <th>Person In Charge</th>
            </tr>

            </thead>
            <tbody>
            <?php

            $query = "SELECT * FROM reservations WHERE bookedStatus = 'canceled'";
            $result = mysqli_query($conn, $query);

            while($row = mysqli_fetch_row($result))
            {
                echo <<<End
            <tr>
            <td>$row[0]</td>
            <td>$row[1]</td>
            <td>$row[3]</td>
            <td>$row[5]</td>
            <td>$row[2]</td>
            
            
            </tr>
            

End;
            }
            mysqli_free_result($result);
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th>Reservation ID</th>
                <th>Program Name</th>
                <th>Program Sponsor</th>
                <th>Room Reserving</th>
                <th>Person In Charge</th>
            </tr>
            </tfoot>
        </table>
        <script>
            $(document).ready(function () {
                var table = $('#datatable_canceled').DataTable({
                    select:{
                        style: 'single'
                    }
                });

                $('#datatable_canceled tbody').on('click', 'tr',  function(){
                    var tr = $(this).closest('tr');
                    var row = table.row(tr);
                    if(row.child.isShown())
                    {
                        row.child.hide();
                        tr.removeClass('shown');
                    }

                    else
                    {
                        var record = row.data();
                        $.ajax({
                            type:"POST",
                            url:"php/Edit_Form.php",
                            data: {reserveID:  record[0]},
                            success:function(response)
                            {
                                row.child(response).show();
                            },
                            error:function(thrownError)
                            {
                                row.child("Error loading content " + thrownError).show();
                            }
                        });
                        tr.addClass('shown');
                    }
                });
            });
        </script>
    </div>

    <div id="create_Reservation" class="tab-pane fade">
        <div class="text-center" id="title_Create">
             <h2>Create Reservation</h2>
        </div>
        <form id="form_reservation">
            <div class="panel panel-primary">
                <div class="panel-heading"><h4>Program Information</h4></div>
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="pull-left col-md-6 ">
                            <label for="prgrm">Program Name:</label>
                            <input type="text" id="prgrm" name="createProgram" required>
                        </div>
                        <div class="col-md-6 pull-left">
                            <label for="spncr">Sponsoring Group(s)</label>
                            <input type="text"  id="spncr" name="createSponsor" required>
                        </div><br>
                        <div class="col-lg-12">
                            <label for="evntdesc">Describe the Event</label>
                            <textarea id = "evntdesc" class="form-control" name = "createEvntdesc" rows="5" cols="100" required></textarea>
                        </div><br>
                    </div>
                </div>
            </div>
            <div class="panel-primary panel">
                <div class="panel-heading"><h4>Contact Information</h4></div>
                <div class="panel-body">
                    <div class="row form-group">
                        <div class="col-md-4  pull-left">
                            <label for="priresp">Contact's Name</label>
                            <input type="text" id="priresp" name="createResponsible" required>
                        </div>
                        <div class="pull-left col-md-4">
                            <label for="phn">Phone Number</label>
                            <input type="tel" id="phn" name="createPhone" placeholder="(555) 555-5555"required>
                        </div>
                        <div class="col-md-4 pull-left">
                            <label for="eml">Email</label>
                            <input type="email" id="eml" name="createEmail" placeholder="name@sample.com" required>
                        </div>

                    </div>
                </div>
            </div>
            <div class="panel-primary panel">
                <div class="panel-heading"><h4>Room Reservation</h4></div>
                <div class="panel-body">
                    <div class="form-group row">
                        <div class="col-md-6 pull-left">
                            <label for="location">Pick a room to reserve: </label>
                            <select id="location" name="createRoom" style="height: 40px; width: 200px">
                                <option value="Room A">Room A</option>
                                <option value="Room B">Room B</option>
                                <option value="Room C">Room C</option>
                                <option value="Conference">Conference</option>
                            </select>
                        </div>
                        <div class="col-md-6 pull-left">
                            <label for="numattend">Number of Attendees</label>
                            <input type="number" id="numattend" name="createAttend" required>
                        </div><br><br>
                        <div class="container col-xs-12">
                            <div class="clearfix row">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Start Time</th>
                                        <th class="text-center">End Time</th>
                                        <th class="text-center">Pre Time(Time Before Event)</th>
                                        </thead>
                                        <tbody id="table_create_body">
                                        <tr id="create_add_row0">
                                            <td>1</td>
                                            <td><input type="text" class="form-control datepicker" name="createDatefrom0"  placeholder="MM/DD/YYYY" required/></td>
                                            <td><input type="text" class="timepicker form-control" name="createStarttime0" placeholder="HH:MM AM/PM" required/></td>
                                            <td><input type="text" class="timepicker form-control" name="createEndtime0" placeholder="HH:MM AM/PM" required></td>
                                            <td><input type="text" class="timepicker form-control" name="createPreeventsetup0" placeholder="HH:MM AM/PM" required/></td>
                                        </tr>
                                        <tr id="create_add_row1"></tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <a id="create_add_date" class="btn btn-primary pull-left">ADD</a> <a class="btn btn-danger pull-right" id="create_delete_date">DELETE</a>

                        </div>
                    </div>
                </div>

            </div>
            <div class="panel-primary panel">
                <div class="panel-heading"><h4>A/V Needs</h4></div>

                <div class="panel-body">
                    <div class="col-lg-12">
                        <h4><strong>A/V Needs: </strong></h4>
                        <label class="checkbox-inline"><input type="checkbox" name="createSmartboard" value="Smartboard">Smartboard</label>
                        <label class="checkbox-inline"><input type="checkbox" name="createProjector" value="projector">Projector</label>
                        <label class="checkbox-inline"><input type="checkbox" name="createDocumentcamera" value="documentcamera">Document Camera</label>
                        <label class="checkbox-inline"><input type="checkbox" name="createExtensioncords" value="extensioncords">Extension Cords</label>
                    </div>

                    <div class="pull-left col-lg-12">
                        <label style="font-size: 20px; " for="avguy">*A/V tech needed to assist with setup?<input type="checkbox" id = "avguy" name="createAvsetup" style="width: 30px; height: 20px; cursor: pointer" value="avsetup"></label>

                    </div>
                </div>
            </div>
            <div class="btn-group col-md-6 text-center" id="reserve">
                <button type="button" id="create_form_submit" class="btn btn-primary btn-lg">Reserve</button>
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function () {

                var index = 1;
                $('.datepicker').datepicker();
                $('.timepicker').timepicker({'minTime': '8:00am',
                    'maxTime': '11:30pm',
                    disableTextInput: true
                });


                $("#create_add_date").click(function(){


                    $('#create_add_row' + index).html("<td>" + (index + 1)+ "</td>" +
                        "<td><input type='text' class='form-control datepicker' name='createDatefrom" + index + "' placeholder='MM/DD/YYYY' required/></td>" +
                        "<td><input type='text' class='timepicker form-control' name='createStarttime" + index + "' placeholder='HH:MM AM/PM' required/></td>" +
                        "<td><input type='text' class='timepicker form-control' name='createEndtime" + index + "' placeholder='HH:MM AM/PM' required></td>" +
                        "<td><input type='text' class='timepicker form-control' name='createPreeventsetup" + index + "' placeholder='HH:MM AM/PM' required></td>"

                    );
                    $('.datepicker').datepicker();
                    $('.timepicker').timepicker({'minTime': '8:00am',
                        'maxTime': '11:30pm',
                        disableText:true
                    });
                    index+=1;
                    $('#table_create_body').append("<tr id='create_add_row"+index+"'></tr>");
                });
                $('#create_delete_date').click(function(){
                    if(index===1)
                    {
                        index=1;
                    }
                    else
                    {
                        index-=1;
                        $("#create_add_row"+index).html('');
                    }
                });
                $('#create_form_submit').on('click', function () {
                    var form = $('#form_reservation');
                    if(valid(form))
                    {
                        var form_data = form.serialize();
                        //Book Event through BookEvent.php
                        $.ajax({
                            type: 'POST',
                            url: 'php/BookEvent.php',
                            data: form_data,
                            success: function(response)
                            {
                                if(response === 'Please fill out all of the inputs before submitting')
                                {
                                    console.log(response);
                                }
                                else
                                {
                                    alert("Pending Reservation Created");
                                    console.log(response);
                                    $('#reservationQueue').load('php/CalendarAdmin.php');
                                }

                            },
                            error: function(response)
                            {
                                alert("AJAX Failure");
                            }
                        });
                    }
                    else
                        alert("Please fill out all of the fields");

                    event.preventDefault();

                });
            });


        </script>

        <div id="div_pop_dt" class="div_pop_dt">
            <form id="new_Event">
                <input type="hidden" name="ReservationID_newEvent" id="ReservationID_newEvent" value="<?php echo $ReservationID?>"/>
                <input type="hidden" name="Book_Status" class="Book_Status" id="Book_Status" value="<?php echo $BookedStatus?>"/>
                <label for="newDate">Date: </label>
                <input type="text" class="datepicker " name="newDate" id="newDate" placeholder="MM/DD/YYYY"/>
                <label for="newStime">Start Time: </label>
                <input type="text" class="timepicker" name="newStime" id="newStime" placeholder="HH:MM AM/PM"/>
                <label for="newEtime">End Time: </label>
                <input type="text" class="timepicker" name="newEtime" id="newEtime" placeholder="HH:MM AM/PM"/>
                <label for="newPtime">Pre Time: </label>
                <input type="text" class="timepicker" name="newPtime" id="newPtime" placeholder="HH:MM AM/PM"/>
            </form>

        </div>
</div>

<?php
}
else
{
	echo "<p><h3>You are not authorized to visit this page.</h3></p>";
	echo "<p><a href='php/UserLogin.php'>User Login</a></p>";
	echo "<p><a href='php/UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='WorkQueue.php'>Work Queue</a></p>";
	echo "<p><a href='Home.html'>Home Page</a></p>";
}
?>
<?php mysqli_close($conn);