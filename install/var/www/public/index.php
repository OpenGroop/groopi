<?php
    session_start();
    if (isset($_SESSION['valid'])) {
        header('Location: devices.php');
        exit;
    }
    include 'form_login.php';
    include 'header.php';
?>

<!-- BEGIN OUTPUT -->
<div id="body">
<div><?php printForm(); ?></div>
</div> <!--/body-->
<!-- END OUTPUT -->

<?php include 'footer.php'; ?>
