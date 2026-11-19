<?php
require_once 'includes/functions.php'; require_role(['technician']);
$id=(int)($_GET['id']??0);
$s=db()->prepare('SELECT r.id,r.status,u.name patient,t.test_name FROM test_requests r JOIN patients p ON p.id=r.patient_id JOIN users u ON u.id=p.user_id JOIN laboratory_tests t ON t.id=r.test_id WHERE r.id=?');$s->execute([$id]);$req=$s->fetch();
if(!$req){http_response_code(404);exit('Request not found.');}
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();
if(isset($_FILES['report']) && $_FILES['report']['error']===UPLOAD_ERR_OK){
$f=$_FILES['report'];$ext=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));$mime=(new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
if($ext==='pdf' && $mime==='application/pdf' && $f['size']<=5*1024*1024){
$stored=bin2hex(random_bytes(16)).'.pdf';$dir=__DIR__.'/uploads';if(!is_dir($dir))mkdir($dir,0750,true);
if(move_uploaded_file($f['tmp_name'],$dir.'/'.$stored)){
$old=db()->prepare('SELECT file_name FROM test_reports WHERE request_id=?');$old->execute([$id]);$oldFile=$old->fetchColumn();if($oldFile && is_file($dir.'/'.$oldFile))unlink($dir.'/'.$oldFile);
db()->prepare('INSERT INTO test_reports(request_id,file_name,original_name,uploaded_by,report_notes) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE file_name=VALUES(file_name),original_name=VALUES(original_name),uploaded_by=VALUES(uploaded_by),report_notes=VALUES(report_notes),uploaded_at=CURRENT_TIMESTAMP')->execute([$id,$stored,basename($f['name']),user()['id'],trim($_POST['notes']??'')]);
db()->prepare("UPDATE test_requests SET status='Completed' WHERE id=?")->execute([$id]);flash('PDF report uploaded and request marked completed.');redirect('worklist.php');
}} flash('Upload a valid PDF file (maximum 5 MB).','error');}else flash('File upload failed.','error');
}
header_html('Upload report');?>
<h1>Upload test report</h1><section class="card"><p><b>Request:</b> #<?=$id?> · <?=e($req['patient'])?> · <?=e($req['test_name'])?></p><form method="post" enctype="multipart/form-data"><?=csrf_field()?><label>Report file (PDF only, max 5 MB)</label><input type="file" name="report" accept="application/pdf,.pdf" required><label>Report notes</label><textarea name="notes"></textarea><button>Upload report</button></form></section><?php footer_html();?>
