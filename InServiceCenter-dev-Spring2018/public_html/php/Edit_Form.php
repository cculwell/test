<?php
/**
 * Created by PhpStorm.
 * User: jtwyn
 * Date: 10/26/2017
 * Time: 4:28 PM
 */
session_start();

include_once "Common.php";

$conn = new mysqli($config['db']['amsti_01']['host']
    , $config['db']['amsti_01']['username']
    , $config['db']['amsti_01']['password']
    , $config['db']['amsti_01']['dbname']);

if ($conn->connect_errno) {
    printf("Connect failed: %s\n", $conn->connect_error);
    exit();
}

if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin' || $_SESSION['valid_status']=='User'))
{

//Get all of the values from the reservations and reservationDate_Time database
    if(isset($_POST['reserveID'])) {

        $reservation_query = "SELECT * FROM reservations WHERE reservationID = '" . $_POST['reserveID'] . "'";
        if($result = mysqli_query($conn, $reservation_query))
        {
            $row = mysqli_fetch_row($result);
            //Free result row
            mysqli_free_result($result);

        }
        else
        {
            printf("ERROR Fetching row");
            exit();
        }
        $ReservationID = $row[0];
        $Program = $row[1];
        $InCharge = $row[2];
        $groupSponsor = $row[3];
        $Description = $row[4];
        $RoomReservation = $row[5];
        $Email = $row[6];
        $PhoneNumber = $row[7];
        $BookedStatus = $row[8];
        $smartBoard = $row[9];
        $Projector = $row[10];
        $ExtensionCord = $row[11];
        $DocumentCamera = $row[12];
        $AV_Need = $row[13];
        $NumberEvents = $row[14];

        //Date and Time query
        $date_time_Query = "Select * from reservationDate_Time where reservationID = '" . $_POST['reserveID'] . "'ORDER BY StartDate ASC ";
        $index = 0;
        if($result = $conn->query($date_time_Query))
        {
            while($date_row = $result->fetch_assoc())
            {
                $EventID[$index] = $date_row['reservationDateTime_ID'];
                $Date[$index] = FormatDate4Report($date_row['StartDate']);
                $StartTime[$index] = FormatTime4Report($date_row['startTime']);
                $EndTime[$index] = FormatTime4Report($date_row['endTime']);
                $PreTime[$index] = FormatTime4Report($date_row['preTime']);
                //Add the Google ID's if the form is for a booked request
                if($BookedStatus === 'booked')
                {
                    $EventStatus[$index] = $date_row['status'];
                    $PublicGoogle[$index] = $date_row['publicGoogle'];
                    $PrivateGoogle[$index] = $date_row['privateGoogle'];
                }
                $index++;
            }
        }
    }

//Handle event deletion
    elseif (isset($_POST['DeleteEventID']))
    {
        $DeleteEventID = $_POST['DeleteEventID'];
        $deleteEvent = "DELETE FROM reservationDate_Time WHERE reservationDateTime_ID = '". $DeleteEventID . "'";
        $result = $conn->query($deleteEvent);
    }






    ?>
<div id="pending_reservations">
    <form id="form_id<?php echo $ReservationID?>">
        <div class="panel panel-primary">
            <div class="panel-heading">Program Information</div>
            <div class="panel-body">
                <div class="row form-group">
                    <input type="hidden" name="ReservationID" value="<?php echo $ReservationID?>"/>
                    <div class="pull-left col-md-4">
                        <label for="program">Program Name: </label><input id="program" name="program" type="text" class="col-md-6" value="<?php echo $Program?>" required/>
                    </div>
                    <div class="pull-right col-md-4">
                        <label for="sponsor">Group Sponsor:</label><input id="sponsor" name="sponsor" type="text" class="col-md-6" value="<?php echo $groupSponsor?>" required/>
                    </div>
                    <div class="col-md-11">
                        <label for="evntdesc">Program Description</label><textarea id="evntdesc" name="evntdesc" rows="3" cols="12" required><?php echo $Description?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-primary">
            <div class="panel-heading">Contact Information</div>
            <div class="panel-body">
                <div class="row form-group">
                    <div class="pull-left col-md-4">
                        <label for="responsible">Contact's Name:</label><input id="responsible" name='responsible' type="text" value="<?php echo $InCharge ?>" required/>
                    </div>
                    <div class="pull-right col-md-4 pull-left">
                        <label for="phone">Contact's Phone#:</label><input id="phone" name="phone" type="text" value="<?php echo $PhoneNumber ?>" required/>
                    </div>
                    <div class="col-md-3 pull-right">
                        <label for="email">Contact's Email:</label><input id="email" name="email" type="text" value="<?php echo $Email ?>" required/>

                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">Room Reservation Dates</div>
            <div class="form-group row panel-body">
                <div class="pull-left col-md-4">
                    <label for="location">Pick a room to reserve: </label>
                    <select id="location" name="room" style="height: 40px; width: 200px" >
                        <option <?php echo ($RoomReservation === 'Room A')?'Selected' : '' ?> value="Room A">Room A</option>
                        <option <?php echo ($RoomReservation === 'Room B')?'Selected' : '' ?> value="Room B">Room B</option>
                        <option <?php echo ($RoomReservation === 'Room C')?'Selected' : '' ?> value="Room C">Room C</option>
                        <option <?php echo ($RoomReservation === 'Conference')?'Selected' : '' ?> value="Conference">Conference</option>
                    </select>
                </div>
                <div class="pull-right col-md-4">
                    <label for="numattend">Number of Attendees</label>
                    <input type="number" id="numattend" name="attend" required value="<?php echo $NumberEvents ?>">
                </div>


            <table class="table table-bordered EventTable" id="TableEvent">
                <thead>
                <?php if($BookedStatus === 'booked'){echo "<th>Status</th>";}?>
                <th>Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Pre Time</th>
                <th></th>
                </thead>
                <tbody id = 'table_body<?php echo $ReservationID?>'>

            <?php
            // PHP to put all of the date and time from the database
            $x = 0;
            for($x = 0; $x < $index; $x++)
            {
                echo
                    "<tr id='add_row$x'>";
                if($BookedStatus==='booked')
                {
                    echo "<td class = 'hidden'><input type='hidden' name='status$x' id='status$x' class = 'status hidden' value='" . $EventStatus[$x] ."' /></td>";
                    echo "<td><input type='text' name='status$x' id='status$x' class = 'status' disabled value='" . $EventStatus[$x] ."' /></td>";
                }
                   echo "<td><input type='text' name='date$x' id='date$x' class='datepicker' value='$Date[$x]' required/></td>".
                    "<td><input type='text' name='stime$x' id='stime$x' class='timepicker' value='$StartTime[$x]' required/></td>".
                    "<td><input type='text' name='etime$x' id='etime$x' class='timepicker' value='$EndTime[$x]' required/></td>".
                    "<td><input type='text' name='ptime$x' id='ptime$x' class='timepicker' value='$PreTime[$x]' required/></td>".
                    "<td class='hidden'><input type='hidden' name='eventsId$x' id='eventsId$x' class='eventID' value='$EventID[$x]'/></td>";
                if($BookedStatus === 'booked' && ($EventStatus[$x] === 'reserved' || $EventStatus[$x] === 'finished'))
                {
                    echo "<td class = 'hidden'><input type='hidden'  name='prgoogle$x' id='prgoogle$x' value='$PrivateGoogle[$x]'/></td>".
                         "<td class = 'hidden'><input type='hidden' name='pugoogle$x' id='pugoogle$x' value='$PublicGoogle[$x]'/></td>".
                         "<td><a id='deleteBookedDate' class='btn btn-danger deleteBookedEvent'>Cancel</a></td>".
                         "</tr>";
                }
                else {
                    echo "<td><a id='deleteEvent' class='btn btn-danger deleteEvent'>Delete</a></td>".
                         "</tr>";
                }
            }
            ?>
                </tbody>
            </table>
                <a id="add_date_btn" class="btn btn-primary add_date_btn">Add Date</a>
                <br><br>
                <script>
                    $(document).ready(function () {
                        var index = <?php echo $x?>;
                        $(document.body).on('click', '.deleteBookedEvent', function(){
                            $(this).closest('tr').find('.status').val('delete');
                            $(this).removeClass();
                            $(this).html('Undo?');
                            $(this).toggleClass('btn btn-primary undeleteEvent');

                        });
                        $(document.body).on('click', '.undeleteEvent', function(){
                            $(this).closest('tr').find('.status').val('reserved');
                            $(this).removeClass();
                            $(this).html('Cancel');
                            $(this).toggleClass('btn btn-danger deleteBookedEvent');

                        });
                        //Delete the date and time from the sql
                        //Must use document.body for dynamic tables
                        $(document.body).on('click', '.deleteEvent', function(){
                            var DeleteEventID = $(this).closest('tr').find('.eventID').val();
                            $.ajax({
                                type: 'POST',
                                url: 'php/Edit_Form.php',
                                data: {DeleteEventID: DeleteEventID},
                                success:function(){

                                    console.log("Event Deleted");
                                },
                                error:function () {
                                    console.log("Error on deleting data");
                                }
                            });
                            //Delete the HTML Row
                            $(this).parent().parent().remove();
                            event.preventDefault();
                        });

                        //Dialog Box for Adding dates and time to the sql
                        $("#div_pop_dt").dialog({
                            autoOpen: false,
                            open:function(){
                                $('.datepicker').datepicker();
                                $('.timepicker').timepicker({'minTime': '7:30am',
                                    'maxTime': '11:30pm',
                                    disableText:true

                                });
                            },
                            buttons: {
                                Insert: function () {
                                    var form = $('#new_Event');

                                    if(valid(form)) {
                                        var newDateForm = form.serialize();
                                        $.ajax({
                                            type: "POST",
                                            url: "php/BookEvent.php",
                                            data: newDateForm + '&index='+index,
                                            success: function (report) {
                                                console.log(report);
                                                $('#table_body<?php echo $ReservationID?>').append(report);
                                                $('#div_pop_dt').dialog("close");
                                            },
                                            error: function () {
                                                console.log("Error On New Date");
                                                $('#div_pop_dt').dialog("close");
                                            }
                                        });
                                        index++;
                                        form.trigger('reset');
                                    }
                                    else{
                                        alert("Please fill out all of the fields");
                                    }
                                },
                                Cancel: function () {

                                    $('#new_Event').trigger('reset');
                                    $(this).dialog("close");

                                }
                            }
                        });
                        var addDate = $('.add_date_btn').on('click', function(){
                            $('#Book_Status').val("<?php echo $BookedStatus ?>");
                            $("#ReservationID_newEvent").val("<?php echo $ReservationID ?>");
                            $("#div_pop_dt").dialog("open")
                                .dialog("option", "width", 500);
                        });
                        $('body').on('focus', '.datepicker', function(){
                            $('.datepicker').datepicker();
                        });

                        $('.timepicker').timepicker({'minTime': '8:00am',
                            'maxTime': '11:30pm',
                            disableText:true
                        });


                    });
                </script>

                <label class="checkbox-inline"><input type="checkbox" name="Smartboard" value="Smartboard" <?php echo($smartBoard === 'Yes')? 'checked':''?> >Smartboard</label></td>
                <label class="checkbox-inline"><input type="checkbox" name="projector" value="projector"<?php echo($Projector === 'Yes')? 'checked':''?>>Projector</label></td>
                <label class="checkbox-inline"><input type="checkbox" name="documentcamera" value="documentcamera"<?php echo($ExtensionCord === 'Yes')? 'checked':''?>>Document Camera</label></td>
                <label class="checkbox-inline"><input type="checkbox" name="extensioncords" value="extensioncords"<?php echo($DocumentCamera === 'Yes')? 'checked':''?>>Extension Cords</label></td>
                <label style="font-size: 20px; " for="avguy">*A/V tech needed to assist with setup?<input type="checkbox" id = "avguy" name="avsetup" style="width: 30px; height: 20px; cursor: pointer" value="avsetup" <?php echo($AV_Need === 'Yes')? 'checked':''?> ></label>
            </div>
        </div>

        <?php
        if($BookedStatus === 'booked')
        {
            echo '<button type="button" class="btn btn-primary pull-left bookedBtns" id="updateBookedEvent'.$ReservationID.'">Update Reservation</button>';
            echo '<button type="button"" class="btn btn-danger pull-right delete_Booked" id="deleteBooked'.$ReservationID.'">Delete Reservation</button>';

        }
        else if($BookedStatus==='pending')
        {
            echo '<button type="button" class="btn btn-primary pull-left" id="bookEvent'.$ReservationID.'">Book Request</button>';
            echo '<button type="button" class="btn btn-danger pull-right  deletePending" id="deletePending'.$ReservationID.'">Delete Request</button>';
        }
        else if($BookedStatus==='canceled')
        {
            echo '<button type="button" class="btn btn-primary pull-left" id="bookEvent'.$ReservationID.'">Book Event</button>';
            echo '<button type="button" class= "btn btn-danger pull-right" id="permanentDelete'.$ReservationID.'">Permanent Delete</button>';
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function(){

                HandleClick(<?php echo $ReservationID?>);
                HandleUpdatePage(<?php echo "$ReservationID , '$RoomReservation'"?>);
            });
        </script>

    </form>
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
