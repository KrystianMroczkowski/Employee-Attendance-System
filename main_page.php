<?php
/*
	session_start();
	
	if (!isset($_SESSION['logged']))
	{
		header('Location: login.php');
		exit();
	}
	*/
?>
<html>
<head>
<title>Strona Główna</title>
<link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
<div id = "menubuttons">
<div id = "button"><a href="new_worker.php" class = "button">Dodaj nowego pracownika do bazy</a> </div>
<div id = "button"><a href="all_workers.php" class = "button">Lista pracowników</a> </div>
<div id = "button"><a href="in_work.php" class = "button">Czy w pracy</a> </div>
<div id = "button"><a href="show.php" class = "button">Sprawdz pracowników obecnych w pracy</a></div>
<div id = "button"><a href="edit_worker.php" class = "button">Zmień dane pracownika</a> </div>
<div id = "button"><a href="to_pay.php" class = "button">Do zapłaty</a></div>
<div id = "button"><a href="delete_worker.php" class = "button">Usuń pracownika</a> </div>
<div id = "button"><a href="logout.php" class = "button">Wyloguj się</a></div>
<div style = "clear:both;"></div>
</div>
</body>
</html>