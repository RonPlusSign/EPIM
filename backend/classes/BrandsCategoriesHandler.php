<?php

/* 
    author DRAKExD
*/

require_once __DIR__ . '/../lib/Bootstrap.php';


class BrandsCategoriesHandler
{
    private $platform;  // "brand" or "category"

    public function __construct()
    {
        $this->platform = NULL;
    }

    public function setPlatform($platform)
    {
        $this->platform = $platform;
    }

    public function addRecord($name)
    {

        if ($this->platform === 'category') {

            try {
                $stm = Database::$pdo->prepare("INSERT INTO category (category.name) VALUES (:name)");

                $stm->bindParam(':name', $name);
                $stm->execute();
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare("INSERT INTO brand (brand.name) VALUES (:name)");

                $stm->bindParam(':name', $name);
                $stm->execute();
            } catch (\Exception $e) {
                echo $e;
            }
        }
    }

    public function deleteRecord($id)
    {

        if ($this->platform === 'category') {
            try {
                $stm = Database::$pdo->prepare("DELETE FROM category WHERE category.id=:id");

                $stm->bindParam(':id', $id);
                $stm->execute();
                http_response_code(200);
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare("DELETE FROM brand WHERE brand.id=:id");

                $stm->bindParam(':id', $id);
                $stm->execute();
                http_response_code(200);
            } catch (\Exception $e) {
                echo $e;
            }
        }
    }

    public function modifyRecord($id, $name)
    {

        if ($this->platform === 'category') {
            try {
                $stm = Database::$pdo->prepare(
                    "UPDATE category
                        SET category.name = :name
                        WHERE category.id=:id"
                );
                $stm->bindParam(':name', $name);
                $stm->bindParam(':id', $id);
                $stm->execute();
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare(
                    "UPDATE brand
                        SET brand.name = :name
                        WHERE brand.id=:id"
                );
                $stm->bindParam(':name', $name);
                $stm->bindParam(':id', $id);
                $stm->execute();
            } catch (\Exception $e) {
                echo $e;
            }
        }
    }
}
