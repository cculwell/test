<?php
session_start();
require "../../resources/config.php";
//echo "tst";
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

if(isset($_POST['trigger_name'])){
    $trigger = $_POST['trigger_name'];
}


// Requests
if(isset($_POST['request_id'])) {
    $request_id = $_POST['request_id'];
}
if(isset($_POST['request_type'])) {
    $request_type = $_POST['request_type'];
}
if(isset($_POST['workflow_state'])){
    $workflow_state = $_POST['workflow_state'];
}
if(isset($_POST['school'])) {
    $school = $_POST['school'];
}
if(isset($_POST['system'])) {
    $system = $_POST['system'];
}
if(isset($_POST['request_desc'])) {
    $request_desc = $_POST['request_desc'];
}
if(isset($_POST['request_just'])) {
    $request_just = $_POST['request_just'];
}
if(isset($_POST['request_location'])) {
    $request_location = $_POST['request_location'];
}
if(isset($_POST['target_participants'])) {
    if(is_numeric($_POST['target_participants'])){
        $target_participants = $_POST['target_participants'];
    } else {
        $target_participants = 0;
    }
}
if(isset($_POST['enrolled_participants'])) {
    if(is_numeric($_POST['enrolled_participants'])){
        $enrolled_participants = $_POST['enrolled_participants'];
    } else {
        $enrolled_participants = 0;
    }
}
if(isset($_POST['total_cost'])) {
    if(is_numeric($_POST['total_cost'])){
        $total_cost = $_POST['total_cost'];
    } else {
        $total_cost = 0;
    }
}
if(isset($_POST['eval_method'])) {
    $eval_method = $_POST['eval_method'];
}
if(isset($_POST['stipd'])) {
    $stipd = $_POST['stipd'];
}
if(isset($_POST['workshop'])) {
    $workshop = $_POST['workshop'];
}
if(isset($_POST['report_date'])) {
    $report_date = $_POST['report_date'];
}
if(isset($_POST['request_title'])) {
    $request_title = $_POST['request_title'];
}
if(isset($_POST['folder_completed'])) {
    $folder_completed = $_POST['folder_completed'];
}
if(isset($_POST['director_name'])) {
    $director_name = $_POST['director_name'];
}
if(isset($_POST['board_approval'])) {
    $board_approval = $_POST['board_approval'];
}
if(isset($_POST['amt_sponsored'])) {
    if(is_numeric($_POST['amt_sponsored'])){
        $amt_sponsored = $_POST['amt_sponsored'];
    } else {
        $amt_sponsored = 0;
    }
}
if(isset($_POST['payment_type'])) {
    $payment_type = $_POST['payment_type'];
}

// Books
if(isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
}
if(isset($_POST['book_title'])) {
    $book_title = $_POST['book_title'];
}
if(isset($_POST['publisher'])) {
    $publisher = $_POST['publisher'];
}
if(isset($_POST['isbn'])) {
    $isbn = $_POST['isbn'];
}
if(isset($_POST['cost_per_book'])) {
    if(is_numeric($_POST['cost_per_book'])){
        $cost_per_book = $_POST['cost_per_book'];
    } else {
        $cost_per_book = 0;
    }
}
if(isset($_POST['study_format'])) {
    $study_format = $_POST['study_format'];
}
if(isset($_POST['admin_signature'])) {
    $admin_signature = $_POST['admin_signature'];
}

// Workshops
if(isset($_POST['workshop_id'])) {
    $workshop_id = $_POST['workshop_id'];
}
if(isset($_POST['program_nbr'])) {
    $program_nbr = $_POST['program_nbr'];
}
if(isset($_POST['pd_title'])) {
    $pd_title = $_POST['pd_title'];
}
if(isset($_POST['target_group'])) {
    $target_group = $_POST['target_group'];
}
if(isset($_POST['actual_participants'])) {
    if(is_numeric($_POST['actual_participants'])){
        $actual_participants = $_POST['actual_participants'];
    } else {
        $actual_participants = 0;
    }
}
if(isset($_POST['travel'])) {
    $travel = $_POST['travel'];
}
if(isset($_POST['room_res_needed'])) {
    $room_res_needed = $_POST['room_res_needed'];
}
if(isset($_POST['support_initiative'])) {
    $support_initiative = $_POST['support_initiative'];
}
if(isset($_POST['curriculum'])) {
    $curriculum = $_POST['curriculum'];
}



// Contacts
if(isset($_POST['contact_id'])) {
    $contact_id = $_POST['contact_id'];
}
if(isset($_POST['contact_name'])){
    $contact_name = $_POST['contact_name'];
}
if(isset($_POST['contact_role'])){
    $contact_role = $_POST['contact_role'];
}
if(isset($_POST['contact_phn_nbr'])){
    $contact_phn_nbr = $_POST['contact_phn_nbr'];
}
if(isset($_POST['contact_email'])){
    $contact_email = $_POST['contact_email'];
}
if(isset($_POST['contact_email'])){
    $contact_email = $_POST['contact_email'];
}
if(isset($_POST['contact_address'])){
    $contact_address = $_POST['contact_address'];
}


// Expenses
if(isset($_POST['expense_id'])){
    $expense_id = $_POST['expense_id'];
}
if(isset($_POST['expense_type'])){
    $expense_type = $_POST['expense_type'];
}
if(isset($_POST['expense_amount'])){
    $expense_amount = $_POST['expense_amount'];
}
if(isset($_POST['expense_note'])){
    $expense_note = $_POST['expense_note'];
}

// Date Times
if(isset($_POST['dt_id'])){
    $dt_id = $_POST['dt_id'];
}
if(isset($_POST['dt_date'])){
    $dt_date = $_POST['dt_date'];
}
if(isset($_POST['dt_start'])){
    $dt_start = $_POST['dt_start'];
}
if(isset($_POST['dt_end'])){
    $dt_end = $_POST['dt_end'];
}
if(isset($_POST['dt_break'])){
    $dt_break = $_POST['dt_break'];
}
if(isset($_POST['dt_note'])){
    $dt_note = $_POST['dt_note'];
}

// Comments
if(isset($_POST['comment_id'])){
    $comment_id = $_POST['comment_id'];
}
if(isset($_POST['comment_date'])){
    $comment_date = $_POST['comment_date'];
}
if(isset($_POST['comment_text'])){
    $comment_text = $_POST['comment_text'];
}

// notes
if(isset($_POST['note_id'])){
    $note_id = $_POST['note_id'];
}
if(isset($_POST['note_date'])){
    $note_date = $_POST['note_date'];
}
if(isset($_POST['note_text'])){
    $note_text = $_POST['note_text'];
}


//echo "Request Title: " . $request_title . PHP_EOL;


function workflow_state_change($mysqli,$request_id, $workflow_state) {

    $sql  = "update requests ";
    $sql .= " set workflow_state = '";
    $sql .= $workflow_state;
    $sql .= "' where request_id = ";
    $sql .= $request_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($mysqli);
    }

}

function delete_contact($mysqli,$contact_id) {

    $sql  = "delete from contacts ";
    $sql .= " where contact_id = ";
    $sql .= $contact_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted Contact record: " . mysqli_error($mysqli);
    }
}


function add_contact($mysqli
    , $request_id
    , $contact_role
    , $contact_name
    , $contact_phn_nbr
    , $contact_email
    , $contact_address) {

    $sql  = " insert into contacts ( ";
    $sql .= " request_id, contact_role, contact_name, contact_phn_nbr, contact_email, contact_address ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $contact_role . "','";
    $sql .= $contact_name . "','";
    $sql .= $contact_phn_nbr . "','";
    $sql .= $contact_email . "','";
    $sql .= $contact_address . "' ) ";


//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add Contact record: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}

function update_contact($mysqli
    , $contact_id
    , $contact_name
    , $contact_role
    , $contact_phn_nbr
    , $contact_email
    , $contact_address) {

    $sql  = " update contacts set ";
    $sql .= " contact_name = '" . $contact_name . "', ";
    $sql .= " contact_role = '" . $contact_role . "', ";
    $sql .= " contact_phn_nbr = '" . $contact_phn_nbr . "', ";
    $sql .= " contact_email = '" . $contact_email . "', ";
    $sql .= " contact_address = '" . $contact_address . "' ";
    $sql .= " where contact_id = " . $contact_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update Contact: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}



function delete_expense($mysqli, $expense_id){

    $sql  = "delete from expenses ";
    $sql .= " where expense_id = ";
    $sql .= $expense_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted Expense record: " . mysqli_error($mysqli);
    }
}

function add_expense($mysqli
    , $request_id
    , $expense_type
    , $expense_amount
    , $expense_note) {

    $sql  = " insert into expenses ( ";
    $sql .= " request_id, expense_type, expense_amount, expense_note ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $expense_type . "','";
    $sql .= $expense_amount . "','";
    $sql .= $expense_note . "' ) ";

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add Expense record: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}

function update_expense($mysqli
    , $expense_id
    , $expense_type
    , $expense_amount
    , $expense_note) {

    $sql  = " update expenses set ";
    $sql .= " expense_type = '" . $expense_type . "', ";
    $sql .= " expense_amount = '" . $expense_amount . "', ";
    $sql .= " expense_note = '" . $expense_note . "' ";
    $sql .= " where expense_id = " . $expense_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update Expense: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}



function delete_dt($mysqli, $dt_id){

    $sql  = "delete from date_times ";
    $sql .= " where request_dt_id = ";
    $sql .= $dt_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted date time record: " . mysqli_error($mysqli);
    }
}

function add_dt($mysqli
    , $request_id
    , $dt_date
    , $dt_start
    , $dt_end
    , $dt_break
    , $dt_note) {

    $sql  = " insert into date_times ( ";
    $sql .= " request_id, request_date, request_start_time, request_end_time, request_break_time, request_dt_note ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $dt_date . "','";
    $sql .= $dt_start . "','";
    $sql .= $dt_end . "','";
    $sql .= $dt_break . "','";
    $sql .= $dt_note . "' ) ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add date time record: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}

function update_dt($mysqli
    , $dt_id
    , $dt_date
    , $dt_start
    , $dt_end
    , $dt_break
    , $dt_note) {

    $sql  = " update date_times set ";
    $sql .= " request_date = '" . $dt_date . "', ";
    $sql .= " request_start_time = '" . $dt_start . "', ";
    $sql .= " request_end_time = '" . $dt_end . "', ";
    $sql .= " request_break_time = '" . $dt_break . "', ";
    $sql .= " request_dt_note = '" . $dt_note . "' ";
    $sql .= " where request_dt_id = " . $dt_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update date time: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}


function add_comment($mysqli
    , $request_id
    , $comment_date
    , $comment_text) {

    $sql  = " insert into comments ( ";
    $sql .= " request_id, comment_date, comment_text ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $comment_date . "','";
    $sql .= $comment_text . "' ) ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add comment record: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}

function update_comment($mysqli
    , $comment_id
    , $comment_date
    , $comment_text) {

    $sql  = " update comments set ";
    $sql .= " comment_date = '" . $comment_date . "', ";
    $sql .= " comment_text = '" . $comment_text . "' ";
    $sql .= " where comment_id = " . $comment_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update Comment: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}

function delete_comment($mysqli, $comment_id){

    $sql  = "delete from comments ";
    $sql .= " where comment_id = ";
    $sql .= $comment_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted Comment: " . mysqli_error($mysqli);
    }
}


function add_note($mysqli
    , $request_id
    , $note_date
    , $note_text) {

    $sql  = " insert into notes ( ";
    $sql .= " request_id, note_date, note_text ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $note_date . "','";
    $sql .= $note_text . "' ) ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add note record: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}

function update_note($mysqli
    , $note_id
    , $note_date
    , $note_text) {

    $sql  = " update notes set ";
    $sql .= " note_date = '" . $note_date . "', ";
    $sql .= " note_text = '" . $note_text . "' ";
    $sql .= " where note_id = " . $note_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update note: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}

function delete_note($mysqli, $note_id){

    $sql  = "delete from notes ";
    $sql .= " where note_id = ";
    $sql .= $note_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted note: " . mysqli_error($mysqli);
    }
}


function delete_workshop($mysqli, $workshop_id){

    $sql  = "delete from workshops ";
    $sql .= " where workshop_id = ";
    $sql .= $workshop_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted workshop: " . mysqli_error($mysqli);
    }
}

function update_workshop($mysqli
    , $workshop_id
    , $program_nbr
    , $pd_title
    , $target_group
    , $actual_participants
    , $travel
    , $room_res_needed
    , $support_initiative
    , $curriculum) {

    $sql  = " update workshops set ";
    $sql .= " program_nbr = '" . $program_nbr . "', ";
    $sql .= " pd_title = '" . $pd_title . "', ";
    $sql .= " target_group = '" . $target_group . "', ";
    $sql .= " actual_participants = '" . $actual_participants . "', ";
    $sql .= " travel = '" . $travel . "', ";
    $sql .= " room_res_needed = '" . $room_res_needed . "', ";
    $sql .= " support_initiative = '" . $support_initiative . "', ";
    $sql .= " curriculum = '" . $curriculum . "' ";
    $sql .= " where workshop_id = " . $workshop_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update Workshop: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}

function add_workshop($mysqli
    , $request_id
    , $program_nbr
    , $pd_title
    , $target_group
    , $actual_participants
    , $travel
    , $room_res_needed
    , $support_initiative
    , $curriculum) {

    $sql  = " insert into workshops ( ";
    $sql .= " request_id, program_nbr, pd_title, target_group, actual_participants, ";
    $sql .= " travel, room_res_needed, support_initiative, curriculum ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $program_nbr . "','";
    $sql .= $pd_title . "','";
    $sql .= $target_group . "','";
    $sql .= $actual_participants . "','";
    $sql .= $travel . "','";
    $sql .= $room_res_needed . "','";
    $sql .= $support_initiative . "','";
    $sql .= $curriculum . "' ) ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add workshop: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}


function delete_book($mysqli, $book_id){

    $sql  = "delete from books ";
    $sql .= " where book_id = ";
    $sql .= $book_id;

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Deleted successfully";
    } else {
        echo "Error Deleted Book: " . mysqli_error($mysqli);
    }
}

function update_book($mysqli
    , $book_id
    , $book_title
    , $publisher
    , $isbn
    , $cost_per_book
    , $study_format
    , $admin_signature) {

    $sql  = " update books set ";
    $sql .= " book_title = '" . $book_title . "', ";
    $sql .= " publisher = '" . $publisher . "', ";
    $sql .= " isbn = '" . $isbn . "', ";
    $sql .= " cost_per_book = '" . $cost_per_book . "', ";
    $sql .= " study_format = '" . $study_format . "', ";
    $sql .= " admin_signature = '" . $admin_signature . "' ";
    $sql .= " where book_id = " . $book_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update book: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}


function add_book($mysqli
    , $request_id
    , $book_title
    , $publisher
    , $isbn
    , $cost_per_book
    , $study_format
    , $admin_signature) {

    $sql  = " insert into books ( ";
    $sql .= " request_id, book_title, publisher, isbn, cost_per_book, ";
    $sql .= " study_format, admin_signature ";
    $sql .= " ) values ( ";
    $sql .= $request_id . ",'";
    $sql .= $book_title . "','";
    $sql .= $publisher . "','";
    $sql .= $isbn . "','";
    $sql .= $cost_per_book . "','";
    $sql .= $study_format . "','";
    $sql .= $admin_signature . "' ) ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add Book: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}

function update_request($mysqli
    , $request_id
    , $request_type
    , $workflow_state
    , $school
    , $system
    , $request_desc
    , $request_just
    , $request_location
    , $target_participants
    , $enrolled_participants
    , $total_cost
    , $eval_method
    , $stipd
    , $workshop
    , $report_date
    , $request_title
    , $folder_completed
    , $director_name
    , $board_approval
    , $amt_sponsored
    , $payment_type) {

    $sql  = " update requests set ";
    $sql .= " request_type = '" . $request_type . "', ";
    $sql .= " workflow_state = '" . $workflow_state . "', ";
    $sql .= " school = '" . $school . "', ";
    $sql .= " system = '" . $system . "', ";
    $sql .= " request_desc = '" . $request_desc . "', ";
    $sql .= " request_just = '" . $request_just . "', ";
    $sql .= " request_location = '" . $request_location . "', ";
    $sql .= " target_participants = '" . $target_participants . "', ";
    $sql .= " enrolled_participants = '" . $enrolled_participants . "', ";
    $sql .= " total_cost = '" . $total_cost . "', ";
    $sql .= " eval_method = '" . $eval_method . "', ";
    $sql .= " stipd = '" . $stipd . "', ";
    $sql .= " workshop = '" . $workshop . "', ";
    $sql .= " report_date = '" . $report_date . "', ";
    $sql .= " request_title = '" . $request_title . "', ";
    $sql .= " folder_completed = '" . $folder_completed . "', ";
    $sql .= " director_name = '" . $director_name . "', ";
    $sql .= " board_approval = '" . $board_approval . "', ";
    $sql .= " amt_sponsored = '" . $amt_sponsored . "', ";
    $sql .= " payment_type = '" . $payment_type . "' ";
    $sql .= " where request_id = " . $request_id . " ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Update successfully";
    } else {
        echo "Error Update requests: " . mysqli_error($mysqli);
    }

//    return $mysqli->insert_id;
}

function add_request($mysqli
    , $request_type
    , $workflow_state
    , $school
    , $system
    , $request_desc
    , $request_just
    , $request_location
    , $target_participants
    , $enrolled_participants
    , $total_cost
    , $eval_method
    , $stipd
    , $workshop
    , $report_date
    , $request_title
    , $folder_completed
    , $director_name
    , $board_approval
    , $amt_sponsored
    , $payment_type) {

    $sql  = " insert into requests ( ";
    $sql .= " request_type, workflow_state, school, system, request_desc, request_just, request_location, ";
    $sql .= " target_participants, enrolled_participants, total_cost, eval_method, stipd, workshop, report_date, ";
    $sql .= " request_title, folder_completed, director_name, board_approval, amt_sponsored, payment_type ";
    $sql .= " ) values ( '";
    $sql .= $request_type . "','";
    $sql .= $workflow_state . "','";
    $sql .= $school . "','";
    $sql .= $system . "','";
    $sql .= $request_desc . "','";
    $sql .= $request_just . "','";
    $sql .= $request_location . "','";
    $sql .= $target_participants . "','";
    $sql .= $enrolled_participants . "','";
    $sql .= $total_cost . "','";
    $sql .= $eval_method . "','";
    $sql .= $stipd . "','";
    $sql .= $workshop . "','";
    $sql .= $report_date . "','";
    $sql .= $request_title . "','";
    $sql .= $folder_completed . "','";
    $sql .= $director_name . "','";
    $sql .= $board_approval . "','";
    $sql .= $amt_sponsored . "','";
    $sql .= $payment_type . "' ) ";

//    echo $sql;

    if (mysqli_query($mysqli, $sql)) {
//        echo "Record Add successfully";
    } else {
        echo "Error Add Request: " . mysqli_error($mysqli);
    }

    return $mysqli->insert_id;
}


//print_r($_POST);

if($trigger=="save_request"){

    $return_ids = array('request_id' => '', 'workshop_id' => '', 'book_id' => '');

    // Insert or update requests
    if($request_id==NULL){
        $request_id = add_request($mysqli
            , $request_type
            , $workflow_state
            , $school
            , $system
            , $request_desc
            , $request_just
            , $request_location
            , $target_participants
            , $enrolled_participants
            , $total_cost
            , $eval_method
            , $stipd
            , $workshop
            , $report_date
            , $request_title
            , $folder_completed
            , $director_name
            , $board_approval
            , $amt_sponsored
            , $payment_type);
//        echo $request_id;
//        $return_ids['request_id'] = $request_id;
        $_POST['request_id'] = $request_id;
    } else {
        update_request($mysqli
            , $request_id
            , $request_type
            , $workflow_state
            , $school
            , $system
            , $request_desc
            , $request_just
            , $request_location
            , $target_participants
            , $enrolled_participants
            , $total_cost
            , $eval_method
            , $stipd
            , $workshop
            , $report_date
            , $request_title
            , $folder_completed
            , $director_name
            , $board_approval
            , $amt_sponsored
            , $payment_type);
//        $return_ids['request_id'] = $request_id;
        $_POST['request_id'] = $request_id;
    }

    // Insert or update workshop
    if($workshop=="Yes"){
        if($workshop_id==NULL){
//            echo "Add workshop";
            $workshop_id = add_workshop($mysqli
                , $request_id
                , $program_nbr
                , $pd_title
                , $target_group
                , $actual_participants
                , $travel
                , $room_res_needed
                , $support_initiative
                , $curriculum);
//            $return_ids['workshop_id'] = $workshop_id;
            $_POST['workshop_id'] = $workshop_id;
        } else {
//            echo "Update Workshop";
            update_workshop($mysqli
                , $workshop_id
                , $program_nbr
                , $pd_title
                , $target_group
                , $actual_participants
                , $travel
                , $room_res_needed
                , $support_initiative
                , $curriculum);
//            $return_ids['workshop_id'] = $workshop_id;
            $_POST['workshop_id'] = $workshop_id;
        }
    }

    // Insert or Update book
    if($request_type=="BookStudy"){
        if($book_id==NULL){
//            echo "add bookstudy";
            $book_id = add_book($mysqli
                , $request_id
                , $book_title
                , $publisher
                , $isbn
                , $cost_per_book
                , $study_format
                , $admin_signature);
            echo $book_id;
//            $return_ids['book_id'] = $book_id;
            $_POST['book_id'] = $book_id;
        } else {
//            echo "update book study";
            update_book($mysqli
                , $book_id
                , $book_title
                , $publisher
                , $isbn
                , $cost_per_book
                , $study_format
                , $admin_signature);
//            $return_ids['book_id'] = $book_id;
            $_POST['book_id'] = $book_id;
        }
    }
    echo json_encode($_POST);
}

//print_r($_POST);

if($trigger=="workflow_state_change"){
    workflow_state_change($mysqli,$request_id,$workflow_state);
}

if($trigger=="delete_contact"){
    delete_contact($mysqli,$contact_id);
}

if($trigger=="update_contact"){
    update_contact($mysqli
        , $contact_id
        , $contact_name
        , $contact_role
        , $contact_phn_nbr
        , $contact_email
        , $contact_address);
    echo $contact_id;
}

if($trigger=="add_contact"){
    $contact_id = add_contact($mysqli
        , $request_id
        , $contact_role
        , $contact_name
        , $contact_phn_nbr
        , $contact_email
        , $contact_address);
    echo $contact_id;
}

if($trigger=="delete_expense"){
    delete_expense($mysqli,$expense_id);
}

if($trigger=="update_expense"){
    update_expense($mysqli
        , $expense_id
        , $expense_type
        , $expense_amount
        , $expense_note);
    echo $expense_id;
}

if($trigger=="add_expense"){
    $expense_id = add_expense($mysqli
        , $request_id
        , $expense_type
        , $expense_amount
        , $expense_note);
    echo $expense_id;
}

if($trigger=="datetime_delete"){
    delete_dt($mysqli,$dt_id);
    echo $dt_id;
}

if($trigger=="update_date_time"){
    update_dt($mysqli
        , $dt_id
        , $dt_date
        , $dt_start
        , $dt_end
        , $dt_break
        , $dt_note);
    echo $dt_id;
}

if($trigger=="add_date_time"){
    $dt_id = add_dt($mysqli
        , $request_id
        , $dt_date
        , $dt_start
        , $dt_end
        , $dt_break
        , $dt_note);
    echo $dt_id;
}

if($trigger=="add_comment"){
    $comment_id = add_comment($mysqli
        , $request_id
        , $comment_date
        , $comment_text);
    echo $comment_id;
}

if($trigger=="update_comment"){
    update_comment($mysqli,$comment_id,$comment_date,$comment_text);
    echo $comment_id;
}

if($trigger=="delete_comment"){
    delete_comment($mysqli,$comment_id);
    echo $comment_id;
}

if($trigger=="add_note"){
    $note_id = add_note($mysqli
        , $request_id
        , $note_date
        , $note_text);
    echo $note_id;
}

if($trigger=="update_note"){
    update_note($mysqli,$note_id,$note_date,$note_text);
    echo $note_id;
}

if($trigger=="delete_note"){
    delete_note($mysqli,$note_id);
    echo $note_id;
}

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