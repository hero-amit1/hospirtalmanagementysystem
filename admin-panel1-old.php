<!DOCTYPE html>
<?php 
include_once('include/config.php');
include_once('include/validation_helper.php');

include('newfunc.php');

if(isset($_POST['docsub']))
{
  $doctor = trim($_POST['doctor'] ?? '');
  $dpassword = $_POST['dpassword'] ?? '';
  $demail = trim($_POST['demail'] ?? '');
  $spec = trim($_POST['special'] ?? 'General');
  $docFees = (isset($_POST['docFees']) && is_numeric($_POST['docFees'])) ? (int)$_POST['docFees'] : 0;

  if(hms_is_non_empty($doctor) && hms_is_non_empty($dpassword) && hms_is_valid_email($demail) && $docFees > 0){
    $hashedPassword = password_hash($dpassword, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($con, "insert into doctb(username,password,email,spec,docFees) values(?,?,?,?,?)");
    if($stmt){
      mysqli_stmt_bind_param($stmt, "ssssi", $doctor, $hashedPassword, $demail, $spec, $docFees);
      $result = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      if($result)
      {
        echo "<script>alert('Doctor added successfully!');</script>";
      }
    }
  }
}


if(isset($_POST['docsub1']))
{
  $demail = trim($_POST['demail'] ?? '');
  if(hms_is_valid_email($demail)){
    $stmt = mysqli_prepare($con, "delete from doctb where email=?");
    if($stmt){
      mysqli_stmt_bind_param($stmt, "s", $demail);
      $result = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      if($result)
      {
        echo "<script>alert('Doctor removed successfully!');</script>";
      }
      else{
        echo "<script>alert('Unable to delete!');</script>";
      }
    }
  } else {
    echo "<script>alert('Please enter a valid doctor email.');</script>";
  }
}


?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Global Hospitals</title>
    
    <link href="https://fonts.googleapis.com/css?family=Manrope:400,600,700|Space+Grotesk:700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    
    <style>
        :root {
            --brand: #0f9d8a;
            --brand-deep: #0c7f70;
            --text: #2c3e50;
            --light-bg: #f8f9fa;
            --border: #e1e8ed;
            --danger: #e74c3c;
            --success: #27ae60;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Manrope", sans-serif;
            color: var(--text);
            line-height: 1.6;
            background: var(--light-bg);
            padding-top: 70px;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-family: "Space Grotesk", sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            color: white !important;
        }

        .navbar .nav-link {
            color: white !important;
            font-weight: 500;
            transition: opacity 0.3s ease;
            margin-left: 1.5rem;
        }

        .navbar .nav-link:hover {
            opacity: 0.8;
        }

        /* Main Container */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .dashboard-header {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--brand);
        }

        .dashboard-header h1 {
            font-family: "Space Grotesk", sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .dashboard-header p {
            color: #95a5a6;
            font-size: 1rem;
        }

        /* Tabs Navigation */
        .nav-tabs-admin {
            border-bottom: none;
            background: white;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            display: flex;
        }

        .nav-tabs-admin .nav-link {
            border: none;
            color: var(--text);
            font-weight: 600;
            padding: 1.2rem 1.5rem;
            border-radius: 0;
            transition: all 0.3s ease;
            background: white;
            white-space: nowrap;
        }

        .nav-tabs-admin .nav-link:hover {
            background: var(--light-bg);
            border-bottom: 3px solid var(--brand);
        }

        .nav-tabs-admin .nav-link.active {
            background: white;
            color: var(--brand);
            border-bottom: 3px solid var(--brand);
        }

        /* Tab Content */
        .tab-content-admin {
            background: white;
            padding: 2rem;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .tab-content-admin h3 {
            font-family: "Space Grotesk", sans-serif;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        /* Forms */
        .form-group-admin {
            margin-bottom: 1.5rem;
        }

        .form-group-admin label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .form-group-admin input,
        .form-group-admin select {
            width: 100%;
            padding: 0.85rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-family: "Manrope", sans-serif;
            color: var(--text);
            background-color: #fff;
            transition: border-color 0.3s ease;
            font-size: 1rem;
        }

        .form-group-admin input:focus,
        .form-group-admin select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 0.2rem rgba(15, 157, 138, 0.1);
        }

        /* Tables */
        .table-responsive-admin {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin: 1.5rem 0;
        }

        .table-admin {
            margin: 0;
            font-size: 0.95rem;
        }

        .table-admin thead {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
            color: white;
        }

        .table-admin thead th {
            border: none;
            padding: 1rem;
            font-weight: 700;
        }

        .table-admin tbody td {
            padding: 1rem;
            border-color: #e8ecf1;
            vertical-align: middle;
        }

        .table-admin tbody tr {
            transition: background-color 0.3s ease;
        }

        .table-admin tbody tr:hover {
            background-color: #f5fffe;
        }

        .table-admin tbody tr:nth-child(even) {
            background-color: #fbfcfd;
        }

        /* Buttons */
        .btn-modern-admin {
            padding: 0.75rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: "Manrope", sans-serif;
            font-size: 0.95rem;
        }

        .btn-primary-admin {
            background: var(--brand);
            color: white;
        }

        .btn-primary-admin:hover {
            background: var(--brand-deep);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 157, 138, 0.3);
        }

        .btn-danger-admin {
            background: var(--danger);
            color: white;
        }

        .btn-danger-admin:hover {
            background: #c0392b;
        }

        .btn-outline-admin {
            border: 2px solid var(--brand);
            background: white;
            color: var(--brand);
        }

        .btn-outline-admin:hover {
            background: var(--brand);
            color: white;
        }

        /* Search Section */
        .search-section {
            margin-bottom: 1.5rem;
            display: flex;
            gap: 0.75rem;
        }

        .search-section input {
            flex: 1;
            padding: 0.85rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-section button {
            padding: 0.85rem 1.5rem;
        }

        /* Stats Grid */
        .stats-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card-admin {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card-admin:hover {
            transform: translateY(-5px);
        }

        .stat-card-admin i {
            font-size: 2.5rem;
            color: var(--brand);
            margin-bottom: 0.5rem;
        }

        .stat-card-admin h3 {
            font-size: 1.5rem;
            color: var(--text);
            margin: 0.5rem 0;
            font-weight: 700;
        }

        .stat-card-admin p {
            color: #95a5a6;
            font-size: 0.9rem;
            margin: 0;
        }

        /* Message */
        #message {
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .password-match {
            color: var(--success);
        }

        .password-notmatch {
            color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-tabs-admin {
                flex-wrap: wrap;
            }

            .stats-grid-admin {
                grid-template-columns: 1fr;
            }

            .dashboard-container {
                padding: 1rem;
            }

            .table-admin {
                font-size: 0.85rem;
            }

            .table-admin thead th,
            .table-admin tbody td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>

    <script>
        var check = function() {
            if (document.getElementById('dpassword').value == document.getElementById('cdpassword').value) {
                document.getElementById('message').style.color = '#27ae60';
                document.getElementById('message').innerHTML = '✓ Matched';
                document.getElementById('message').classList.add('password-match');
                document.getElementById('message').classList.remove('password-notmatch');
            } else {
                document.getElementById('message').style.color = '#e74c3c';
                document.getElementById('message').innerHTML = '✗ Not Matching';
                document.getElementById('message').classList.add('password-notmatch');
                document.getElementById('message').classList.remove('password-match');
            }
        }

        function alphaOnly(event) {
            var key = event.keyCode;
            return ((key >= 65 && key <= 90) || key == 8 || key == 32);
        };
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#"><i class="fa fa-hospital-o"></i> Global Hospitals</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="logout1.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1>Welcome, Receptionist</h1>
            <p>Manage doctors, patients, appointments, and medical records</p>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs-admin" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#dashboard" role="tab">
                    <i class="fa fa-tachometer"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#doctors" role="tab">
                    <i class="fa fa-user-md"></i> Doctor List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#patients" role="tab">
                    <i class="fa fa-users"></i> Patient List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#appointments" role="tab">
                    <i class="fa fa-calendar"></i> Appointments
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#prescriptions" role="tab">
                    <i class="fa fa-file-text"></i> Prescriptions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#add-doctor" role="tab">
                    <i class="fa fa-plus"></i> Add Doctor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#delete-doctor" role="tab">
                    <i class="fa fa-trash"></i> Delete Doctor
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content tab-content-admin">



      <div class="tab-pane fade show active" id="list-dash" role="tabpanel" aria-labelledby="list-dash-list">
        <div class="container-fluid container-fullw bg-white" >
              <div class="row">
               <div class="col-sm-4">
                  <div class="panel panel-white no-radius text-center">
                    <div class="panel-body">
                      <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-users fa-stack-1x fa-inverse"></i> </span>
                      <h4 class="StepTitle" style="margin-top: 5%;">Doctor List</h4>
                      <script>
                        function clickDiv(id) {
                          document.querySelector(id).click();
                        }
                      </script> 
                      <p class="links cl-effect-1">
                        <a href="#list-doc" onclick="clickDiv('#list-doc-list')">
                          View Doctors
                        </a>
                      </p>
                    </div>
                  </div>
                </div>

                <div class="col-sm-4" style="left: -3%">
                  <div class="panel panel-white no-radius text-center">
                    <div class="panel-body" >
                      <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-users fa-stack-1x fa-inverse"></i> </span>
                      <h4 class="StepTitle" style="margin-top: 5%;">Patient List</h4>
                      
                      <p class="cl-effect-1">
                        <a href="#app-hist" onclick="clickDiv('#list-pat-list')">
                          View Patients
                        </a>
                      </p>
                    </div>
                  </div>
                </div>
              

                <div class="col-sm-4">
                  <div class="panel panel-white no-radius text-center">
                    <div class="panel-body" >
                      <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span>
                      <h4 class="StepTitle" style="margin-top: 5%;">Appointment Details</h4>
                    
                      <p class="cl-effect-1">
                        <a href="#app-hist" onclick="clickDiv('#list-app-list')">
                          View Appointments
                        </a>
                      </p>
                    </div>
                  </div>
                </div>
                </div>

                <div class="row">
                <div class="col-sm-4" style="left: 13%;margin-top: 5%;">
                  <div class="panel panel-white no-radius text-center">
                    <div class="panel-body" >
                      <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-list-ul fa-stack-1x fa-inverse"></i> </span>
                      <h4 class="StepTitle" style="margin-top: 5%;">Prescription List</h4>
                    
                      <p class="cl-effect-1">
                        <a href="#list-pres" onclick="clickDiv('#list-pres-list')">
                          View Prescriptions
                        </a>
                      </p>
                    </div>
                  </div>
                </div>


                <div class="col-sm-4" style="left: 18%;margin-top: 5%">
                  <div class="panel panel-white no-radius text-center">
                    <div class="panel-body" >
                      <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-plus fa-stack-1x fa-inverse"></i> </span>
                      <h4 class="StepTitle" style="margin-top: 5%;">Manage Doctors</h4>
                    
                      <p class="cl-effect-1">
                        <a href="#app-hist" onclick="clickDiv('#list-adoc-list')">Add Doctors</a>
                        &nbsp|
                        <a href="#app-hist" onclick="clickDiv('#list-ddoc-list')">
                          Delete Doctors
                        </a>
                      </p>
                    </div>
                  </div>
                </div>
                </div>
                        

      
                
              </div>
            </div>
      
                
      






      <div class="tab-pane fade" id="list-doc" role="tabpanel" aria-labelledby="list-home-list">
              

              <div class="col-md-8">
      <form class="form-group" action="doctorsearch.php" method="post">
        <div class="row">
        <div class="col-md-10"><input type="text" name="doctor_contact" placeholder="Enter Email ID" class = "form-control"></div>
        <div class="col-md-2"><input type="submit" name="doctor_search_submit" class="btn btn-primary" value="Search"></div></div>
      </form>
    </div>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Specialization</th>
                    <th scope="col">Email</th>
                    <th scope="col">Password</th>
                    <th scope="col">Fees</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $con=mysqli_connect("localhost","root","","myhmsdb");
                    global $con;
                    $query = "select * from doctb";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){
                      $username = $row['username'];
                      $spec = $row['spec'];
                      $email = $row['email'];
                      $password = $row['password'];
                      $docFees = $row['docFees'];
                      
                      echo "<tr>
                        <td>$username</td>
                        <td>$spec</td>
                        <td>$email</td>
                        <td>$password</td>
                        <td>$docFees</td>
                      </tr>";
                    }

                  ?>
                </tbody>
              </table>
        <br>
      </div>
    

    <div class="tab-pane fade" id="list-pat" role="tabpanel" aria-labelledby="list-pat-list">

       <div class="col-md-8">
      <form class="form-group" action="patientsearch.php" method="post">
        <div class="row">
        <div class="col-md-10"><input type="text" name="patient_contact" placeholder="Enter Contact" class = "form-control"></div>
        <div class="col-md-2"><input type="submit" name="patient_search_submit" class="btn btn-primary" value="Search"></div></div>
      </form>
    </div>
        
              <table class="table table-hover">
                <thead>
                  <tr>
                  <th scope="col">Patient ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Email</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Password</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $con=mysqli_connect("localhost","root","","myhmsdb");
                    global $con;
                    $query = "select * from patreg";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){
                      $pid = $row['pid'];
                      $fname = $row['fname'];
                      $lname = $row['lname'];
                      $gender = $row['gender'];
                      $email = $row['email'];
                      $contact = $row['contact'];
                      $password = $row['password'];
                      
                      echo "<tr>
                        <td>$pid</td>
                        <td>$fname</td>
                        <td>$lname</td>
                        <td>$gender</td>
                        <td>$email</td>
                        <td>$contact</td>
                        <td>$password</td>
                      </tr>";
                    }

                  ?>
                </tbody>
              </table>
        <br>
      </div>


      <div class="tab-pane fade" id="list-pres" role="tabpanel" aria-labelledby="list-pres-list">

       <div class="col-md-8">
  
        <div class="row">
        
    
        
              <table class="table table-hover">
                <thead>
                  <tr>
                  <th scope="col">Doctor</th>
                    <th scope="col">Patient ID</th>
                    <th scope="col">Appointment ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Appointment Date</th>
                    <th scope="col">Appointment Time</th>
                    <th scope="col">Disease</th>
                    <th scope="col">Allergy</th>
                    <th scope="col">Prescription</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $con=mysqli_connect("localhost","root","","myhmsdb");
                    global $con;
                    $query = "select * from prestb";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){
                      $doctor = $row['doctor'];
                      $pid = $row['pid'];
                      $ID = $row['ID'];
                      $fname = $row['fname'];
                      $lname = $row['lname'];
                      $appdate = $row['appdate'];
                      $apptime = $row['apptime'];
                      $disease = $row['disease'];
                      $allergy = $row['allergy'];
                      $pres = $row['prescription'];

                      
                      echo "<tr>
                        <td>$doctor</td>
                        <td>$pid</td>
                        <td>$ID</td>
                        <td>$fname</td>
                        <td>$lname</td>
                        <td>$appdate</td>
                        <td>$apptime</td>
                        <td>$disease</td>
                        <td>$allergy</td>
                        <td>$pres</td>
                      </tr>";
                    }

                  ?>
                </tbody>
              </table>
        <br>
      </div>
      </div>
      </div>




      <div class="tab-pane fade" id="list-app" role="tabpanel" aria-labelledby="list-pat-list">

         <div class="col-md-8">
      <form class="form-group" action="appsearch.php" method="post">
        <div class="row">
        <div class="col-md-10"><input type="text" name="app_contact" placeholder="Enter Contact" class = "form-control"></div>
        <div class="col-md-2"><input type="submit" name="app_search_submit" class="btn btn-primary" value="Search"></div></div>
      </form>
    </div>
        
              <table class="table table-hover">
                <thead>
                  <tr>
                  <th scope="col">Appointment ID</th>
                  <th scope="col">Patient ID</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Email</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Doctor Name</th>
                    <th scope="col">Consultancy Fees</th>
                    <th scope="col">Appointment Date</th>
                    <th scope="col">Appointment Time</th>
                    <th scope="col">Appointment Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                    $con=mysqli_connect("localhost","root","","myhmsdb");
                    global $con;

                    $query = "select * from appointmenttb;";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){
                  ?>
                      <tr>
                        <td><?php echo $row['ID'];?></td>
                        <td><?php echo $row['pid'];?></td>
                        <td><?php echo $row['fname'];?></td>
                        <td><?php echo $row['lname'];?></td>
                        <td><?php echo $row['gender'];?></td>
                        <td><?php echo $row['email'];?></td>
                        <td><?php echo $row['contact'];?></td>
                        <td><?php echo $row['doctor'];?></td>
                        <td><?php echo $row['docFees'];?></td>
                        <td><?php echo $row['appdate'];?></td>
                        <td><?php echo $row['apptime'];?></td>
                        <td>
                    <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                    {
                      echo "Active";
                    }
                    if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
                    {
                      echo "Cancelled by Patient";
                    }

                    if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
                    {
                      echo "Cancelled by Doctor";
                    }
                        ?></td>
                      </tr>
                    <?php } ?>
                </tbody>
              </table>
        <br>
      </div>

<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">...</div>

      <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
        <form class="form-group" method="post" action="admin-panel1.php">
          <div class="row">
                  <div class="col-md-4"><label>Doctor Name:</label></div>
                  <div class="col-md-8"><input type="text" class="form-control" name="doctor" onkeydown="return alphaOnly(event);" required></div><br><br>
                  <div class="col-md-4"><label>Specialization:</label></div>
                  <div class="col-md-8">
                   <select name="special" class="form-control" id="special" required="required">
                      <option value="head" name="spec" disabled selected>Select Specialization</option>
                      <option value="General" name="spec">General</option>
                      <option value="Cardiologist" name="spec">Cardiologist</option>
                      <option value="Neurologist" name="spec">Neurologist</option>
                      <option value="Pediatrician" name="spec">Pediatrician</option>
                    </select>
                    </div><br><br>
                  <div class="col-md-4"><label>Email ID:</label></div>
                  <div class="col-md-8"><input type="email"  class="form-control" name="demail" required></div><br><br>
                  <div class="col-md-4"><label>Password:</label></div>
                  <div class="col-md-8"><input type="password" class="form-control"  onkeyup='check();' name="dpassword" id="dpassword"  required></div><br><br>
                  <div class="col-md-4"><label>Confirm Password:</label></div>
                  <div class="col-md-8"  id='cpass'><input type="password" class="form-control" onkeyup='check();' name="cdpassword" id="cdpassword" required>&nbsp &nbsp<span id='message'></span> </div><br><br>
                   
                  
                  <div class="col-md-4"><label>Consultancy Fees:</label></div>
                  <div class="col-md-8"><input type="text" class="form-control"  name="docFees" required></div><br><br>
                </div>
          <input type="submit" name="docsub" value="Add Doctor" class="btn btn-primary">
        </form>
      </div>

      <div class="tab-pane fade" id="list-settings1" role="tabpanel" aria-labelledby="list-settings1-list">
        <form class="form-group" method="post" action="admin-panel1.php">
          <div class="row">
          
                  <div class="col-md-4"><label>Email ID:</label></div>
                  <div class="col-md-8"><input type="email"  class="form-control" name="demail" required></div><br><br>
                  
                </div>
          <input type="submit" name="docsub1" value="Delete Doctor" class="btn btn-primary" onclick="confirm('do you really want to delete?')">
        </form>
      </div>


       <div class="tab-pane fade" id="list-attend" role="tabpanel" aria-labelledby="list-attend-list">...</div>

       <div class="tab-pane fade" id="list-mes" role="tabpanel" aria-labelledby="list-mes-list">

         <div class="col-md-8">
      <form class="form-group" action="messearch.php" method="post">
        <div class="row">
        <div class="col-md-10"><input type="text" name="mes_contact" placeholder="Enter Contact" class = "form-control"></div>
        <div class="col-md-2"><input type="submit" name="mes_search_submit" class="btn btn-primary" value="Search"></div></div>
      </form>
    </div>
        
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th scope="col">User Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Message</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                    $con=mysqli_connect("localhost","root","","myhmsdb");
                    global $con;

                    $query = "select * from contact;";
                    $result = mysqli_query($con,$query);
                    while ($row = mysqli_fetch_array($result)){
              
                      #$fname = $row['fname'];
                      #$lname = $row['lname'];
                      #$email = $row['email'];
                      #$contact = $row['contact'];
                  ?>
                      <tr>
                        <td><?php echo $row['name'];?></td>
                        <td><?php echo $row['email'];?></td>
                        <td><?php echo $row['contact'];?></td>
                        <td><?php echo $row['message'];?></td>
                      </tr>
                    <?php } ?>
                </tbody>
              </table>
        <br>
      </div>



    </div>
  </div>
</div>
   </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.10.1/sweetalert2.all.min.js"></script>
  </body>
</html>