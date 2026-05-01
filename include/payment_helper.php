<?php
include_once('validation_helper.php');

function hms_handle_payment_update(mysqli $con): void
{
    if (!isset($_POST['update_data'])) {
        return;
    }

    $contact = trim($_POST['contact'] ?? '');
    $status = trim($_POST['status'] ?? '');

    if (!hms_is_valid_contact($contact) || !hms_is_non_empty($status)) {
        return;
    }

    $stmt = mysqli_prepare($con, "UPDATE appointmenttb SET payment=? WHERE contact=?");
    if (!$stmt) {
        return;
    }

    mysqli_stmt_bind_param($stmt, "ss", $status, $contact);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        header("Location:updated.php");
        exit();
    }
}
