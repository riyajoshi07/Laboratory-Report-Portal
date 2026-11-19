
<?php
require_once 'includes/functions.php';
require_login();

$role = user()['role'];

// Admin dashboard statistics
$stats = [];

if ($role === 'admin') {
    $stats = [
        'Total Tests' => db()->query("SELECT COUNT(*) FROM laboratory_tests")->fetchColumn(),
        'Total Patients' => db()->query("SELECT COUNT(*) FROM patients")->fetchColumn(),
        'Total Doctors' => db()->query("SELECT COUNT(*) FROM doctors")->fetchColumn(),
        'Total Technicians' => db()->query("SELECT COUNT(*) FROM technicians")->fetchColumn(),
        'Pending Requests' => db()->query("SELECT COUNT(*) FROM test_requests WHERE status='Requested'")->fetchColumn(),
        'Completed Tests' => db()->query("SELECT COUNT(*) FROM test_requests WHERE status='Completed'")->fetchColumn()
    ];
}

header_html('Dashboard');
?>

<h1>Dashboard</h1>
<p class="muted">Welcome to your Laboratory Report Management System.</p>

<?php if ($role === 'admin'): ?>

<h2>Overview</h2>

<div class="stats-grid">
    <?php foreach ($stats as $label => $value): ?>
        <div class="stat-card">
            <h3><?= e($value) ?></h3>
            <p><?= e($label) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<h2>Management Modules</h2>

<div class="grid">
    <a class="tile" href="staff.php">
        <h2>Manage Staff</h2>
        <p>Create doctor and technician accounts.</p>
    </a>

    <a class="tile" href="categories.php">
        <h2>Test Categories</h2>
        <p>Add and view test categories.</p>
    </a>

    <a class="tile" href="tests.php">
        <h2>Laboratory Tests</h2>
        <p>Maintain the test catalogue.</p>
    </a>

    <a class="tile" href="reports.php">
        <h2>Generate Reports</h2>
        <p>View request and report summary.</p>
    </a>
</div>

<?php elseif ($role === 'doctor'): ?>

<div class="grid">
    <a class="tile" href="request_test.php">
        <h2>Request a Test</h2>
        <p>Submit a lab test request for a patient.</p>
    </a>
    <a class="tile" href="reports.php">
        <h2>View Reports</h2>
        <p>Review patient test results.</p>
    </a>
</div>

<?php elseif ($role === 'technician'): ?>

<div class="grid">
    <a class="tile" href="worklist.php">
        <h2>Worklist</h2>
        <p>Update test status and upload reports.</p>
    </a>
</div>

<?php else: ?>

<div class="grid">
    <a class="tile" href="reports.php">
        <h2>My Reports</h2>
        <p>View and download your reports.</p>
    </a>
</div>

<?php endif; ?>

<?php footer_html(); ?>

