<?php
    require_once('lib/groop/src/groop_db_user.php');

    $msg_remove = '';


    if (isset($_POST['BTN_USER_REMOVE']) ) {
        $user = DBUser::getUser($_GET['id']);
        if ($_POST['TXT_USER_REMOVE'] == $user['username']) {
            DBUser::deleteUser($user['id']);
            header('Location: settings_users.php');
            exit;
        } else {
            $msg_remove = "Failed. User name entered incorrectly.";
        }

    }


    function printRemoveForm($userid) {
        global $msg_remove;
        $action = $_SERVER['PHP_SELF'] .'?id='.$userid;
        echo '<form role="form" action="'.htmlspecialchars($action).'" method="post">'.PHP_EOL;
        echo '<div>REMOVE USER</div>'.PHP_EOL;
        echo '<div><input type="text" name="TXT_USER_REMOVE" placeholder="ENTER USER NAME" required/></div>'.PHP_EOL;
        echo '<div><button type="submit" name="BTN_USER_REMOVE">Submit</button>'.PHP_EOL;
        echo '<div>'.$msg_remove.'</div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }
?>