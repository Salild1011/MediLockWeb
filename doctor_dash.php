<?php
session_start();
require_once('config.php');

$user = $_SESSION['user'];

$query = "SELECT * FROM `doctor_details` where email='$user'";
$res = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($res);
$_SESSION["doctor_id"] = $row["id"];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>MediLock - Doctor Dashboard</title>

  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/info.css" rel="stylesheet">
  <link href="css/doctor.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="doctor_dash.html">
      <img src="img/logo_trans.png" width="30" height="30" class="d-inline-block align-top" alt="">
      MediLock
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <span class="navbar-nav mr-auto"></span>
      <button class="btn btn-outline-warning my-2 my-sm-0" onclick="logout()" style="margin-left: 10px;">Sign Out</button>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-info">
            <div class="name">
              <p id="name">Dr. <?php echo $row['name']; ?></p>
            </div>
            <hr>
            <div class="content">
              <h5>Info</h5>
              <p id="info">
                <table>
                  <tr>
                    <td>ID:</td>
                    <td id="medid"><?php echo $row['id']; ?></td>
                  </tr>
                  <tr>
                    <td>Qualification:</td>
                    <td id="qual"><?php echo $row['qualification']; ?></td>
                  </tr>
                  <tr>
                    <td>Speciality:</td>
                    <td id="spec"><?php echo $row['speciality']; ?></td>
                  </tr>
                  <tr>
                    <td>Experience:</td>
                    <td id="exp"><?php echo $row['exp']; ?> years</td>
                  </tr>
                </table>
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-info">
            <div class="name">
              <p>Add a patient</p>
            </div>

            <hr>

            <div class="content">
              <h5>Enter Details: </h5>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12">
                    <form id="patient_add" action="doctor_dash.php" method="post">
                      <input type="date" name="treat_date" placeholder="Treatment Date"/>
                      <input type="text" name="patient_id" placeholder="Patient ID"/>
                      <input type="text" name="problem" placeholder="Diagnosis"/>
                      <input type="text" name="treatment" placeholder="Treatment"/>
                      <input type="submit" name="add" class="bg-primary" style="color: white;" value="Add">
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-info">
              <div class="name">
                <p>Pending Requests</p>
              </div>

              <hr>

              <div class="content">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="thankyouModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Patient registered successfully!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/popper.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    function logout() {
      window.location.replace("signout.php");
    }
  </script>
  <?php
    if (isset($_POST['treat_date']) && isset($_POST['patient_id']) && isset($_POST['problem']) && isset($_POST['treatment'])) {
      $treat_date = mysqli_real_escape_string($conn, $_POST['treat_date']);
      $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
      $problem = mysqli_real_escape_string($conn, $_POST['problem']);
      $treatment = mysqli_real_escape_string($conn, $_POST['treatment']);
      $doctor_id = $_SESSION["doctor_id"];

      $query = "INSERT INTO `patient_details` VALUES ('$patient_id', '$doctor_id', '$treat_date', '$problem', '$treatment')";
      $sql = mysqli_query($conn, $query);

      echo "<script>$('#thankyouModal').modal('show')</script>";
    }
  ?>
</body>
</html>
