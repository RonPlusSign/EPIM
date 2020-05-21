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

    public function getBrandsCategoriesID($id)
    {
        if ($this->platform === 'category') {

            try {
                $stm = Database::$pdo->prepare("SELECT * FROM category WHERE :id = category.id");
                $stm->bindParam(':id', $id);
                $stm->execute();
                $category = $stm->fetch(PDO::FETCH_ASSOC);
                if ($stm->rowCount() > 0) {
                    return $category;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare("SELECT  * FROM brand WHERE :id = brand.id");
                $stm->bindParam(':id', $id);
                $stm->execute();
                $brand = $stm->fetch(PDO::FETCH_ASSOC);
                if ($stm->rowCount() > 0) {
                    return $brand;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                echo $e;
            }
        }
    }

    public function getBrandsCategories()
    {
        if ($this->platform === 'category') {

            try {
                $stm = Database::$pdo->prepare("SELECT * FROM category");
                $stm->execute();
                $allCategories = $stm->fetchAll(PDO::FETCH_ASSOC);
                if ($stm->rowCount() > 0) {
                    return $allCategories;
                } else {
                    return [];
                }
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare("SELECT * FROM brand");
                $stm->execute();
                $allBrands = $stm->fetchAll(PDO::FETCH_ASSOC);
                if ($stm->rowCount() > 0) {
                    return $allBrands;
                } else {
                    return [];
                }
            } catch (\Exception $e) {
                echo $e;
            }
        }
    }




    public function addRecord($name)
    {

        if ($this->platform === 'category') {

            try {
                $stm = Database::$pdo->prepare("INSERT INTO category (category.name) VALUES (:name)");

                $stm->bindParam(':name', $name);
                $stm->execute();

                if ($stm->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare("INSERT INTO brand (brand.name) VALUES (:name)");

                $stm->bindParam(':name', $name);
                $stm->execute();

                if ($stm->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
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
                if ($stm->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                echo $e;
            }
        } else if ($this->platform === 'brand') {
            try {
                $stm = Database::$pdo->prepare("DELETE FROM brand WHERE brand.id=:id");

                $stm->bindParam(':id', $id);
                $stm->execute();
                if ($stm->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
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
                if ($stm->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
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
                if ($stm->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            } catch (\Exception $e) {
                echo $e;
            }
        }
    }
}
