<?php
session_start();
if (!isset($_SESSION['logged']))
{
    header('Location: login.php');
    exit();
}
if(isset($_POST['card_id']))
{
    $card_id = $_POST['card_id'];
    require_once "dbconnect.php";
    try
    {
        $connection = new mysqli ($host, $db_user, $db_password, $db_name);
        if ($connection->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            $result = $connection->query("SELECT id, first_name, last_name FROM employees WHERE id_number ='$card_id'");
            $rows = $result->num_rows;
            if($rows == 0)
            {
                echo '<span style="color:red;">Nie rozpoznano numeru id!</span>';
                exit();
            }
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $resultD = $connection->query("SELECT * FROM in_work WHERE card_id = '$card_id' AND status = '1'");
            $rowsD = $resultD->num_rows;
            if($rowsD>0)
            {
                $rowD = $resultD->fetch_assoc();
                $czas = $rowD['start_time'];
                $current_time = date('Y-m-d H:i:s');
                $diff = strtotime($current_time) - strtotime($czas);
                $diffD = $diff/60;
                if($connection->query("UPDATE in_work SET end_time = now(), status = '0', in_total = '$diffD' WHERE card_id ='$card_id' AND status = '1'"))
                {
                    $_SESSION['show'] = true;
                }
                 else throw new Exception($connection->error);
            }
            else
            {
             $status = 1;
             if($connection->query("INSERT INTO in_work VALUES ('$id', '$card_id', now(), 0, '$status', 0)"))
             {
                 $_SESSION['show'] = true;
             }
             else throw new Exception($connection->error);
            }

        }
    }
    catch (Exception $e)
    {
        echo '<span style="color:red;">Błąd serwera!</span>';
        echo '<br />Informacja developerska: '.$e;
    }
}
?>



<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id = "container">
<form method = "post">
Id Karty: <input type = "number" name = "card_id"><br><br/>
<input type = "submit" value = "Potwierdź">
</form>
<a href="main_page.php">Powrót do strony głównej</a> &nbsp; &nbsp;
</div>
</body>
</html>