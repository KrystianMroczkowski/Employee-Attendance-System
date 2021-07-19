<?php
session_start();
if(isset($_POST['login']))
{
    $OK = true;
    $login = $_POST['login'];
    $pasw = $_POST['pasw'];

    $login = htmlentities($login, ENT_QUOTES, "UTF-8");
    
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
        if ($result = $connection->query(
        sprintf("SELECT * FROM registered WHERE login = '%s'", mysqli_real_escape_string($connection,$login))));
        {
            $rows = $result->num_rows;
            if($rows>0)
            {
                $str = $result->fetch_assoc();
                if(password_verify($pasw, $str['password']))
                {
                $_SESSION['logged'] = true;
                header("Location: main_page.php");
                }
                else  $_SESSION['e_error'] = "Niepoprawny login lub hasło!";
            }  $_SESSION['e_error'] = "Niepoprawny login lub hasło!";
           
        } 
    $connection->close();
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
<title>Logowanie</title>
<style>
	.error
	{
		color:red;
		margin-top: 10px;
		margin-bottom: 10px;
	}
</style>
<link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
    <div id = "container">
<form  method = "post">
<input type = "text" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'" name = "login"><br><br/>
<input type = "password" placeholder="Hasło" onfocus="this.placeholder=''" onblur="this.placeholder='Hasło'" name = "pasw"><br><br/>
<input type = "submit" value = "Zaloguj">
<br><br/>
<div id = "register">
<a href="db_registration.php">Zarejestruj się!</a>
</div>
<div id = "error">
<?php
if (isset($_SESSION['e_error']))
{
    echo '<div class ="error">'.$_SESSION['e_error'].'</div>';
    unset($_SESSION['e_error']);
} ?>
</div>
</form>
</div>
</body>
</html>
