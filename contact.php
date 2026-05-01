<?php 
include_once('include/config.php');
include_once('include/validation_helper.php');
if(isset($_POST['btnSubmit']))
{
	$name = trim($_POST['txtName'] ?? '');
	$email = trim($_POST['txtEmail'] ?? '');
	$contact = trim($_POST['txtPhone'] ?? '');
	$message = trim($_POST['txtMsg'] ?? '');

	$result = false;
	if(hms_is_non_empty($name) && hms_is_valid_email($email) && hms_is_valid_contact($contact) && hms_is_non_empty($message)){
		$stmt = mysqli_prepare($con, "insert into contact(name,email,contact,message) values(?,?,?,?)");
		if($stmt){
			mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $contact, $message);
			$result = mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
	}
	
	if($result)
    {
    	echo '<script type="text/javascript">'; 
		echo 'alert("Message sent successfully!");'; 
		echo 'window.location.href = "contact.html";';
		echo '</script>';
    }
}