<?php
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Результаты были сохранены';
    setcookie('name', '', 100000);
    setcookie('email_value', '', 100000);
    setcookie('birthdayear_value', '', 100000);
    setcookie('gen_value', '', 100000);
    setcookie('body_value', '', 100000);
    setcookie('biographiya_value', '', 100000);
    setcookie('ability', '', 100000);

  }
  //Ошибки
  
  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['birthdayear'] = !empty($_COOKIE['birthdayear_error']);
  $errors['gen'] = !empty($_COOKIE['gen_error']);
  $errors['body'] = !empty($_COOKIE['body_error']);
  $errors['ability'] = !empty($_COOKIE['ability_error']);
  $errors['biographiya'] = !empty($_COOKIE['biographiya_error']);

  if ($errors['name']) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Как вас зовут?</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Напишить свой e-mail</div>';
  }
  if ($errors['birthdayear']) {
    setcookie('birthdayear_error', '', 100000);
    $messages[] = '<div class="error">В каком году вы родились?</div>';
  }
  if ($errors['gen']) {
    setcookie('gen_error', '', 100000);
    $messages[] = '<div class="error">Какого вы пола?</div>';
  }
  if ($errors['body']) {
    setcookie('body_error', '', 100000);
    $messages[] = '<div class="error">Сколько у вас конечностей?</div>';
  }
  if ($errors['ability']) {
    setcookie('ability_error', '', 100000);
    $messages[] = '<div class="error">Какие бы вы хотели суперспособности?</div>';
  }
  if ($errors['biographiya']) {
    setcookie('biographiya_error', '', 100000);
    $messages[] = '<div class="error">Напишите про себя</div>';
  }
  
  $values = array();
  $user = 'u54409';
$pass = '3113126';
$db = new PDO('mysql:host=localhost;dbname=u54409', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  try{
      $id=$_GET['edit_id'];
      $get=$db->prepare("select * from forma where id=?");
      $get->execute(array($id));
      $user=$get->fetchALL();
      $values['name']=$user[0]['name'];
      $values['email']=$user[0]['email'];
      $values['birthday']=$user[0]['birthday'];
      $values['sex']=$user[0]['sex'];
      $values['limbs']=$user[0]['limbs'];
      $values['biographiya']=$user[0]['biographiya'];
      $get2=$db->prepare("select a_id from abforma where app_id=?");
      $get2->execute(array($id));
      $pwrs=$get2->fetchALL();

	  $temp=array(0=>empty($pwrs[0]['a_id'])?null:$pwrs[0]['a_id'],1=>empty($pwrs[1]['a_id'])?null:$pwrs[1]['a_id'],2=>empty($pwrs[2]['a_id'])?null:$pwrs[2]['a_id'],3=>empty($pwrs[3]['a_id'])?null:$pwrs[3]['a_id']);
      $values['ability'] = $temp;
  }
  catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
  }
  include('editform.php');
}
else {
  if(!empty($_POST['edit'])){
	$id=$_POST['id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $year=$_POST['birthdayear'];
    $sex=$_POST['gen'];
    $limb=$_POST['body'];
    $bio=$_POST['biographiya'];
	$pwrs=$_POST['ability'];
    $user = 'u54409';
$pass = '3113126';
$db = new PDO('mysql:host=localhost;dbname=u54409', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
        $upd=$db->prepare("update forma set name=?,email=?,birthday=?,sex=?,limbs=?,biographiya=? where id=?");
        $upd->execute(array($name,$email,$year,$sex,$limb,$bio,$id));
        $del=$db->prepare("delete from abforma where app_id=?");
        $del->execute(array($id));
        $upd=$db->prepare("insert into abforma set a_id=?,app_id=?");
	  foreach ($pwrs as $ability) {
		$upd->execute([$ability,$id]);
	  }
    
    header('Location: edit.php?edit_id='.$id);
  }
  elseif(!empty($_POST['del'])) {
    $id=$_POST['id'];
    $user = 'u54409';
$pass = '3113126';
$db = new PDO('mysql:host=localhost;dbname=u54409', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    try {
      $del=$db->prepare("delete from abforma where app_id=?");
      $del->execute(array($id));
	  $del1=$db->prepare("delete from users where app_id=?");
      $del1->execute(array($id));
      $stmt = $db->prepare("delete from forma where id=?");
      $stmt -> execute(array($id));
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
    exit();
    }
    setcookie('del','1');
    setcookie('del_user',$id);
    header('Location: admin.php');
  }
  else{
    header('Loction: admin.php');
  }
}