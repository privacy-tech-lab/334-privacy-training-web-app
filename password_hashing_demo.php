<?php
// Demo for hashing a password.

$password = "TestPassword%12";

// Using PHP's built-in password hashing function (https://www.php.net/manual/en/function.password-hash.php).
// The first parameter is the password in plaintext and the second parameter is the hashing algorithm.
// PASSWORD_DEFAULT uses the bcrypt algorithm.
// Note that password_hash() automatically generates a salt. Thus, the hardcoded password is different
// every time it is hashed.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo($hashed_password)

// For verifying whether a password a user enters upon login is identical to a hashed password, PHP provides 
// the built-in password_verify($password, $hashed_password) function (https://www.php.net/manual/en/function.password-verify.php).
?>
