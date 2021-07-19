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
        $result = $connection->query("SELECT worker_id FROM in_work WHERE status = '1'");
        if(!$result)
        {
            throw new Exception($connection->error);
        }
        else 
        {
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
</tr><tr>
END;
}
for ($i = 1; $i <= $rows; $i++) 
{
    $row = mysqli_fetch_assoc($result);
    $a1 = $row['worker_id'];
    $resultD = $connection->query("SELECT * FROM employees WHERE id = '$a1'");
    if(!$resultD)
    {
        throw new Exception($connection->error);
    }
    $rowsD = $result->num_rows;
    $rowD = mysqli_fetch_assoc($resultD);
    $a2 = $rowD['id_number'];
    $a3 = $rowD['first_name'];
    $a4 = $rowD['last_name'];
echo<<<END
<td width="50" align="center">$a1</td>
<td width="100" align="center">$a2</td>
<td width="100" align="center">$a3</td>
<td width="100" align="center">$a4</td>
</tr><tr>
END;

}
echo "Znaleziono ".$_SESSION['number']." pracowników";
?>
</tr></table>
<a href="main_page.php">Powrót do strony głównej</a> &nbsp; &nbsp;

</body>
</html>
