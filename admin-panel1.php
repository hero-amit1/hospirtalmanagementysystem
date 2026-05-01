<?php 
include_once('include/config.php');
include_once('include/validation_helper.php');
if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}

if(!isset($_SESSION['username']) || trim((string)$_SESSION['username']) === ''){
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Dashboard - Global Hospitals</title>
    
    <link href="https://fonts.googleapis.com/css?family=Manrope:400,600,700|Space+Grotesk:700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        (function lockBackNavigation() {
            history.pushState(null, '', location.href);
            
            window.addEventListener('popstate', function() {
                history.forward();
            });
        })();
    </script>
    
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
            flex-wrap: wrap;
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
            font-size: 0.95rem;
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
        .btn-admin {
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

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .form-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

            .form-grid {
                grid-template-columns: 1fr;
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
            <h1>Welcome, Admin</h1>
            <p>Manage doctors, patients, appointments, and medical records efficiently</p>
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
            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-pane fade show active">
                <h3>Dashboard Overview</h3>
                <div class="stats-grid-admin">
                    <div class="stat-card-admin">
                        <i class="fa fa-user-md"></i>
                        <h3>Doctor List</h3>
                        <p>Manage Specialists</p>
                        <a href="#doctors" data-toggle="tab" class="btn btn-outline-admin" style="margin-top: 1rem; font-size: 0.85rem; padding: 0.5rem 1rem;">View</a>
                    </div>
                    <div class="stat-card-admin">
                        <i class="fa fa-users"></i>
                        <h3>Patient List</h3>
                        <p>Registered Patients</p>
                        <a href="#patients" data-toggle="tab" class="btn btn-outline-admin" style="margin-top: 1rem; font-size: 0.85rem; padding: 0.5rem 1rem;">View</a>
                    </div>
                    <div class="stat-card-admin">
                        <i class="fa fa-calendar"></i>
                        <h3>Appointments</h3>
                        <p>Scheduled Visits</p>
                        <a href="#appointments" data-toggle="tab" class="btn btn-outline-admin" style="margin-top: 1rem; font-size: 0.85rem; padding: 0.5rem 1rem;">View</a>
                    </div>
                    <div class="stat-card-admin">
                        <i class="fa fa-file-text"></i>
                        <h3>Prescriptions</h3>
                        <p>Medical Records</p>
                        <a href="#prescriptions" data-toggle="tab" class="btn btn-outline-admin" style="margin-top: 1rem; font-size: 0.85rem; padding: 0.5rem 1rem;">View</a>
                    </div>
                </div>
            </div>

            <!-- Doctors Tab -->
            <div id="doctors" class="tab-pane fade">
                <h3>Doctor List</h3>
                <div class="search-section">
                    <form action="doctorsearch.php" method="post" style="width: 100%; display: flex; gap: 0.75rem;">
                        <input type="text" name="doctor_contact" placeholder="Search by Email ID" required>
                        <button type="submit" name="doctor_search_submit" class="btn btn-primary-admin">Search</button>
                    </form>
                </div>
                <div class="table-responsive-admin">
                    <table class="table table-hover table-admin">
                        <thead>
                            <tr>
                                <th>Doctor Name</th>
                                <th>Specialization</th>
                                <th>Email</th>
                                <th>Consultancy Fees</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $con=mysqli_connect("localhost","root","","myhmsdb");
                                $query = "select username, spec, email, docFees from doctb order by username";
                                $result = mysqli_query($con,$query);
                                while ($row = mysqli_fetch_array($result)){
                                    echo "<tr>
                                        <td><strong>{$row['username']}</strong></td>
                                        <td>{$row['spec']}</td>
                                        <td>{$row['email']}</td>
                                        <td>Rs. {$row['docFees']}</td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Patients Tab -->
            <div id="patients" class="tab-pane fade">
                <h3>Patient List</h3>
                <div class="search-section">
                    <form action="patientsearch.php" method="post" style="width: 100%; display: flex; gap: 0.75rem;">
                        <input type="text" name="patient_contact" placeholder="Search by Contact Number" required>
                        <button type="submit" name="patient_search_submit" class="btn btn-primary-admin">Search</button>
                    </form>
                </div>
                <div class="table-responsive-admin">
                    <table class="table table-hover table-admin">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $con=mysqli_connect("localhost","root","","myhmsdb");
                                $query = "select pid,fname,lname,gender,email,contact from patreg order by fname";
                                $result = mysqli_query($con,$query);
                                while ($row = mysqli_fetch_array($result)){
                                    echo "<tr>
                                        <td><strong>#{$row['pid']}</strong></td>
                                        <td>{$row['fname']}</td>
                                        <td>{$row['lname']}</td>
                                        <td>{$row['gender']}</td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['contact']}</td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Appointments Tab -->
            <div id="appointments" class="tab-pane fade">
                <h3>Appointment Details</h3>
                <div class="search-section">
                    <form action="appsearch.php" method="post" style="width: 100%; display: flex; gap: 0.75rem;">
                        <input type="text" name="app_contact" placeholder="Search by Patient Contact" required>
                        <button type="submit" name="app_search_submit" class="btn btn-primary-admin">Search</button>
                    </form>
                </div>
                <div class="table-responsive-admin">
                    <table class="table table-hover table-admin">
                        <thead>
                            <tr>
                                <th>Apt ID</th>
                                <th>Patient ID</th>
                                <th>Name</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Fee</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $con=mysqli_connect("localhost","root","","myhmsdb");
                                $query = "select ID,pid,fname,lname,doctor,appdate,apptime,docFees,userStatus,doctorStatus from appointmenttb order by appdate DESC";
                                $result = mysqli_query($con,$query);
                                while ($row = mysqli_fetch_array($result)){
                                    $status = "Active";
                                    if($row['userStatus']==0 && $row['doctorStatus']==1) $status = "Cancelled by Patient";
                                    else if($row['userStatus']==1 && $row['doctorStatus']==0) $status = "Cancelled by Doctor";
                                    echo "<tr>
                                        <td><strong>#{$row['ID']}</strong></td>
                                        <td>#{$row['pid']}</td>
                                        <td>{$row['fname']} {$row['lname']}</td>
                                        <td>{$row['doctor']}</td>
                                        <td>{$row['appdate']}</td>
                                        <td>{$row['apptime']}</td>
                                        <td>Rs. {$row['docFees']}</td>
                                        <td><span style='padding: 0.3rem 0.6rem; border-radius: 4px; background: #e9f8f5; color: #0b6b60; font-weight: 600; font-size: 0.85rem;'>$status</span></td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Prescriptions Tab -->
            <div id="prescriptions" class="tab-pane fade">
                <h3>Prescription Records</h3>
                <div class="table-responsive-admin">
                    <table class="table table-hover table-admin">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Disease</th>
                                <th>Allergy</th>
                                <th>Prescription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $con=mysqli_connect("localhost","root","","myhmsdb");
                                $query = "select doctor,fname,lname,appdate,apptime,disease,allergy,prescription from prestb order by appdate DESC";
                                $result = mysqli_query($con,$query);
                                while ($row = mysqli_fetch_array($result)){
                                    echo "<tr>
                                        <td><strong>{$row['doctor']}</strong></td>
                                        <td>{$row['fname']} {$row['lname']}</td>
                                        <td>{$row['appdate']}</td>
                                        <td>{$row['apptime']}</td>
                                        <td>{$row['disease']}</td>
                                        <td>{$row['allergy']}</td>
                                        <td><small>{$row['prescription']}</small></td>
                                    </tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Doctor Tab -->
            <div id="add-doctor" class="tab-pane fade">
                <h3>Add New Doctor</h3>
                <div class="form-section" style="max-width: 500px;">
                    <form method="post" action="admin-panel1.php">
                        <div class="form-group-admin">
                            <label>Doctor Name *</label>
                            <input type="text" name="doctor" placeholder="Enter full name" onkeydown="return alphaOnly(event);" required>
                        </div>
                        <div class="form-group-admin">
                            <label>Specialization *</label>
                            <select name="special" required>
                                <option value="" disabled selected>Select Specialization</option>
                                <option value="General">General</option>
                                <option value="Cardiologist">Cardiologist</option>
                                <option value="Neurologist">Neurologist</option>
                                <option value="Pediatrician">Pediatrician</option>
                                <option value="Orthopedist">Orthopedist</option>
                                <option value="Dermatologist">Dermatologist</option>
                            </select>
                        </div>
                        <div class="form-group-admin">
                            <label>Email ID *</label>
                            <input type="email" name="demail" placeholder="Enter email address" required>
                        </div>
                        <div class="form-group-admin">
                            <label>Password *</label>
                            <input type="password" name="dpassword" id="dpassword" placeholder="Minimum 6 characters" onkeyup='check();' required>
                        </div>
                        <div class="form-group-admin">
                            <label>Confirm Password *</label>
                            <div style="position: relative;">
                                <input type="password" name="cdpassword" id="cdpassword" placeholder="Re-enter password" onkeyup='check();' required>
                                <span id='message' style="position: absolute; right: 10px; top: 15px; font-size: 0.9rem;"></span>
                            </div>
                        </div>
                        <div class="form-group-admin">
                            <label>Consultancy Fees (Rs) *</label>
                            <input type="number" name="docFees" placeholder="Enter fees amount" min="100" required>
                        </div>
                        <button type="submit" name="docsub" class="btn btn-admin btn-primary-admin" style="width: 100%;">Add Doctor</button>
                    </form>
                </div>
            </div>

            <!-- Delete Doctor Tab -->
            <div id="delete-doctor" class="tab-pane fade">
                <h3>Delete Doctor</h3>
                <div class="form-section" style="max-width: 500px;">
                    <form method="post" action="admin-panel1.php" onsubmit="return confirm('Are you sure you want to delete this doctor? This action cannot be undone.');">
                        <div class="form-group-admin">
                            <label>Doctor Email ID *</label>
                            <input type="email" name="demail" placeholder="Enter doctor's email address" required>
                        </div>
                        <p style="color: #e74c3c; font-size: 0.9rem; margin-bottom: 1rem;">
                            <i class="fa fa-warning"></i> Warning: Deleting a doctor cannot be undone. All associated records will be affected.
                        </p>
                        <button type="submit" name="docsub1" class="btn btn-admin btn-danger-admin" style="width: 100%;">Delete Doctor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
