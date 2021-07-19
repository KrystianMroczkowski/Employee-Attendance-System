<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location: login.php');
		exit();
	}
if(isset($_POST['id']))
{
    $id = $_POST['id'];
    $_SESSION['id'] = $id;
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
                if($connection->query("DELETE FROM employees WHERE id = '$id'"))
                {
                    $_SESSION['success'] = true;
                }
                else throw new Exception;
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
Usuń pracownika<br><br/>
Id:
<input type = "number" min ="0" name ="id"><br><br/>
<input type = "submit" value = "Potwierdz"><br><br/>
<?php
if(isset($_SESSION['success']))
{
    echo "Pomyślnie usunięto pracownika";
    unset($_SESSION['success']);
}
?>
<br><br/>
<a href="main_page.php">Powrót do strony głównej</a> &nbsp; &nbsp;
</form>
</div>
</body>
</html>
