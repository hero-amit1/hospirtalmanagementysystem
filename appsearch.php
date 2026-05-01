<!DOCTYPE html>
 <?php #include("func.php");?>
<html>
<head>
	<title>Patient Details</title>
  <link rel="shortcut icon" type="image/x-icon" href="images/favicon.png" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">

</head>
<body>
<?php
include("newfunc.php");
include_once('include/validation_helper.php');
if(isset($_POST['app_search_submit']))
{
	$contact = trim($_POST['app_contact'] ?? '');
  if(!hms_is_valid_contact($contact)){
    echo "<script> alert('Please enter a valid 10-digit contact number.');
          window.location.href = 'admin-panel1.php#list-doc';</script>";
    exit;
  }
  $stmt = mysqli_prepare($con, "SELECT * FROM appointmenttb WHERE contact=? LIMIT 1");
  if(!$stmt){
    echo "<script> alert('Unable to process request right now.');
          window.location.href = 'admin-panel1.php#list-doc';</script>";
    exit;
  }
  mysqli_stmt_bind_param($stmt, "s", $contact);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $row=mysqli_fetch_array($result);
  mysqli_stmt_close($stmt);
  if($row['fname']=="" & $row['lname']=="" & $row['email']=="" & $row['contact']=="" & $row['doctor']=="" & $row['docFees']=="" & $row['appdate']=="" & $row['apptime']==""){
    echo "<script> alert('No entries found! Please enter valid details'); 
          window.location.href = 'admin-panel1.php#list-doc';</script>";
  }
  else {
    echo "<div class='container-fluid' style='margin-top:50px;'>
    <div class='card'>
    <div class='card-body' style='background-color:#342ac1;color:#ffffff;'>
  <table class='table table-hover'>
    <thead>
      <tr>
        <th scope='col'>First Name</th>
        <th scope='col'>Last Name</th>
        <th scope='col'>Email</th>
        <th scope='col'>Contact</th>
        <th scope='col'>Doctor Name</th>
        <th scope='col'>Consultancy Fees</th>
        <th scope='col'>Appointment Date</th>
        <th scope='col'>Appointment Time</th>
        <th scope='col'>Appointment Status</th>
      </tr>
    </thead>
    <tbody>";
  
    
          $fname = $row['fname'];
          $lname = $row['lname'];
          $email = $row['email'];
          $contact = $row['contact'];
          $doctor = $row['doctor'];
          $docFees= $row['docFees'];
          $appdate= $row['appdate'];
          $apptime = $row['apptime'];
          if(($row['userStatus']==1) && ($row['doctorStatus']==1))  
                    {
                      $appstatus = "Active";
                    }
                    if(($row['userStatus']==0) && ($row['doctorStatus']==1))  
                    {
                      $appstatus = "Cancelled by You";
                    }

                    if(($row['userStatus']==1) && ($row['doctorStatus']==0))  
                    {
                      $appstatus = "Cancelled by Doctor";
                    }
          echo "<tr>
            <td>".htmlspecialchars($fname)."</td>
            <td>".htmlspecialchars($lname)."</td>
            <td>".htmlspecialchars($email)."</td>
            <td>".htmlspecialchars($contact)."</td>
            <td>".htmlspecialchars($doctor)."</td>
            <td>".htmlspecialchars($docFees)."</td>
            <td>".htmlspecialchars($appdate)."</td>
            <td>".htmlspecialchars($apptime)."</td>
            <td>".htmlspecialchars($appstatus)."</td>
          </tr>";
    echo "</tbody></table><center><a href='admin-panel1.php' class='btn btn-light'>Back to your Dashboard</a></div></center></div></div></div>";
  }
  }
	
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script> 
</body>
</html>