<?php
session_start();
if (isset($_POST['email']))
{
    $OK = true;
    
    $login = $_POST['login'];
    //login check
    if (((strlen($login)<3) || (strlen($login)>20)))
    {
        $OK = false;
        $_SESSION['e_login'] = "Login musi mieć od 3 do 20 znaków";
    }
    if (ctype_alnum($login)==false)
    {
        $OK = false;
        $_SESSION['e_login'] = "Login może składać się liter i cyfr(bez polskich znaków)";
    }
    //email check
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
    {
        $OK=false;
        $_SESSION['e_email']="Podaj poprawny adres e-mail!";
    }
    // password check
    $pasw1 = $_POST['pasw1'];
    $pasw2 = $_POST['pasw2'];
    if ($pasw1!=$pasw2)
    {
        $OK = false;
        $_SESSION['e_pass'] = "Podane hasła nie są zgodne";
    }
    if (((strlen($pasw1)<8) || (strlen($pasw1)>20)))
    {
       $OK = false;
       $_SESSION['e_pass'] = "Hasło musi mieć od 8 do 20 znaków"; 
    }
    $pasw_hash = password_hash($pasw1, PASSWORD_DEFAULT);

    $_SESSION['fr_login'] = $login;
	$_SESSION['fr_email'] = $email;
    $_SESSION['fr_pasw1'] = $pasw1;
    $_SESSION['fr_pasw2'] = $pasw2;
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
            $result = $connection->query("SELECT id FROM registered WHERE email = '$email'");
            if(!$result)
            {
                throw new Exception($connection->error);
            }
            $number_of_emails = $result->num_rows;
            if($number_of_emails>0)
            {   
            $OK = false;
            $_SESSION['e_email'] = "Na ten email już zostało utworzone konto";
            }
            $result = $connection->query("SELECT id FROM registered WHERE login = '$login'");
            if(!$result)
            {
                throw new Exception($connection->error);
            }
            $number_of_logins = $result->num_rows;
            if($number_of_logins>0)
            {
                $OK = false;
                $_SESSION['e_login'] = "Istnieje już taki login";
            }
            if ($OK == true)
            {
                if($connection->query("INSERT INTO registered VALUES (NULL, '$login', '$pasw_hash', '$email')"))
                {
                $_SESSION['success'] = true;
                header("Location: login.php");
                }  
                else 
                {
                throw new Exception($connection->error);
                }
            }
            $connection->close();
        }
    }
    catch(Exception $e)
    {
        echo '<span style="color:red;">Błąd serwera!</span>';
        echo '<br />Informacja developerska: '.$e;
    }
}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<title> Rejestracja </title>
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
<form method = "post">
Login: <br/> <input type = "text" value ="<?php
if(isset($_SESSION['fr_login']))
{
    echo $_SESSION['fr_login'];
    unset($_SESSION['fr_login']);
}
?>" name = "login"><br/>
<?php
if (isset($_SESSION['e_login']))
{
    echo '<div class ="error">'.$_SESSION['e_login'].'</div>';
    unset($_SESSION['e_login']);
}
?>
Email: <br/><input type = "text" value="<?php
if(isset($_SESSION['fr_email']))
{
echo $_SESSION['fr_email'];
unset($_SESSION['fr_email']);
}
?>" name = "email"><br/>
<?php
if(isset($_SESSION['e_email']))
{
    echo '<div class="error">'.$_SESSION['e_email'].'</div>';
    unset($_SESSION['e_email']);
}
?>
Haslo: <br/><input type = "password" value="<?php
if(isset($_SESSION['fr_pasw1']))
{
    echo $_SESSION['fr_pasw1'];
    unset($_SESSION['fr_pasw1']);
}
?>" name = "pasw1"><br/>
<?php
if(isset($_SESSION['e_pass']))
{
    echo '<div class ="error">'.$_SESSION['e_pass'].'</div>';
    unset($_SESSION['e_pass']);
}
?>
Powtórz haslo: <br/><input type = "password" value ="<?php
if(isset($_SESSION['fr_pasw2']))
{
    echo  $_SESSION['fr_pasw2'];
    unset($_SESSION['fr_pasw2']);
}?>" name = "pasw2"><br/>
<input type = "submit" value = "Zarejestruj się">
<br><br/>
<div id = "link">
<a href="login.php">Powrót do strony logowania</a>
</div>
</form>
</div>
</body>
</html>