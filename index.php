<?php
// Include the config file.
require_once "config.php";

// Define variables and initialize with empty values.
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted.
// $_SERVER is an array containing information such as headers, paths, and script 
// locations. The entries in this array are created by the web server.
// $_SERVER, $_POST, and similarly styled variables are superglobal variables
// in PHP. Unlike normal variables that you assign yourself, PHP has some built-in 
// variables that are always available in your script and in all scopes. These are 
// called Superglobals (awesome name right?) and are predefined in PHP.
// https://teamtreehouse.com/community/why-post-is-all-with-capital-letters
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is available or already taken.
    // empty is a built-in function to determine whether a variable is empty.
    // trim is a built-in function to strip whitespace (or other characters) 
    // from the beginning and end of a string.
    // $_POST is a built-in associative array of variables passed to the current 
    // script via the HTTP POST method.
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Create a PHP variable with a SQL SELECT statement. The ? is a placeholder
        // that will be replaced with the username the user entered into the form.
        $sql = "SELECT id FROM users_table WHERE username = ?";

        // The mysqli PHP extension allows you to access the functionality provided 
        // by MySQL 4.1 and above. https://www.php.net/manual/en/intro.mysqli.php
        // The built-in mysqli_prepare() function is used to prepare a SQL 
        // statement for execution. It has two parameters: the MySQL connection 
        // to use and the SQL query. Note: Do not add semicolon to the end of the 
        // query! https://www.w3schools.com/php/func_mysqli_prepare.asp
        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables for the parameter markers, i.e., the ?, in the SQL 
            // statement prepared by mysqli_prepare() or mysqli_stmt_prepare().
            // https://www.php.net/manual/en/mysqli-stmt.bind-param.php
            // There are three parameters: the prepared SQL statement, "s" for
            // string (which specifies the type for the corresponding bind variable
            // $param_username to be a string), and the bind variable $param_username.
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set $param_username to what the user has entered as username, which
            // is accessible to us via the superglobal variable $_POST.
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement with the built-in
            // mysqli_stmt_execute function.
            if(mysqli_stmt_execute($stmt)){

                // The mysqli_stmt_store_result function accepts a statement 
                // object as a parameter and stores the resultset of the given 
                // statement locally, if it executes a SELECT, SHOW or, DESCRIBE 
                // statement. 
                // https://www.tutorialspoint.com/php/php_function_mysqli_stmt_store_result.htm
                mysqli_stmt_store_result($stmt);

                // The built-in mysqli_stmt_num_rows function accepts a statement 
                // object as a parameter and returns the number of rows in the 
                // result set of the given statement.
                // https://www.tutorialspoint.com/php/php_function_mysqli_stmt_num_rows.htm
                // If the result of the executed SQL statement contains a row,
                // we know that the username is already taken. Otherwise, it is not,
                // in which case, assign the username to the variable $username
                // for later use.
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement.
        mysqli_stmt_close($stmt);
    }

    // Check if password satisfies the password requirements. If it does,
    // assign it the variable $password.
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 10){
        $password_err = "Password must have at least 10 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Check if passwords in the confirm password form match.
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check for input errors before inserting the username and password into our
    // database. Only proceed if the error variables are all empty.
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Create a PHP variable with a SQL INSERT statement. The ?s are placeholders
        // that will be replaced with the values of the username and password
        // variables.
        $sql = "INSERT INTO users_table (username, password) VALUES (?, ?)";

        // The built-in mysqli_prepare() function is used to prepare a SQL 
        // statement for execution. It has two parameters: the MySQL connection 
        // to use and the SQL query. Note: Do not add semicolon to the end of the 
        // query! https://www.w3schools.com/php/func_mysqli_prepare.asp
        if($stmt = mysqli_prepare($link, $sql)){

            // Bind variables for the parameter markers, i.e., the ?s, in the SQL 
            // statement prepared by mysqli_prepare() or mysqli_stmt_prepare().
            // https://www.php.net/manual/en/mysqli-stmt.bind-param.php
            // There are three parameters: the prepared SQL statement, "ss" for
            // string string (which specifies the types for the corresponding bind 
            // variables $param_username and $param_password to be strings), and 
            // the bind variables $param_username and $param_password.
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters of the mysqli_stmt_bind_param $param_username and
            // $param_password to the $username and the hash of the $password
            // the user entered. password_hash is a built-in function to create
            // a password hash.
            $param_username = $username;
            $param_password = $password;

            // Attempt to execute the prepared statement with the built-in
            // mysqli_stmt_execute function.
            if(mysqli_stmt_execute($stmt)){
                // If it works, we have successfully inserted a new username and 
                // corresponding password into the database, and we can redirect
                // to the login page.
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement.
        mysqli_stmt_close($stmt);
    }

    // Close connection.
    mysqli_close($link);
}
?>

<!-- The form for signing up a new user. -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>OnePhoto Sign Up</h2>
        <p>Please fill this form to create an account.</p>
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
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
            <p><a href="privacy.html">Privacy Policy</a></p>
        </form>
    </div>
</body>
</html>
