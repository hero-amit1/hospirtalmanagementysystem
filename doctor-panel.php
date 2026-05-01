<!DOCTYPE html>
<?php 
include('func1.php');
include_once('include/config.php');
if(!isset($_SESSION['dname']) || trim((string)$_SESSION['dname']) === ''){
    echo "<script>window.location.href='index.php';</script>";
    exit();
}
$doctor = $_SESSION['dname'];

if(isset($_GET['cancel']))
{
  $appointmentId = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;
  if($appointmentId > 0){
    $stmt = mysqli_prepare($con, "UPDATE appointmenttb SET doctorStatus='0' WHERE ID=?");
    if($stmt){
      mysqli_stmt_bind_param($stmt, "i", $appointmentId);
      $query = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      if($query)
      {
        echo "<script>alert('Appointment successfully cancelled');</script>";
      }
    }
  }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Doctor Dashboard - Global Hospitals</title>
    
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
        .nav-tabs-doctor {
            border-bottom: none;
            background: white;
            border-radius: 12px;
            padding: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
            display: flex;
            flex-wrap: wrap;
        }

        .nav-tabs-doctor .nav-link {
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

        .nav-tabs-doctor .nav-link:hover {
            background: var(--light-bg);
            border-bottom: 3px solid var(--brand);
        }

        .nav-tabs-doctor .nav-link.active {
            background: white;
            color: var(--brand);
            border-bottom: 3px solid var(--brand);
        }

        /* Tab Content */
        .tab-content-doctor {
            background: white;
            padding: 2rem;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .tab-content-doctor h3 {
            font-family: "Space Grotesk", sans-serif;
            margin-bottom: 1.5rem;
            color: var(--text);
        }

        /* Tables */
        .table-responsive-doctor {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin: 1.5rem 0;
        }

        .table-doctor {
            margin: 0;
            font-size: 0.95rem;
        }

        .table-doctor thead {
            background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
            color: white;
        }

        .table-doctor thead th {
            border: none;
            padding: 1rem;
            font-weight: 700;
        }

        .table-doctor tbody td {
            padding: 1rem;
            border-color: #e8ecf1;
            vertical-align: middle;
        }

        .table-doctor tbody tr {
            transition: background-color 0.3s ease;
        }

        .table-doctor tbody tr:hover {
            background-color: #f5fffe;
        }

        .table-doctor tbody tr:nth-child(even) {
            background-color: #fbfcfd;
        }

        /* Buttons */
        .btn-doctor {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: "Manrope", sans-serif;
            font-size: 0.85rem;
            display: inline-block;
        }

        .btn-success-doctor {
            background: var(--success);
            color: white;
        }

        .btn-success-doctor:hover {
            background: #229954;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
            text-decoration: none;
            color: white;
        }

        .btn-danger-doctor {
            background: var(--danger);
            color: white;
        }

        .btn-danger-doctor:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            text-decoration: none;
            color: white;
        }

        /* Stats Grid */
        .stats-grid-doctor {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card-doctor {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card-doctor:hover {
            transform: translateY(-5px);
        }

        .stat-card-doctor i {
            font-size: 2.5rem;
            color: var(--brand);
            margin-bottom: 0.5rem;
        }

        .stat-card-doctor h3 {
            font-size: 1.5rem;
            color: var(--text);
            margin: 0.5rem 0;
            font-weight: 700;
        }

        .stat-card-doctor p {
            color: #95a5a6;
            font-size: 0.9rem;
            margin: 0;
        }

        .stat-card-doctor a {
            margin-top: 1rem;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .status-active {
            background: #e9f8f5;
            color: #0b6b60;
        }

        .status-cancelled {
            background: #fadbd8;
            color: #922b21;
        }

        /* Search Bar */
        .search-bar {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .search-bar input {
            flex: 1;
            padding: 0.85rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-bar button {
            padding: 0.85rem 1.5rem;
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-bar button:hover {
            background: var(--brand-deep);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 157, 138, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-tabs-doctor {
                flex-wrap: wrap;
            }

            .stats-grid-doctor {
                grid-template-columns: 1fr;
            }

            .dashboard-container {
                padding: 1rem;
            }

            .table-doctor {
                font-size: 0.85rem;
            }

            .table-doctor thead th,
            .table-doctor tbody td {
                padding: 0.75rem 0.5rem;
            }

            .btn-doctor {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }
    </style>
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
            <h1>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['dname']); ?></h1>
            <p>Manage your appointments and patient prescriptions</p>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs-doctor" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#dashboard" role="tab">
                    <i class="fa fa-tachometer"></i> Dashboard
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
        </ul>

        <!-- Tab Content -->
        <div class="tab-content tab-content-doctor">
            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-pane fade show active">
                <h3>Dashboard Overview</h3>
                <div class="stats-grid-doctor">
                    <div class="stat-card-doctor">
                        <i class="fa fa-calendar-check-o"></i>
                        <h3>Appointments</h3>
                        <p>Scheduled Patient Visits</p>
                        <a href="#appointments" data-toggle="tab" class="btn btn-doctor" style="background: var(--brand); color: white; text-decoration: none;">View</a>
                    </div>
                    <div class="stat-card-doctor">
                        <i class="fa fa-file-text-o"></i>
                        <h3>Prescriptions</h3>
                        <p>Patient Medical Records</p>
                        <a href="#prescriptions" data-toggle="tab" class="btn btn-doctor" style="background: var(--brand); color: white; text-decoration: none;">View</a>
                    </div>
                </div>
            </div>

            <!-- Appointments Tab -->
            <div id="appointments" class="tab-pane fade">
                <h3>My Appointments</h3>
                <div class="table-responsive-doctor">
                    <table class="table table-hover table-doctor">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Gender</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Prescribe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $con=mysqli_connect("localhost","root","","myhmsdb");
                                $dname = $_SESSION['dname'];
                                $query = "select pid,ID,fname,lname,gender,email,contact,appdate,apptime,userStatus,doctorStatus from appointmenttb where doctor='$dname' order by appdate DESC";
                                $result = mysqli_query($con,$query);
                                while ($row = mysqli_fetch_array($result)){
                                    $status = "Active";
                                    if($row['userStatus']==0 && $row['doctorStatus']==1) $status = "Cancelled by Patient";
                                    else if($row['userStatus']==1 && $row['doctorStatus']==0) $status = "Cancelled by You";
                                    
                                    $statusClass = ($row['userStatus']==1 && $row['doctorStatus']==1) ? "status-active" : "status-cancelled";
                            ?>
                                    <tr>
                                        <td><strong>#<?php echo $row['pid']; ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                                        <td><?php echo htmlspecialchars($row['apptime']); ?></td>
                                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
                                        <td>
                                            <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1)) { ?>
                                                <a href="doctor-panel.php?ID=<?php echo $row['ID']?>&cancel=update" 
                                                   onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                   class="btn btn-doctor btn-danger-doctor">Cancel</a>
                                            <?php } else { ?>
                                                <span style="color: #999;">Cancelled</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if(($row['userStatus']==1) && ($row['doctorStatus']==1)) { ?>
                                                <a href="prescribe.php?pid=<?php echo $row['pid']?>&ID=<?php echo $row['ID']?>&fname=<?php echo $row['fname']?>&lname=<?php echo $row['lname']?>&appdate=<?php echo $row['appdate']?>&apptime=<?php echo $row['apptime']?>"
                                                   class="btn btn-doctor btn-success-doctor">Prescribe</a>
                                            <?php } else { ?>
                                                <span style="color: #999;">-</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Prescriptions Tab -->
            <div id="prescriptions" class="tab-pane fade">
                <h3>Patient Prescriptions</h3>
                <div class="table-responsive-doctor">
                    <table class="table table-hover table-doctor">
                        <thead>
                            <tr>
                                <th>Patient ID</th>
                                <th>Patient Name</th>
                                <th>Appointment ID</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Disease</th>
                                <th>Allergy</th>
                                <th>Prescription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $con=mysqli_connect("localhost","root","","myhmsdb");
                                $doctor = $_SESSION['dname'];
                                $query = "select pid,fname,lname,ID,appdate,apptime,disease,allergy,prescription from prestb where doctor='$doctor' order by appdate DESC";
                                $result = mysqli_query($con,$query);
                                
                                if(!$result){
                                    echo "<tr><td colspan='8' class='text-center text-danger'>Error: " . mysqli_error($con) . "</td></tr>";
                                } else if(mysqli_num_rows($result) == 0) {
                                    echo "<tr><td colspan='8' class='text-center text-muted'>No prescriptions yet</td></tr>";
                                } else {
                                    while ($row = mysqli_fetch_array($result)){
                            ?>
                                        <tr>
                                            <td><strong>#<?php echo $row['pid']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                                            <td><strong>#<?php echo $row['ID']; ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                                            <td><?php echo htmlspecialchars($row['apptime']); ?></td>
                                            <td><?php echo htmlspecialchars($row['disease']); ?></td>
                                            <td><?php echo htmlspecialchars($row['allergy']); ?></td>
                                            <td><small><?php echo htmlspecialchars($row['prescription']); ?></small></td>
                                        </tr>
                            <?php 
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
