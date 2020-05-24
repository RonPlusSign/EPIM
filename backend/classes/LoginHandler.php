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
            if (!isset($_SESSION)) session_start();

            $stm = Database::$pdo->prepare("SELECT * FROM user
                                            WHERE user.email = :email");
            $stm->bindParam(':email', $email);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);


            if (password_verify(trim($password), $user["password"])) {
                // User found
                $_SESSION["logged"] = true;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION['timeout'] = time() + self::SESSION_TIMEOUT;
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
     * Checks if the user is logged (and session is not expired)
     * @return TRUE if the user is logged in, FALSE otherwise
     */
    static public function isLogged()
    {
        if (!isset($_SESSION)) session_start();

        if (isset($_SESSION["logged"]) && $_SESSION["logged"] && isset($_SESSION['timeout'])) {
            // Check if the session is expired
            if ($_SESSION['timeout'] < time()) {
                // If session is expired, logout
                self::logout();
                return false;
            }

            // Session is active, resetting timeout
            $_SESSION['timeout'] = time() + self::SESSION_TIMEOUT;

            return true; // User is logged
        } else return false;
    }

    /**
     * Checks if the user is an admin
     * @return TRUE if the user is an admin, FALSE otherwise
     */
    static public function isAdmin()
    {
        try {
            if (!isset($_SESSION)) session_start();

            // Check if the user is logged
            if (self::isLogged()) {
                // Check if the user is admin
                $stm = Database::$pdo->prepare("SELECT user.is_admin FROM user
                                            WHERE user.id = :id");
                $stm->bindParam(':id', $_SESSION["user_id"]);
                $stm->execute();

                $user = $stm->fetch(PDO::FETCH_ASSOC);

                if ($user["is_admin"]) return true;
                else return false;
            } else return false;
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
}
