<!DOCTYPE html>
<?php
include('func1.php');
$pid='';
$ID='';
$appdate='';
$apptime='';
$fname = '';
$lname= '';
if(!isset($_SESSION['dname']) || trim((string)$_SESSION['dname']) === ''){
  echo "<script>window.location.href='index.php';</script>";
  exit();
}
$doctor = $_SESSION['dname'];
if(isset($_GET['pid']) && isset($_GET['ID']) && ($_GET['appdate']) && isset($_GET['apptime']) && isset($_GET['fname']) && isset($_GET['lname'])) {
$pid = $_GET['pid'];
  $ID = $_GET['ID'];
  $fname = $_GET['fname'];
  $lname = $_GET['lname'];
  $appdate = $_GET['appdate'];
  $apptime = $_GET['apptime'];
}



if(isset($_POST['prescribe']) && isset($_POST['pid']) && isset($_POST['ID']) && isset($_POST['appdate']) && isset($_POST['apptime']) && isset($_POST['lname']) && isset($_POST['fname'])){
  $appdate = $_POST['appdate'];
  $apptime = $_POST['apptime'];
  $disease = trim($_POST['disease']);
  $allergy = trim($_POST['allergy']);
  $fname = trim($_POST['fname']);
  $lname = trim($_POST['lname']);
  $pid = (int)$_POST['pid'];
  $ID = (int)$_POST['ID'];
  $prescription = trim($_POST['prescription']);

  $existsStmt = mysqli_prepare($con, "SELECT 1 FROM prestb WHERE ID=? LIMIT 1");
  $exists = false;
  if($existsStmt){
    mysqli_stmt_bind_param($existsStmt, "i", $ID);
    mysqli_stmt_execute($existsStmt);
    mysqli_stmt_store_result($existsStmt);
    $exists = mysqli_stmt_num_rows($existsStmt) > 0;
    mysqli_stmt_close($existsStmt);
  }

  if($exists){
    echo "<script>alert('Prescription already exists for this appointment.');</script>";
  } else {
    $stmt = mysqli_prepare($con, "INSERT INTO prestb(doctor,pid,ID,fname,lname,appdate,apptime,disease,allergy,prescription) VALUES (?,?,?,?,?,?,?,?,?,?)");
    if($stmt){
      mysqli_stmt_bind_param($stmt, "siisssssss", $doctor, $pid, $ID, $fname, $lname, $appdate, $apptime, $disease, $allergy, $prescription);
      $query = mysqli_stmt_execute($stmt);
      mysqli_stmt_close($stmt);
      if($query)
      {
        echo "<script>alert('Prescribed successfully!'); window.location.href='doctor-panel.php';</script>";
      }
      else{
        echo "<script>alert('Unable to process your request. Try again!');</script>";
      }
    }
  }
  // else{
  //   echo "<script>alert('GET is not working!');</script>";
  // }initial
  // enga error?
}

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
  <title>Prescribe - Global Hospitals</title>

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
      color: #fff !important;
    }

    .navbar .nav-link {
      color: #fff !important;
      font-weight: 500;
      margin-left: 1rem;
    }

    .dashboard-container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 2rem;
    }

    .dashboard-header {
      background: #fff;
      border-radius: 12px;
      padding: 2rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      border-left: 5px solid var(--brand);
    }

    .dashboard-header h1 {
      font-family: "Space Grotesk", sans-serif;
      font-size: 1.8rem;
      margin-bottom: 0.4rem;
    }

    .dashboard-header p {
      margin: 0;
      color: #5f7285;
    }

    .prescribe-card {
      background: #fff;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      margin-bottom: 1rem;
    }

    .visit-meta {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.75rem;
      margin-bottom: 1.25rem;
    }

    .meta-pill {
      background: #f4fbfa;
      border: 1px solid #d9ece8;
      border-radius: 8px;
      padding: 0.6rem 0.8rem;
      font-size: 0.92rem;
    }

    .meta-pill strong {
      color: #0b6b60;
      margin-right: 0.35rem;
    }

    .form-group-modern {
      margin-bottom: 1rem;
    }

    .form-group-modern label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.45rem;
    }

    .form-group-modern textarea {
      width: 100%;
      border: 2px solid var(--border);
      border-radius: 10px;
      padding: 0.85rem;
      min-height: 120px;
      resize: vertical;
      font-family: "Manrope", sans-serif;
    }

    .form-group-modern textarea:focus {
      outline: none;
      border-color: var(--brand);
      box-shadow: 0 0 0 0.2rem rgba(15, 157, 138, 0.12);
    }

    .form-actions {
      display: flex;
      gap: 0.75rem;
      flex-wrap: wrap;
      margin-top: 0.5rem;
    }

    .btn-modern {
      border: none;
      border-radius: 8px;
      padding: 0.78rem 1.1rem;
      font-weight: 600;
      cursor: pointer;
    }

    .btn-primary-modern {
      background: var(--brand);
      color: #fff;
    }

    .btn-primary-modern:hover {
      background: var(--brand-deep);
    }

    .btn-outline-modern {
      background: #fff;
      color: #355067;
      border: 1px solid #b9c9d8;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-outline-modern:hover {
      background: #f3f8fc;
      text-decoration: none;
      color: #355067;
    }

    @media (max-width: 768px) {
      .dashboard-container {
        padding: 1rem;
      }

      .visit-meta {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#"><i class="fa fa-hospital-o"></i> Global Hospitals</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="doctor-panel.php"><i class="fa fa-arrow-left"></i> Back to Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="logout1.php"><i class="fa fa-sign-out"></i> Logout</a></li>
      </ul>
    </div>
  </nav>

  <div class="dashboard-container">
    <div class="dashboard-header">
      <h1>Prescribe for Patient</h1>
      <p>Dr. <?php echo htmlspecialchars($doctor); ?>, complete the clinical notes below and submit.</p>
    </div>

    <div class="prescribe-card">
      <div class="visit-meta">
        <div class="meta-pill"><strong>Patient:</strong> <?php echo htmlspecialchars(trim($fname . ' ' . $lname)); ?></div>
        <div class="meta-pill"><strong>Patient ID:</strong> <?php echo (int)$pid; ?></div>
        <div class="meta-pill"><strong>Appointment ID:</strong> <?php echo (int)$ID; ?></div>
        <div class="meta-pill"><strong>Date/Time:</strong> <?php echo htmlspecialchars($appdate . ' ' . $apptime); ?></div>
      </div>

      <form name="prescribeform" method="post" action="prescribe.php">
        <div class="form-group-modern">
          <label for="disease">Disease</label>
          <textarea id="disease" name="disease" placeholder="Enter diagnosis details" required></textarea>
        </div>

        <div class="form-group-modern">
          <label for="allergy">Allergies</label>
          <textarea id="allergy" name="allergy" placeholder="Enter known allergies" required></textarea>
        </div>

        <div class="form-group-modern">
          <label for="prescription">Prescription</label>
          <textarea id="prescription" name="prescription" style="min-height: 170px;" placeholder="Enter medicines, dosage, and instructions" required></textarea>
        </div>

        <input type="hidden" name="fname" value="<?php echo htmlspecialchars($fname); ?>" />
        <input type="hidden" name="lname" value="<?php echo htmlspecialchars($lname); ?>" />
        <input type="hidden" name="appdate" value="<?php echo htmlspecialchars($appdate); ?>" />
        <input type="hidden" name="apptime" value="<?php echo htmlspecialchars($apptime); ?>" />
        <input type="hidden" name="pid" value="<?php echo (int)$pid; ?>" />
        <input type="hidden" name="ID" value="<?php echo (int)$ID; ?>" />

        <div class="form-actions">
          <button type="submit" name="prescribe" class="btn-modern btn-primary-modern"><i class="fa fa-check"></i> Submit Prescription</button>
          <a href="doctor-panel.php" class="btn-modern btn-outline-modern"><i class="fa fa-times"></i> Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>



