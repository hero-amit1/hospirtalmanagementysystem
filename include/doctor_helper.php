<?php
include_once('validation_helper.php');

function hms_add_doctor_from_post(mysqli $con, string $redirect = "adddoc.php"): void
{
    if (!isset($_POST['doc_sub'])) {
        return;
    }

    $username = trim($_POST['username'] ?? $_POST['doctor'] ?? $_POST['name'] ?? '');
    $rawPassword = trim($_POST['dpassword'] ?? 'temp123');
    $email = trim($_POST['demail'] ?? (preg_replace('/\s+/', '', strtolower($username)) . '@example.com'));
    $spec = trim($_POST['spec'] ?? $_POST['special'] ?? 'General');
    $docFees = (isset($_POST['docFees']) && is_numeric($_POST['docFees'])) ? (int) $_POST['docFees'] : 500;

    if (!hms_is_non_empty($username) || !hms_is_valid_email($email)) {
        return;
    }

    $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare($con, "INSERT INTO doctb(username,password,email,spec,docFees) VALUES(?,?,?,?,?)");
    if (!$stmt) {
        return;
    }

    mysqli_stmt_bind_param($stmt, "ssssi", $username, $hashedPassword, $email, $spec, $docFees);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        header("Location:$redirect");
        exit();
    }
}
