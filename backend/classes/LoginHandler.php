<?php
/*
    Author: daniel
 */

require_once __DIR__ . '/../lib/Bootstrap.php';

class LoginHandler
{

    /**
     * Checks if the user exists and sets the $_SESSION["logged"] & $_SESSION["user_id"]
     * @return TRUE if the login was successful, FALSE otherwise
     */
    function doLogin($email, $password)
    {

        try {
            $stm = Database::$pdo->prepare("SELECT * FROM user
                                            WHERE user.email = :email");
            $stm->bindParam(':email', $email);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode($user);

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
    static function checkLogin()
    {
        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
            // user is logged
            return true;
        }
    }

    /**
     * Checks if the user is an admin
     * @return TRUE if the user is an admin, FALSE otherwise
     */
    static function checkAdmin()
    {
        try {
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
    static function logout()
    {
        session_destroy();
        http_response_code(200);
    }
}
