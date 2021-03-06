<?php 

    require_once ('../lib/groop/src/groop_constants.php');

    class DBUser {

        /* =============================================
        // RETRIEVES USER INFO OF GIVEN USERID
        // RETURNS:  array()
        // =============================================*/
        static function getUser($userid) {
            $result = [
                'status'   => '',
                'id'       => '',
                'username' => ''
            ];

            try { $pdo = new PDO(Constants::USER_DB); }
            catch (EXCEPTION $e) {
                $result['status'] = Constants::DB_CONN_ERR;
                return $result;
            }

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
                $result['status'] = Constants::DB_ACTION_OK;
            } catch (EXCEPTION $e) {
                $result['status'] = Constants::DB_QUERY_ERR;
                return $result;
            }

            return $result;
        }


        /* =============================================
        // RETRIEVES USER INFO OF GIVEN USERNAME
        // RETURNS:  array()
        // =============================================*/
        static function getUserByName($user) {
            $result = [
                'status'   => '',
                'id'       => '',
                'username' => ''
            ];

            try { $pdo = new PDO(Constants::USER_DB); }
            catch (EXCEPTION $e) {
                $result['status'] = Constants::DB_CONN_ERR;
                return $result;
            }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "SELECT id, user FROM hash WHERE user = ?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($user));
                $statement->bindColumn('id',   $result['id']);
                $statement->bindColumn('user', $result['username']);
                $statement->fetch(PDO::FETCH_BOUND);
                $statement->closeCursor();
                $statement = null;
                $pdo = null;
                $result['status'] = Constants::DB_ACTION_OK;
            } catch (EXCEPTION $e) {
                $result['status'] = Constants::DB_QUERY_ERR;
                return $result;
            }

            return $result;
        }


        /* =======================
        // ADDS A USER TO USER.DB
        // $user:     username of new user
        // $password: password to new user account
        // RETURNS:   integer
        // =======================*/
        static function add($user, $password) {
            $status = null;

            $u = DBUser::getUserByName($user);

            if ($u['status'] < 0) {
                $status = $u['status'];
                return $status;
            }

            if ($u['id'] != null) {
                $status = '-3';
                return $status;
            }

            $options = ['cost' => 9,];
            $hash = password_hash($password, PASSWORD_BCRYPT, $options);

            try { $pdo = new PDO(Constants::USER_DB); } 
            catch (EXCEPTION $e) { 
                $status = -1;
                return $status;
            }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "INSERT INTO hash (value, user) VALUES (?,?)";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($hash,$user));
                $pdo->commit();
                $statement->closeCursor();
                $pdo = null;
                $status = 0;
            } catch(EXCEPTION $e) {
                $status = -2;
            }

            return $status;
        }

        /*
        // RETRIEVES USER DATA
        // $username: the user's data to retreive
        // RETURNS: array()
        */
        static function getLogin($username) {
            $result = [
                'status'   => '',
                'id'       => '',
                'username' => '',
                'hash'     => ''
            ];

            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) {
                error_log($e);
                $result['status'] = Constants::DB_CONN_ERR;
                return $result;
            }

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
                $result['status'] = Constants::DB_ACTION_OK;
            } catch (EXCEPTION $e) {
                error_log($e);
                $result['status'] = Constants::DB_QUERY_ERR;
                return $result;
            }
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

        /*
        // UPDATES USER'S PASSWORD HASH
        // $userid   (int)   : id of user to update
        // $password (string): password to hash
        // RETURNS: integer representing execution status
        //      0 == execution success
        //      1 == execution fail - no database connection
        //      2 == execution fail - cannot update database
        */
        static function updateHash($userid, $password) {
            $status = '';
            $options = ['cost' => 9,];
            $hash = password_hash($password, PASSWORD_BCRYPT, $options);

            try {
                $pdo = new PDO(Constants::USER_DB);
            } catch (EXCEPTION $e) {
                $status = Constants::DB_CONN_ERR;
                return $status;
            }

            try {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "UPDATE hash SET value=? WHERE id=?";
                $statement = $pdo->prepare($sql);
                $statement->execute(array($hash,$userid));
                $pdo->commit();
                $pdo = null;
                $status = Constants::DB_ACTION_OK;
            } catch (EXCEPTION $e) {
                $status = Constants::DB_QUERY_ERR;
                return $status;
            }

            return $status;
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
