<?php
session_start();
$errors = isset($_SESSION['reg_errors']) ? $_SESSION['reg_errors'] : [];
// Clear errors after displaying
unset($_SESSION['reg_errors']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Error</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body { background: #f8f9fa; }
        .error-container { max-width: 500px; margin: 80px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 32px; }
    </style>
</head>
<body>
    <div class="error-container">
        <h3 class="text-danger">Registration Error</h3>
        <?php if (!empty($errors)): ?>
            <ul class="list-group mb-3">
                <?php foreach ($errors as $error): ?>
                    <li class="list-group-item list-group-item-danger"><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning">An unknown error occurred.</div>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary">Back to Registration</a>
    </div>
</body>
</html> 