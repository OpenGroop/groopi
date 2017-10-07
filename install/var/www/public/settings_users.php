<?php
    include 'session_check.php';
    include 'session_check_admin.php';
    include 'page_template.php';
    include 'form_user_add.php';

    printHeader();
    printBanner();

?>
<div id="content">
<div><?php printTitle('SETTINGS:USERS') ?></div>
<div class="settings-header"><b>ADD USER</b></div>
<div><?php printUserForm(); ?></div>
<div class="settings-header"><b>EDIT USER</b></div>
<div class="settings-subheading"><?php include 'user_list.php'; ?></div>
</div> <!--/content-->

<?php printFooter() ?>
