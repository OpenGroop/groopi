<?php
$options = ['cost' => 9,];
$hash = password_hash("password", PASSWORD_BCRYPT, $options);
echo $hash;
?>
