<?php
    session_start();
    if (isset($_SESSION['valid'])) {
        header('Location: devices.php');
        exit;
    }
    include 'page_template.php';
    include 'form_login.php';
    printHeader();
    printBanner();
?>

<!-- BEGIN OUTPUT -->
<div id="content">
<div class="index"><?php printLoginForm(); ?></div>
</div> <!--/content-->
<!-- END OUTPUT -->

<?php printFooter(); ?>
