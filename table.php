<head>
  <link rel="stylesheet" href="style.css" type="text/css">
</head>
<style>
  .error {
    border: 2px solid pink;
  }
  table {
  text-align: center;
  border-spacing: 100px 0;
}
</style>
<body>
  <div class="table">
    <table>
      <tr>
        <th>Имя</th>
        <th>E-mail</th>
        <th>Год рождения</th>
        <th>Пол</th>
        <th>Количество конечностей</th>
        <th>Сверхспособности</th>
        <th>Биография</th>
      </tr>
      <?php
      foreach($users as $user){
      ?>
            <tr>
              <td><?= $user['name']?></td>
              <td><?= $user['email']?></td>
              <td><?= $user['birthday']?></td>
              <td><?= $user['sex']?></td>
              <td><?= $user['limbs']?></td>
              <td><?php 
                $user_ability=array(
                    "1"=>FALSE,
                    "2"=>FALSE,
                    "3"=>FALSE,
					"4"=>FALSE
                );
                foreach($pwrs as $pwr){
                    if($pwr['app_id']==$user['id']){
                        if($pwr['a_id']=='1'){
                            $user_ability['1']=TRUE;
                        }
                        if($pwr['a_id']=='2'){
                            $user_ability['2']=TRUE;
                        }
                        if($pwr['a_id']=='3'){
                            $user_ability['3']=TRUE;
                        }
						if($pwr['a_id']=='4'){
                            $user_ability['4']=TRUE;
                        }
                    }
                }
				if($user_ability['1']){echo 'Бессмертие<br>';}
                if($user_ability['2']){echo 'Прохождение сквозь стены<br>';}
                if($user_ability['3']){echo 'Левитация<br>';}
                if($user_ability['4']){echo 'Не чувствовать боль<br>';}?>
              </td>
              <td><?= $user['biographiya']?></td>
              <td>
                <form method="get" action="edit.php">
                  <input name=edit_id value="<?= $user['id']?>" hidden>
                  <input type="submit" value=Edit>
                </form>
              </td>
            </tr>
      <?php
       }
      ?>
    </table>
    <?php
	printf('Бессмертие: %d <br>',$ability_count[0]);
    printf('Прохождение сквозь стены: %d <br>',$ability_count[1]);
    printf('Левитация: %d <br>',$ability_count[2]);
    printf('Не чувствовать боль: %d <br>',$ability_count[3]);
    ?>
  </div>
</body>
