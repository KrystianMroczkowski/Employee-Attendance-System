<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
<body>
<table width="1000" align="center" border="1" bordercolor="#d5d5d5"  cellpadding="0" cellspacing="0">
        <tr>
<?php
session_start();
if (!isset($_SESSION['logged']))
	{
		header('Location: login.php');
		exit();
	}
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
        $result = $connection->query("SELECT * FROM employees");
        if(!$result)
        {
            throw new Exception($connection->error);
        }
        else {
        $rows = mysqli_num_rows($result);
        $_SESSION['number'] = $rows;
        }
    }
}
catch (Exception $e)
{
    echo '<span style="color:red;">Błąd serwera!</span>';
    echo '<br />Informacja developerska: '.$e;
}
if($rows>=1)
{
    echo<<<END
<td width="100" align="center" bgcolor="#36b03c">id</td>
<td width="100" align="center" bgcolor="#36b03c">id_number</td>
<td width="100" align="center" bgcolor="#36b03c">first_name</td>
<td width="100" align="center" bgcolor="#36b03c">last_name</td>
<td width="50" align="center" bgcolor="#36b03c">gender</td>
<td width="100" align="center" bgcolor="#36b03c">hire_date</td>
<td width="100" align="center" bgcolor="#36b03c">fee_per_hour</td>
</tr><tr>
END;
}

for ($i = 1; $i <= $rows; $i++) 
{
    
    $row = mysqli_fetch_assoc($result);
    $a1 = $row['id'];
    $a2 = $row['id_number'];
    $a3 = $row['first_name'];
    $a4 = $row['last_name'];
    $a5 = $row['gender'];
    $a6 = $row['hire_date'];
    $a7 = $row['fee_per_hour'];
    	
    
echo<<<END
<td width="50" align="center">$a1</td>
<td width="100" align="center">$a2</td>
<td width="100" align="center">$a3</td>
<td width="100" align="center">$a4</td>
<td width="100" align="center">$a5</td>
<td width="100" align="center">$a6</td>
<td width="100" align="center">$a7</td>
</tr><tr>
END;
        
}
echo "Znaleziono ".$_SESSION['number']." pracowników";
?>
</tr></table>
<a href="delete_worker.php">Usuń pracownika</a> <br><br/>
<a href="main_page.php">Powrót do strony głównej</a> &nbsp; &nbsp;

</body>
</html>