<?php
require_once 'includes/functions.php'; require_login();
$id=(int)($_GET['id']??0);$sql="SELECT tr.file_name,tr.original_name,p.user_id patient_user,d.user_id doctor_user FROM test_reports tr JOIN test_requests r ON r.id=tr.request_id JOIN patients p ON p.id=r.patient_id JOIN doctors d ON d.id=r.doctor_id WHERE r.id=?";
$s=db()->prepare($sql);$s->execute([$id]);$r=$s->fetch();
if(!$r){http_response_code(404);exit('Report not found.');}
$role=user()['role'];
if(!($role==='admin'||($role==='patient' && (int)$r['patient_user']===(int)user()['id'])||($role==='doctor' && (int)$r['doctor_user']===(int)user()['id'])||$role==='technician')){http_response_code(403);exit('Access denied.');}
$path=__DIR__.'/uploads/'.$r['file_name'];if(!is_file($path)){http_response_code(404);exit('File missing.');}
header('Content-Type: application/pdf');header('Content-Disposition: attachment; filename="'.preg_replace('/[^A-Za-z0-9._-]/','_', $r['original_name']).'"');header('Content-Length: '.filesize($path));readfile($path);exit;
