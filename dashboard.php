<?php
session_start();
// Set timezone to Manila/Philippines
date_default_timezone_set('Asia/Manila');
if (!isset($_SESSION['user_id'])) {
    header("Location: public/index.php");
    exit();
}

include 'database/db_connect.php';
$user_id = $_SESSION['user_id'];

// Get user data
$user_stmt = $conn->prepare("SELECT first_name, last_name, ojt_hours, hours_completed, start_date FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

// Calculate the total logged hours from attendance records (only completed logs)
$attendanceSumStmt = $conn->prepare("SELECT SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))) AS total_seconds FROM attendance WHERE user_id = ? AND time_out IS NOT NULL");
$attendanceSumStmt->bind_param("i", $user_id);
$attendanceSumStmt->execute();
$sumResult = $attendanceSumStmt->get_result();
$sumRow = $sumResult->fetch_assoc();
$totalLoggedSeconds = $sumRow['total_seconds'] ? $sumRow['total_seconds'] : 0;
$totalLoggedHours = $totalLoggedSeconds / 3600;

// Recalculate progress and remaining hours based on the logged hours
$remaining_hours = $user['ojt_hours'] - $totalLoggedHours;
if ($remaining_hours < 0) {
    $remaining_hours = 0;  // avoid negative display
}

if ($totalLoggedHours >= $user['ojt_hours']) {
    $progress_percent = 100;
    $progress_message = "Completed";
    $buttons_disabled = true;
} else {
    $progress_percent = ($totalLoggedHours / $user['ojt_hours']) * 100;
    $progress_message = number_format($progress_percent, 1) . "% Completed";
    $buttons_disabled = false;
}

$days_remaining = ceil($remaining_hours / 8);

// Function to format a duration in h, m, s
function formatDuration($seconds) {
    $hours = floor($seconds / 3600);
    $remainder = $seconds % 3600;
    $minutes = floor($remainder / 60);
    $secs = $remainder % 60;
    $parts = [];
    if ($hours > 0) {
        $parts[] = $hours . 'h';
    }
    if ($minutes > 0) {
        $parts[] = $minutes . 'm';
    }
    // Always show seconds if nothing else, or if seconds are non-zero
    if ($secs > 0 || empty($parts)) {
        $parts[] = $secs . 's';
    }
    return implode(' ', $parts);
}

// Check if there's any pending attendance log
$pendingStmt = $conn->prepare("SELECT id FROM attendance WHERE user_id = ? AND status = 'Pending' LIMIT 1");
$pendingStmt->bind_param("i", $user_id);
$pendingStmt->execute();
$pendingResult = $pendingStmt->get_result();
$hasPending = $pendingResult->num_rows > 0;

// Get attendance records to display, sorted by created_at
$attendance_stmt = $conn->prepare("
    SELECT date, time_in, time_out, status 
    FROM attendance 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$attendance_stmt->bind_param("i", $user_id);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
// Store all logs in an array
$logs = $attendance_result->fetch_all(MYSQLI_ASSOC);
// Show only the 5 most recent logs on the dashboard
$recentLogs = array_slice($logs, 0, 5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OJT Portal - Dashboard</title>
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        /* Sidebar Styles */
        .sidebar {
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            height: 100vh;
            position: fixed;
            width: 250px;
            z-index: 1030;
        }
        .sidebar-brand {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem 1rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .stat-card {
            background: white;
            height: 100%;
        }
        .stat-card .card-body {
            padding: 1.5rem;
        }
        .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0;
            color: #2c3e50;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 500;
        }
        /* Enhanced Stats Card Styles */
        .stat-card .rounded-circle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-card .bi {
            font-size: 1.2rem;
        }
        /* Progress Bar */
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }
        .progress-bar {
            border-radius: 4px;
        }
        /* Time Tracking Buttons */
        .time-btn {
            height: 48px;
            font-size: 1rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            width: 100%;
        }
        .time-btn .bi {
            font-size: 1.1rem;
        }
        /* Clock Display */
        .clock-display {
            background: white;
            border-radius: 10px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        #clock {
            font-family: 'SF Mono', SFMono-Regular, ui-monospace, monospace;
            font-size: 1.5rem;
            color: #2c3e50;
            margin: 0;
        }
        /* Table Styles */
        .table > :not(caption) > * > * {
            padding: 1rem;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: none;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        /* Badges */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 500;
        }
        /* Navigation */
        .nav-link {
            color: #6c757d;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin: 0.25rem 1rem;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }
        /* Card Header Icons */
        .card-title .bi {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }
        /* Progress Stats Icons */
        .progress-stats .bi {
            font-size: 0.875rem;
        }
        /* Logout Button */
        .logout-btn {
            margin: 1rem;
        }
        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .modal-header {
            border-bottom: 1px solid #e9ecef;
            padding: 1.5rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
        }
        .modal-xl {
            max-width: 1200px;
        }
        
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        /* Enhanced Table Styles */
        .table-container {
            margin: 1rem 0;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 0 0 1px rgba(0,0,0,0.05);
        }
        
        .dataTables_wrapper .row {
            margin: 0.5rem 0;
            align-items: center;
        }
        
        .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            margin-left: 0.5rem;
        }
        
        .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            margin: 0 0.5rem;
        }
        
        /* Enhanced Status Badge Styles */
        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 600;
            text-transform: uppercase;
            border-radius: 1rem;
            display: inline-block;
            min-width: 90px;
            text-align: center;
        }
        
        .status-completed {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
        }
        
        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        
        /* Enhanced Button Styles */
        .dt-buttons .btn {
            margin-right: 0.5rem;
        }
        
        .view-logs-btn {
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .view-logs-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        /* Responsive Enhancements */
        @media (max-width: 768px) {
            .modal-xl {
                margin: 0.5rem;
            }
            
            .dataTables_wrapper .row {
                flex-direction: column;
                gap: 1rem;
            }
            
            .dataTables_length, .dataTables_filter {
                text-align: left;
                width: 100%;
            }
            
            .dt-buttons {
                margin-bottom: 1rem;
            }
        }
        /* Responsive Design */
        @media (max-width: 992px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand text-center">
            <i class="bi bi-person-workspace fs-2 text-primary mb-2"></i>
            <h5 class="mb-0">OJT Portal</h5>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link active" href="#">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </nav>
        <button type="button" class="btn btn-outline-danger logout-btn position-absolute bottom-0 start-0 end-0 mx-3 mb-3" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right"></i> Sign Out
        </button>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Welcome, <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>!</h4>
                <p class="text-muted mb-0">Track your OJT progress and attendance</p>
            </div>
            <div class="clock-display d-flex align-items-center">
                <i class="bi bi-clock me-4 text-primary fs-2"></i>
                <div id="clock"></div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3" style="width: 70px; height: 70px">
                            <i class="bi bi-clock-history text-primary fs-2"></i>
                        </div>
                        <div class="text-end">
                            <p class="stat-label mb-0">Total Hours Required</p>
                            <h3 class="stat-value fs-4"><?php echo $user['ojt_hours']; ?> hrs</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3" style="width: 70px; height: 70px">
                            <i class="bi bi-check2-circle text-success fs-2"></i>
                        </div>
                        <div class="text-end">
                            <p class="stat-label mb-0">Hours Completed</p>
                            <h3 class="stat-value fs-4"><?php echo number_format($totalLoggedHours, 2); ?> hrs</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-2 me-3" style="width: 70px; height: 70px">
                            <i class="bi bi-hourglass-split text-warning fs-2"></i>
                        </div>
                        <div class="text-end">
                            <p class="stat-label mb-0">Remaining Hours</p>
                            <h3 class="stat-value fs-4"><?php echo number_format($remaining_hours, 2); ?> hrs</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card stat-card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="rounded-circle bg-info bg-opacity-10 p-2 me-3" style="width: 70px; height: 70px">
                            <i class="bi bi-calendar-event text-info fs-2"></i>
                        </div>
                        <div class="text-end">
                            <p class="stat-label mb-0">Days Remaining</p>
                            <h3 class="stat-value fs-4"><?php echo $days_remaining; ?> days</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Progress and Time Tracking -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 d-flex align-items-center justify-content-center" style="height: 40px; width: 40px">
                                <i class="bi bi-graph-up text-primary"></i>
                            </div>
                            <h5 class="card-title mb-0">Progress Overview</h5>
                        </div>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" 
                                style="width: <?php echo $progress_percent; ?>%" 
                                aria-valuenow="<?php echo $progress_percent; ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between text-muted small">
                            <span>
                                <i class="bi bi-pie-chart-fill me-1"></i>
                                <?php echo $progress_message; ?>
                            </span>
                            <span>
                                <i class="bi bi-flag-fill me-1"></i>
                                <?php echo $user['ojt_hours']; ?> hours total
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center bg-opacity-10 p-2 me-3" style="height: 40px; width: 40px">
                                <i class="bi bi-stopwatch text-primary"></i>
                            </div>
                            <h5 class="card-title mb-0">Time Tracking</h5>
                        </div>
                        <div class="d-flex gap-3">
                            <button class="btn btn-primary time-btn d-flex align-items-center justify-content-center" onclick="timeIn()" <?php if($hasPending || $buttons_disabled) echo 'disabled'; ?>>
                                <i class="bi bi-box-arrow-in-right me-2"></i> Time In
                            </button>
                            <button class="btn btn-danger time-btn d-flex align-items-center justify-content-center" onclick="timeOut()" <?php if($buttons_disabled) echo 'disabled'; ?>>
                                <i class="bi bi-box-arrow-left me-2"></i> Time Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Logs -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 d-flex align-items-center justify-content-center" style="height: 40px; width: 40px">
                            <i class="bi bi-journal-text text-primary"></i>
                        </div>
                        <h5 class="card-title mb-0">Recent Time Logs</h5>
                    </div>
                    <button type="button" class="btn btn-outline-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#viewAllLogsModal">
                        <i class="bi bi-eye me-2"></i>
                        All Logs
                    </button>
                </div>
                <div class="table-responsive" style="max-height: 300px">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Total Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recentLogs as $row): ?>
                                <tr>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo date("h:i A", strtotime($row['time_in'])); ?></td>
                                    <td>
                                        <?php echo $row['time_out'] ? date("h:i A", strtotime($row['time_out'])) : '--'; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($row['time_out']) {
                                            $diff = strtotime($row['time_out']) - strtotime($row['time_in']);
                                            echo formatDuration($diff);
                                        } else {
                                            echo '--';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <span style="width: 80px;" class="badge bg-<?php echo $row['status'] === 'Completed' ? 'success' : 'warning'; ?> rounded-pill">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View All Logs Modal -->
    <div class="modal fade" id="viewAllLogsModal" tabindex="-1" aria-labelledby="viewAllLogsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="viewAllLogsModalLabel">
                            <i class="bi bi-clock-history me-2"></i>
                            Time Logs History
                        </h5>
                        <p class="text-muted mb-0 mt-1">View and manage all your attendance records</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-container">
                        <table id="logsTable" class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Total Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($logs as $row): ?>
                                    <tr>
                                        <td>
                                            <i class="bi bi-calendar-date text-primary me-2"></i>
                                            <?php echo $row['date']; ?>
                                        </td>
                                        <td>
                                            <i class="bi bi-box-arrow-in-right text-success me-2"></i>
                                            <?php echo date("h:i A", strtotime($row['time_in'])); ?>
                                        </td>
                                        <td>
                                            <?php if($row['time_out']): ?>
                                                <i class="bi bi-box-arrow-left text-danger me-2"></i>
                                                <?php echo date("h:i A", strtotime($row['time_out'])); ?>
                                            <?php else: ?>
                                                <span class="text-muted">--</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($row['time_out']) {
                                                $diff = strtotime($row['time_out']) - strtotime($row['time_in']);
                                                echo '<i class="bi bi-hourglass-split text-primary me-2"></i>' . formatDuration($diff);
                                            } else {
                                                echo '<span class="text-muted">--</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $row['status'] === 'Completed' ? 'status-completed' : 'status-pending'; ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="bi bi-box-arrow-right text-danger me-2"></i>
                        Sign Out
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to sign out of your account?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="actions/logout.php" class="btn btn-danger">Sign Out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update clock every second
        function updateClock() {
            const options = { 
                timeZone: 'Asia/Manila',
                hour12: true,
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', options);
            document.getElementById('clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Time tracking functions
        function timeIn() {
            fetch('actions/time_in.php')
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
        }

        function timeOut() {
            fetch('actions/time_out.php')
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                });
        }
    </script>
</body>
</html>
