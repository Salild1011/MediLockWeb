<?php
session_start();
require_once('config.php');

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>MediLock</title>

  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <link href="css/styles.css" rel="stylesheet">
  <link href="css/info.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.html">
      <img src="img/logo_trans.png" width="30" height="30" class="d-inline-block align-top" alt="">
      MediLock
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <span class="navbar-nav mr-auto"></span>
      <button class="btn btn-outline-warning my-2 my-sm-0" onclick="login()" style="margin-left: 10px;">Sign Out</button>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-info">
            <div class="name">
              <p id="name">Name</p>
            </div>

            <hr>

            <div class="content">
            <h5>Info</h5>
            <p id="info">
              <table>
                <tr>
                  <td>ID:</td>
                  <td id="medid"><?php echo $user; ?></td>
                </tr>
                <tr>
                  <td>Gender:</td>
                  <td id="gender"></td>
                </tr>
                <tr>
                  <td>Height:</td>
                  <td id="height"></td>
                </tr>
                <tr>
                  <td>Weight:</td>
                  <td id="weight"></td>
                </tr>
                <tr>
                  <td>Age:</td>
                  <td id="age"></td>
                </tr>
                <tr>
                  <td>Blood Group:</td>
                  <td id="blg"></td>
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
              <p>Doctors</p>
            </div>

            <hr>

            <div class="content">
              <h5>List of doctors </h5>
              <p id="doc-info">
                <?php
                  $query = "SELECT `doctor_id` FROM `patient_details` WHERE patient_id='$user'";
                  $sql = mysqli_query($conn, $query);

                  echo "<ul>";
                  while ($row = mysqli_fetch_array($sql)) {
                    echo "<li>" . $row[0] . "</li>";
                  }
                  echo "</ul>";
                ?>
              </p>
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
              <p>Medical Records(Secured)</p>
            </div>
            <hr>
            <div class="content">
              <table>
                <tr>
                  <td>Block ID:</td>
                  <td id="pub_key"></td>
                </tr>
                <tr>
                  <td>Allergies:</td>
                  <td id="allergy"></td>
                </tr>
                <tr>
                  <td>Medication:</td>
                  <td id="medication"></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="js/popper.min.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
      window.WebSocket = window.WebSocket || window.MozWebSocket;

      var connection = new WebSocket('ws://127.0.0.1:1355');

      connection.onopen = function () {
        // connection is opened and ready to use
        var msg = {"id": "salilezio1011@gmail.com"}
        connection.send(JSON.stringify(msg));
      };

      connection.onerror = function (error) {
        // an error occurred when sending/receiving data
      };

      connection.onmessage = function (message) {
        // try to decode json (I assume that each message
        // from server is json)
        try {
          var json = JSON.parse(message.data);
          var hash = json.hash;
          var url = "https://test.bigchaindb.com/api/v1/transactions/" + hash;

          $.getJSON(url, function(data) {
            console.log(data);
            var patient = data.asset.data.patient_json;

            $("#name").text(patient.name);
            $("#age").text(patient.age);
            $("#blg").text(patient.blood_group);
            $("#gender").text(patient.gender);
            $("#height").text(patient.height + " cm");
            $("#weight").text(patient.weight + " kg");
            $("#pub_key").text(data.id);
            $("#allergy").text(patient.allergies);
            $("#medication").text(patient.medication);

            var doc_id = patient.doctor_id;

            console.log(patient);

            $.ajax({
              type: "POST",
              url: './doctor_details.php',
              data: {id: doc_id},
              success: function(data) {
                console.log(data);
                console.log(data.toString());

                //TODO never worked
                // var docJson = JSON.parse(data.toString());
                // var docJson = data;
                // console.log(typeof docJson);
                // var ans = JSON.parse(docJson);
                // var docData = "<ul>";
                // for (var i = 0; i < docJson.length; i++) {
                //   docData += "<li>" + docJson[i].name + "(" + docJson[i].speciality + ")" + " - " + docJson[i].qualification + "</li>";
                // }
                // docData += "</ul>";
                // console.log(docData);
                // $("#doc-infor").text(docData);
              }
            });
          });
        } catch (e) {
          console.log('This doesn\'t look like a valid JSON: ', message.data);
          return;
        }
        // handle incoming message
      };
    });
  </script>
</body>
</html>
