<?php
require_once("config.php");
session_start();

$user = mysqli_real_escape_string($conn, $_POST['user']);
$pass = mysqli_real_escape_string($conn, $_POST['pass']);

$query = "SELECT `profile_type` FROM `users` where email='$user' and pass='$pass'";
$sql = mysqli_query($conn, $query);
$count = mysqli_num_rows($sql);

if($count == 1) {
  $row = mysqli_fetch_array($sql);
  $_SESSION['user'] = $user;
  echo $row[0];
}
else {
  echo "Invalid";
}
