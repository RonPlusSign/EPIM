
<?php
/*
    Author: regi18
 */

require_once __DIR__ . '/../lib/Bootstrap.php';
require_once __DIR__ . '/../classes/LoginHandler.php';

class UserHandler
{
    // ------------------------------------------------------- //
    //                        User methods                     //
    // ------------------------------------------------------- //

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
    public static function editUser($userData)
    {
        // session_start();

        if (LoginHandler::isLogged()) {
            $id = $_SESSION["user_id"];
            try {
                $stm = Database::$pdo->prepare("UPDATE user
                                            SET email            = COALESCE(:email,email)
                                              , name             = COALESCE(:name,name)
                                              , surname          = COALESCE(:surname,surname)
                                              , phone_number     = COALESCE(:phone_number,phone_number)
                                              , password         = COALESCE(:passwordHash,password)
                                            WHERE id = :id");

                $data = [
                    ':email' => isset($userData->email) ? $userData->email : NULL,
                    ':name' => isset($userData->name) ? $userData->name : NULL,
                    ':surname' => isset($userData->surname) ? $userData->surname : NULL,
                    ':phone_number' => isset($userData->phoneNumber) ? $userData->phoneNumber : NULL,
                    ':passwordHash' => isset($userData->password) ? password_hash(trim($userData->password), PASSWORD_DEFAULT) : NULL,
                    ':id' => +$id
                ];

                $stm->execute($data);
                return $stm->rowCount();
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    // ---------------------------------------------------------- //
    //                        Address methods                     //
    // ---------------------------------------------------------- //

    /**
     * Adds an address to the address table (if not present) and to the user's address list
     */
    public static function addAddress($city, $street, $houseNumber, $postalCode, $phoneNumber)
    {
        if (LoginHandler::isLogged()) {
            $userId = $_SESSION["user_id"];
            try {
                // Check if the address is already present in the database
                $stm = Database::$pdo->prepare("SELECT id FROM address
                                            WHERE street = :street
                                            AND city = :city
                                            AND house_number = :houseNumber
                                            AND postal_code = :postalCode
                                            ");

                $data = [
                    "street" => $street,
                    "city" => $city,
                    "houseNumber" => $houseNumber,
                    "postalCode" => $postalCode
                ];

                $stm->execute($data);
                // If the query returns some value, the address is already into the database
                if ($stm->rowCount() > 0) {
                    // Add the address into user_address table
                    $addressId = $stm->fetch(PDO::FETCH_ASSOC);

                    $query = "INSERT INTO user_address (address, user, phone_number)  VALUES (:addressId, :userId, :phoneNumber)";

                    $data = [
                        "addressId" => $addressId,
                        "userId" => $userId,
                        "phoneNumber" => $phoneNumber
                    ];

                    $stm = Database::$pdo->prepare($query);

                    $stm->execute($data);

                    return $stm->rowCount();
                }
                // Add the address into the address table, then to the address list
                else {
                    // Check if the address is already present in the database
                    $stm = Database::$pdo->prepare("INSERT INTO address(street, city, house_number, postal_code)
                        VALUES (:street, :city, :houseNumber, :postalCode)");

                    $data = [
                        "street" => $street,
                        "city" => $city,
                        "houseNumber" => $houseNumber,
                        "postalCode" => $postalCode
                    ];

                    $stm->execute($data);

                    // Check if the adding was done correctly
                    if ($stm->rowCount() > 0) {
                        // Insert the address to the user's addresses

                        $addressId = Database::$pdo->lastInsertId();

                        $query = "INSERT INTO user_address (address, user, phone_number)  VALUES (:addressId, :userId, :phoneNumber)";

                        $data = [
                            "addressId" => $addressId,
                            "userId" => $userId,
                            "phoneNumber" => $phoneNumber
                        ];

                        $stm = Database::$pdo->prepare($query);

                        $stm->execute($data);

                        return $stm->rowCount();
                    } else return false;
                }
            } catch (\Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Adds an address to the address table (if not present) and to the user's address list
     */
    public static function getAddresses()
    {
        $addresses = [];

        if (LoginHandler::isLogged()) {
            $userId = $_SESSION["user_id"];

            $stm = Database::$pdo->prepare("SELECT
                    address.id,
                    address.city,
                    city.name AS cityName,
                    street,
                    house_number AS houseNumber,
                    postal_code AS postalCode,
                    phone_number AS phoneNumber
                FROM address
                INNER JOIN user_address
                    ON address.id = user_address.address
                INNER JOIN city
                    ON city.id = address.city
                WHERE user_address.user = :userId");

            $stm->bindParam(":userId", $userId);
            $stm->execute();

            $addresses = $stm->fetchAll(PDO::FETCH_ASSOC);

            if (empty($addresses)) return [];
            else return $addresses;
        } else return false;
    }

    public static function removeAddress($addressId)
    {
        if (LoginHandler::isLogged()) {
            $userId = $_SESSION["user_id"];


            $stm = Database::$pdo->prepare("DELETE FROM user_address 
                WHERE
                address = :addressId AND
                user = :userId");

            $data = [
                "addressId" => $addressId,
                "userId" => $userId,
            ];

            $stm->execute($data);

            return $stm->rowCount();
        } else return false;
    }
}
