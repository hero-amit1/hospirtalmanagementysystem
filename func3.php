<?php
session_start();
include_once('include/config.php');
include_once('include/payment_helper.php');
include_once('include/doctor_helper.php');
if(isset($_POST['adsub'])){
	$username = trim($_POST['username1'] ?? '');
	$password = $_POST['password2'] ?? '';
	$stmt = mysqli_prepare($con, "SELECT username, password FROM admintb WHERE username=? LIMIT 1");
	if($stmt){
		mysqli_stmt_bind_param($stmt, "s", $username);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt) === 1){
			mysqli_stmt_bind_result($stmt, $dbUsername, $storedPassword);
			mysqli_stmt_fetch($stmt);
			$isValidPassword = password_verify($password, $storedPassword) || hash_equals($storedPassword, $password);
			if($isValidPassword){
				if(password_get_info($storedPassword)['algo'] === 0){
					$newHash = password_hash($password, PASSWORD_DEFAULT);
					$updateStmt = mysqli_prepare($con, "UPDATE admintb SET password=? WHERE username=?");
					if($updateStmt){
						mysqli_stmt_bind_param($updateStmt, "ss", $newHash, $dbUsername);
						mysqli_stmt_execute($updateStmt);
						mysqli_stmt_close($updateStmt);
					}
				}
				session_regenerate_id(true);
				$_SESSION['username'] = $dbUsername;
				mysqli_stmt_close($stmt);
				header("Location:admin-panel1.php");
				exit();
			}
		}
		mysqli_stmt_close($stmt);
	}
	// header("Location:error2.php");
	echo("<script>alert('Invalid Username or Password. Try Again!');
          window.location.href = 'index.php';</script>");
}
hms_handle_payment_update($con);




function display_docs()
{
	global $con;
	$query="select username from doctb";
	$result=mysqli_query($con,$query);
	while($row=mysqli_fetch_array($result))
	{
		$name=$row['username'];
		# echo'<option value="" disabled selected>Select Doctor</option>';
		echo '<option value="'.$name.'">'.$name.'</option>';
	}
}

hms_add_doctor_from_post($con);
