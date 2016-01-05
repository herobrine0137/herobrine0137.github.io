<?php

$myhost = "localhost";
$myuser = "root";
$mypass = "test";
$mydb = "glard0w";
$key = "2147828743";

$con = mysqli_connect($myhost, $myuser, $mypass, $mydb);

if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>