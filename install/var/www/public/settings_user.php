<?php
    require ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../lib/groop/src/groop_db_user.php');

    $user = DBUser::getUser($_GET['id']);
    $action = $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'];
    $msg_pw = '';

    if ( isset($_POST['BTN_PW']) ) {
        if ($_POST['TXT_PW_1'] == $_POST['TXT_PW_2']) {
            sleep(2);
            $status = DBUser::updateHash($user['id'], $_POST['TXT_PW_1']);
            if ($status == 0) {
                $msg_pw = 'Password has been changed..';
            } else {
                $msg_pw = 'There seems to be a problem..(' . $status . ')';
            }

        } else {
            $msg_pw = "The passwords do not match..";
        }
    }

    $msg_remove = '';

    if (isset($_POST['BTN_USER_REMOVE']) ) {
        $status = DBUser::deleteUser($user['id']);
        if ($status == 0) {
            header('HTTP/1.1 303');
            header('Location: settings_users.php');
            exit;
        } else {
            $msg_remove = 'There seems to be a problem..(' . $status . ')';
        }
    }


    function printRemoveForm($userid, $msg) {
        $action = $_SERVER['PHP_SELF'] .'?id='.$userid;
        echo '<form class="form" role="form" action="'.htmlspecialchars($action).'" method="post">'.PHP_EOL;
        echo '<div><button type="submit" name="BTN_USER_REMOVE">REMOVE</button>'.PHP_EOL;
        echo '<div>'.$msg.'</div>'.PHP_EOL;
        echo '</form>'.PHP_EOL;
    }


    printHeader();
    printBanner();
    printNavigation();
?>

<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/ <a href="settings_users.php" target="_self">USERS</a>
</div>
<div><?php printTitle2('SETTINGS:USER', $user['username']) ?></div>
<hr>
<div class="map-title">CHANGE PASSWORD</div>
<form class="form" action="<?php htmlspecialchars($action) ?>" method="post">
    <div class="map">
        <div class="map-key">Password:</div>
        <div class="map-value"><input type="password" name="TXT_PW_1" required/></div>
    </div>

    <div class="map">
        <div class="map-key">Password:</div>
        <div class="map-value"><input type="password" name="TXT_PW_2" required/></div>
    </div>

    <div><button type="submit" name="BTN_PW">Submit</button>
    <div><?php echo $msg_pw ?></div>
</form>
<hr>
<?php
if ($user['id'] >= 2) {
    echo '<div class="map-title">REMOVE USER</div>'.PHP_EOL;
    echo '<div>'.printRemoveForm($user['id'], $msg_remove).'</div>'.PHP_EOL;
    echo '<hr>'.PHP_EOL;
}
?>
</div> <!--/content-->
<?php printFooter(); ?>
