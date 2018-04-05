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
?>

<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="css/Admin.css" />
    </head>
<body>
<?php
	if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
	{?>
        <form method="POST" action="">
            <p><label for="subject">Subject (*required):</label><br>
            <input type="text" id="subject_text_edit" name="subject" size="40" required/></p>
            <p><label style="padding-top: 10px;" for="message">Mail Body (*required):</label><br>
            <textarea id="message_text_edit" name="message" cols="50"   rows="10" required=""></textarea></p>
            <button id="send_emails_button" type="submit" value="submit">Send E-mails</button>
        </form>

        <br><br><hr>

        <button id="del_subscriber_btn" name="del_subscriber_btn">Delete Subscriber</button>
        <br><br>
        <table id="subscribers_table" class="display table-responsive" cellspacing="0" width="100%"> 
            <thead>
                <tr> 
                    <th>Subscriber E-mail</th> 
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT email FROM subscribers ORDER BY id ASC";

                    if ($result = mysqli_query($mysqli, $sql))
                    {
                        while ($row = mysqli_fetch_row($result))
                        {
                            echo
                                "<tr><td style='text-align: center;'>" . $row[0] . "</td></tr>"; 
                        }
                    }
                ?>
            </tbody>
        </table>

        <div id="busy_box" style="text-align: center;">
            <label>Please wait while we send the latest</label>
            <label>newsletter to our subscribers.</label>
            <label>This could take a few minutes...</label><br><br>
            <img src="img/ajax-loader.gif" />
        </div>
        <div id="results_box"></div>

    <script>
        var subscribers = $('#subscribers_table').DataTable({
                        lengthChange: false,
                        select: {
                            style:          'single'
                        },
                        columnDefs: [
                            { "width": 800, "targets": 0}
                        ]
                    });

        $('#subscribers_table tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                subscribers.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );
     
        $('#del_subscriber_btn').click( function () {
            if (subscribers.row('.selected').data() == null)
            {
                alert("Please select a subscriber to delete.");
                return false;
            }

            var email_id = subscribers.row('.selected').data()[0];

            if (!(confirm('Are you sure you want to delete subscriber "' + email_id + '"?'))) 
            {
                return false;
            } 
            else 
            {
                $.ajax({

                    type: "POST",
                    url: "php/email/delete_subscriber.php",
                    data: {
                        email: email_id
                    },

                    success: function(data) {
                        if (data == 'removed')
                        {
                            subscribers.row('.selected').remove().draw();                                    
                        }
                        else
                        {
                            alert(data); //Will print error returned by the database
                        }

                    },
                    error: function(data){
                            alert(data); //Will print error returned
                    }
                });
            }
        });

        $('#send_emails_button').click(function(e) {
            e.preventDefault();
            var subject = document.getElementById('subject_text_edit').value;
            var message = document.getElementById('message_text_edit').value;

            if (subject == "")
            {
                alert("Subject line is required.");
                return false;
            }

            if (message == "")
            {
                alert("Message body is required.");
                return false;
            }

            $.ajax({
                type: "POST",
                url: "php/email/send_email.php",
                data:
                {
                    subject: subject,
                    message: message
                },
                beforeSend: function(){
                    $("#busy_box").dialog("open");
                },
                success: function(data)
                {
                    $("#busy_box").dialog("close");
                    $("#results_box").html(data);
                    $("#results_box").dialog('open');
                },
                error: function(jqXHR, exception) {
                    $error = 'ERROR: (' + jqXHR + ')' + " " + exception;

                    $("#busy_box").dialog("close");
                    $("#results_box").html($error);
                    $("#results_box").dialog('open');
                }
            });
        });

        $("#results_box").dialog({
            title: 'E-mail Results',
            modal: true,
            height: 300,
            width: 400,
            autoOpen: false,
            buttons: [
                { 
                    id: "ok",
                    text: "OK", 
                    class: "btn btn-secondary",
                    click: function() { 
                        $("#results_box").empty();
                        $(this).dialog("close");
                    }
                }
            ],
            open: function(event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            }
        });

        $("#busy_box").dialog({
            title: 'Sending Newsletter',
            modal: true,
            height: 200,
            width: 300,
            autoOpen: false,
            open: function(event, ui) {
                $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
            }
        });
    </script>
</div>
<?php
	}
	else
	{
		echo "<p><h3>You are not authorized to view this report.</h3></p>";
		echo "<p><a href='UserLogin.php'>User Login</a></p>";
		echo "<p><a href='UserLogout.php'>User Logout</a></p>";
		echo "<p><a href='../Home.html'>Home Page</a></p>";
	}
?>
</body>
</html>