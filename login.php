<?php
// Initialize the session.
session_start();

// Check if the user is already logged in. If yes, then redirect them to the welcome page.
// $_SESSION is a superglobal variable built into PHP that contains session variables
// available to the current script. The "loggedin" session variable is true if the user
// is logged in. The built in isset function is used to determine if a variable is declared 
// and is different than NULL. It is used here to make sure that the loggedin variable is 
// set to anything at all.
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file.
require_once "config.php";

// Define variables and initialize with empty strings.
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted.
// $_SERVER is an array containing information such as headers, paths, and script 
// locations. The entries in this array are created by the web server.
// $_SERVER, $_POST, and similarly styled variables are superglobal variables
// in PHP. Unlike normal variables that you assign yourself, PHP has some built-in 
// variables that are always available in your script and in all scopes. These are 
// called Superglobals (awesome name right?) and are predefined in PHP.
// https://teamtreehouse.com/community/why-post-is-all-with-capital-letters
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Set variables to the values entered by the user and available to the script
    // for later insertion into the SQL statement below.
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Create a PHP variable with a SQL Select statement.
    $sql = "SELECT * FROM users_table WHERE username = '$user' and password = '$pass'";

    // The mysqli_query() function built into PHP performs a query against a database.
    // https://www.w3schools.com/php/func_mysqli_query.asp
	$res = mysqli_query($link, $sql);

    // The built-in mysqli_stmt_num_rows function accepts a statement 
    // object as a parameter and returns the number of rows in the 
    // result set of the given statement.
    // https://www.tutorialspoint.com/php/php_function_mysqli_stmt_num_rows.htm
    // If the result of the executed SQL statement contains a row,
    // we know that the username and password exists and we can log the user in.
	if(mysqli_num_rows($res) == 0){
        $password_err = "The username and/or password you entered was not valid.";
    }
    else{
        // Username and password is correct, so start a new session.
    		session_start();

    		// Store data in session variables.
    		$_SESSION["loggedin"] = true;
    		$_SESSION["id"] = $id;
    		$_SESSION["username"] = $username;
    		$_SESSION["display_username"] = $user;

    		// Redirect user to welcome page.
    		header("location: welcome.php");
    }

    // Close connection.
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1>Welcome to OnePhoto!</h1>
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <!-- htmlspecialchars is a built-in PHP function to convert special characters to HTML entities.
        $_SERVER is an array built into PHP containing information such as headers, paths, and script 
        locations. The entries in this array are created by the web server. With $_SERVER["PHP_SELF"] we
        are echoing the complete path, including all parameters, to our site. PHP_SELF is a variable that 
        returns the name and path of the current file (from the root folder). However, without htmlspcialchars 
        we would have an XSS vulnerability (see https://www.webadminblog.com/index.php/2010/02/23/a-xss-vulnerability-in-almost-every-php-form-ive-ever-written/).
        Various errors related to the user-entered strings (or omissions of those) are echoed to the
        output of the form for the user to see. -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="index.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
