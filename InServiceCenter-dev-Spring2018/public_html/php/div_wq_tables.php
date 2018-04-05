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
if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin' || $_SESSION['valid_status']=='User'))
{
?>
<div id="tabs">
    <ul>
        <li><a href="#tab_new_req">New Requests</a></li>
        <li><a href="#tab_under_review">Under Review</a></li>
        <li><a href="#tab_board_vote">Board Vote</a></li>
        <li><a href="#tab_start_po">Start Purchase Order</a></li>
        <li><a href="#tab_order_issued">Order/Contract Issued</a></li>
        <li><a href="#tab_completed">Completed</a></li>
        <li><a href="#tab_canceled">Canceled</a></li>
        <li><a href="#tab_all">All Requests</a></li>
    </ul>
    <div id="tab_new_req">
        <table id="tbl_new_req" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'New' ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
            </table>
            <script>
                var queue_new = $('#tbl_new_req').DataTable({
                    select: {
                        style:          'single'
                    }
                });
                //queue.rows( { selected: true } ).data();
                queue_new.on( 'select', function ( e, dt, type, indexes ) {
                    if ( type === 'row' ) {
                        //var data = queue.rows( indexes ).data().pluck( 'id' );
                        var record = queue_new.rows( { selected: true } ).data();
                        // do something with the ID of the selected items
                        console.log(record);
            //            console.log(record[0][0]);
                        $.ajax({

                            type: "POST",//post
                            //url: $(this).attr('href'),
                            url: "php/div_wq_form.php",
                            data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                            success: function(data) {
                                // data is ur summary
                                $('#div_wq_form').html(data);
                            }

                        });
                    }
                } );
            </script>
    </div>
    <div id="tab_under_review">
        <table id="tbl_under_review" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'Under Review' ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_under_review = $('#tbl_under_review').DataTable({
                select: {
                    style:          'single'
                }
            });
            //queue.rows( { selected: true } ).data();
            queue_under_review.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                    //var data = queue.rows( indexes ).data().pluck( 'id' );
                    var record = queue_under_review.rows( { selected: true } ).data();
                    // do something with the ID of the selected items
                    //            console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }

                    });
                }
            } );


        </script>
    </div>


    <div id="tab_board_vote">
        <table id="tbl_board_vote" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'Board Vote' ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_board_vote = $('#tbl_board_vote').DataTable({
                select: {
                    style:          'single'
                }
            });
            //queue.rows( { selected: true } ).data();
            queue_board_vote.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                    //var data = queue.rows( indexes ).data().pluck( 'id' );
                    var record = queue_board_vote.rows( { selected: true } ).data();
                    // do something with the ID of the selected items
                    //            console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }

                    });
                }
            } );


        </script>
    </div>

    <div id="tab_start_po">
        <table id="tbl_start_po" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP


            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'Start Purchase Order' ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_start_po = $('#tbl_start_po').DataTable({
                select: {
                    style:          'single'
                }
            });
            //queue.rows( { selected: true } ).data();
            queue_start_po.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                    //var data = queue.rows( indexes ).data().pluck( 'id' );
                    var record = queue_start_po.rows( { selected: true } ).data();
                    // do something with the ID of the selected items
                    //            console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }

                    });
                }
            } );


        </script>
    </div>

    <div id="tab_order_issued">
        <table id="tbl_order_issued" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'Order/Contract Issued' ";



            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_order_issued = $('#tbl_order_issued').DataTable({
                select: {
                    style:          'single'
                }
            });
            //queue.rows( { selected: true } ).data();
            queue_order_issued.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                    //var data = queue.rows( indexes ).data().pluck( 'id' );
                    var record = queue_order_issued.rows( { selected: true } ).data();
                    // do something with the ID of the selected items
                    //            console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }

                    });
                }
            } );


        </script>
    </div>

    <div id="tab_completed">
        <table id="tbl_completed" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'Completed' ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_completed = $('#tbl_completed').DataTable({
                select: {
                    style:          'single'
                }
            });

            queue_completed.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                   var record = queue_completed.rows( { selected: true } ).data();
//                    var rec = queue_completed.row().select();
                    // do something with the ID of the selected items
//                    console.log(record);
                     console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }
                    });
                }
            });
        </script>
    </div>

    <div id="tab_canceled">
        <table id="tbl_canceled" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where workflow_state = 'Canceled' ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_canceled = $('#tbl_canceled').DataTable({
                select: {
                    style:          'single'
                }
            });
            //queue.rows( { selected: true } ).data();
            queue_canceled.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                    //var data = queue.rows( indexes ).data().pluck( 'id' );
                    var record = queue_canceled.rows( { selected: true } ).data();
                    // do something with the ID of the selected items
                    //            console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }

                    });
                }
            } );
        </script>
    </div>


    <div id="tab_all">
        <table id="tbl_all" class="display table-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>Request ID</th>
                <th>Type</th>
                <th>State</th>
                <th>School</th>
                <th>System</th>
                <th>Program #</th>
                <th>Program Title</th>
            </tr>
            </tfoot>
            <tbody>
            <?PHP

            $sql  = " select ";
            $sql .= " r.request_id, request_type, workflow_state, school, system, program_nbr, pd_title";
            $sql .= " from requests r left join workshops w on r.request_id = w.request_id";
            $sql .= " where 1=1 ";

            if ($result=mysqli_query($mysqli,$sql))
            {
                // Fetch one and one row
                while ($row=mysqli_fetch_row($result))
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
                        ."</tr>";
                }
                // Free result set
                mysqli_free_result($result);
            }

            //      mysqli_close($mysqli);

            ?>
            </tbody>
        </table>
        <script>
            var queue_all = $('#tbl_all').DataTable({
                select: {
                    style:          'single'
                }
            });
            //queue.rows( { selected: true } ).data();
            queue_all.on( 'select', function ( e, dt, type, indexes ) {
                if ( type === 'row' ) {
                    //var data = queue.rows( indexes ).data().pluck( 'id' );
                    var record = queue_all.rows( { selected: true } ).data();
                    // do something with the ID of the selected items
                    //            console.log(record[0][0]);
                    $.ajax({

                        type: "POST",//post
                        //url: $(this).attr('href'),
                        url: "php/div_wq_form.php",
                        data: "request_id="+record[0][0], // appears as $_POST['id'] @ ur backend side
                        success: function(data) {
                            // data is ur summary
                            $('#div_wq_form').html(data);
                        }

                    });
                }
            } );
        </script>
    </div>

</div>
<script>
//    $("#tabs").tabs();
    $("#tabs").tabs({
        activate: function(event, ui) {
//            alert("test");
//            console.log(ui.newPanel.selected);
//            $(ui.newPanel.selected).refresh();
//            if (ui.newPanel.selector == "#tab_under_review") {
//                alert("tab tab_under_review");
//            }
        }
    });
</script>
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
