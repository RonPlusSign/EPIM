
<?php
/*
    Author: RonPlusSign (Andrea Delli)
 */

require_once __DIR__ . '/../lib/Bootstrap.php';
require_once __DIR__ . '/../classes/LoginHandler.php';
require_once __DIR__ . '/../classes/ProductsHandler.php';

class AddressHandler
{
    /**
     * Returns a list of all the regions
     */
    public static function getRegions()
    {
        $stm = Database::$pdo->query("SELECT * FROM region ORDER BY name");

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns an object representing a specific region
     */
    public static function getRegion($id)
    {
        $stm = Database::$pdo->prepare("SELECT * FROM region WHERE id = :id");
        $stm->bindParam(":id", $id);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a list of all the provinces
     */
    public static function getProvinces()
    {
        $stm = Database::$pdo->query("SELECT * FROM province ORDER BY name");

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Returns a list of provinces of a specific region
     */
    public static function getProvincesByRegion($regionId)
    {
        $stm = Database::$pdo->prepare("SELECT id, name FROM province WHERE region = :regionId ORDER BY name");
        $stm->bindParam(":regionId", $regionId);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns an object representing a specific province
     */
    public static function getProvince($id)
    {
        $stm = Database::$pdo->prepare("SELECT * FROM province WHERE id = :id");
        $stm->bindParam(":id", $id);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a list of all the cities
     */
    public static function getCities()
    {
        $stm = Database::$pdo->query("SELECT * FROM city ORDER BY name");

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns a list of cities of a specific province
     */
    public static function getCitiesByProvince($provinceId)
    {
        $stm = Database::$pdo->prepare("SELECT id, name FROM city WHERE province = :provinceId ORDER BY name");
        $stm->bindParam(":provinceId", $provinceId);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns an object representing a specific city
     */
    public static function getCity($id)
    {
        $stm = Database::$pdo->prepare("SELECT * FROM city WHERE id = :id");
        $stm->bindParam(":id", $id);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }
}
