<?php
session_start();
session_unset();
// session_distroy();
header("location:login.php");
echo"hello";

?>