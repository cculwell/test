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
        <button id="del_newsletter_btn" name="del_newsletter_btn">Delete Newsletter</button>
        <button id="set_current_newsletter" name="set_current_newsletter">Set Current Newsletter</button>
        <br><br>
        <table id="newsletter_table" class="display table-responsive" cellspacing="0" width="100%"> 
            <thead>
                <tr> 
                    <th>Newsletter Name</th> 
                    <th>Current Newsletter</th> 
                </tr>
            </thead>
            <tbody>
                <?php

                    $sql = "SELECT name, current FROM newsletters";

                    if ($result = mysqli_query($mysqli, $sql))
                    {
                        while ($row = mysqli_fetch_row($result))
                        {
                            echo
                                "<tr>"
                                ."<td style='text-align: center;'>" . $row[0] . "</td>";

                            if ($row[1] == 'yes') // Is this the file set to view by site visitors?
                            {
                                echo
                                    "<td style='text-align: center;'><img src='img/accept.png' /></td>"
                                    ."</tr>"; 
                            }
                            else
                            {
                                echo
                                    "<td></td></tr>";                                   
                            }
                        }
                    }
                ?>
            </tbody>
        </table><br>

        <form id='upload_newsletter' enctype='multipart/form-data'>
            <label>Upload Newsletter:</label>
            <input type="file" name="newsletter_to_upload" id="newsletter_to_upload"><br>
            <input type="button" name="submit" id="submit_newsletter" value="Upload">
            <br><br><div id="newsletter_processing_message"></div>
        </form>
    <script>
        var newsletters = $('#newsletter_table').DataTable({
                        lengthChange: false,
                        select: {
                            style:          'single'
                        },
                        columnDefs: [
                            { "width": 400, "targets": 0},
                            { "width": 400, "targets": 1}
                        ]
                    });

        $('#newsletter_table tbody').on( 'click', 'tr', function () {
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                newsletters.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        });
     
        $('#del_newsletter_btn').click( function () {
            if (newsletters.row('.selected').data() == null)
            {
                alert("Please select a newsletter to delete.");
                return false;
            }

            var newsletter_file = newsletters.row('.selected').data()[0];
            var form_data = new FormData(); 

            if (!(confirm('Are you sure you want to delete "' + newsletter_file + '"?'))) 
            {
                return false;
            } 
            else 
            {
                form_data.append('file', newsletter_file);
                form_data.append('table', 'newsletters');

                $.ajax({

                    type: "POST",
                    url: "php/admin/delete_file.php",
                    dataType: 'text',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,

                    success: function(data) {
                        if (data == 'deleted')
                        {
                            newsletters.row('.selected').remove().draw();                                    
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

        $('#set_current_newsletter').click( function () {
            if (newsletters.row('.selected').data() == null)
            {
                alert("Please select a newsletter to set as current.");
                return false;
            }

            var newsletter_file = newsletters.row('.selected').data()[0];
            var form_data = new FormData(); 

            if(newsletter_file == null)
            {
                return false;
            }

            form_data.append('file', newsletter_file);
            form_data.append('table', 'newsletters');

            $.ajax({

                type: "POST",
                url: "php/admin/set_current_file.php",
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,

                success: function(data) {
                    if (data == 'set_current')
                    {
                        $('#pop_newsletter_upload').load("php/Newsletters.php");
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
        });

        $('#submit_newsletter').click( function () {
            if ($('#newsletter_to_upload').val().length == 0)
            {
                alert('Please select a newsletter to upload');
                return false;
            }

            var file_data = document.getElementById("newsletter_to_upload").files;
            var form_data = new FormData(); 

            if ((file_data[0].type != 'application/pdf') ||
                (file_data[0].name.substr(file_data[0].name.lastIndexOf('.')) != '.pdf'))
            {
                alert("Only pdf files are supported for upload");
                return false;
            }

            if (file_data[0].size > 10000000) //10MB
            {
                alert("File sizes must be 10MB and under");
                return false;
            }

            form_data.append('the_file', file_data[0]);
            form_data.append('table', 'newsletters');
            form_data.append('directory', 'Newsletters');

            document.getElementById("newsletter_processing_message").innerHTML = "Processing.....";

            $.ajax({

                type: 'POST',
                url : 'php/admin/upload_file.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,                         

                success: function(data) {
                    document.getElementById("newsletter_processing_message").innerHTML = "";
                    if (data.indexOf('successfully uploaded') >= 0)
                    {   
                        alert(data);
                        $('#pop_newsletter_upload').load("php/Newsletters.php");
                    }
                    else
                    {
                        alert(data); //Will print error returned by the database
                    }
                },
                error: function(data){
                    document.getElementById("newsletter_processing_message").innerHTML = "";
                    alert(data); //Will print error returned
                }
            });
        });
    </script>
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

<?php
    mysqli_close($mysqli);
?>