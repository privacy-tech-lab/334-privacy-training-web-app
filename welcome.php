<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
echo "You are logged in as user: " . $_SESSION["display_username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<!-- https://stackoverflow.com/questions/32913226/auto-refresh-page-every-30-seconds -->
	<meta http-equiv="refresh" content="30"/>
	<p></p><a href="logout.php" class="btn btn-danger">Log Out</a></p>
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>OnePhoto</h1>
    </div>
    <p>
	<p><img src="A_Mystery_Location.jpg" alt="A Mystery Image" height="233" width="416"></p>
	<p>A mystery location photo.</p>
</body>
</html>
