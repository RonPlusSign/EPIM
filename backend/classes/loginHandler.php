<?php
/*
    Author: daniel
 */

require_once __DIR__ . '/../lib/Bootstrap.php';

class loginHandler
{

    function doLogin()
    {
        $json = json_decode(file_get_contents('php://input'), true);

        $passwordHash = password_hash($json["password"], PASSWORD_BCRYPT);

        try {
            $stm = Database::$pdo->prepare("SELECT * FROM user
                                            WHERE user.email = :email
                                            AND user.password = :userPassword");
            $stm->bindParam(':email', $json["email"]);
            $stm->bindParam(':userPassword', $passwordHash);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // User not found
                http_response_code(403);
            } else {
                // User found
                $_SESSION["logged"] = true;
                $_SESSION["user_id"] = $user["id"];
                http_response_code(204);
            }
        } catch (\Exception $e) {
            echo $e;
        }
    }

    function checkLogin()
    {
        $answer = [
            "logged" => false
        ];

        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
            // user is logged
            $answer["logged"] = true;
            //check if is an admin
            checkAdmin();
        }

        echo json_encode($answer);
    }

    static function checkAdmin()
    {
        $json = json_decode(file_get_contents('php://input'), true);
        $passwordHash = password_hash($json["password"], PASSWORD_BCRYPT);
        try {
            $stm = Database::$pdo->prepare("SELECT user.is_admin FROM user
                                            WHERE user.email = :email
                                            AND user.password = :userPassword");
            $stm->bindParam(':email', $json["email"]);
            $stm->bindParam(':userPassword', $passwordHash);
            $stm->execute();
            $user = $stm->fetch(PDO::FETCH_ASSOC);
            if($user["is_admin"] == true)
            {
                return true
            }
    
        } catch (\Exception $e) {
            echo $e;
        }
        return false
    }

    function logout()
    {
        session_destroy();
        http_response_code(200);
    }
}