<?php
session_start();
if (!isset($_SESSION['logged']))
{
    header('Location: login.php');
    exit();
}
if(isset($_POST['first_name']))
{
    $OK = true;
    //
    require_once "dbconnect.php";
    $connection = new mysqli ($host, $db_user, $db_password, $db_name);
    try
    {
        if($connection->connect_errno != 0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {   
            $id = $_POST['id'];
            $result = $connection->query("SELECT * FROM employees WHERE id = '$id'");
            if(!$result)
            {
                throw new Exception($connection->error);
            }
            $row = $result->fetch_assoc();
            $id_number = $row['id_number'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $hire_date = $row['hire_date'];
            $fee = $row['fee_per_hour'];
            //sprawdzenie
            if($_POST['card_id']!=0)
            {
                $id_number = $_POST['card_id'];
            }
            if ($_POST['first_name']!="")
            {
                if(ctype_alpha($_POST['first_name'])==false)
                {
                    $_SESSION['error_first_name'] = "Podaj poprawne imie";
                    $OK = false;
                }
            $first_name = $_POST['first_name'];    
            }
            if($_POST['last_name']!="")
            {
                if(ctype_alpha($_POST['last_name'])==false)
                {
                    $_SESSION['error_last_name'] = "Podaj poprawne nazwisko";
                    $OK = false;
                }
                $last_name = $_POST['last_name'];
            }
            $temp_date = $_POST['hire_date'];
            if (strtotime($temp_date)!=0)
            {
                $hire_date = $temp_date;
            }
            if($_POST['money']!=0)
            {
                $fee = $_POST['money'];
            }
            if($OK == true)
            {
            if($resultD = $connection->query("UPDATE employees SET id_number = '$id_number', first_name = '$first_name', last_name = '$last_name', hire_date = '$hire_date', fee_per_hour = '$fee' WHERE id = '$id'"))
            {
                $_SESSION['successD'] = true;
            }
            else throw new Exception($connection->error);
            }
            

        }
        $connection->close();
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
<style>
	.error
	{
		color:red;
		margin-top: 10px;
		margin-bottom: 10px;
	}
</style>
</head>
<body>
<div id = "container">
<form method = "post">
Wprowadź id pracownika którego dane chcesz zmienić<br><br/>
Id: <input type = "number" min = 0 name = "id"><br><br/>
Wprowadz dane które chcesz zmienić<br><br/>
Imię: <input type ="text" name = "first_name"><br><br/>
<?php
if(isset($_SESSION['error_first_name']))
{
    echo '<div class ="error">'.$_SESSION['error_first_name'].'</div>';
    unset($_SESSION['error_first_name']);
}
?>
Nazwisko: <input type = "text" name = "last_name"><br><br/>
<?php
if(isset($_SESSION['error_last_name']))
{
    echo '<div class ="error">'.$_SESSION['error_last_name'].'</div>';
    unset($_SESSION['error_last_name']);
}
?>
Numer_id: <input type ="text" name = "card_id"><br><br/>
Data przyjęcia: <input type = "date" name ="hire_date"><br><br/>
Stawka godzinowa: <input type = "number" min = "0" step = "0.1" name = "money"><br><br/>
<input type = "submit" value = "Potwierdź zmiany"><br><br/>
<?php
if(isset($_SESSION['successD']))
{
    echo "Zmiana danych przebiegła pomyślnie";
    unset($_SESSION['successD']);
}
?>
<br><br/>
<a href="main_page.php">Powrót do strony głównej</a> &nbsp; &nbsp;
</form>
</div>
</body>
</html>