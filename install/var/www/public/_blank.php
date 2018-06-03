<?php
    require      ('../session/session_check_admin.php');
    require_once ('../page/page_template.php');

    // HEADER
    printHeader();

    // BODY
    printBanner();
    printNavigation();

?>
<!-- HTML START -->

<div id="content">
<?php echo printTitle('PAGE TITLE HERE') ?>
<hr>

</div> <!--/.content-->

<?php
    printFooter(); 
?>
