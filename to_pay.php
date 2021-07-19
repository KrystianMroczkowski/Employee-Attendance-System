<?php
session_start();
if (!isset($_SESSION['logged']))
{
    header('Location: login.php');
    exit();
}
if(isset($_POST['id']))
{
    if($_POST['id']!=0)
    {
        $id = $_POST['id'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
        $date_end = date('Y-m-d H:i:s', strtotime($date_end . ' +1 day'));

        require_once "dbconnect.php";

        $connection = new mysqli ($host, $db_user, $db_password, $db_name);
        try
        {
            if ($connection->connect_errno != 0)
            {
                throw new Exception(mysqli_connect_errno());
            }
            else
            {
                $result = $connection->query("SELECT SUM(in_total) AS value_sum FROM in_work WHERE start_time>='$date_start' AND worker_id = '$id' AND start_time<='$date_end'");
                if(!$result)
                {
                    throw new Exception($connection->error);
                }
                $row = $result->fetch_assoc();
                $sum = $row['value_sum'];
                $sum = round($sum);
                $sum = $sum/60;
                $result = $connection->query("SELECT fee_per_hour FROM employees WHERE id = '$id'");
                if(!$result)
                {
                    throw new Exception($connection->error);
                }
                $row = $result->fetch_assoc();
                $fee = $row['fee_per_hour'];
                $to_pay = $sum * $fee;
                $to_pay = round($to_pay, 2);
                echo "Do zapłaty: ".$to_pay."  Liczba przepracowanych godzin: ".round($sum, 2); 
            }
        }
        catch (Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera!</span>';
            echo '<br />Informacja developerska: '.$e;
        }
    }
}
?>


<html>
<head>
<link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
<div id = "container">
<form method = "post">
Id: <input type = "number" min = "0" name = "id"><br><br/>
Data początkowa: <input type ="date" name = "date_start"><br><br/>
Data końcowa: <input type = "date" name = "date_end"><br><br/>
<input type = "submit" value = "Potwierdź"><br><br/>
</form>
<a href="main_page.php">Powrót do strony głównej</a> &nbsp; &nbsp;
</div>
</body>
</html>