<?php
    require "../../../../resources/config.php";

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

    $request_id = $_POST['request_id'];

    $total_expenses = 0.0;
    $table_results = "<table id='expenses_table' cellspacing='0' width='100%'>
                <thead>
                    <tr>
                        <th>Expense</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";

    $sql = "SELECT DISTINCT(e.expense_type), SUM(e.expense_amount) AS total
            FROM expenses e
            WHERE e.request_id='$request_id'
            GROUP BY e.expense_type";

    if ($result = mysqli_query($mysqli, $sql))
    {
        while ($row = $result->fetch_array(MYSQLI_ASSOC))
        {
            $total = number_format((float)$row['total'], 2, '.', '');
            $total_expenses += $total;
            $table_results .= "<tr><td>". $row['expense_type']  ."</td><td>$". $total ."</td></tr>";
        }
    }

    $total_expenses = number_format((float)$total_expenses, 2, '.', '');
    $table_results .= "<tr><td><br/></td></tr>";
    $table_results .= "<tr style='font-weight:bold'><td>TOTAL</td><td>$" . $total_expenses . "</td></tr>";

    echo $table_results;

    mysqli_free_result($result);
    mysqli_close($mysqli);
?>