<?php
/*
    Author: daniel
 */

require_once __DIR__ . '/../lib/Bootstrap.php';

class LoginHandler
{

    private const SESSION_TIMEOUT = 7200; // 2 hours timeout

    /**
     * Checks if the user exists and sets the $_SESSION["logged"] & $_SESSION["user_id"]
     * @return TRUE if the login was successful, FALSE otherwise
     */
    static public function loginUser($email, $password)
    {
        try {
            session_start();
            self::handleSessionTimeout();
            
            $stm = Database::$pdo->prepare("SELECT * FROM user
                                            WHERE user.email = :email");
            $stm->bindParam(':email', $email);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);


            if (password_verify(trim($password), $user["password"])) {
                // User found
                $_SESSION["logged"] = true;
                $_SESSION["user_id"] = $user["id"];
                return true;
            } else {
                // User not found
                return false;
            }
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Checks if the user is logged ($_SESSION["logged"] === true)
     * @return TRUE if the user is logged in, FALSE otherwise
     */
    static public function isLogged()
    {
        session_start();
        self::handleSessionTimeout();

        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
            // user is logged
            return true;
        } else return false;
    }

    /**
     * Checks if the user is an admin
     * @return TRUE if the user is an admin, FALSE otherwise
     */
    static public function isAdmin()
    {
        try {
            session_start();

            self::handleSessionTimeout();

            $stm = Database::$pdo->prepare("SELECT user.is_admin FROM user
                                            WHERE user.id = :id");
            $stm->bindParam(':id', $_SESSION["user_id"]);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);
            if ($user["is_admin"])
                return true;
            else return false;
        } catch (\Exception $e) {
            echo $e;
        }
    }

    /**
     * Destroys the session
     */
    static public function logout()
    {
        session_destroy();
        http_response_code(200);
    }


    static public function handleSessionTimeout()
    {
        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > self::SESSION_TIMEOUT) {
            session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
            $_SESSION['CREATED'] = time();  // update creation time
        }
    }
}
