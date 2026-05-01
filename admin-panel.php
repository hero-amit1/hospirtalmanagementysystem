<!DOCTYPE html>
<?php 
include('func.php');  
include('newfunc.php');
$con=mysqli_connect("localhost","root","","myhmsdb");

if(!isset($_SESSION['pid']) || (int)$_SESSION['pid'] <= 0){
  echo "<script>window.location.href='index1.php';</script>";
  exit();
}

  $pid = $_SESSION['pid'];
  $username = $_SESSION['username'];
  $email = $_SESSION['email'];
  $fname = $_SESSION['fname'];
  $gender = $_SESSION['gender'];
  $lname = $_SESSION['lname'];
  $contact = $_SESSION['contact'];

if(isset($_POST['app-submit']))
{
  $pid = $_SESSION['pid'];
  $username = $_SESSION['username'];
  $email = $_SESSION['email'];
  $fname = $_SESSION['fname'];
  $lname = $_SESSION['lname'];
  $gender = $_SESSION['gender'];
  $contact = $_SESSION['contact'];
  $doctor=trim($_POST['doctor'] ?? '');
  $email=$_SESSION['email'];
  $docFees=0;
  $appdate=$_POST['appdate'] ?? '';
  $apptime=$_POST['apptime'] ?? '';

  if($doctor === '' || $appdate === '' || $apptime === ''){
    echo "<script>alert('Please complete all appointment fields.');</script>";
  } else {
    // Always resolve consultancy fees from DB to avoid stale/tampered client values.
    $fee_stmt = mysqli_prepare($con, "SELECT docFees FROM doctb WHERE username=? LIMIT 1");
    if($fee_stmt){
      mysqli_stmt_bind_param($fee_stmt, "s", $doctor);
      mysqli_stmt_execute($fee_stmt);
      mysqli_stmt_bind_result($fee_stmt, $resolvedFee);
      if(mysqli_stmt_fetch($fee_stmt)){
        $docFees = (int)$resolvedFee;
      }
      mysqli_stmt_close($fee_stmt);
    }

    if($docFees <= 0){
      echo "<script>alert('Please choose a valid doctor.');</script>";
    }
  }

  if($docFees <= 0 || $doctor === '' || $appdate === '' || $apptime === ''){
    // Stop processing when validation fails.
  } else {
  $cur_date = date("Y-m-d");
  date_default_timezone_set('Asia/Kolkata');
  $cur_time = date("H:i:s");
  $apptime1 = strtotime($apptime);
  $appdate1 = strtotime($appdate);

  if(date("Y-m-d",$appdate1)>=$cur_date){
    if((date("Y-m-d",$appdate1)==$cur_date and date("H:i:s",$apptime1)>$cur_time) or date("Y-m-d",$appdate1)>$cur_date) {
      $check_query = false;
      $check_stmt = mysqli_prepare($con, "SELECT apptime FROM appointmenttb WHERE doctor=? AND appdate=? AND apptime=?");
      if($check_stmt){
        mysqli_stmt_bind_param($check_stmt, "sss", $doctor, $appdate, $apptime);
        mysqli_stmt_execute($check_stmt);
        $check_query = mysqli_stmt_get_result($check_stmt);
        mysqli_stmt_close($check_stmt);
      }

        if($check_query && mysqli_num_rows($check_query)==0){
          $insert_stmt = mysqli_prepare($con, "INSERT INTO appointmenttb(pid,fname,lname,gender,email,contact,doctor,docFees,appdate,apptime,userStatus,doctorStatus) VALUES(?,?,?,?,?,?,?,?,?,?,1,1)");
          $query = false;
          if($insert_stmt){
            mysqli_stmt_bind_param($insert_stmt, "issssssiss", $pid, $fname, $lname, $gender, $email, $contact, $doctor, $docFees, $appdate, $apptime);
            $query = mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);
          }

          if($query)
          {
            echo "<script>alert('Your appointment successfully booked');</script>";
          }
          else{
            echo "<script>alert('Unable to process your request. Please try again!');</script>";
          }
      }
      else{
        echo "<script>alert('We are sorry to inform that the doctor is not available in this time or date. Please choose different time or date!');</script>";
      }
    }
    else{
      echo "<script>alert('Select a time or date in the future!');</script>";
    }
  }
  else{
      echo "<script>alert('Select a time or date in the future!');</script>";
  }
  }
}

if(isset($_GET['cancel']))
  {
    $appointmentId = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;
    if($appointmentId > 0){
      $stmt = mysqli_prepare($con, "UPDATE appointmenttb SET userStatus='0' WHERE ID=?");
      if($stmt){
        mysqli_stmt_bind_param($stmt, "i", $appointmentId);
        $query = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if($query)
        {
          echo "<script>alert('Your appointment successfully cancelled');</script>";
        }
      }
    }
  }

function generate_bill(){
  $con=mysqli_connect("localhost","root","","myhmsdb");
  $pid = $_SESSION['pid'];
  $output='';
  $billId = isset($_GET['ID']) ? (int)$_GET['ID'] : 0;
  $stmt = mysqli_prepare($con, "SELECT p.pid,p.ID,p.fname,p.lname,p.doctor,p.appdate,p.apptime,p.disease,p.allergy,p.prescription,a.docFees FROM prestb p INNER JOIN appointmenttb a ON p.ID=a.ID WHERE p.pid=? AND p.ID=?");
  $query = false;
  if($stmt){
    mysqli_stmt_bind_param($stmt, "ii", $pid, $billId);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
  }
  while($query && ($row = mysqli_fetch_array($query))){
    $output .= '
    <label> Patient ID : </label>'.$row["pid"].'<br/><br/>
    <label> Appointment ID : </label>'.$row["ID"].'<br/><br/>
    <label> Patient Name : </label>'.$row["fname"].' '.$row["lname"].'<br/><br/>
    <label> Doctor Name : </label>'.$row["doctor"].'<br/><br/>
    <label> Appointment Date : </label>'.$row["appdate"].'<br/><br/>
    <label> Appointment Time : </label>'.$row["apptime"].'<br/><br/>
    <label> Disease : </label>'.$row["disease"].'<br/><br/>
    <label> Allergies : </label>'.$row["allergy"].'<br/><br/>
    <label> Prescription : </label>'.$row["prescription"].'<br/><br/>
    <label> Fees Paid : </label>'.$row["docFees"].'<br/>
    ';
  }
  
  return $output;
}

if(isset($_GET["generate_bill"])){
  require_once("TCPDF/tcpdf.php");
  $obj_pdf = new TCPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
  $obj_pdf -> SetCreator(PDF_CREATOR);
  $obj_pdf -> SetTitle("Generate Bill");
  $obj_pdf -> SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
  $obj_pdf -> SetHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
  $obj_pdf -> SetFooterFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
  $obj_pdf -> SetDefaultMonospacedFont('helvetica');
  $obj_pdf -> SetFooterMargin(PDF_MARGIN_FOOTER);
  $obj_pdf -> SetMargins(PDF_MARGIN_LEFT,'5',PDF_MARGIN_RIGHT);
  $obj_pdf -> SetPrintHeader(false);
  $obj_pdf -> SetPrintFooter(false);
  $obj_pdf -> SetAutoPageBreak(TRUE, 10);
  $obj_pdf -> SetFont('helvetica','',12);
  $obj_pdf -> AddPage();

  $content = '';

  $content .= '
      <br/>
      <h2 align ="center"> Global Hospitals</h2></br>
      <h3 align ="center"> Bill</h3>
  ';
 
  $content .= generate_bill();
  $obj_pdf -> writeHTML($content);
  ob_end_clean();
  $obj_pdf -> Output("bill.pdf",'I');
}

function get_specs(){
  $con=mysqli_connect("localhost","root","","myhmsdb");
  $query=mysqli_query($con,"select username,spec from doctb");
  $docarray = array();
    while($row =mysqli_fetch_assoc($query))
    {
        $docarray[] = $row;
    }
    return json_encode($docarray);
}

?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Patient Dashboard - Global Hospitals</title>
  
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
      max-width: 1200px;
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

    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-card i {
      font-size: 2.5rem;
      color: var(--brand);
      margin-bottom: 0.5rem;
    }

    .stat-card h3 {
      font-size: 1.8rem;
      color: var(--text);
      margin: 0.5rem 0;
      font-weight: 700;
    }

    .stat-card p {
      color: #95a5a6;
      font-size: 0.9rem;
      margin: 0;
    }

    /* Tabs Navigation */
    .nav-tabs-custom {
      border-bottom: none;
      background: white;
      border-radius: 12px;
      padding: 0;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }

    .nav-tabs-custom .nav-link {
      border: none;
      color: var(--text);
      font-weight: 600;
      padding: 1.2rem 1.5rem;
      border-radius: 0;
      transition: all 0.3s ease;
      background: white;
    }

    .nav-tabs-custom .nav-link:hover {
      background: var(--light-bg);
      border-bottom: 3px solid var(--brand);
    }

    .nav-tabs-custom .nav-link.active {
      background: white;
      color: var(--brand);
      border-bottom: 3px solid var(--brand);
    }

    /* Tab Content */
    .tab-content-custom {
      background: white;
      padding: 2rem;
      border-radius: 0 0 12px 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .tab-content-custom h3 {
      font-family: "Space Grotesk", sans-serif;
      margin-bottom: 1.5rem;
      color: var(--text);
    }

    /* Forms */
    .form-group-modern {
      margin-bottom: 1.5rem;
    }

    .form-group-modern label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.75rem;
      color: var(--text);
      font-size: 0.95rem;
    }

    .form-group-modern input,
    .form-group-modern select {
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

    #appointments .form-group-modern.mb-0 {
      margin-bottom: 0;
    }

    #appointments .select-wrap {
      position: relative;
      border: 2px solid #cfd9e3;
      border-radius: 10px;
      background: linear-gradient(180deg, #fbfdff 0%, #f5f9fc 100%);
      transition: border-color 0.2s ease, background 0.2s ease;
      padding: 0.85rem 0 0.85rem 0.85rem;
    }

    #appointments .appt-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 1rem;
      margin-bottom: 1.25rem;
      flex-wrap: wrap;
    }

    #appointments .appt-title {
      margin: 0;
    }

    #appointments .appt-subtitle {
      margin: 0.25rem 0 0;
      color: #5f7285;
      font-size: 0.95rem;
    }

    #appointments .appt-status {
      padding: 0.4rem 0.75rem;
      border: 1px solid #d9e4ee;
      border-radius: 999px;
      background: #f7fbff;
      color: #395268;
      font-size: 0.82rem;
      font-weight: 700;
      white-space: nowrap;
    }

    #appointments .appt-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem;
    }

    #appointments .appt-card {
      border: none;
      border-radius: 0;
      background: transparent;
      padding: 0;
    }

    #appointments .select-wrap {
      position: relative;
      border: 2px solid #cfd9e3;
      border-radius: 8px;
      background: #ffffff;
      transition: border-color 0.2s ease;
      overflow: hidden;
      height: 44px;
    }

    #appointments .select-wrap:focus-within {
      border-color: #0f9d8a;
    }

    #appointments .select-wrap::after {
      content: "▼";
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 0.7rem;
      pointer-events: none;
      z-index: 3;
    }

    #appointments .select-wrap .appt-select {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
      z-index: 2;
      border: none !important;
      background: transparent !important;
      box-shadow: none !important;
    }

    #appointments .select-display {
      position: absolute;
      left: 12px;
      right: 32px;
      top: 0;
      height: 44px;
      display: flex;
      align-items: center;
      color: #1b2733;
      font-size: 1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      pointer-events: none;
      z-index: 1;
    }

    #appointments .select-display.placeholder {
      color: #9ca3af;
    }

    #appointments .appt-actions {
      margin-top: 1.1rem;
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
      align-items: center;
    }

    #appointments .btn-outline-modern {
      border: 1px solid #b9c9d8;
      background: #fff;
      color: #355067;
      border-radius: 8px;
      font-weight: 600;
      padding: 0.82rem 1.1rem;
      cursor: pointer;
    }

    #appointments .btn-outline-modern:hover {
      background: #f3f8fc;
    }

    #appointments .hint-text {
      color: #5f7285;
      font-size: 0.85rem;
      margin-top: 0.5rem;
    }

    #appointments .fee-pill {
      display: inline-block;
      margin-top: 0.4rem;
      padding: 0.25rem 0.55rem;
      border-radius: 999px;
      background: #e9f8f5;
      color: #0b6b60;
      font-size: 0.78rem;
      font-weight: 700;
    }

    #appointments .appt-select.is-placeholder {
      color: #6c757d !important;
      -webkit-text-fill-color: #6c757d !important;
    }

    #appointments .appt-select:focus {
      color: #1b2733 !important;
      background: #ffffff !important;
      border-color: #0f9d8a !important;
      box-shadow: 0 0 0 0.2rem rgba(15, 157, 138, 0.12);
    }

    .form-group-modern input:focus,
    .form-group-modern select:focus {
      outline: none;
      border-color: var(--brand);
      background: #f8fffe;
    }

    /* Buttons */
    .btn-modern {
      padding: 0.85rem 1.5rem;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: "Manrope", sans-serif;
    }

    .btn-primary-modern {
      background: linear-gradient(135deg, var(--brand) 0%, var(--brand-deep) 100%);
      color: white;
    }

    .btn-primary-modern:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(15, 157, 138, 0.3);
    }

    .btn-danger-modern {
      background: var(--danger);
      color: white;
    }

    .btn-danger-modern:hover {
      background: #c0392b;
    }

    .btn-success-modern {
      background: var(--success);
      color: white;
    }

    /* Tables */
    .table-modern {
      border-collapse: collapse;
    }

    .table-modern thead th {
      background: var(--light-bg);
      color: var(--text);
      font-weight: 700;
      border: none;
      padding: 1rem;
    }

    .table-modern tbody td {
      padding: 1rem;
      border: 1px solid var(--border);
      vertical-align: middle;
    }

    .table-modern tbody tr:hover {
      background: var(--light-bg);
    }

    /* Status Badge */
    .badge-active {
      background: var(--success);
      color: white;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    .badge-cancelled {
      background: var(--danger);
      color: white;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .dashboard-header h1 {
        font-size: 1.5rem;
      }

      .stats-grid {
        grid-template-columns: 1fr;
      }

      .tab-content-custom {
        padding: 1rem;
      }

      body {
        padding-top: 60px;
      }

      #appointments .appt-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <i class="fa fa-hospital-o"></i> Global Hospitals
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Dashboard Container -->
  <div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
      <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
      <p>Manage your appointments, medical records, and prescriptions</p>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs-custom" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#dashboard">
          <i class="fa fa-tachometer"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#appointments">
          <i class="fa fa-calendar"></i> Book Appointment
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#history">
          <i class="fa fa-history"></i> Appointment History
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#prescriptions">
          <i class="fa fa-file-text"></i> Prescriptions
        </a>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content tab-content-custom">
      <!-- Dashboard Tab -->
      <div id="dashboard" class="tab-pane fade show active">
        <h3>Dashboard Overview</h3>
        <div class="stats-grid">
          <div class="stat-card">
            <i class="fa fa-calendar-check-o"></i>
            <h3>Appointments</h3>
            <p>Manage Your Visits</p>
          </div>
          <div class="stat-card">
            <i class="fa fa-file-text-o"></i>
            <h3>Prescriptions</h3>
            <p>View Medical Records</p>
          </div>
          <div class="stat-card">
            <i class="fa fa-clock-o"></i>
            <h3>History</h3>
            <p>Track Your Visits</p>
          </div>
          <div class="stat-card">
            <i class="fa fa-user-md"></i>
            <h3>Doctors</h3>
            <p>Expert Specialists</p>
          </div>
        </div>
      </div>

      <!-- Book Appointment Tab -->
      <div id="appointments" class="tab-pane fade">
        <div class="appt-head">
          <div>
            <h3 class="appt-title">Book New Appointment</h3>
            <p class="appt-subtitle">Pick specialization, doctor, date, and time. We will auto-fill consultation fee.</p>
          </div>
          <span id="apptStatus" class="appt-status">Form Incomplete</span>
        </div>
        <form method="POST" action="admin-panel.php">
          <div class="appt-grid">
            <div class="appt-card">
              <div class="form-group-modern mb-0">
                <label for="spec">Specialization *</label>
                <div class="select-wrap">
                  <select name="spec" class="form-control appt-select" id="spec" required>
                    <option value="" disabled selected>Select Specialization</option>
                    <?php display_specs(); ?>
                  </select>
                  <span id="specDisplay" class="select-display placeholder">Select Specialization</span>
                </div>
              </div>
            </div>
            <div class="appt-card">
              <div class="form-group-modern mb-0">
                <label for="doctor">Doctor *</label>
                <div class="select-wrap">
                  <select name="doctor" class="form-control appt-select" id="doctor" required disabled>
                    <option value="" disabled selected>Select Doctor</option>
                    <?php display_docs(); ?>
                  </select>
                  <span id="doctorDisplay" class="select-display placeholder">Select Doctor</span>
                </div>
              </div>
            </div>
            <div class="appt-card">
              <div class="form-group-modern mb-0">
                <label for="appdate">Appointment Date *</label>
                <input type="date" name="appdate" id="appdate" class="form-control" required>
              </div>
            </div>
            <div class="appt-card">
              <div class="form-group-modern mb-0">
                <label for="apptime">Appointment Time *</label>
                <div class="select-wrap">
                  <select name="apptime" class="form-control appt-select" id="apptime" required>
                    <option value="" disabled selected>Select Time</option>
                    <option value="08:00:00">8:00 AM</option>
                    <option value="10:00:00">10:00 AM</option>
                    <option value="12:00:00">12:00 PM</option>
                    <option value="14:00:00">2:00 PM</option>
                    <option value="16:00:00">4:00 PM</option>
                  </select>
                  <span id="timeDisplay" class="select-display placeholder">Select Time</span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group-modern">
            <label for="docFees">Consultancy Fees</label>
            <input type="text" name="docFees" id="docFees" class="form-control" readonly placeholder="Fees will be filled automatically">
            <span id="feePill" class="fee-pill" style="display:none;">Fee Selected</span>
            <p class="hint-text">Tip: morning slots are usually less crowded.</p>
          </div>

          <div class="appt-actions">
            <button type="submit" name="app-submit" id="bookBtn" class="btn btn-modern btn-primary-modern" disabled>
              <i class="fa fa-check"></i> Book Appointment
            </button>
            <button type="button" id="clearBtn" class="btn-outline-modern">Clear Form</button>
          </div>
        </form>

        <script>
          const specSelect = document.getElementById('spec');
          const doctorSelect = document.getElementById('doctor');
          const timeSelect = document.getElementById('apptime');
          const feeInput = document.getElementById('docFees');
          const dateInput = document.getElementById('appdate');
          const specDisplay = document.getElementById('specDisplay');
          const doctorDisplay = document.getElementById('doctorDisplay');
          const timeDisplay = document.getElementById('timeDisplay');
          const feePill = document.getElementById('feePill');
          const bookBtn = document.getElementById('bookBtn');
          const apptStatus = document.getElementById('apptStatus');
          const clearBtn = document.getElementById('clearBtn');

          const today = new Date().toISOString().split('T')[0];
          dateInput.setAttribute('min', today);

          function updateFormState() {
            const ok = !!(specSelect.value && doctorSelect.value && dateInput.value && timeSelect.value);
            bookBtn.disabled = !ok;
            apptStatus.textContent = ok ? 'Ready to Submit' : 'Form Incomplete';
            apptStatus.style.background = ok ? '#e9f8f5' : '#f7fbff';
            apptStatus.style.color = ok ? '#0b6b60' : '#395268';
            apptStatus.style.borderColor = ok ? '#bde9df' : '#d9e4ee';
          }

          function syncSelectVisualState(selectEl, displayEl, placeholderText) {
            const hasValue = !!(selectEl && selectEl.value);
            if (!selectEl) return;
            const selectedLabel = (selectEl.options && selectEl.selectedIndex >= 0)
              ? selectEl.options[selectEl.selectedIndex].textContent
              : placeholderText;
            if (hasValue) {
              selectEl.classList.remove('is-placeholder');
              selectEl.style.color = '#1b2733';
              selectEl.style.webkitTextFillColor = '#1b2733';
              if (displayEl) {
                displayEl.textContent = selectedLabel;
                displayEl.classList.remove('placeholder');
              }
            } else {
              selectEl.classList.add('is-placeholder');
              selectEl.style.color = '#6c757d';
              selectEl.style.webkitTextFillColor = '#6c757d';
              if (displayEl) {
                displayEl.textContent = placeholderText;
                displayEl.classList.add('placeholder');
              }
            }
          }

          const allDoctorOptions = Array.from(doctorSelect.options)
            .slice(1)
            .map((opt) => ({
              value: opt.value,
              label: opt.textContent,
              spec: (opt.getAttribute('data-spec') || '').trim().toLowerCase(),
              fee: opt.getAttribute('data-value') || ''
            }));

          function resetDoctorSelect() {
            doctorSelect.innerHTML = '<option value="" disabled selected>Select Doctor</option>';
            doctorSelect.disabled = true;
            feeInput.value = '';
            feePill.style.display = 'none';
            syncSelectVisualState(doctorSelect, doctorDisplay, 'Select Doctor');
            updateFormState();
          }

          function fillDoctorsForSpec(specValue) {
            const normalizedSpec = (specValue || '').trim().toLowerCase();
            doctorSelect.innerHTML = '<option value="" disabled selected>Select Doctor</option>';

            const filtered = allDoctorOptions.filter((d) => d.spec === normalizedSpec);
            filtered.forEach((d) => {
              const opt = document.createElement('option');
              opt.value = d.value;
              opt.textContent = d.label;
              opt.setAttribute('data-spec', d.spec);
              opt.setAttribute('data-value', d.fee);
              doctorSelect.appendChild(opt);
            });

            doctorSelect.disabled = filtered.length === 0;
            feeInput.value = '';
            feePill.style.display = 'none';
            syncSelectVisualState(doctorSelect, doctorDisplay, 'Select Doctor');
            updateFormState();
          }

          document.getElementById('spec').onchange = function() {
            syncSelectVisualState(this, specDisplay, 'Select Specialization');
            fillDoctorsForSpec(this.value);
            updateFormState();
          };

          document.getElementById('doctor').onchange = function() {
            var selectedOption = this.options[this.selectedIndex];
            var selection = selectedOption ? selectedOption.getAttribute('data-value') : '';
            document.getElementById('docFees').value = selection || '';
            if (selection) {
              feePill.textContent = 'Fee: Rs. ' + selection;
              feePill.style.display = 'inline-block';
            } else {
              feePill.style.display = 'none';
            }
            syncSelectVisualState(this, doctorDisplay, 'Select Doctor');
            updateFormState();
          };

          timeSelect.onchange = function() {
            syncSelectVisualState(this, timeDisplay, 'Select Time');
            updateFormState();
          };

          dateInput.onchange = function() {
            updateFormState();
          };

          clearBtn.onclick = function() {
            specSelect.selectedIndex = 0;
            syncSelectVisualState(specSelect, specDisplay, 'Select Specialization');
            resetDoctorSelect();
            timeSelect.selectedIndex = 0;
            syncSelectVisualState(timeSelect, timeDisplay, 'Select Time');
            dateInput.value = '';
            feeInput.value = '';
            feePill.style.display = 'none';
            updateFormState();
          };

          resetDoctorSelect();
          syncSelectVisualState(specSelect, specDisplay, 'Select Specialization');
          syncSelectVisualState(timeSelect, timeDisplay, 'Select Time');
          updateFormState();
        </script>
      </div>

      <!-- Appointment History Tab -->
      <div id="history" class="tab-pane fade">
        <h3>Appointment History</h3>
        <div class="table-responsive">
          <table class="table table-modern">
            <thead>
              <tr>
                <th>Doctor</th>
                <th>Consultancy Fees</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $con=mysqli_connect("localhost","root","","myhmsdb");
                $query = "select ID,doctor,docFees,appdate,apptime,userStatus,doctorStatus from appointmenttb where fname ='$fname' and lname='$lname';";
                $result = mysqli_query($con,$query);
                while ($row = mysqli_fetch_array($result)){
              ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                  <td>Rs. <?php echo htmlspecialchars($row['docFees']); ?></td>
                  <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                  <td><?php echo htmlspecialchars($row['apptime']); ?></td>
                  <td>
                    <?php 
                      if(($row['userStatus']==1) && ($row['doctorStatus']==1)) {
                        echo '<span class="badge-active">Active</span>';
                      } elseif(($row['userStatus']==0) && ($row['doctorStatus']==1)) {
                        echo '<span class="badge-cancelled">Cancelled by You</span>';
                      } elseif(($row['userStatus']==1) && ($row['doctorStatus']==0)) {
                        echo '<span class="badge-cancelled">Cancelled by Doctor</span>';
                      }
                    ?>
                  </td>
                  <td>
                    <?php 
                      if(($row['userStatus']==1) && ($row['doctorStatus']==1)) {
                    ?>
                      <a href="admin-panel.php?ID=<?php echo $row['ID']; ?>&cancel=update" 
                         onclick="return confirm('Are you sure you want to cancel this appointment?')"
                         title="Cancel Appointment">
                        <button class="btn btn-modern btn-danger-modern btn-sm">Cancel</button>
                      </a>
                    <?php 
                      } else {
                        echo '<span class="badge-cancelled">Cancelled</span>';
                      }
                    ?>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Prescriptions Tab -->
      <div id="prescriptions" class="tab-pane fade">
        <h3>My Prescriptions & Bills</h3>
        <div class="table-responsive">
          <table class="table table-modern">
            <thead>
              <tr>
                <th>Doctor</th>
                <th>Appointment ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Disease</th>
                <th>Allergies</th>
                <th>Prescription</th>
                <th>Bill</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $con=mysqli_connect("localhost","root","","myhmsdb");
                $query = "select doctor,ID,appdate,apptime,disease,allergy,prescription from prestb where pid='$pid';";
                $result = mysqli_query($con,$query);
                while ($row = mysqli_fetch_array($result)){
              ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['doctor']); ?></td>
                  <td><?php echo htmlspecialchars($row['ID']); ?></td>
                  <td><?php echo htmlspecialchars($row['appdate']); ?></td>
                  <td><?php echo htmlspecialchars($row['apptime']); ?></td>
                  <td><?php echo htmlspecialchars($row['disease']); ?></td>
                  <td><?php echo htmlspecialchars($row['allergy']); ?></td>
                  <td><?php echo htmlspecialchars($row['prescription']); ?></td>
                  <td>
                    <form method="get" style="display: inline;">
                      <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                      <input type="submit" name="generate_bill" class="btn btn-modern btn-success-modern btn-sm" value="Generate PDF">
                    </form>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>


