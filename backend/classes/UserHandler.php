
<?php
/*
    Author: regi18
 */

require_once __DIR__ . '/../lib/Bootstrap.php';
require_once __DIR__ . '/../classes/LoginHandler.php';

class UserHandler
{
    /**
     * Sign-up a new user
     */
    static public function signUp($email, $name, $surname, $phoneNumber, $password)
    {
        try {
            $query = "INSERT INTO user (email, name, surname, phone_number, password)  VALUES (:email, :name, :surname, :phoneNumber, :passwordHash)";

            $stm = Database::$pdo->prepare($query);

            $data = [
                ':email' => trim($email),
                ':name' => trim($name),
                ':surname' => trim($surname),
                ':phoneNumber' => trim($phoneNumber),
                ':passwordHash' => password_hash(trim($password), PASSWORD_DEFAULT)
            ];

            $stm->execute($data);

            return $stm->rowCount();
        } catch (\Exception $e) {
        }
    }


    /**
     * Set isAdmin flag for user
     */
    public static function setAdminFlag($id, $status)
    {
        // Check if logged as admin
        if (LoginHandler::isAdmin()) {
            try {
                $stm = Database::$pdo->prepare("UPDATE user SET is_admin = :status WHERE id = :id");

                $status = +$status;

                $stm->bindParam(":status", $status);
                $stm->bindParam(":id", $id);
                $stm->execute();

                return $stm->rowCount();
            } catch (\Exception $e) {
                echo $e;
            }
        } else {
            return false;
        }
    }


    /**
     * Get all users (only if logged as admin)
     */
    public static function getUsers()

    {
        if (LoginHandler::isAdmin()) {
            return Database::$pdo->query("SELECT id, email, name, surname, phone_number as phoneNumber, is_admin as isAdmin FROM user")->fetchAll(PDO::FETCH_ASSOC);
        } else return false;
    }

    /**
     * Get single user
     */
    public static function getUser($id)
    {
        try {
            $stm = Database::$pdo->prepare("SELECT id, email, name, surname, phone_number as phoneNumber, is_admin as isAdmin from user WHERE id = :id");

            $stm->bindParam(":id", $id);
            $stm->execute();
            return $stm->fetch(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            echo $e;
        }
    }


    /**
     * Edit a user.
     * It will patch every parameter it will receive.
     * Every other parameter will remain unchanged.
     * 
     * @param stdClass $userData An array containing the JSON with the user
     */
    public static function editUser($id, $userData)
    {
        session_start();

        if (LoginHandler::isLogged() && $_SESSION["user_id"] === +$id) {
            try {
                $stm = Database::$pdo->prepare("UPDATE user
                                            SET email            = COALESCE(:email,email)
                                              , name             = COALESCE(:name,name)
                                              , surname          = COALESCE(:surname,surname)
                                              , phone_number     = COALESCE(:phone_number,phone_number)
                                              , password         = COALESCE(:passwordHash,password)
                                            WHERE id = :id");

                $data = [
                    ':email' => $userData->email,
                    ':name' => $userData->name,
                    ':surname' => $userData->surname,
                    ':phone_number' => $userData->phoneNumber,
                    ':passwordHash' => $userData->password === NULL ? NULL : password_hash(trim($userData->password), PASSWORD_DEFAULT),
                    ':id' => +$id
                ];

                $stm->execute($data);
                return $stm->rowCount();
            } catch (\Exception $e) {
                echo $e;
            }
        } else {
            return false;
        }
    }
}
