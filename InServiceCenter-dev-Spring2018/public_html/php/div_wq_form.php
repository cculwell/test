<?php
session_start();
require "../../resources/config.php";
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

//echo "Today is " . date("m/d/Y") . "<br>";

?>

<?PHP
if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin' || $_SESSION['valid_status']=='User'))
{
if (isset($_POST['request_id'])) {
    //echo "This var is set so I will print.";
    $request_id = $_POST['request_id'];


    $sql  = "select ";
    $sql .= "  r.request_id ";
    $sql .= ", r.request_type ";
    $sql .= ", r.workflow_state ";
    $sql .= ", r.school ";
    $sql .= ", r.system ";
    $sql .= ", r.request_desc ";
    $sql .= ", r.request_just ";
    $sql .= ", r.request_location ";
    $sql .= ", r.target_participants ";
    $sql .= ", r.enrolled_participants ";
    $sql .= ", r.total_cost ";
    $sql .= ", r.eval_method ";
    $sql .= ", r.stipd ";
    $sql .= ", r.workshop ";
    $sql .= ", r.report_date ";
    $sql .= ", r.request_title ";
    $sql .= ", r.folder_completed ";
    $sql .= ", r.director_name ";
    $sql .= ", r.board_approval ";
    $sql .= ", r.amt_sponsored ";
    $sql .= ", r.payment_type ";
    // inservice_order in table but should be removed ?

    $sql .= ", b.book_id ";
    $sql .= ", b.book_title ";
    $sql .= ", b.publisher ";
    $sql .= ", b.isbn ";
    $sql .= ", b.cost_per_book ";
    $sql .= ", b.study_format ";
    $sql .= ", b.admin_signature ";

    $sql .= ", w.workshop_id ";
    $sql .= ", w.program_nbr ";
    $sql .= ", w.pd_title ";
    $sql .= ", w.target_group ";
    $sql .= ", w.actual_participants ";
    $sql .= ", w.travel ";
    $sql .= ", w.room_res_needed ";
    $sql .= ", w.support_initiative ";
    $sql .= ", w.curriculum ";

    $sql .= " from requests r ";
    $sql .= " left join workshops w ";
    $sql .= "   on r.request_id = w.request_id ";
    $sql .= " left join books b ";
    $sql .= "   on r.request_id = b.request_id ";
    $sql .= " where r.request_id = ";
    $sql .= $request_id;

//    echo $sql;


    if ($result=mysqli_query($mysqli,$sql))
    {
//        $row = mysqli_fetch_row($result);

        $row = $result->fetch_array(MYSQLI_ASSOC);
        // Free result set
        mysqli_free_result($result);
    }


//        while (list($key, $value) = each($row)) {
//            echo "Key: $key; Value: $value<br />\n";
//        }

//    print_r($row);

    // Requests
    $request_type = $row['request_type'];
    $workflow_state = $row['workflow_state'];
    $system = str_replace(' ','_',$row['system']);
    $school = str_replace(' ','_',$row['school']);
    $request_desc = $row['request_desc'];
    $request_just = $row['request_just'];
    $request_location = $row['request_location'];
    $target_participants = $row['target_participants'];
    $enrolled_participants = $row['enrolled_participants'];
    $total_cost = $row['total_cost'];
    $eval_method = $row['eval_method'];
    $stipd = $row['stipd'];
    $workshop = $row['workshop'];
    $report_date = $row['report_date'];
    $request_title = $row['request_title'];
    $folder_completed = $row['folder_completed'];
    $director_name = $row['director_name'];
    $board_approval = $row['board_approval'];
    $amt_sponsored = $row['amt_sponsored'];
    $payment_type = $row['payment_type'];

    // Books
    $book_id = $row['book_id'];
    $book_title = $row['book_title'];
    $publisher = $row['publisher'];
    $isbn = $row['isbn'];
    $cost_per_book = $row['cost_per_book'];
    $study_format = $row['study_format'];
    $admin_signature = $row['admin_signature'];

    // Workshops
    $workshop_id = $row['workshop_id'];
    $program_nbr = $row['program_nbr'];
    $pd_title = $row['pd_title'];
    $target_group = $row['target_group'];
    $actual_participants = $row['actual_participants'];
    $travel = $row['travel'];
    $room_res_needed = $row['room_res_needed'];
    $support_initiative = $row['support_initiative'];
    $curriculum = $row['curriculum'];

}
else
{
    // Requests
    $request_id = null;
    $request_type = null;
    $workflow_state = null;
    $system = null;
    $school = null;
    $request_desc = null;
    $request_just = null;
    $request_location = null;
    $target_participants = 0;
    $enrolled_participants = 0;
    $total_cost = 0;
    $eval_method = null;
    $stipd = null;
    $workshop = null;
    $report_date = date("Y-m-d");
    $request_title = null;
    $folder_completed = null;
    $director_name = null;
    $board_approval = null;
    $amt_sponsored = 0;
    $payment_type = null;

    // Books
    $book_id = null;
    $book_title = null;
    $publisher = null;
    $isbn = null;
    $cost_per_book = 0;
    $study_format = null;
    $admin_signature = null;

    // Workshops
    $workshop_id = null;
    $program_nbr = null;
    $pd_title = null;
    $target_group = null;
    $actual_participants = 0;
    $travel = null;
    $room_res_needed = null;
    $support_initiative = null;
    $curriculum = null;
}
?>

<style>
    label {
        font-size: smaller;
    }

    input {
        font-size: smaller;
    }

    select {
        font-size: smaller;
    }

    option {
        font-size: smaller;
    }

    .ui-widget
    {
        font-size: 80%;
    }

</style>

<form id="request_form">

    <!-- Request Info -->
    <div class="row input-group" id="request_buttons">
        <div class="form-group col-xs-12" id="request_btn_sec">
            <button id="new_request_btn" name="new_request_btn">New</button>
            <button id="save_request_btn" name="save_request_btn">Save</button>
        </div>

        <script>

            new_request_btn = $("#new_request_btn").button();

            new_request_btn.click(function(e) {
                e.preventDefault();
                location.reload();
            });

            save_request_btn = $("#save_request_btn").button();

            save_request_btn.click(function (e) {
                e.preventDefault();
                //request table items
                $request_id = $("#request_id").val();
                $request_type = $("#request_type").val();
                $workflow_state = $("#workflow_state").val();
                $school = $("#school").val();
                $system = $("#system").val();
                $request_desc = $("#request_desc").val();
                $request_just = $("#request_just").val();
                $request_location = $("#request_location").val();
                $target_participants = $("#target_participants").val();
                $enrolled_participants = $("#enrolled_participants").val();
                $total_cost = $("#total_cost").val();
                $eval_method = $("#eval_method").val();
                $stipd = $("#stipd").val();
                $workshop = $("#workshop").val();
                $report_date = $("#report_date").val();
                $request_title = $("#request_title").val();
                $folder_completed = $("#folder_completed").val();
                $director_name = $("#director_name").val();
                $board_approval = $("#board_approval").val();
                $amt_sponsored = $("#amt_sponsored").val();
                $payment_type = $("#payment_type").val();

                $book_id = $("#book_id").val();
                $book_title = $("#book_title").val();
                $publisher = $("#publisher").val();
                $isbn = $("#isbn").val();
                $cost_per_book = $("#cost_per_book").val();
                $study_format = $("#study_format").val();
                $admin_signature = $("#admin_signature").val();

                $workshop_id = $("#workshop_id").val();
                $program_nbr = $("#program_nbr").val();
                $pd_title = $("#pd_title").val();
                $target_group = $("#target_group").val();
                $actual_participants = $("#actual_participants").val();
                $travel = $("#travel").val();
                $room_res_needed = $("#room_res_needed").val();
                $support_initiative = $("#support_initiative").val();
                $curriculum = $("#curriculum").val();

//
//                if($request_id == null || $request_id == undefined || $.isEmptyObject($request_id)){
//                    console.log("new request");
//                } else {
//                    console.log("update request");

                    $.ajax({
                        type: "POST",
                        url: "php/workqueue.php",
                        data: {
                            trigger_name: "save_request",
                            // request table items
                            request_id: $request_id,
                            request_type: $request_type,
                            workflow_state: $workflow_state,
                            school: $school,
                            system: $system,
                            request_desc: $request_desc,
                            request_just: $request_just,
                            request_location: $request_location,
                            target_participants: $target_participants,
                            enrolled_participants: $enrolled_participants,
                            total_cost: $total_cost,
                            eval_method: $eval_method,
                            stipd: $stipd,
                            workshop: $workshop,
                            report_date: $report_date,
                            request_title: $request_title,
                            folder_completed: $folder_completed,
                            director_name: $director_name,
                            board_approval: $board_approval,
                            amt_sponsored: $amt_sponsored,
                            payment_type: $payment_type,

                            book_id: $book_id,
                            book_title: $book_title,
                            publisher: $publisher,
                            isbn: $isbn,
                            cost_per_book: $cost_per_book,
                            study_format: $study_format,
                            admin_signature: $admin_signature,

                            workshop_id: $workshop_id,
                            program_nbr: $program_nbr,
                            pd_title: $pd_title,
                            target_group: $target_group,
                            actual_participants: $actual_participants,
                            travel: $travel,
                            room_res_needed: $room_res_needed,
                            support_initiative: $support_initiative,
                            curriculum: $curriculum

                        },
                        dataType: "json",
                        success: function(data) {
                            console.log("success: save_request");
//                            console.log(data);
//                            console.debug(data);
//                            var obj = JSON.parse(data);
                            test = JSON.stringify(data,null,'\t');
                            console.log(test);
                            $("#request_id").val(data.request_id);
                            $("#workshop").val(data.workshop_id);
                            $("#book_id").val(data.book_id);
                        },
                        error: function(data) {
                            console.log("error: save_request");
                            console.log(data);

                        },
                        complete: function(data) {
                            console.log("complete: save_request");
                            $("#div_wq_tables").load( 'php/div_wq_tables.php' );
                        }
                    });

//                }
            });

        </script>
    </div>

    <div class="row form-group" id="request_info">
        <div class="col-xs-4">
            <label for="request_id">Request ID:</label>
            <input type="text" id="request_id" name="request_id" size="5"
                   style="text-align: center"
                   value="<?php echo $request_id;?>" disabled>

        </div>

        <input id="workshop_id" name="workshop_id" value="<?php echo $workshop_id;?>" hidden>

        <div class="col-xs-4 col-xs-pull-0">
            <label for="workshop">Workshop:</label>
            <input type="checkbox" id="workshop" name="workshop" size="10"
                   value="<?php echo $workshop;?>">

            <script>

                if($("#workshop").val() == 'Yes'){
                    $( "#workshop" ).prop( "checked", true );

                    $("#program_nbr_fg").show();
                    $("#pd_title_fg").show();
                    $("#target_group_fg").show();
                    $("#actual_participants_fg").show();
                    $("#travel_fg").show();
                    $("#room_res_fg").show();
                    $("#curriculum_sec").show();
                    $("#support_initiative_sec").show();
                } else {
                    $( "#workshop" ).prop( "checked", false );

                    $("#program_nbr_fg").hide();
                    $("#pd_title_fg").hide();
                    $("#target_group_fg").hide();
                    $("#actual_participants_fg").hide();
                    $("#travel_fg").hide();
                    $("#room_res_fg").hide();
                    $("#curriculum_sec").hide();
                    $("#support_initiative_sec").hide();
                }

                $("#workshop").change(function(e) {
                    if (this.checked) {
                        $("#workshop").val('Yes');

                        $("#program_nbr_fg").show();
                        $("#pd_title_fg").show();
                        $("#target_group_fg").show();
                        $("#actual_participants_fg").show();
                        $("#travel_fg").show();
                        $("#room_res_fg").show();
                        $("#curriculum_sec").show();
                        $("#support_initiative_sec").show();
                    } else {
                        $("#workshop").val('No');

                        $("#program_nbr_fg").hide();
                        $("#pd_title_fg").hide();
                        $("#target_group_fg").hide();
                        $("#actual_participants_fg").hide();
                        $("#travel_fg").hide();
                        $("#room_res_fg").hide();
                        $("#curriculum_sec").hide();
                        $("#support_initiative_sec").hide();
                    }
                });



            </script>

        </div>

        <div class="col-xs-4 col-xs-pull-0">
            <label for="report_date">Report:</label>
            <input type="date" id="report_date" name="report_date" size="5"
                   style="width: 125px;"
                   value="<?php echo $report_date;?>" class="">
        </div>
    </div>

    <div class="row form-group" id="request_type_state_row">

        <div class="col-xs-4 form-group">
            <label class="col-xs-4 control-label" for="workflow_state">Workflow:</label>

            <div class="col-xs-8">
                <select id="workflow_state" class="form-control ui-corner-all" style="width: 150px;">
                    <option <?php if($workflow_state == 'New') echo"selected";?> value="New">New</option>
                    <option <?php if($workflow_state == 'Under Review') echo"selected";?> value="Under Review">Under Review</option>
                    <option <?php if($workflow_state == 'Board Vote') echo"selected";?> value="Board Vote">Board Vote</option>
                    <option <?php if($workflow_state == 'Start Purchase Order') echo"selected";?> value="Start Purchase Order">Start Purchase Order</option>
                    <option <?php if($workflow_state == 'Order/Contract Issued') echo"selected";?> value="Order/Contract Issued">Order/Contract Issued</option>
                    <option <?php if($workflow_state == 'Completed') echo"selected";?> value="Completed">Completed</option>
                    <option <?php if($workflow_state == 'Canceled') echo"selected";?> value="Canceled">Canceled</option>
                </select>
                <script>
                    $(document).ready(function(){
                        $('#workflow_state').change(function(e){
                            $this = $(e.target);
                            $request_id = $('#request_id')[0].value;
                            $.ajax({
                                type: "POST",
                                url:  "php/workqueue.php", // Don't know asp/asp.net at all so you will have to do this bit
                                data: { trigger_name: "workflow_state_change",
                                    request_id: $request_id,
                                    workflow_state: $this.val()
                                },
                                dataType: "html",
                                success: function(data){
                                    console.log("success:");
                                    console.log(data);
                                    $("#div_wq_tables").load( 'php/div_wq_tables.php' );

                                },
                                error: function(data){
                                    console.log("success:");
                                },
                                complete: function (data) {

                                }
                            });
                        });

                    });
                </script>
            </div>
        </div>


        <div class="col-xs-4 form-group">
            <label class="col-xs-3 control-label" for="request_type">Type:</label>

            <div class="col-xs-9 col-xs-pull-1" size="10">
                <select id="request_type" class="form-control">
                    <option <?php if($request_type == 'General') echo"selected";?> value="General">General</option>
                    <option <?php if($request_type == 'BookStudy') echo"selected";?> value="BookStudy">Book Study</option>
                </select>

                <script>

                    $("#request_type").change(function(e){
                        e.preventDefault();
                        rt_val = $("#request_type").val();
                        if(rt_val == 'General') {
//                            console.log('General');
                            $("#request_desc_row").show();
                            $("#book_title_row").hide();
                            $("#format_method_row").show();
                            $("#study_format_sec").hide();
                            $("#eval_method_sec").show();
                            $("#cost_per_book_div").hide();
                            $("#admin_signature_div").hide();

                        } else {
//                            console.log('BookStudy');
                            $("#request_desc_row").hide();
                            $("#book_title_row").show();
                            $("#format_method_row").show();
                            $("#study_format_sec").show();
                            $("#eval_method_sec").hide();
                            $("#cost_per_book_div").show();
                            $("#admin_signature_div").show();
                        }
                    });

                    $(document).ready(function(){
                        rt_val = $("#request_type").val();
                        if(rt_val == 'General') {
//                            console.log('General');
                            $("#request_desc_row").show();
                            $("#book_title_row").hide();
                            $("#format_method_row").show();
                            $("#study_format_sec").hide();
                            $("#eval_method_sec").show();
                            $("#cost_per_book_div").hide();
                            $("#admin_signature_div").hide();
                        } else {
//                            console.log('BookStudy');
                            $("#request_desc_row").hide();
                            $("#book_title_row").show();
                            $("#format_method_row").show();
                            $("#study_format_sec").show();
                            $("#eval_method_sec").hide();
                            $("#cost_per_book_div").show();
                            $("#admin_signature_div").show();
                        }
                    });

                </script>

            </div>
        </div>


        <div class="col-xs-4 col-xs-pull-1 form-group" id="support_initiative_sec">
            <label class="col-xs-8 control-label" for="support_initiative">Support Initiative:</label>

            <div class="col-xs-4 col-xs-pull-2" size="5">
                <select id="support_initiative" name="support_initiative" class="form-control" style="width: 100px">
                    <option <?php if($support_initiative == '') echo"selected";?> value=""></option>
                    <option <?php if($support_initiative == 'AMSTI') echo"selected";?> value="AMSTI">AMSTI</option>
                    <option <?php if($support_initiative == 'ASIM') echo"selected";?> value="ASIM">ASIM</option>
                    <option <?php if($support_initiative == 'TIM') echo"selected";?> value="TIM">TIM</option>
                    <option <?php if($support_initiative == 'RIC') echo"selected";?> value="RIC">RIC</option>
                    <option <?php if($support_initiative == 'LEA') echo"selected";?> value="LEA">LEA</option>
                    <option <?php if($support_initiative == 'ALSDE') echo"selected";?> value="ALSDE">ALSDE</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row col-xs-12 pull-left" id="request_title_row">

        <div class="col-xs-8 col-xs-pull-1 form-group">
            <label for="request_title">Title:</label>
            <input type="text"  id="request_title" name="request_title" style="width: 270px"
                   maxlength="100" value="<?php echo $request_title;?>">
        </div>

    </div>


    <div class="row col-xs-12 form-group pull-left" id="pd_title_row">

        <div class="col-xs-8 col-xs-pull-1 form-group" id="pd_title_fg">
            <label for="pd_title">PD Title:</label>
            <input type="text"  id="pd_title" name="pd_title" style="width: 250px"
                    maxlength="100" value="<?php echo $pd_title;?>">
        </div>

        <div class="col-xs-4 col-xs-pull-2 form-group" id="program_nbr_fg">
            <label for="program_nbr">Program #:</label>
            <input type="text" id="program_nbr" name="program_nbr" size="10"
                   value="<?php echo $program_nbr;?>"
        </div>

    </div>



    <div class="row form-group" id="school_system_row">
        <div class="col-xs-6 pull-left">
            <label for="system">System:</label>
            <style> select { width: 200px } </style>

            <select id="system" name="system">
                <option value="">--</option>
                <?php
                $system_results = $mysqli->query("select distinct system from systems_schools order by system") or die($mysqli->error);
                while ($row = mysqli_fetch_array($system_results)) {
                    $system_val = str_replace(' ','_',$row['system']);
                    echo "<option value='" . $system_val . "'>" . $row['system'] . "</option>";
                }
                ?>
            </select>

        </div>


        <div class="col-xs-6 pull-left">
            <label for="system">School:</label>
            <style> select { width: 200px } </style>

            <select id="school" name="school">
                <option value="">--</option>
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
            $('#system').val("<?php echo $system; ?>").attr('selected','selected');
            $("#school").chained("#system");
            $('#school').val("<?php echo $school; ?>").attr('selected','selected');


        </script>

    </div>

        <div id="wq_detail_tabs">
            <ul id="wq_details_tabs_ul">
                <li><a href="#request_info">Request Info</a></li>
                <li><a href="#contacts">Contacts</a></li>
                <li><a href="#expenses">Expenses</a></li>
                <li><a href="#eval_comments">Comments</a></li>
                <li><a href="#staff_notes">Notes</a></li>
            </ul>

            <div id="request_info">
                <!-- Request Description -->
                <div class="row form-group" id="request_desc_row">
                    <div class="col-xs-12">
                        <label class="col-md-push-12 pull-left">Request Description</label>
                        <textarea class="form-control col-md-6" style="width:100%" rows="3"
                                  id="request_desc" name="request_desc"><?php echo $request_desc;?></textarea>
                    </div>
                </div>




                <!-- Request Justification -->
                <div class="row form-group" id="request_just_row">
                    <div class="col-xs-12">
                        <label class="col-md-push-12 pull-left">Need / Justification</label>
                        <textarea class="form-control col-md-6" style="width:100%" rows="3"
                                  id="request_just" name="request_just"><?php echo $request_just;?></textarea>
                    </div>
                </div>



                <!-- Book Title -->
                <div class="row form-group" id="book_title_row">
                    <input type="number" id="book_id" name="book_id" hidden value="<?php echo $book_id;?>">

                    <div class="form-group col-xs-4">
                        <label for="book_title">Book Title</label>
                        <input type="text" id="book_title" name="book_title" size="20" maxlength="100"
                               style="text-align: center"
                            value="<?php echo $book_title;?>">
                    </div>

                    <div class="form-group col-xs-4 ">
                        <label for="publisher">Publisher</label>
                        <input type="text"  id="publisher" name="publisher" size="20" maxlength="50"
                               style="text-align: center"
                            value="<?php echo $publisher;?>">
                    </div>
                    <div class="form-group col-xs-4">
                        <label for="isbn" style="width: 100px;">ISBN</label>
                        <input type="text"  id="isbn" name="isbn" size="20" maxlength="25"
                               style="text-align: center"
                            value="<?php echo $isbn;?>">
                    </div>

                </div>


                <!-- Method of Eval or Format of Study -->
                <div class="row form-group" id="format_method_row">

                    <div class="form-group col-xs-4 " id="study_format_sec">
                        <label for="study_format">Study Format</label>
                        <input type="text"  id="study_format" name="study_format" size="20"
                               style="text-align: center"
                               maxlength="100" value="<?php echo $study_format;?>">
                    </div>

                    <div class="form-group col-xs-4" id="eval_method_sec">
                        <label for="eval_method">Evaluation Method</label>
                        <input type="text"  id="eval_method" name="eval_method" size="20"
                               style="text-align: center"
                               maxlength="50" value="<?php echo $eval_method;?>">
                    </div>

                    <div class="form-group col-xs-4">
                        <div class="input-group input-group-xs">
                            <label for="total_cost">Request Amt.</label>
                            <input type="text"  id="total_cost" name="total_cost" size="20"
                                   style="text-align: center"
                                   maxlength="25" value="<?php echo $total_cost;?>">
                        </div>
                    </div>
                    <div class="form-group col-xs-4" id="cost_per_book_div">
                        <label for="cost_per_book">Cost / Book:</label>
                        <input type="text"  id="cost_per_book" name="cost_per_book" maxlength="25" size="20"
                               style="text-align: center"
                               value="<?php echo $cost_per_book; ?>">
                    </div>
                </div>

                <!-- Location and Targets -->
                <div class="row form-group" id="location_participants_row">
                    <div class="form-group col-xs-4" id="target_part_sec">
                        <label for="eval_method">Target #</label>
                        <input type="text"  id="target_participants" name="target_participants" size="20"
                               style="text-align: center"
                               maxlength="50" value="<?php echo $target_participants;?>">
                    </div>

                    <div class="form-group col-xs-4">
                        <label for="total_cost">Enrolled #</label>
                        <input type="text"  id="enrolled_participants" name="enrolled_participants" size="20"
                               style="text-align: center"
                               maxlength="25" value="<?php echo $enrolled_participants;?>">
                    </div>

                    <div class="form-group col-xs-4" id="actual_participants_fg">
                        <label for="total_cost">Actual #</label>
                        <input type="text"  id="actual_participants" name="actual_participants" size="20"
                               style="text-align: center"
                               maxlength="25" value="<?php echo $actual_participants;?>">
                    </div>
                </div>


                <!-- curriculum -->
                <div class="row input-group col-xs-12" id="curriculum_participants_row">
                    <div class="form-group col-xs-4" id="curriculum_sec">
                        <label for="curriculum">Curriculum:</label>
                        <select id="curriculum" name="curriculum">
                            <option <?php if($curriculum == '') echo"selected";?>  value="">--</option>
                            <option <?php if($curriculum == 'Biology') echo"selected";?>  value="Biology">Biology</option>
                            <option <?php if($curriculum == 'Chemistry') echo"selected";?>  value="Chemistry">Chemistry</option>
                            <option <?php if($curriculum == 'English/Language Art') echo"selected";?>  value="English/Language Art">English/Language Art</option>
                            <option <?php if($curriculum == 'Technology') echo"selected";?>  value="Technology">Technology</option>
                            <option <?php if($curriculum == 'Career Tech') echo"selected";?>  value="Career Tech">Career Tech</option>
                            <option <?php if($curriculum == 'Counseling') echo"selected";?>  value="Counseling">Counseling</option>
                            <option <?php if($curriculum == 'Climate and Culture') echo"selected";?>  value="Climate and Culture">Climate and Culture</option>
                            <option <?php if($curriculum == 'Effective Instruction') echo"selected";?>  value="Effective Instruction">Effective Instruction</option>
                            <option <?php if($curriculum == 'Fine Arts') echo"selected";?>  value="Fine Arts">Fine Arts</option>
                            <option <?php if($curriculum == 'Foreign Language') echo"selected";?>  value="Foreign Language">Foreign Language</option>
                            <option <?php if($curriculum == 'Gifted') echo"selected";?>  value="Gifted">Gifted</option>
                            <option <?php if($curriculum == 'Interdisciplinary') echo"selected";?>  value="Interdisciplinary">Interdisciplinary</option>
                            <option <?php if($curriculum == 'Leadership') echo"selected";?>  value="Leadership">Leadership</option>
                            <option <?php if($curriculum == 'Library Media Services') echo"selected";?>  value="Library Media Services">Library Media Services</option>
                            <option <?php if($curriculum == 'Mathematics') echo"selected";?>  value="Mathematics">Mathematics</option>
                            <option <?php if($curriculum == 'NBCT') echo"selected";?>  value="NBCT">NBCT</option>
                            <option <?php if($curriculum == 'Physics') echo"selected";?>  value="Physics">Physics</option>
                            <option <?php if($curriculum == 'Physical Education') echo"selected";?>  value="Physical Education">Physical Education</option>
                            <option <?php if($curriculum == 'Science') echo"selected";?>  value="Science">Science</option>
                            <option <?php if($curriculum == 'Social Studies') echo"selected";?>  value="Social Studies">Social Studies</option>
                            <option <?php if($curriculum == 'Special Education') echo"selected";?>  value="Special Education">Special Education</option>
                            <option <?php if($curriculum == 'Other') echo"selected";?>  value="Other">Other</option>
                        </select>

                        <script>
                            $("#curriculum").selectmenu();
                        </script>
                    </div>

                    <div class="form-group col-xs-4" id="target_group_fg">
                        <label for="target_group">Audience</label>
                        <input type="text"  id="target_group" name="target_group" maxlength="50" size="20"
                               style="text-align: center"
                               value="<?php echo $target_group;?>">
                    </div>

                    <div class="form-group col-xs-4" id="travel_fg">
                        <label for="travel">Travel:</label>
                        <select id="travel" name="travel">
                            <option <?php if($travel == '') echo"selected";?>  value="">--</option>
                            <option <?php if($travel == 'Yes') echo"selected";?>  value="Yes">Yes</option>
                            <option <?php if($travel == 'No') echo"selected";?>  value="No">No</option>
                        </select>

                        <script>
                            $("#travel").selectmenu();
                        </script>
                    </div>


                </div>

                <!-- Location and Targets -->
                <div class="row input-group" id="location_participants_row">
                    <div class="form-group col-xs-12" id="location_sec">
                        <label for="study_format">Location:</label>
                        <input type="text"  id="request_location" name="request_location" size="75"
                               maxlength="100" value="<?php echo $request_location;?>">
                    </div>
                </div>


                <!-- Date/Time Buttons -->
                <div class="row input-group" id="dt_button_row">
                    <div class="form-group col-xs-12" id="dt_button_sec">
                        <button id="dt_new_btn">New</button>
                        <button id="dt_edit_btn">Edit</button>
                        <button id="dt_delete_btn">Delete</button>
                    </div>
                </div>

                <!-- Date / Times-->
                <div id="date_time_div" class="form-group row">
                    <table id="tbl_date_times" class="display table-responsive" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Breaks</th>
                            <th>Hours</th>
                            <th>Notes</th>
<!--                            <th></th>-->
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
<!--                            <th></th>-->
                        </tr>
                        </tfoot>
                        <tbody>
                        <?PHP

                        $sql_dates_times  = "select ";
                        $sql_dates_times .= "request_dt_id, request_date, request_start_time, request_end_time, ";
                        $sql_dates_times .= "request_break_time, ";
                        $sql_dates_times .= "round(TIME_TO_SEC(timediff(request_end_time, request_start_time))/3600 - request_break_time, 2) as total_hours, ";
                        $sql_dates_times .= "request_dt_note ";
                        $sql_dates_times .= "from date_times where request_id =";
                        $sql_dates_times .= $request_id;
                        $sql_dates_times .= " order by request_date, request_start_time";

                        if ($result_dt=mysqli_query($mysqli,$sql_dates_times))
                        {
                            // Fetch one and one row
                            while ($row=mysqli_fetch_row($result_dt))
                            {
                                echo
                                    "<tr>"
                                        ."<td>".$row[0] ."</td>"
                                        ."<td>".$row[1] ."</td>"
                                        ."<td>".$row[2] ."</td>"
                                        ."<td>".$row[3] ."</td>"
                                        ."<td>".$row[4] ."</td>"
                                        ."<td>".$row[5] ."</td>"
                                        ."<td>".$row[6] ."</td>"
//                                        ."<td>"
//                                            . "<button type='button' class='btn btn-default btn-sm' id=''>"
//                                                . "<span class='glyphicon glyphicon-edit'></span>"
//                                            . "</button>"
//                                        ."</td>"
                                    ."</tr>";
                            }
                            // Free result set
                            mysqli_free_result($result_dt);
                        }

                        //      mysqli_close($mysqli);

                        ?>
                        </tbody>
                    </table>
                    <div id="div_pop_dt">
                        <form class="form form-vertical" id="pop_dt_form_id">
                            <div class="form-group pop_dt_id_grp">
                                <label class="column-label col-xs-3" for="pop_dt_id" hidden>ID</label>
                                <input class="col-xs-9" type="number" id="pop_dt_id" disabled hidden>
                            </div>
                            <div class="form-group">
                                <label class="column-label col-xs-3" for="pop_dt_date">Date:</label>
                                <input class="col-xs-9" type="date" id="pop_dt_date">
                            </div>
                            <div class="form-group">
                                <label class="column-label col-xs-3" for="pop_dt_start">Start Time:</label>
                                <input class="col-xs-9" type="time" id="pop_dt_start">
                            </div>
                            <div class="form-group">
                                <label class="column-label col-xs-3" for="pop_dt_end">End Time:</label>
                                <input class="col-xs-9" type="time" id="pop_dt_end">
                            </div>
                            <div class="form-group">
                                <label class="column-label col-xs-3" for="pop_dt_break">Break Time:</label>
                                <input class="col-xs-9" type="number" id="pop_dt_break">
                            </div>
                            <div class="form-group">
                                <label class="column-label col-xs-3" for="pop_dt_hours">Hours:</label>
                                <input class="col-xs-9" type="number" id="pop_dt_hours">
                            </div>
                            <div class="form-group">
                                <label class="column-label col-xs-3" for="pop_dt_note">Note:</label>
                                <input class="col-xs-9" type="text" id="pop_dt_note">
                            </div>
                        </form>
                    </div>
                    <script>
                        var date_times = $('#tbl_date_times').DataTable({
                            "footerCallback": function ( row, data, start, end, display ) {
                                var api = this.api(), data;

                                // Remove the formatting to get integer data for summation
                                var intVal = function ( i ) {
                                    return typeof i === 'string' ?
                                        i.replace(/[\$,]/g, '')*1 :
                                        typeof i === 'number' ?
                                            i : 0;
                                };

                                // Total over all pages
                                total = api
                                    .column( 5 )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );

                                // Total over this page
                                pageTotal = api
                                    .column( 5, { page: 'current'} )
                                    .data()
                                    .reduce( function (a, b) {
                                        return intVal(a) + intVal(b);
                                    }, 0 );

                                // Update footer
                                $( api.column( 5 ).footer() ).html(
                                    //pageTotal +' ( '+ total +' total)'
                                    'Total: ' + pageTotal
                                );
                            },
                            select: {
                                style:      'single'
                            },
                            ordering: false,
                            info:     false,
                            searching: false,
                            paging:   false,
                            columnDefs: [
                                {
                                    "targets": [ 0 ],
                                    "visible": false,
                                    "searchable": false
                                },
                                { "width": "15%", "targets": 1},
                                { "width": "15%", "targets": 2},
                                { "width": "15%", "targets": 3},
                                { "width": "10%", "targets": 4},
                                { "width": "15%", "targets": 5},
                                { "width": "25%", "targets": 6}
                            ]

                        });
                        date_times.row().select();

                        $("#div_pop_dt").dialog({
                            title: "Date / Time",
                            autoOpen: false,
                            buttons: {
                                "Add / Update": function(){
                                    $request_id = $("#request_id").val();
                                    $dt_id = $("#pop_dt_id").val();
                                    $dt_date = $("#pop_dt_date").val();
                                    $dt_start = $("#pop_dt_start").val();
                                    $dt_end = $("#pop_dt_end").val();
                                    $dt_break = $("#pop_dt_break").val();
                                    $dt_note = $("#pop_dt_note").val();

                                    console.log($dt_id);

                                    if($dt_id == null
                                        || $dt_id == undefined
                                        || $.isEmptyObject($dt_id)){

                                        console.log("new date/time");

                                        $.ajax({
                                            type: "POST",
                                            url: "php/workqueue.php",
                                            data: {
                                                trigger_name: "add_date_time",
                                                request_id: $request_id,
                                                dt_id: $dt_id,
                                                dt_date: $dt_date,
                                                dt_start: $dt_start,
                                                dt_end: $dt_end,
                                                dt_break: $dt_break,
                                                dt_note: $dt_note
                                            },
                                            dataType: "json",
                                            success: function(data) {
                                                console.log("success: add_date_time");
                                                    console.log(data);

                                                $("#pop_dt_id").val(data);

                                                date_times.row.add([
                                                    $("#pop_dt_id").val(),
                                                    $("#pop_dt_date").val(),
                                                    $("#pop_dt_start").val(),
                                                    $("#pop_dt_end").val(),
                                                    $("#pop_dt_break").val(),
                                                    $("#pop_dt_hours").val(),
                                                    $("#pop_dt_note").val()
                                                ]).draw();
                                            },
                                            error: function(data) {
                                                console.log("error: add_date_time");
                                                console.log(data);
                                            },
                                            complete: function(data) {
                                                console.log("complete: add_date_time");
                                                $('.ui-dialog-content').dialog('close');
                                                date_times.row().select();
                                            }
                                        });
                                    } else {
                                        console.log("edit date/time");

                                        $.ajax({
                                            type: "POST",
                                            url: "php/workqueue.php",
                                            data: {
                                                trigger_name: "update_date_time",
                                                request_id: $request_id,
                                                dt_id: $dt_id,
                                                dt_date: $dt_date,
                                                dt_start: $dt_start,
                                                dt_end: $dt_end,
                                                dt_break: $dt_break,
                                                dt_note: $dt_note
                                            },
                                            dataType: "json",
                                            success: function(data) {
                                                console.log("success: update_date_time");
                                                console.log(data);

                                                date_times
                                                    .rows('.selected')
                                                    .remove()
                                                    .draw();

                                                $("#pop_dt_id").val(data);

                                                date_times.row.add([
                                                    $("#pop_dt_id").val(),
                                                    $("#pop_dt_date").val(),
                                                    $("#pop_dt_start").val(),
                                                    $("#pop_dt_end").val(),
                                                    $("#pop_dt_break").val(),
                                                    $("#pop_dt_hours").val(),
                                                    $("#pop_dt_note").val()
                                                ]).draw();
                                            },
                                            error: function(data) {
                                                console.log("error: update_date_time");
                                                console.log(data);
                                            },
                                            complete: function(data) {
                                                console.log("complete: update_date_time");
                                                $('.ui-dialog-content').dialog('close');
                                                date_times.row().select();
                                            }
                                        });
                                    }

                                },
                                Cancel: function(){
                                    $(this).dialog("close");
                                }
                            }
                        });

                        $("#dt_new_btn").click(function(e) {
                            e.preventDefault();
                            $("#pop_dt_id").val(null);
                            $("#pop_dt_date").val(null);
                            $("#pop_dt_start").val(null);
                            $("#pop_dt_end").val(null);
                            $("#pop_dt_break").val(null);
                            $("#pop_dt_hours").val(null);
                            $("#pop_dt_note").val(null);

                            $("#div_pop_dt").dialog("open")
                                .dialog("option", "width", 500);

                        });

                        $("#dt_edit_btn").click(function(e) {
                            e.preventDefault();
                            var date_time = date_times.rows( { selected: true } ).data();
                            console.log(date_time[0]);
                            $("#pop_dt_id").val(date_time[0][0]);
                            $("#pop_dt_date").val(date_time[0][1]);
                            $("#pop_dt_start").val(date_time[0][2]);
                            $("#pop_dt_end").val(date_time[0][3]);
                            $("#pop_dt_break").val(date_time[0][4]);
                            $("#pop_dt_hours").val(date_time[0][5]);
                            $("#pop_dt_note").val(date_time[0][6]);


                            $("#div_pop_dt").dialog("open")
                                .dialog("option", "width", 500);

                        });

                        $("#dt_delete_btn").click(function(e) {
                            e.preventDefault();
//                            alert("You clicked Delete");

                            $this = $(e.target);
                            var dt_record = date_times.rows({ selected: true} ).data();
                            $dt_id = dt_record[0][0];
                            console.log($dt_id);

                            $.ajax({
                                type: "POST",
                                url: "php/workqueue.php",
                                data: { trigger_name: "datetime_delete",
                                    dt_id: $dt_id
                                },
                                dataType: "json",
                                success: function(data) {
                                    console.log("success: datetime delete");
                                    console.log(data);
                                },
                                error: function(data){
                                    console.log("error: datetime delete");
                                    console.log(data);
                                },
                                complete: function (data) {
                                    console.log("complete: datetime delete");
                                    console.log(data);
                                    date_times
                                        .rows('.selected')
                                        .remove()
                                        .draw();
                                    date_times.row().select();
                                }
                            });
                        });


                    </script>

                </div>
            </div>


            <div id="contacts">
                <!-- Contacts Buttons -->
                <div class="row input-group" id="contact_button_row">
                    <div class="form-group col-xs-12" id="contact_button_sec">
                        <button id="contact_new_btn">New</button>
                        <button id="contact_edit_btn">Edit</button>
                        <button id="contact_delete_btn">Delete</button>
                    </div>
                </div>


                <table id="tbl_contacts" class="display table-responsive" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Phone #</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Phone #</th>
                        <th>Email</th>
                        <th>Address</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?PHP

                    $sql_contacts  = "select ";
                    $sql_contacts .= "contact_id, contact_name, contact_role, contact_phn_nbr, contact_email, contact_address ";
                    $sql_contacts .= "from contacts where request_id =";
                    $sql_contacts .= $request_id;

                    if ($result_contacts=mysqli_query($mysqli,$sql_contacts))
                    {
                        // Fetch one and one row
                        while ($row=mysqli_fetch_row($result_contacts))
                        {
                            echo
                                "<tr>"
                                ."<td>".$row[0] ."</td>"
                                ."<td>".$row[1] ."</td>"
                                ."<td>".$row[2] ."</td>"
                                ."<td>".$row[3] ."</td>"
                                ."<td>".$row[4] ."</td>"
                                ."<td>".$row[5] ."</td>"
                                ."</tr>";
                        }
                        // Free result set
                        mysqli_free_result($result_contacts);
                    }

                    //      mysqli_close($mysqli);

                    ?>
                    </tbody>
                </table>

<!--                 Popup Contacts-->
                <div id="div_pop_contact">
                    <form class="form form-vertical" id="pop_contact_form_id">
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_contact_id" hidden>ID</label>
                            <input class="col-xs-9" type="number" id="pop_contact_id" disabled hidden>
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_contact_name">Name:</label>
                            <input class="col-xs-9" type="text" id="pop_contact_name">
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_contact_role">Role:</label>
<!--                            <input class="col-xs-9" type="text" id="pop_contact_role">-->
                            <select id="pop_contact_role" name="pop_contact_role" style="width: 300px">
                                <option value="">--</option>
                                <option value="Contact">Contact</option>
                                <option value="Company">Company</option>
                                <option value="Facilitator">Facilitator</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_contact_phn_nbr">Phone #:</label>
                            <input class="col-xs-9" type="tel" id="pop_contact_phn_nbr">
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_contact_email">Email:</label>
                            <input class="col-xs-9" type="email" id="pop_contact_email">
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_contact_address">Address:</label>
                            <input class="col-xs-9" type="text" id="pop_contact_address">
                        </div>
                    </form>
                </div>

                <script>
                    var contacts = $('#tbl_contacts').DataTable({
                        select: {
                            style:          'single'
                        },
                        columnDefs: [
                            {
                                "targets": [ 0 ],
                                "visible": false,
                                "searchable": false
                            }
                        ]
                    });
                    contacts.row().select();


                    var contact_dialog = $("#div_pop_contact").dialog({
                        title: "Contact",
                        autoOpen: false,
                        buttons: {
                            "Add / Update": function(){
                                $request_id = $("#request_id").val();
                                $c_id = $("#pop_contact_id").val();
                                $c_name = $("#pop_contact_name").val();
                                $c_role = $("#pop_contact_role").val();
                                $c_phn = $("#pop_contact_phn_nbr").val();
                                $c_email = $("#pop_contact_email").val();
                                $c_add = $("#pop_contact_address").val();

                                console.log($c_id);
                                if($c_id == null
                                      || $c_id == undefined
                                      || $.isEmptyObject($c_id)
                                  ) {
//                                   console.log("if true");
                                    $.ajax({
                                        type: "POST",
                                        url: "php/workqueue.php",
                                        data: { trigger_name: "add_contact",
                                            request_id:         $request_id,
                                            contact_name:       $c_name,
                                            contact_role:       $c_role,
                                            contact_phn_nbr:    $c_phn,
                                            contact_email:      $c_email,
                                            contact_address:    $c_add
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: add_contact");
//                                            console.log(data);

                                            $("#pop_contact_id").val(data);

                                            contacts.row.add( [
                                                $("#pop_contact_id").val(),
                                                $("#pop_contact_name").val(),
                                                $("#pop_contact_role").val(),
                                                $("#pop_contact_phn_nbr").val(),
                                                $("#pop_contact_email").val(),
                                                $("#pop_contact_address").val()
                                            ]).draw();

                                        },
                                        error: function(data){
                                            console.log("error: add_contact");
//                                            console.log(data);
                                        },
                                        complete: function(data){
//                                            console.log("complete: add_contact");
                                            $('.ui-dialog-content').dialog('close');
                                            contacts.row().select();
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url:  "php/workqueue.php",
                                        data: { trigger_name: "update_contact",
                                            contact_id:         $c_id,
                                            contact_name:       $c_name,
                                            contact_role:       $c_role,
                                            contact_phn_nbr:    $c_phn,
                                            contact_email:      $c_email,
                                            contact_address:    $c_add
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: edit_contact");
//                                            console.log(data);
                                            contacts
                                                .rows('.selected')
                                                .remove()
                                                .draw();
                                            contacts
                                                .row.add( [
                                                $("#pop_contact_id").val(),
                                                $("#pop_contact_name").val(),
                                                $("#pop_contact_role").val(),
                                                $("#pop_contact_phn_nbr").val(),
                                                $("#pop_contact_email").val(),
                                                $("#pop_contact_address").val()
                                                ])
                                                .draw();
                                        },
                                        error: function(data){
                                            console.log("error: edit_contact");
                                        },
                                        complete: function (data) {
                                            $('.ui-dialog-content').dialog('close');
                                            contacts.row().select();
                                        }
                                    });
                                }
                            },

                            Cancel: function(){
                                $(this).dialog("close");
                            }
                        }
                    });

                    $("#contact_new_btn").click(function(e) {
                        e.preventDefault();
                        $("#pop_contact_id").val(null);
                        $("#pop_contact_name").val(null);
                        $("#pop_contact_role").val(null);
                        $("#pop_contact_phn_nbr").val(null);
                        $("#pop_contact_email").val(null);
                        $("#pop_contact_address").val(null);

                        $("#div_pop_contact").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#contact_edit_btn").click(function(e) {
                        e.preventDefault();
                        var contact = contacts.rows( { selected: true } ).data();
                        $("#pop_contact_id").val(contact[0][0]);
                        $("#pop_contact_name").val(contact[0][1]);
                        $("#pop_contact_role").val(contact[0][2]);
                        $("#pop_contact_phn_nbr").val(contact[0][3]);
                        $("#pop_contact_email").val(contact[0][4]);
                        $("#pop_contact_address").val(contact[0][5]);

                        $("#div_pop_contact").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#contact_delete_btn").click(function(e) {
                        e.preventDefault();
                        $this = $(e.target);
                        var contact_record = contacts.rows( { selected: true } ).data();
                        $contact_id = contact_record[0][0];
                        console.log($contact_id);

                        $.ajax({
                            type: "POST",
                            url:  "php/workqueue.php",
                            data: { trigger_name: "delete_contact",
                                contact_id: $contact_id
                            },
                            dataType: "json",
                            success: function(data){
                                console.log("success:");
                                console.log(data);
                            },
                            error: function(data){
                                console.log("success:");
                            },
                            complete: function (data) {
                                contact_record
                                    .rows('.selected')
                                    .remove()
                                    .draw();
                            }
                        });
                    });


                </script>


            </div> <!-- End Contacts -->



            <div id="expenses">
                <div class="row input-group" id="expense_button_row">
                    <div class="form-group col-xs-12" id="expense_button_sec">
                        <button id="expense_new_btn">New</button>
                        <button id="expense_edit_btn">Edit</button>
                        <button id="expense_delete_btn">Delete</button>
                    </div>
                </div>


                <table id="tbl_expenses" class="display table-responsive" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Note</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?PHP

                    $sql_expenses  = "select ";
                    $sql_expenses .= "expense_id, expense_type, expense_amount, expense_note ";
                    $sql_expenses .= "from expenses where request_id = ";
                    $sql_expenses .= $request_id;

                    if ($result_expenses=mysqli_query($mysqli,$sql_expenses))
                    {
                        // Fetch one and one row
                        while ($row=mysqli_fetch_row($result_expenses))
                        {
                            echo
                                "<tr>"
                                ."<td>".$row[0] ."</td>"
                                ."<td>".$row[1] ."</td>"
                                ."<td>".$row[2] ."</td>"
                                ."<td>".$row[3] ."</td>"
                                ."</tr>";
                        }
                        // Free result set
                        mysqli_free_result($result_expenses);
                    }

                    //      mysqli_close($mysqli);

                    ?>
                    </tbody>
                </table>

                <!--                 Popup expenses-->
                <div id="div_pop_expense">
                    <form class="form form-vertical" id="pop_expense_form_id">
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_expense_id" hidden>ID</label>
                            <input class="col-xs-9" type="number" id="pop_expense_id" disabled hidden>
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_expense_type">Type:</label>
<!--                            <input class="col-xs-9" type="text" id="pop_expense_type">-->
                            <select id="pop_expense_type" name="pop_expense_type" style="width: 300px">
                                <option value="">--</option>
                                <option value="Books">Books</option>
                                <option value="Consultant Expense">Consultant Expense</option>
                                <option value="Facility Rental">Facility Rental</option>
                                <option value="Substitute Reimbursement">Substitute Reimbursement</option>
                                <option value="Travel Expense">Travel Expense</option>
                                <option value="Misc.">Misc.</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_expense_amount">Amount:</label>
                            <input class="col-xs-9" type="text" id="pop_expense_amount">
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_expense_note">Note:</label>
                            <input class="col-xs-9" type="tel" id="pop_expense_note">
                        </div>
                    </form>
                </div>

                <script>
                    var expenses = $('#tbl_expenses').DataTable({
                        "footerCallback": function ( row, data, start, end, display ) {
                            var api = this.api(), data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function ( i ) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '')*1 :
                                    typeof i === 'number' ?
                                        i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column( 2 )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Total over this page
                            pageTotal = api
                                .column( 2, { page: 'current'} )
                                .data()
                                .reduce( function (a, b) {
                                    return intVal(a) + intVal(b);
                                }, 0 );

                            // Update footer
                            $( api.column( 2 ).footer() ).html(
                                //pageTotal +' ( '+ total +' total)'
                                'Total: ' + pageTotal
                            );
                        },
                        select: {
                            style:          'single'
                        },
                        columnDefs: [
                            {
                                "targets": [ 0 ],
                                "visible": false,
                                "searchable": false
                            },
                            { "width": "20%", "targets": 1},
                            { "width": "30%", "targets": 2},
                            { "width": "50%", "targets": 3},
                        ]
                    });
                    expenses.row().select();


                    var expense_dialog = $("#div_pop_expense").dialog({
                        title: "Expense",
                        autoOpen: false,
                        buttons: {
                            "Add / Update": function(){
                                $request_id = $("#request_id").val();
                                $expense_id = $("#pop_expense_id").val();
                                $expense_type = $("#pop_expense_type").val();
                                $expense_amount = $("#pop_expense_amount").val();
                                $expsene_note = $("#pop_expense_note").val();

//                                console.log($expense_id);
                                if($expense_id == null
                                    || $expense_id == undefined
                                    || $.isEmptyObject($expense_id)
                                ) {
//                                   console.log("if true");
                                    $.ajax({
                                        type: "POST",
                                        url: "php/workqueue.php",
                                        data: { trigger_name: "add_expense",
                                            request_id:         $request_id,
                                            expense_id:         $expense_id,
                                            expense_type:       $expense_type,
                                            expense_amount:     $expense_amount,
                                            expense_note:       $expsene_note
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: add_expense");
//                                            console.log(data);

                                            $("#pop_expense_id").val(data);

                                            expenses.row.add( [
                                                $("#pop_expense_id").val(),
                                                $("#pop_expense_type").val(),
                                                $("#pop_expense_amount").val(),
                                                $("#pop_expense_note").val()
                                            ]).draw();

                                        },
                                        error: function(data){
                                            console.log("error: add_expense");
//                                            console.log(data);
                                        },
                                        complete: function(data){
                                            console.log("complete: add_expense");
                                            $('.ui-dialog-content').dialog('close');
                                            expenses.row().select();
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url:  "php/workqueue.php",
                                        data: { trigger_name: "update_expense",
                                            expense_id:         $expense_id,
                                            expense_type:       $expense_type,
                                            expense_amount:      $expense_amount,
                                            expense_note:       $expsene_note
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: edit_expense");
//                                            console.log(data);
                                            expenses
                                                .rows('.selected')
                                                .remove()
                                                .draw();
                                            expenses
                                                .row.add( [
                                                $("#pop_expense_id").val(),
                                                $("#pop_expense_type").val(),
                                                $("#pop_expense_amount").val(),
                                                $("#pop_expense_note").val()
                                            ])
                                                .draw();
                                        },
                                        error: function(data){
                                            console.log("error: edit_expense");
                                        },
                                        complete: function (data) {
                                            $('.ui-dialog-content').dialog('close');
                                            expenses.row().select();
                                        }
                                    });
                                }
                            },

                            Cancel: function(){
                                $(this).dialog("close");
                            }
                        }
                    });

                    $("#expense_new_btn").click(function(e) {
                        e.preventDefault();
                        $("#pop_expense_id").val(null);
                        $("#pop_expense_type").val(null);
                        $("#pop_expense_amount").val(null);
                        $("#pop_expense_note").val(null);

                        $("#div_pop_expense").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#expense_edit_btn").click(function(e) {
                        e.preventDefault();
                        var expense = expenses.rows( { selected: true } ).data();
                        $("#pop_expense_id").val(expense[0][0]);
                        $("#pop_expense_type").val(expense[0][1]);
                        $("#pop_expense_amount").val(expense[0][2]);
                        $("#pop_expense_note").val(expense[0][3]);

                        $("#div_pop_expense").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#expense_delete_btn").click(function(e) {
                        e.preventDefault();
                        $this = $(e.target);
                        var expense_record = expenses.rows( { selected: true } ).data();
                        $expense_id = expense_record[0][0];
                        console.log($expense_id);

                        $.ajax({
                            type: "POST",
                            url:  "php/workqueue.php",
                            data: { trigger_name: "delete_expense",
                                expense_id: $expense_id
                            },
                            dataType: "json",
                            success: function(data){
                                console.log("success:");
                                console.log(data);
                            },
                            error: function(data){
                                console.log("success:");
                            },
                            complete: function (data) {
                                expense_record
                                    .rows('.selected')
                                    .remove()
                                    .draw();
                            }
                        });
                    });


                </script>

            </div> <!-- End Expenses -->


            <div id="eval_comments">
                <div class="row input-group" id="comments_button_row">
                    <div class="form-group col-xs-12" id="comments_button_sec">
                        <button id="comment_new_btn">New</button>
                        <button id="comment_edit_btn">Edit</button>
                        <button id="comment_delete_btn">Delete</button>
                    </div>
                </div>


                <table id="tbl_comments" class="display table-responsive" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Comment</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?PHP

                    $sql_comments  = "select ";
                    $sql_comments .= "comment_id, comment_date, comment_text ";
                    $sql_comments .= "from comments where request_id = ";
                    $sql_comments .= $request_id;

                    if ($result_comments=mysqli_query($mysqli,$sql_comments))
                    {
                        // Fetch one and one row
                        while ($row=mysqli_fetch_row($result_comments))
                        {
                            echo
                                "<tr>"
                                ."<td>".$row[0] ."</td>"
                                ."<td>".$row[1] ."</td>"
                                ."<td>".$row[2] ."</td>"
                                ."</tr>";
                        }
                        // Free result set
                        mysqli_free_result($result_comments);
                    }

                    //      mysqli_close($mysqli);

                    ?>
                    </tbody>
                </table>

                <!--                 Popup comments-->
                <div id="div_pop_comment">
                    <form class="form form-vertical" id="pop_comment_form_id">
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_comment_id" hidden>ID</label>
                            <input class="col-xs-9" type="number" id="pop_comment_id" disabled hidden>
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_comment_date">Date:</label>
                            <input class="col-xs-9" type="date" id="pop_comment_date">
                        </div>

                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_comment_text">Comment:</label>
                            <input class="col-xs-9" type="text" id="pop_comment_text">

                        </div>
                    </form>
                </div>

                <script>
                    var comments = $('#tbl_comments').DataTable({
                        select: {
                            style:          'single'
                        },
                        columnDefs: [
                            {
                                "targets": [ 0 ],
                                "visible": false,
                                "searchable": false
                            },
                            { "width": "20%", "targets": 1},
                            { "width": "80%", "targets": 2}
                        ]
                    });

                    comments.row().select();


                    var comment_dialog = $("#div_pop_comment").dialog({
                        title: "Comment",
                        autoOpen: false,
                        buttons: {
                            "Add / Update": function(){
                                $request_id = $("#request_id").val();
                                $comment_id = $("#pop_comment_id").val();
                                $comment_date = $("#pop_comment_date").val();
                                $comment_text = $("#pop_comment_text").val();


//                                console.log($comment_id);
                                if($comment_id == null
                                    || $comment_id == undefined
                                    || $.isEmptyObject($comment_id)
                                ) {
//                                   console.log("if true");
                                    $.ajax({
                                        type: "POST",
                                        url: "php/workqueue.php",
                                        data: { trigger_name: "add_comment",
                                            request_id:         $request_id,
                                            comment_id:         $comment_id,
                                            comment_date:       $comment_date,
                                            comment_text:       $comment_text
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: add_comment");
//                                            console.log(data);

                                            $("#pop_comment_id").val(data);

                                            comments.row.add( [
                                                $("#pop_comment_id").val(),
                                                $("#pop_comment_date").val(),
                                                $("#pop_comment_text").val()
                                            ]).draw();

                                        },
                                        error: function(data){
                                            console.log("error: add_comment");
//                                            console.log(data);
                                        },
                                        complete: function(data){
                                            console.log("complete: add_comment");
                                            $('.ui-dialog-content').dialog('close');
                                            comments.row().select();
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url:  "php/workqueue.php",
                                        data: { trigger_name: "update_comment",
                                            comment_id:         $comment_id,
                                            comment_date:       $comment_date,
                                            comment_text:       $comment_text
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: edit_comment");
//                                            console.log(data);
                                            comments
                                                .rows('.selected')
                                                .remove()
                                                .draw();

                                            comments
                                                .row.add( [
                                                $("#pop_comment_id").val(),
                                                $("#pop_comment_date").val(),
                                                $("#pop_comment_text").val()
                                            ])
                                                .draw();
                                        },
                                        error: function(data){
                                            console.log("error: edit_comment");
                                        },
                                        complete: function (data) {
                                            $('.ui-dialog-content').dialog('close');
                                            comments.row().select();
                                        }
                                    });
                                }
                            },

                            Cancel: function(){
                                $(this).dialog("close");
                            }
                        }
                    });

                    $("#comment_new_btn").click(function(e) {
                        e.preventDefault();
                        $("#pop_comment_id").val(null);
                        $("#pop_comment_date").val(null);
                        $("#pop_comment_text").val(null);

                        $("#div_pop_comment").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#comment_edit_btn").click(function(e) {
                        e.preventDefault();
                        var comment = comments.rows( { selected: true } ).data();
                        $("#pop_comment_id").val(comment[0][0]);
                        $("#pop_comment_date").val(comment[0][1]);
                        $("#pop_comment_text").val(comment[0][2]);

                        $("#div_pop_comment").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#comment_delete_btn").click(function(e) {
                        e.preventDefault();
                        $this = $(e.target);
                        var comment_record = comments.rows( { selected: true } ).data();
                        $comment_id = comment_record[0][0];
                        console.log($comment_id);

                        $.ajax({
                            type: "POST",
                            url:  "php/workqueue.php",
                            data: { trigger_name: "delete_comment",
                                comment_id: $comment_id
                            },
                            dataType: "json",
                            success: function(data){
                                console.log("success:");
                                console.log(data);
                            },
                            error: function(data){
                                console.log("error:");
                            },
                            complete: function (data) {
                                comment_record
                                    .rows('.selected')
                                    .remove()
                                    .draw();
                            }
                        });
                    });

                </script>

            </div> <!-- End comments -->


            <div id="staff_notes">
                <div class="row input-group" id="notes_button_row">
                    <div class="form-group col-xs-12" id="notes_button_sec">
                        <button id="note_new_btn">New</button>
                        <button id="note_edit_btn">Edit</button>
                        <button id="note_delete_btn">Delete</button>
                    </div>
                </div>


                <table id="tbl_notes" class="display table-responsive" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>note</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?PHP

                    $sql_notes  = "select ";
                    $sql_notes .= "note_id, note_date, note_text ";
                    $sql_notes .= "from notes where request_id = ";
                    $sql_notes .= $request_id;

                    if ($result_notes=mysqli_query($mysqli,$sql_notes))
                    {
                        // Fetch one and one row
                        while ($row=mysqli_fetch_row($result_notes))
                        {
                            echo
                                "<tr>"
                                ."<td>".$row[0] ."</td>"
                                ."<td>".$row[1] ."</td>"
                                ."<td>".$row[2] ."</td>"
                                ."</tr>";
                        }
                        // Free result set
                        mysqli_free_result($result_notes);
                    }

                    //      mysqli_close($mysqli);

                    ?>
                    </tbody>
                </table>

                <!--                 Popup notes-->
                <div id="div_pop_note">
                    <form class="form form-vertical" id="pop_note_form_id">
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_note_id" hidden>ID</label>
                            <input class="col-xs-9" type="number" id="pop_note_id" disabled hidden>
                        </div>
                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_note_date">Date:</label>
                            <input class="col-xs-9" type="date" id="pop_note_date">
                        </div>

                        <div class="form-group">
                            <label class="column-label col-xs-3" for="pop_note_text">note:</label>
                            <input class="col-xs-9" type="text" id="pop_note_text">

                        </div>
                    </form>
                </div>

                <script>
                    var notes = $('#tbl_notes').DataTable({
                        select: {
                            style:          'single'
                        },
                        columnDefs: [
                            {
                                "targets": [ 0 ],
                                "visible": false,
                                "searchable": false
                            },
                            { "width": "20%", "targets": 1},
                            { "width": "80%", "targets": 2}
                        ]
                    });

                    notes.row().select();


                    var note_dialog = $("#div_pop_note").dialog({
                        title: "note",
                        autoOpen: false,
                        buttons: {
                            "Add / Update": function(){
                                $request_id = $("#request_id").val();
                                $note_id = $("#pop_note_id").val();
                                $note_date = $("#pop_note_date").val();
                                $note_text = $("#pop_note_text").val();


//                                console.log($note_id);
                                if($note_id == null
                                    || $note_id == undefined
                                    || $.isEmptyObject($note_id)
                                ) {
//                                   console.log("if true");
                                    $.ajax({
                                        type: "POST",
                                        url: "php/workqueue.php",
                                        data: { trigger_name: "add_note",
                                            request_id:         $request_id,
                                            note_id:         $note_id,
                                            note_date:       $note_date,
                                            note_text:       $note_text
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: add_note");
//                                            console.log(data);

                                            $("#pop_note_id").val(data);

                                            notes.row.add( [
                                                $("#pop_note_id").val(),
                                                $("#pop_note_date").val(),
                                                $("#pop_note_text").val()
                                            ]).draw();

                                        },
                                        error: function(data){
                                            console.log("error: add_note");
//                                            console.log(data);
                                        },
                                        complete: function(data){
                                            console.log("complete: add_note");
                                            $('.ui-dialog-content').dialog('close');
                                            notes.row().select();
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        type: "POST",
                                        url:  "php/workqueue.php",
                                        data: { trigger_name: "update_note",
                                            note_id:         $note_id,
                                            note_date:       $note_date,
                                            note_text:       $note_text
                                        },
                                        dataType: "json",
                                        success: function(data){
                                            console.log("success: edit_note");
//                                            console.log(data);
                                            notes
                                                .rows('.selected')
                                                .remove()
                                                .draw();
                                            notes
                                                .row.add( [
                                                $("#pop_note_id").val(),
                                                $("#pop_note_date").val(),
                                                $("#pop_note_text").val()
                                            ])
                                                .draw();
                                        },
                                        error: function(data){
                                            console.log("error: edit_note");
                                        },
                                        complete: function (data) {
                                            $('.ui-dialog-content').dialog('close');
                                            notes.row().select();
                                        }
                                    });
                                }
                            },

                            Cancel: function(){
                                $(this).dialog("close");
                            }
                        }
                    });

                    $("#note_new_btn").click(function(e) {
                        e.preventDefault();
                        $("#pop_note_id").val(null);
                        $("#pop_note_date").val(null);
                        $("#pop_note_text").val(null);

                        $("#div_pop_note").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#note_edit_btn").click(function(e) {
                        e.preventDefault();
                        var note = notes.rows( { selected: true } ).data();
                        $("#pop_note_id").val(note[0][0]);
                        $("#pop_note_date").val(note[0][1]);
                        $("#pop_note_text").val(note[0][2]);

                        $("#div_pop_note").dialog("open")
                            .dialog("option", "width", 500);

                    });

                    $("#note_delete_btn").click(function(e) {
                        e.preventDefault();
                        $this = $(e.target);
                        var note_record = notes.rows( { selected: true } ).data();
                        $note_id = note_record[0][0];
                        console.log($note_id);

                        $.ajax({
                            type: "POST",
                            url:  "php/workqueue.php",
                            data: { trigger_name: "delete_note",
                                note_id: $note_id
                            },
                            dataType: "json",
                            success: function(data){
                                console.log("success:");
                                console.log(data);
                            },
                            error: function(data){
                                console.log("error:");
                            },
                            complete: function (data) {
                                note_record
                                    .rows('.selected')
                                    .remove()
                                    .draw();
                            }
                        });
                    });

                </script>

            </div> <!-- End notes -->


        </div>
    <div class="row form-group">
        <div>

        </div>
    </div>

    <div class="row form-group col-xs-12" id="admin_sig_row">
        <div class="form-group">
            <div id="admin_signature_div">
                <div class="col-xs-6">
                    <label for="admin_signature">Admin Signature:</label>
                    <input type="text" id="admin_signature" name="admin_signature" size="35"
                           value="<?php echo $admin_signature;?>">
                </div>
            </div>

                <div class="col-xs-6">
                    <label for="folder_completed">Folder Completed:</label>
                    <input type="text" id="folder_completed" name="folder_completed" size="35"
                           value="<?php echo $folder_completed;?>">
                </div>

            </div>
        </div>

    </div>

    <div class="row form-group" id="stipd_row">
        <div class="form-group">
            <div id="stipd_sec">

                <div class="row form-group" id="request_info">
                    <div class="col-xs-4">
                        <label for="stipd">STIPD:</label>
                        <select id="stipd" name="stipd" style="width: 100px">
                            <option <?php if($stipd == '') echo"selected";?> value="">--</option>
                            <option <?php if($stipd == 'Yes') echo"selected";?> value="Yes">Yes</option>
                            <option <?php if($stipd == 'No') echo"selected";?> value="No">No</option>
                        </select>

                        <script>
                            $("#stipd").selectmenu();
                        </script>

                    </div>

                    <div class="col-xs-4 col-xs-pull-0">
                        <label for="director_name">Director:</label>
                        <input type="text" id="director_name" name="director_name" size="25"
                               style="text-align: center"
                               value="<?php echo $director_name;?>">
                    </div>

                    <div class="col-xs-4 col-xs-pull-0">
                        <label for="board_approval">Board Approval:</label>
                        <select id="board_approval" name="board_approval" style="width: 100px">
                            <option <?php if($board_approval == '') echo"selected";?> value="">--</option>
                            <option <?php if($board_approval == 'Yes') echo"selected";?> value="Yes">Yes</option>
                            <option <?php if($board_approval == 'No') echo"selected";?> value="No">No</option>
                        </select>

                        <script>
                            $("#board_approval").selectmenu();
                        </script>
                    </div>
            </div>
            <div class="row form-group">

                <div class="col-xs-4">
                    <label for="amt_sponsored">Amt Sponsored:</label>
                    <input type="number" id="amt_sponsored" name="amt_sponsored" size="5"
                           style="text-align: center"
                           value="<?php echo $amt_sponsored;?>" style="width: 50px">
                </div>

                <div class="col-xs-4 col-xs-pull-0">
                    <label for="payment_type">Payment Type:</label>
                    <input type="text" id="payment_type" name="payment_type" size="20"
                           style="text-align: center"
                           value="<?php echo $payment_type; ?>">
                </div>

                <div class="col-xs-4" id="room_res_fg">
                    <label for="room_res_needed">Rm Reservation:</label>
                    <select id="room_res_needed" name="room_res_needed" >
                        <option <?php if($room_res_needed == '') echo"selected";?>  value="">--</option>
                        <option <?php if($room_res_needed == 'Yes') echo"selected";?>  value="Yes">Yes</option>
                        <option <?php if($room_res_needed == 'No') echo"selected";?>  value="No">No</option>
                    </select>

                    <script>
                        $("#room_res_needed").selectmenu();
                    </script>
                </div>

            </div>


        </div>
    </div>

        <script>
            $("#wq_detail_tabs").tabs();
        </script>


</form>

</html>

<?php
}
else
{
	echo "<p><h3>You are not authorized to visit this page.</h3></p>";
	echo "<p><a href='UserLogin.php'>User Login</a></p>";
	echo "<p><a href='UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='../Home.html'>Home Page</a></p>";
}
mysqli_close($mysqli);
?>
