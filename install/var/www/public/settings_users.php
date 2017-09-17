<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'form_user_add.php';
    include 'header.php';
    include 'nav_main.php';

?>
<div id="body">
<div>SETTINGS</div>
<div>USERS</div>
<div><?php printForm(); ?></div>
<div><?php include 'user_list.php'; ?></div>
</div> <!--/body-->
