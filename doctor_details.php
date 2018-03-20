<?php
require_once("config.php");
session_start();

$doc_id = $_POST['id'];
$row = array();

foreach ($doc_id as $val) {
  $query = "SELECT * FROM `doctor_details` WHERE id='$val'";
  $sql = mysqli_query($conn, $query);
  $count = mysqli_num_rows($sql);

  if ($count == 1) {
    $ans = mysqli_fetch_assoc($sql);
    array_push($row, $ans);
  }

  echo json_encode($row);
}
