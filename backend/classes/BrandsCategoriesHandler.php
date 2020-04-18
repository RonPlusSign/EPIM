<?php

/* 
    author DRAKExD
*/

require_once __DIR__ . '/../lib/Bootstrap.php';


class BrandsCategories
{
    private $extraFilter;
    private $extraFilterString;

    public function __construct(){
        $this->extraFilter = NULL;

    }

    public function setExtraFilter($filter, $filterString)   
    {         $this->extraFilter = $filter; 
              $this->extraFilterString = $filterString;   
    }

    public function add_record($name){

        if ($this->extraFilter == 'category') {
            $query = "INSERT INTO category (category.name)
                            VALUES ('$name')";


        } else if ($this->extraFilter == 'brand') {
            $query = "INSERT INTO brand (brand.name)
                        VALUES ('$name') ";
        }
    }

    public function delete_record($id){

        if ($this->extraFilter == 'category') {
            $query = "DELETE FROM category WHERE category.id="$id"";

        } else if ($this->extraFilter == 'brand') {
            $query = "DELETE FROM brand WHERE brand.id="$id"";
        }
    }

    public function modify_record($id, $name){

        if ($this->extraFilter == 'category') {
            $query = "UPDATE category
                    SET category.name = '$name'
                    WHERE category.id='$id'";

        } else if ($this->extraFilter == 'brand') {
            $query = "UPDATE brand 
                    SET brand.name = '$name'
                    WHERE brand.id = '$id'";
        }
    }
}