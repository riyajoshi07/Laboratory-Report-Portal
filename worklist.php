<?php
require_once 'includes/functions.php'; require_role(['technician']);
$tech=db()->prepare('SELECT id FROM technicians WHERE user_id=?');$tech->execute([user()['id']]);$techId=(int)$tech->fetchColumn();
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$rid=(int)($_POST['request_id']??0);$status=$_POST['status']??'';
$check=db()->prepare("SELECT id FROM test_requests WHERE id=? AND status<>'Cancelled'");$check->execute([$rid]);
if($check->fetch() && in_array($status,['In Progress','Completed'],true)){
 db()->prepare('UPDATE test_requests SET status=?,technician_id=? WHERE id=?')->execute([$status,$techId,$rid]); flash('Test status updated.');
}else flash('Invalid request or status.','error');redirect('worklist.php');}
header_html('Technician worklist');
$sql="SELECT r.id,r.status,r.request_date,u.name patient,t.test_name FROM test_requests r JOIN patients p ON p.id=r.patient_id JOIN users u ON u.id=p.user_id JOIN laboratory_tests t ON t.id=r.test_id ORDER BY r.request_date DESC";
$rows=db()->query($sql)->fetchAll();?>
<h1>Technician worklist</h1><section class="card"><table><tr><th>Request</th><th>Patient</th><th>Test</th><th>Status</th><th>Update</th><th>Report</th></tr>
<?php foreach($rows as $r):?><tr><td>#<?=$r['id']?></td><td><?=e($r['patient'])?></td><td><?=e($r['test_name'])?></td><td><?=e($r['status'])?></td><td><form class="inline" method="post"><?=csrf_field()?><input type="hidden" name="request_id" value="<?=$r['id']?><select name="status">
    <option value="In Progress" <?= $r['status']==='In Progress'?'selected':'' ?>>In Progress</option>
    <option value="Completed" <?= $r['status']==='Completed'?'selected':'' ?>>Completed</option>
</select><button class="small-btn">Save</button></form></td><td><a href="upload_report.php?id=<?=$r['id']?>">Upload</a></td></tr><?php endforeach;?></table></section><?php footer_html();?>
