<?php
require_once 'includes/functions.php'; require_login();
$role=user()['role'];$uid=user()['id'];
$sql="SELECT r.id,r.status,r.request_date,p.patient_code,pu.name patient,du.name doctor,t.test_name,tr.id report_id,tr.original_name
FROM test_requests r JOIN patients p ON p.id=r.patient_id JOIN users pu ON pu.id=p.user_id
JOIN doctors d ON d.id=r.doctor_id JOIN users du ON du.id=d.user_id JOIN laboratory_tests t ON t.id=r.test_id
LEFT JOIN test_reports tr ON tr.request_id=r.id";
$params=[];
if($role==='patient'){$sql.=" WHERE p.user_id=?";$params[]=$uid;}
elseif($role==='doctor'){$sql.=" WHERE d.user_id=?";$params[]=$uid;}
$sql.=" ORDER BY r.request_date DESC";
$s=db()->prepare($sql);$s->execute($params);$rows=$s->fetchAll();
header_html('Test reports');?>
<h1><?= $role==='patient'?'My reports':($role==='doctor'?'Patient test reports':'Test request and report summary') ?></h1>
<section class="card"><table><tr><th>Request</th><th>Patient</th><th>Test</th><th>Doctor</th><th>Status</th><th>Requested</th><th>Report</th></tr>
<?php foreach($rows as $r):?><tr><td>#<?=$r['id']?></td><td><?=e($r['patient'])?></td><td><?=e($r['test_name'])?></td><td><?=e($r['doctor'])?></td><td><?=e($r['status'])?></td><td><?=e($r['request_date'])?></td><td><?php if($r['report_id']):?><a href="download.php?id=<?=$r['id']?>">Download PDF</a><?php else:?>Pending<?php endif;?></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="7">No test requests found.</td></tr><?php endif;?></table></section><?php footer_html();?>
