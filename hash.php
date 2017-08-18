<?php
$options = ['cost' => 9,];
$hash = password_hash($argv[1], PASSWORD_BCRYPT, $options);
?>