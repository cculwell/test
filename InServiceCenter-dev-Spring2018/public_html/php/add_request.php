<?php
/**
 * User: rickmilliken
 * Date: 9/3/17
 * Time: 2:16 PM
 * PHP file that will add a request to the database.
 */

require "../../resources/config.php";
require "../php/captcha/get_captcha_hash.php";


$captcha_entered = $_POST['captcha'];
$captcha_hash = $_POST['captcha_hash'];

//$report_date = date("Y-m-d");
//echo $report_date;



# Handle single-quotes for string nad null
function add_quotes($str) {
    if (is_string($str)) {
        return sprintf("'%s'", $str);
    } elseif (is_null($str)) {
        return sprintf("%s", "null");
    } else {
        return sprintf("%s", $str);
    }
}

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

function convert_time($time){
    if(strlen($time)==6){
        $hour = substr($time,0,1);
        $min =  substr($time,2,2);
        $ap = substr($time,4,2);
    }
    else {
        $hour = substr($time,0,2);
        $min =  substr($time,3,2);
        $ap = substr($time,5,2);
    }
    if($ap == 'pm') {
        $hour = $hour + 12;
    }
    return $hour . ":" . $min . ":00";
}

function convert_date($date){
    $mon = substr($date,0,2);
    $day = substr($date,3,2);
    $year = substr($date,6,4);
    return $year . "-" . $mon . "-" . $day;
}




if (rpHash($captcha_entered) == $captcha_hash) {
//    debug_to_console("matched");

    $request_parms = array(
        "request_type" => $_POST['RequestType']
    , "workflow_state" => "New"
    , "school" => str_replace('_', ' ',$_POST['school'])
    , "system" => str_replace('_',' ',$_POST['system'])
    , "request_desc" => htmlspecialchars($_POST['request_desc'])
    , "request_just" => htmlspecialchars($_POST['request_just'])
    , "request_location" => $_POST['request_location']
    , "target_participants" => $_POST['target_participants']
    , "enrolled_participants" => $_POST['enrolled_participants']
    , "total_cost" => $_POST['total_cost']
    , "eval_method" => $_POST['eval_method']
    , "report_date" => date("Y-m-d")
    );

    $book_parms = array(
        "book_title" => $_POST['book_title']
    , "publisher" => $_POST['publisher']
    , "isbn" => $_POST['isbn']
    , "cost_per_book" => $_POST['cost_per_book']
    , "study_format" => $_POST['study_format']
    );

    if($request_parms['request_type'] == 'General') {
        $contacts_parms = array(
            array("contact_role" => "Contact"
            , "contact_name" => $_POST['contact_name']
            , "contact_phn_nbr" => $_POST['contact_phn_nbr']
            , "contact_email" => $_POST['contact_email'])

        , array("contact_role" => "Company"
            , "contact_name" => $_POST['company_name']
            , "contact_phn_nbr" => $_POST['company_phn_nbr']
            , "contact_email" => $_POST['company_email'])

        );
    }

    if($request_parms['request_type'] == 'BookStudy') {
        $contacts_parms = array(
            array("contact_role" => "Contact"
            , "contact_name" => $_POST['contact_name']
            , "contact_phn_nbr" => $_POST['contact_phn_nbr']
            , "contact_email" => $_POST['contact_email'])

        , array("contact_role" => "Facilitator"
            , "contact_name" => $_POST['facilitator_name']
            , "contact_phn_nbr" => $_POST['facilitator_phn_nbr']
            , "contact_email" => $_POST['facilitator_email'])
        );
    }

// Dynamically build an array for date times
    $date_time_parms = array();
    $i = 0;
    foreach($_POST as $key => $value)
    {
        if(preg_match('/^date/', $key)) {
            $date_time_parms[$i] = array(
                'request_date' => $_POST["date" . $i]
            , 'request_start_time' => $_POST["sTime" . $i]
            , 'request_end_time' => $_POST["eTime" . $i]
            , 'request_break_time' => $_POST["bTime" . $i]);
            $i++;
        }
    }

# build request sql statement for insert
    $request_sql  = "insert into requests (";
    $request_sql .= implode(',', array_keys($request_parms));
    $request_sql .= ") values (";
    $request_sql .= implode(',', array_map('add_quotes', $request_parms));
    $request_sql .= ")";


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


// auto commit off
    $mysqli->autocommit(false);

# begin transaction
    $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

    try {
        # execute query of proc call.
        if (!$mysqli->query($request_sql)) {
            //echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
            throw new Exception("Cannot insert record. Reason :".$mysqli->error);
        }
        // get request_id
        $request_id = $mysqli->insert_id;

        if($request_parms['request_type'] == 'BookStudy') {

            // insert book info
            $book_sql = "insert into books (";
            $book_sql .= "request_id,";
            $book_sql .= implode(',', array_keys($book_parms));
            $book_sql .= ") values (";
            $book_sql .= $request_id . ",";
            $book_sql .= implode(',', array_map('add_quotes', $book_parms));
            $book_sql .= ")";

            if (!$mysqli->query($book_sql)) {
                //echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
                throw new Exception("Cannot insert record. Reason :" . $mysqli->error);
            }
        }
        // insert contact info
        foreach ($contacts_parms as $contact) {
            $contact_sql = "insert into contacts (";
            $contact_sql .= "request_id,";
            $contact_sql .= implode(',', array_keys($contact));
            $contact_sql .= ") values (";
            $contact_sql .= $request_id . ",";
            $contact_sql .= implode(',', array_map('add_quotes', $contact));
            $contact_sql .= ")";

            if (!$mysqli->query($contact_sql)) {
                //echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
                throw new Exception("Cannot insert record. Reason :".$mysqli->error);
            }

        }

        // insert dates and times
        foreach ($date_time_parms as $dt) {
            $dt_sql = "insert into date_times (";
            $dt_sql .= "request_id,";
            $dt_sql .= implode(',', array_keys($dt));
            $dt_sql .= ") values (";
            $dt_sql .= $request_id . ",'";
            $dt_sql .= convert_date($dt['request_date']) . "','";
            $dt_sql .= convert_time($dt['request_start_time']) . "','";
            $dt_sql .= convert_time($dt['request_end_time']) . "',";
            $dt_sql .= $dt['request_break_time'] ;
            $dt_sql .= ")";

//            print_r($dt_sql);
            if (!$mysqli->query($dt_sql)) {
                //echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
                throw new Exception("Cannot insert record. Reason :".$mysqli->error);
            }

        }

        // commit the transaction
        $mysqli->commit();
        /* commit transaction */
        if (!$mysqli->commit()) {
            //print("Transaction commit failed\n");
//            echo "<script> console.log('Transaction commit failed')</script>";
//            phpAlert("Transaction failed");
            exit();
        }
        else {
            //print "Successful Insert\n";
//            echo "<script> console.log('Transaction successful')</script>";
//            phpAlert("Transaction successful");
        }

        echo $request_id;

    }
    catch (Exception $e)
    {
//    echo "exception thrown\n";
        echo "<script> console.log('Exception Thrown')</script>";
        phpAlert("Exception Thrown");
        $mysqli->rollback();
        echo $e;
    }

    /* close connection */
    $mysqli->close();



} else {
//    debug_to_console("did not match");
    echo "captcha failed";

}



?>