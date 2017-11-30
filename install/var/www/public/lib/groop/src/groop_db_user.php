<?php 

    require_once ('lib/groop/src/groop_constants.php');

    class DBUser {


        /* =======================
        // ADDS A USER TO USER.DB
        // $user:     username of new user
        // $password: password to new user account
        // RETURNS:   no return
        // =======================*/
        static function add($user, $password) {
            $options = ['cost' => 9,];
            $hash = password_hash($password, PASSWORD_BCRYPT, $options);

            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) { die("Unable to connect" . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "INSERT INTO hash (value, user) VALUES (?,?)";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($hash,$user));
                $pdo->commit();
                $statement->closeCursor();
                $pdo = null;
            } catch(EXCEPTION $e) {}            
        }

        /*
        // RETRIEVES USER DATA
        // $username: the user's data to retreive
        // RETURNS: array()
        */
        static function getLogin($username) {
            $result = [
                'id'       => '',
                'username' => '',
                'hash'     => ''
            ];

            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "SELECT * FROM hash WHERE user=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($username));
                $statement->bindColumn('id',    $result['id']);
                $statement->bindColumn('user',  $result['username']);
                $statement->bindColumn('value', $result['hash']);
                $statement->fetch(PDO::FETCH_BOUND);
                $statement->closeCursor();
                $statement = null;
                $pdo = null;
            } catch (EXCEPTION $e) {}
            return $result;
        }

        /* =============================================
        // RETRIEVES USERNAMES AND USERIDS OF ALL USERS
        // RETURNS: nested array()
        // =============================================*/
        static function getUsers() {
            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "SELECT id, user FROM hash ORDER BY id";
                $statement = $pdo->query($sql);
                $result = $statement->fetchAll();
                $statement->closeCursor();
                $statement = null;
                $pdo = null;
            } catch (EXCEPTION $e) {}
            return $result;       
        }

        /* =============================================
        // RETRIEVES USERNAME AND USERID OF GIVEN USER
        // RETURNS: nested array()
        // =============================================*/
        static function getUser($userid) {
            $result = [
                'id'      => '',
                'username' => ''
            ];
            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) { die('Unable to connect: ' . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "SELECT id, user FROM hash WHERE id = ?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($userid));
                $statement->bindColumn('id',   $result['id']);
                $statement->bindColumn('user', $result['username']);
                $statement->fetch(PDO::FETCH_BOUND);                
                $statement->closeCursor();
                $statement = null;
                $pdo = null;
            } catch (EXCEPTION $e) {}
            return $result;       
        }

        /*
        // UPDATES USER'S PASSWORD HASH
        // $userid (int):      id of user to update
        // $password (string): password to hash
        // RETURNS: no return
        */
        static function updateHash($userid, $password) {
            $options = ['cost' => 9,];
            $hash = password_hash($password, PASSWORD_BCRYPT, $options);

            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }
            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "UPDATE hash SET value=? WHERE id=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($hash,$userid));
                $pdo->commit();
                $pdo = null;
                $msg = "Password  succesfully changed.";
            } catch (EXCEPTION $e) {}
        }

        static function deleteUser($userid) {
            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) { die("Unable to connect: " . $e->getMessage()); }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "DELETE FROM hash WHERE id=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($userid));
                $pdo->commit();
                $statement->closeCursor();
                $pdo = null;
            } catch (EXCEPTION $e) {}

        } 

    }
?>