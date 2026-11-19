<?php
require_once 'includes/functions.php'; require_role(['admin']);
if($_SERVER['REQUEST_METHOD']==='POST'){ verify_csrf();
$name=trim($_POST['name']??''); $email=trim($_POST['email']??''); $pass=$_POST['password']??''; $role=$_POST['role']??'';
if($name && filter_var($email,FILTER_VALIDATE_EMAIL) && strlen($pass)>=8 && in_array($role,['doctor','technician'],true)){
 try{
  $s=db()->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)');
  $s->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT),$role]); $uid=(int)db()->lastInsertId();
  if($role==='doctor') db()->prepare('INSERT INTO doctors(user_id,specialization) VALUES(?,?)')->execute([$uid,trim($_POST['specialization']??'')]);
  else db()->prepare('INSERT INTO technicians(user_id,staff_code) VALUES(?,?)')->execute([$uid,trim($_POST['staff_code']??'TECH-'.$uid)]);
  flash('Staff account created.');
 }catch(Throwable $e){flash('Could not create account. Email or staff code may already exist.','error');}
}else flash('Enter valid details. Password must be at least 8 characters.','error');
redirect('staff.php'); }
header_html('Manage staff');
$rows=db()->query("SELECT id,name,email,role,active FROM users WHERE role IN ('doctor','technician') ORDER BY id DESC")->fetchAll();
?>
<h1>Manage laboratory staff</h1>
<section class="card"><h2>Create doctor / technician</h2><form method="post"><?=csrf_field()?>
<div class="form-grid"><div><label>Full name</label><input name="name" required></div><div><label>Email</label><input type="email" name="email" required></div><div><label>Temporary password (8+ characters)</label><input type="password" name="password" minlength="8" required></div><div><label>Role</label><select name="role" id="role" onchange="document.getElementById('doctorFields').hidden=this.value!=='doctor';document.getElementById('techFields').hidden=this.value!=='technician'"><option value="doctor">Doctor</option><option value="technician">Technician</option></select></div><div id="doctorFields"><label>Specialization</label><input name="specialization"></div><div id="techFields" hidden><label>Staff code</label><input name="staff_code" placeholder="TECH-002"></div></div><button>Create account</button></form></section>
<section class="card"><h2>Existing staff</h2><table><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th></tr><?php foreach($rows as $r): ?><tr><td><?=e($r['name'])?></td><td><?=e($r['email'])?></td><td><?=e($r['role'])?></td><td><?= $r['active']?'Active':'Inactive' ?></td></tr><?php endforeach; ?></table></section>
<?php footer_html(); ?>
