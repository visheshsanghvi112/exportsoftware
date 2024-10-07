<?php
session_start();
include('config.php'); // Include your database configuration

// Handle backup request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['backup_now'])) {
        // Ensure backup script is executed
        $backupScript = 'backup_script.php';
        if (file_exists($backupScript)) {
            exec('php ' . escapeshellarg($backupScript), $output, $return_var);
            $backupStatus = ($return_var === 0) ? "Backup created successfully." : "Backup failed.";
        } else {
            $backupStatus = "Backup script not found.";
        }
    }
}

// List backup files
$backupDir = 'C:\\Users\\gener\\OneDrive\\Desktop\\backups\\';
$backupFiles = [];

if (is_dir($backupDir)) {
    $backupFiles = array_diff(scandir($backupDir), array('.', '..'));
} else {
    $backupStatus = "Backup directory does not exist.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup and Security Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: auto;
        }
        .status-message {
            margin-top: 20px;
        }
        .backup-list {
            margin-top: 20px;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
        }
        .list-group-item a {
            text-decoration: none;
            color: white;
        }
        .backup-actions {
            margin-bottom: 20px;
        }
        .btn-backup {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-backup:hover {
            background-color: #0056b3;
        }
        .btn-download {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .btn-download:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Backup and Security Management</h1>

        <div class="backup-actions mb-4">
            <form method="post" class="form-inline">
                <button type="submit" name="backup_now" class="btn btn-backup">Backup Now</button>
            </form>
        </div>

        <?php if (isset($backupStatus)): ?>
            <div class="status-message alert alert-info">
                <?php echo htmlspecialchars($backupStatus); ?>
            </div>
        <?php endif; ?>

        <div class="backup-list">
            <h3>Backup Files</h3>
            <ul class="list-group">
                <?php if (!empty($backupFiles)): ?>
                    <?php foreach ($backupFiles as $file): ?>
                        <li class="list-group-item">
                            <span><?php echo htmlspecialchars($file); ?></span>
                            <a href="<?php echo htmlspecialchars($backupDir . $file); ?>" download class="btn btn-download"><i class="fas fa-download"></i> Download</a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item">No backup files found.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('form').on('submit', function() {
            return confirm('Are you sure you want to create a backup?');
        });
    });
    </script>
</body>
</html>
