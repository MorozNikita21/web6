<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
	setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    $messages[] = 'Результаты были сохранены';
	
    if (!empty($_COOKIE['pass'])) {
      $messages[] = sprintf('Вы можете <a href="login.php">войти на аккаунт</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> если хотите измененить данные',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
  }
  }
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
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['birthdayear'] = empty($_COOKIE['birthdayear_value']) ? '' : $_COOKIE['birthdayear_value'];
  $values['gen'] = empty($_COOKIE['gen_value']) ? '' : $_COOKIE['gen_value'];
  $values['body'] = empty($_COOKIE['body_value']) ? '' : $_COOKIE['body_value'];
  $values['ability'] = empty($_COOKIE['ability_value']) ? array() : json_decode($_COOKIE['ability_value']);
  $values['biographiya'] = empty($_COOKIE['biographiya_value']) ? '' : $_COOKIE['biographiya_value'];

  if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      !empty($_SESSION['login'])) {
    $user = 'u54409';
    $pass = '3113126';
    $db = new PDO('mysql:host=localhost;dbname=u54409', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    try{
      $get=$db->prepare("select * from forma where id=?");
      $get->bindParam(1,$_SESSION['uid']);
      $get->execute();
      $inf=$get->fetchALL();
      $values['name']=$inf[0]['name'];
      $values['email']=$inf[0]['email'];
      $values['birthdayear']=$inf[0]['birthdayear'];
      $values['gen']=$inf[0]['gen'];
      $values['body']=$inf[0]['body'];
      $values['biographiya']=$inf[0]['biographiya'];

      $get2=$db->prepare("select a_id from abforma where app_id=?");
      $get2->bindParam(1,$_SESSION['uid']);
      $get2->execute();
      $inf2=$get2->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['ability_id']=='1'){
          $values['1']=1;
        }
        if($inf2[$i]['ability_id']=='2'){
          $values['2']=1;
        }
        if($inf2[$i]['ability_id']=='3'){
          $values['3']=1;
        }
		if($inf2[$i]['ability_id']=='4'){
          $values['4']=1;
        }
      }
    }
    catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
    }
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }
  include('form.php');
}
else{
  if(!empty($_POST['logout'])){
    session_destroy();
    header('Location: index.php');
  }
  else{
    $regex_name='/[a-z,A-Z,а-я,А-Я,-]*$/';
    $regex_email='/[a-z]+\w*@[a-z]+\.[a-z]{2,4}$/';
	
$errors = FALSE;
if (empty($_POST['name']) or !preg_match($regex_name,$_POST['name'])) {
  setcookie('name_error', '1', time() + 24 * 60 * 60);
  setcookie('name_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  setcookie('name_error','',100000);
}

if (empty($_POST['email']) || !preg_match($regex_email, $_POST['email'])) {
  setcookie('email_error', '1', time() + 24 * 60 * 60);
  setcookie('email_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  setcookie('email_error','',100000);
}

if (empty($_POST['birthdayear']) || !is_numeric($_POST['birthdayear']) || !preg_match('/^\d+$/', $_POST['birthdayear'])) {
  setcookie('birthdayear_error', '1', time() + 24 * 60 * 60);
  setcookie('birthdayear_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('birthdayear_value', $_POST['birthdayear'], time() + 30 * 24 * 60 * 60);
  setcookie('birthdayear_error','',100000);
}

if (empty($_POST['gen']) || ($_POST['gen']!='m' && $_POST['gen']!='f')) {
  setcookie('gen_error', '1', time() + 24 * 60 * 60);
  setcookie('gen_value', '', 100000);
  $errors = TRUE;
}
else {
  setcookie('gen_value', $_POST['gen'], time() + 30 * 24 * 60 * 60);
  setcookie('gen_error','',100000);
}
if (empty($_POST['body']) || ($_POST['body']!='0' && $_POST['body']!='4' && $_POST['body']!='5')) {
   setcookie('body_error', '1', time() + 24 * 60 * 60);
   setcookie('body_value', '', 100000);
   $errors = TRUE;
}
else {
  setcookie('body_value', $_POST['body'], time() + 30 * 24 * 60 * 60);
  setcookie('body_error','',100000);
}

foreach ($_POST['ability'] as $ability) {
  if (!is_numeric($ability) || !in_array($ability, [1, 2, 3, 4])) {
    setcookie('ability_error', '1', time() + 24 * 60 * 60);
	setcookie('ability_value', '', 100000);
    $errors = TRUE;
    break;
  }
}
if (!empty($_POST['ability'])) {
  setcookie('ability_value', json_encode($_POST['ability']), time() + 24 * 60 * 60);
  setcookie('ability_error', '', time() + 24 * 60 * 60);
}

if (empty($_POST['biographiya']) || !preg_match('/^[0-9A-Za-z0-9А-Яа-я,\.\s]+$/', $_POST['biographiya'])) {
    setcookie('biographiya_error', '1', time() + 24 * 60 * 60);
	setcookie('biographiya_value', '', time() + 30 * 24 * 60 * 60);
    $errors = TRUE;
}
else {
  setcookie('biographiya_value', $_POST['biographiya'], time() + 30 * 24 * 60 * 60);
  setcookie('biographiya_error', '', time() + 24 * 60 * 60);
}

if ($errors) {
	setcookie('save','',100000);
    header('Location: login.php');
}
    else {
      setcookie('name_error', '', 100000);
      setcookie('email_error', '', 100000);
      setcookie('birthdayear_error', '', 100000);
      setcookie('gen_error', '', 100000);
      setcookie('body_error', '', 100000);
      setcookie('ability_error', '', 100000);
    }
	
	$user = 'u54409';
    $pass = '3113126';
    $db = new PDO('mysql:host=localhost;dbname=u54409', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
    if (!empty($_COOKIE[session_name()]) && !empty($_SESSION['login']) and !$errors) {
    $app_id=$_SESSION['uid'];
    $upd=$db->prepare("update forma set name=?,email=?,birthday=?,sex=?,limbs=?,biographiya=? where id=?");
    $upd->execute(array($_POST['name'],$_POST['email'],$_POST['birthdayear'],$_POST['gen'],$_POST['body'],$_POST['biographiya'],$id));
    $del=$db->prepare("delete from abforma where app_id=?");
    $del->execute(array($id));
	  $stmt = $db->prepare("INSERT INTO abforma SET app_id = ?, a_id=?");
	  foreach ($_POST['ability'] as $ability) {
		$stmt->execute([$app_id,$ability ]);
	  }
  }
  else {
    if(!$errors){
      $login = 'N'.substr(uniqid(),-6);
      $password = substr(md5(uniqid()),0,15);
      $hashed=password_hash($password,PASSWORD_DEFAULT);
      print($hashed);
      setcookie('login', $login);
      setcookie('pass', $password);
      try {
        $stmt = $db->prepare("INSERT INTO forma SET name=?,email=?,birthday=?,sex=?,limbs=?,biographiya=?");
        $stmt -> execute(array($_POST['name'],$_POST['email'],$_POST['birthdayear'],$_POST['gen'],$_POST['body'],$_POST['biographiya']));
        $app_id=$db->lastInsertId();
        //$pwr=$db->prepare("INSERT INTO ability_application SET ability_id=?,application_id=?");
        //foreach($pwrs as $power){ 
        //  $pwr->execute(array($power,$id));
        //}
		  $stmt = $db->prepare("INSERT INTO abforma SET app_id = ?, a_id=?");

  foreach ($_POST['ability'] as $ability) {
    $stmt->execute([$app_id,$ability ]);
  }
        $usr=$db->prepare("insert into users set app_id=?,login=?,pass=?");
        $usr->execute(array($app_id,$login,$hashed));
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
      }
    }
  }
    if(!$errors){
      setcookie('save', '1');
    }
    header('Location: ./');
  }

}