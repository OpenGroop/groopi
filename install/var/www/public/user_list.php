<?php 
    require_once ('lib/groop/src/groop_db_user.php');
    
    $users = DBUser::getUsers();
    foreach ($users as $user) {
        echo '<div><a href="settings_user.php?id='.$user['id'].'" target="_self" >'.$user['user'].'</a></div>'.PHP_EOL;
    }
?>
