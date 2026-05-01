<!DOCTYPE html>
 <?php #include("func.php");?>
<html>
<head>
	<title>User Messages</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
<?php
include("newfunc.php");
include_once('include/validation_helper.php');
if(isset($_POST['mes_search_submit']))
{
	$contact = trim($_POST['mes_contact'] ?? '');
  if(!hms_is_valid_contact($contact)){
    echo "<script> alert('Please enter a valid 10-digit contact number.');
          window.location.href = 'admin-panel1.php#list-doc';</script>";
    exit;
  }
  $stmt = mysqli_prepare($con, "SELECT * FROM contact WHERE contact=? LIMIT 1");
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
  if($row['name']=="" & $row['email']=="" & $row['contact']=="" & $row['message']==""){
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
        <th scope='col'>User Name</th>
        <th scope='col'>Email</th>
        <th scope='col'>Contact</th>
        <th scope='col'>Message</th>
      </tr>
    </thead>
    <tbody>";
  
    
          $name = $row['name'];
          $email = $row['email'];
          $contact = $row['contact'];
          $message = $row['message'];
          echo "<tr>
            <td>".htmlspecialchars($name)."</td>
            <td>".htmlspecialchars($email)."</td>
            <td>".htmlspecialchars($contact)."</td>
            <td>".htmlspecialchars($message)."</td>
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