<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'form_user_password.php';
    include 'form_user_remove.php';
    require_once 'lib/groop/src/groop_db_user.php';

    $user = DBUser::getUser($_GET['id']);

    include 'header.php';
    include 'nav_main.php';
?>
<div id="body">
<div>SETTINGS</div>
<div>USER: <?php echo $user['username'];   ?></div>
<div><?php printPasswordForm($user['id']); ?></div>

<?php 
if ($user['id'] > 2) {
    echo '<div>'.printRemoveForm($user['id']).'</div>'.PHP_EOL;
}
?>

</div> <!--/body-->
<?php include 'footer.php'; ?>