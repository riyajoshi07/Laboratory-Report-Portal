<?php
require_once 'includes/functions.php'; require_login();
header_html('Dashboard');
$role=user()['role'];
?>
<h1>Dashboard</h1>
<p class="muted">Choose an action from your role dashboard.</p>
<div class="grid">
<?php if($role==='admin'): ?>
<a class="tile" href="staff.php"><h2>Manage staff</h2><p>Create doctor and technician accounts.</p></a>
<a class="tile" href="categories.php"><h2>Test categories</h2><p>Add and view test categories.</p></a>
<a class="tile" href="tests.php"><h2>Laboratory tests</h2><p>Maintain the test catalogue.</p></a>
<a class="tile" href="reports.php"><h2>Generate reports</h2><p>View request and report summary.</p></a>
<?php elseif($role==='doctor'): ?>
<a class="tile" href="request_test.php"><h2>Request a test</h2><p>Submit a lab test request for a patient.</p></a>
<a class="tile" href="reports.php"><h2>View reports</h2><p>Review patient test results.</p></a>
<?php elseif($role==='technician'): ?>
<a class="tile" href="worklist.php"><h2>Worklist</h2><p>Update test status and upload reports.</p></a>
<?php else: ?>
<a class="tile" href="reports.php"><h2>My reports</h2><p>View and download your reports.</p></a>
<?php endif; ?>
</div>
<?php footer_html(); ?>
