
<?php

session_start();

session_unset();
session_destroy();

header("Location: CSS/login.html");
exit();

?>

