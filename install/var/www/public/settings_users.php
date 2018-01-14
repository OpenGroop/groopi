<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');
    require_once ('../lib/groop/src/groop_db_user.php');

    $msg = '';

    if (isset($_POST['BTN_USER_ADD']) ) {
        if ($_POST['TXT_PW_1'] == $_POST['TXT_PW_2']) {
            $status = DBUser::add($_POST['TXT_USER'],$_POST['TXT_PW_1']);
            if ($status == 0) {
            	header('HTTP/1.1 303');
            	header('Location: https://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['PHP_SELF']);
            	exit;
        	} else {
        		$msg = 'There seems to be a problem..(' . $status .')';
        	}
        } else {
            $msg = "Passwords do not match..";
        }
    }

    printHeader();
    printBanner();
    printNavigation();

?>
<div id="content">
<div class="breadcrumbs spaced">
<a href="settings.php" target="_self">SETTINGS</a>
/
</div>
<div><?php printTitle('SETTINGS:USERS') ?></div>

<hr>
<div>
<form class="form" role="form" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
<div class="map-title">ADD USER</div>
<div class="map">
    <div class="map-key">Username:</div>
    <div class="map-value"><input type="text" name="TXT_USER" required/></div>
</div>

<div class="map">
    <div class="map-key">Password:</div>
    <div class="map-value"><input type="password" name="TXT_PW_1" required /></div>
</div>

<div class="map">
    <div class="map-key">Password:</div>
    <div class="map-value"><input type="password" name="TXT_PW_2" required /></div>
</div>

<div><button type="submit"  name="BTN_USER_ADD">SUBMIT</button></div>
<div><?php echo $msg ?></div>
</form>
</div>
<hr>
<div class="map-title">EDIT USER</div>
<div><?php include 'list_users.php'; ?></div>
<hr>
</div> <!--/content-->

<?php printFooter() ?>
