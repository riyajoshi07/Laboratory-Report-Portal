<?php
require_once 'includes/functions.php'; require_role(['admin']);
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$n=trim($_POST['name']??'');$d=trim($_POST['description']??'');if($n){try{db()->prepare('INSERT INTO test_categories(name,description) VALUES(?,?)')->execute([$n,$d]);flash('Category added.');}catch(Throwable $e){flash('Category already exists or could not be saved.','error');}}redirect('categories.php');}
header_html('Test categories');$rows=db()->query('SELECT * FROM test_categories ORDER BY name')->fetchAll();?>
<h1>Test categories</h1><section class="card"><form method="post"><?=csrf_field()?><label>Category name</label><input name="name" required><label>Description</label><textarea name="description"></textarea><button>Add category</button></form></section>
<section class="card"><table><tr><th>Category</th><th>Description</th></tr><?php foreach($rows as $r):?><tr><td><?=e($r['name'])?></td><td><?=e($r['description'])?></td></tr><?php endforeach;?></table></section><?php footer_html();?>
