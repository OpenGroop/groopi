<?php
$username = 'user';  // EDIT THIS VALUE
$password = 'value'; // EDIT THIS VALUE
$options = ['cost' => 9,];
$hash = password_hash($password, PASSWORD_BCRYPT, $options);

try {
    $pdo = new PDO('sqlite:/srv/sqlite3/data/user.db');
} catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }
                
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();
    $sql = 'INSERT INTO hash(value, user) VALUES(?,?)';
    $statement = $pdo->prepare($sql);
    $statement->execute(array($hash,$username));
    $pdo->commit();
    $pdo = null;
} catch (EXCEPTION $e) { die("Unable to commit: " . $e->getMessage());}

?>
