<?php 
    require_once ('../lib/groop/src/groop_db_user.php');
    
    $users = DBUser::getUsers();
    foreach ($users as $user) {
        echo '<div class="map">'.PHP_EOL;
        echo '    <div class="map-key">' . $user['user'] . '</div>'.PHP_EOL;
        echo '    <div class="map-value"><a href="settings_user.php?id='.$user['id'].'" target="_self" >edit</a></div>'.PHP_EOL;
        echo '</div>'.PHP_EOL;
    }
?>
