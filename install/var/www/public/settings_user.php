<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';
    include 'form_user_password.php';
    include 'form_user_remove.php';
    require_once 'lib/groop/src/groop_db_user.php';

    $user = DBUser::getUser($_GET['id']);

    printHeader();
    printBanner();
?>
<div id="content">
<div><?php printTitle('SETTINGS:USER') ?></div>
<div class="settings-header"><b><?php echo 'USER: ' . $user['username'] ?></b></div>

<div class="settings-header"><b>CHANGE PASSWORD</b></div>
<div><?php printPasswordForm($user['id']); ?></div>

<?php
if ($user['id'] > 2) {
    echo '<div class="settings-header"><b>REMOVE USER</b></div>'.PHP_EOL;
    echo '<div>'.printRemoveForm($user['id']).'</div>'.PHP_EOL;
}
?>
</div> <!--/content-->
<?php printFooter(); ?>
