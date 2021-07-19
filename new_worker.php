<?php

    session_start();
    if (!isset($_SESSION['logged']))
	{
		header('Location: login.php');
		exit();
	}

    else 
    {
        if(isset($_POST['first_name']))
        {
        $OK = true;
        $first_name = $_POST['first_name'];
        if(ctype_alpha($first_name)==false)
        {
        $OK = false;
        $_SESSION['e_first_name'] = "Imię nie może zawierać znaków specjalnych ani cyfr"; 
        }
        $last_name = $_POST['last_name'];
        if(ctype_alpha($last_name)==false)
        {
        $OK = false;
        $_SESSION['e_last_name'] = "Nazwisko nie może zawierać znaków specjalnych ani cyfr"; 
        }
        $card_id = $_POST['card_id'];
        $gender = $_POST['gender'];
        $date = $_POST['hire_date'];
        if (strtotime($date)==0)
        {
        $OK = false;
        $_SESSION['e_date'] = "Proszę wybrać date";
        }
        $salary = $_POST['money'];
        if($salary==0)
        {
            $OK = false;
            $_SESSION['e_salary'] = "Wprowadz stawke";
        }

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
                if($OK == true)
                {
                    if($connection->query("INSERT INTO employees VALUES (NULL, '$card_id', '$first_name', '$last_name', '$gender', '$date', '$salary')"))
                    {
                    $_SESSION['success'] = true;
                    }
                    else throw new Exception($connection->error);
                    
                }
            }
            $connection->close();
        }
        catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera!</span>';
            echo '<br />Informacja developerska: '.$e;
        }

        
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
Wprowadź dane <br><br/>
Imie: <input type = "text" name = "first_name"><br><br/>
<?php
if(isset($_SESSION['e_first_name']))
{
    echo '<div class ="error">'.$_SESSION['e_first_name'].'</div>';
    unset($_SESSION['e_first_name']);
}
?>
Nazwisko: <input type ="text" name = "last_name"><br><br/>
<?php
if(isset($_SESSION['e_last_name']))
{
    echo '<div class ="error">'.$_SESSION['e_last_name'].'</div>';
    unset($_SESSION['e_last_name']);
}
?>
Numer_id: <input type ="text" name = "card_id"><br><br/>
Płeć: <select name = "gender">
<option> M </option>
<option> F </option>
</select><br><br/>
Data przyjęcia: <input type = "date" name ="hire_date"><br><br/>
<?php
if(isset($_SESSION['e_date']))
{
    echo '<div class ="error">'.$_SESSION['e_date'].'</div>';
    unset($_SESSION['e_date']);
}
?>
Stawka godzinowa: <input type = "number" min = "0" step = "0.1" name = "money"><br><br/>
<?php
if(isset($_SESSION['e_salary']))
{
    echo '<div class ="error">'.$_SESSION['e_salary'].'</div>';
    unset($_SESSION['e_salary']);
}
?>
<input type = "submit" value = "Wprowadź"><br><br/>
<?php
if(isset($_SESSION['success']))
{
    echo "Pomyślnie dodano pracownika do bazy danych";
    unset ($_SESSION['success']);
}
?>
<br>
<a href="main_page.php">Powrót do strony głównej</a> 
</form>
</div>
</body>
</html>