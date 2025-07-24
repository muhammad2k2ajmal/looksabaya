<?php
    // class Categories
    // {
    //     private $ID;
    //     private $CatId;
    //     private $subCatId;
    //     private $subSubCategoryId;
    //     private $Name;
    //     private $Files;
    //     private $Status;
    //     private $Table;
    //     private $conndb;
        
    //     function checkCategories($Name, $Table)
    //     {  
    //         $conn = new dbClass;
    //         $this->Name = $Name;
    //         $this->Table = $Table;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getRowCount("SELECT * FROM $Table WHERE `name` = '$Name'");
    //         return $stmt;
    //     }
        
    //     function addCategories($Name, $Files,$new, $Status)
    //     {  
    //         $conn = new dbClass;
    //         $this->Name = $Name;
    //         $this->Files = $Files;
    //         $this->Status = $Status;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->execute("INSERT INTO `category`(`name`, `image`, `status`, `new`) VALUES ('$Name', '$Files', '$Status', '$new')");
    //         return $stmt;
    //     }
        
    //     function updateCategories($Name, $Files,$new, $Status, $ID)
    //     {  
    //         $conn = new dbClass;
    //         $this->ID = $ID;
    //         $this->Name = $Name;
    //         $this->Files = $Files;
    //         $this->Status = $Status;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->execute("UPDATE `category` SET `name` = '$Name', `image` = '$Files', `new` = '$new', `status` = '$Status', `updated_at` = now() WHERE `id` = '$ID'");
    //         return $stmt;
    //     }
    
    //     function getAllCategories() 
    //     {  
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getAllData("SELECT * FROM `category` ORDER BY `id` DESC");
    //         return $stmt;
    //     }
    
    //     function getCategories($ID) 
    //     {  
    //         $conn = new dbClass;
    //         $this->ID = $ID;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getData("SELECT * FROM `category` WHERE `id` = '$ID'");
    //         return $stmt;
    //     }
    
    //     function allCategories() 
    //     {  
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getAllData("SELECT * FROM `category` WHERE `status` = '1' ORDER BY `id` ASC");
    //         return $stmt;
    //     }
    
    //     function checkSubCategories($Name, $CatId)
    //     {  
    //         $conn = new dbClass;
    //         $this->Name = $Name;
    //         $this->CatId = $CatId;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getRowCount("SELECT * FROM `sub_category` WHERE `name` = '$Name' AND `category_id` = '$CatId'");
    //         return $stmt;
    //     }
    
    //     function addSubCategories($CatId, $Name, $Files,$new, $Status)
    //     {  
    //         $conn = new dbClass;
    //         $this->CatId = $CatId;
    //         $this->Name = $Name;
    //         $this->Files = $Files;
    //         $this->Status = $Status;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->execute("INSERT INTO `sub_category`(`category_id`, `name`, `image`, `status`,`new`) VALUES ('$CatId', '$Name', '$Files', '$Status','$new')");
    //         return $stmt;
    //     }
        
    //     function updateSubCategories($CatId, $Name, $Files,$new, $Status, $ID)
    //     {  
    //         $conn = new dbClass;
    //         $this->ID = $ID;
    //         $this->CatId = $CatId;
    //         $this->Name = $Name;
    //         $this->Files = $Files;
    //         $this->Status = $Status;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->execute("UPDATE `sub_category` SET `category_id` = '$CatId', `name` = '$Name', `image` = '$Files', `new` = '$new', `status` = '$Status', `updated_at` = now() WHERE `id` = '$ID'");
    //         return $stmt;
    //     }
    
    //     function getAllSubCategories() 
    //     {  
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getAllData("SELECT * FROM `sub_category` ORDER BY `id` DESC");
    //         return $stmt;
    //     }
    
    //     function getSubCategories($ID) 
    //     {  
    //         $conn = new dbClass;
    //         $this->ID = $ID;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getData("SELECT * FROM `sub_category` WHERE `id` = '$ID'");
    //         return $stmt;
    //     }
    
       
    
    //     function getCatgoriescount($CatId)
    //     {
    //         $conn = new dbClass;
    //         $this->CatId = $CatId;
    //         $this->conndb = $conn;
    //         $rowCount = $conn->getRowCount(
    //             "SELECT p.*, c.id  
    //                 FROM product p 
    //                 JOIN category c ON p.category_id = c.id
    //                 WHERE p.status = 1 AND c.id = '" . $CatId . "'"
    //         );
    //         return $rowCount;
    //     }
    
    //     function getSubCatgoriescount($CatId, $subCatId)
    //     {
    //         $conn = new dbClass;
    //         $this->CatId = $CatId;
    //         $this->subCatId = $subCatId;
    //         $this->conndb = $conn;
    //         $rowCount = $conn->getRowCount(
    //             "SELECT p.*, c.id, sc.id 
    //                     FROM product p 
    //                     JOIN category c ON p.category_id = c.id
    //                     JOIN sub_category sc ON p.subcategory_id = sc.id
    //                     WHERE p.status = 1 AND c.id = '" . $CatId . "' AND sc.id = '" . $subCatId . "'"
    //         );
    //         return $rowCount;
    //     }
    
    //     function checkSubSubCategories($Name, $CatId, $subCatId)
    //     {  
    //         $conn = new dbClass;
    //         $this->Name = $Name;
    //         $this->CatId = $CatId;
    //         $this->subCatId = $subCatId;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getRowCount("SELECT * FROM `sub_sub_category` WHERE `name` = '$Name' AND `category_id` = '$CatId' AND `sub_category_id` = '$subCatId'");
    //         return $stmt;
    //     }
    
    //     function addSubSubCategories($CatId, $subCatId, $Name, $Files, $new, $Status)
    //     {  
    //         $conn = new dbClass;
    //         $this->CatId = $CatId;
    //         $this->subCatId = $subCatId;
    //         $this->Name = $Name;
    //         $this->Files = $Files;
    //         $this->Status = $Status;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->execute("INSERT INTO `sub_sub_category`(`category_id`, `sub_category_id`, `name`, `image`, `status`, `new`) VALUES ('$CatId', '$subCatId', '$Name', '$Files', '$Status','$new')");
    //         return $stmt;
    //     }
    
    //     function updateSubSubCategories($CatId, $subCatId, $Name, $Files, $new, $Status, $ID)
    //     {  
    //         $conn = new dbClass;
    //         $this->ID = $ID;
    //         $this->CatId = $CatId;
    //         $this->subCatId = $subCatId;
    //         $this->Name = $Name;
    //         $this->Files = $Files;
    //         $this->Status = $Status;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->execute("UPDATE `sub_sub_category` SET `category_id` = '$CatId', `sub_category_id` = '$subCatId', `name` = '$Name', `image` = '$Files', `new` = '$new', `status` = '$Status', `updated_at` = now() WHERE `id` = '$ID'");
    //         return $stmt;
    //     }
    
    //     function getAllSubSubCategories()
    //     {  
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getAllData("SELECT * FROM `sub_sub_category` ORDER BY `id` DESC");
    //         return $stmt;
    //     }
    
    //     function getSubSubCategories($ID)
    //     {  
    //         $conn = new dbClass;
    //         $this->ID = $ID;
    //         $this->conndb = $conn;
        
    //         $stmt = $conn->getData("SELECT * FROM `sub_sub_category` WHERE `id` = '$ID'");
    //         return $stmt;
    //     }
    
      
    //     function getCatgoriesArray($categoryIds) {
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
    //         // Ensure categoryIds is a non-empty array
    //         $ids = array_filter((array)$categoryIds, 'is_numeric');
    //         if (empty($ids)) {
    //             return [];
    //         }
    //         $idsString = implode(',', array_map('intval', $ids));
    //         return $this->conndb->getAllData("SELECT id, name FROM category WHERE id IN ($idsString)");
    //     }
    
    //     function getSubCatgoriesArray($subcategoryIds) {
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
    //         // Ensure subcategoryIds is a non-empty array
    //         $ids = array_filter((array)$subcategoryIds, 'is_numeric');
    //         if (empty($ids)) {
    //             return [];
    //         }
    //         $idsString = implode(',', array_map('intval', $ids));
    //         return $this->conndb->getAllData("SELECT id, name FROM sub_category WHERE id IN ($idsString)");
    //     }
    
    //     function getSubSubCategoriesArray($subcategoryIds) {
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
    //         // Ensure subcategoryIds is a non-empty array
    //         $ids = array_filter((array)$subcategoryIds, 'is_numeric');
    //         if (empty($ids)) {
    //             return [];
    //         }
    //         $idsString = implode(',', array_map('intval', $ids));
    //         return $this->conndb->getAllData("SELECT id, name FROM sub_sub_category WHERE id IN ($idsString)");
    //     }
    //     function getSubCatgoriesDropdown($categoryIds) {
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
    //         $ids = implode(',', array_map('intval', (array)$categoryIds));
    //         return $this->conndb->getAllData("SELECT id, name,category_id FROM sub_category WHERE category_id IN ($ids) AND status = 1");
    //     }
        
    //     function getSubSubCategoriesDropdown($categoryIds, $subcategoryIds) {
    //         $conn = new dbClass;
    //         $this->conndb = $conn;
    //         $subIds = implode(',', array_map('intval', (array)$subcategoryIds));
    //         return $this->conndb->getAllData("SELECT id, name ,sub_category_id,category_id FROM sub_sub_category WHERE sub_category_id IN ($subIds) AND status = 1");
    //     }
    // }
    class Categories
{
    private $ID;
    private $Name;
    private $Files;
    private $Status;
    private $Table;
    private $conndb;
    
    function checkCategories($Name, $Table)
    {  
        $conn = new dbClass;
        $this->Name = $Name;
        $this->Table = $Table;
        $this->conndb = $conn;
        
        $stmt = $conn->getRowCount("SELECT * FROM $Table WHERE `name` = '$Name'");
        return $stmt;
    }
    
    function addCategories($Name, $Files, $Status)
    {  
        $conn = new dbClass;
        $this->Name = $Name;
        $this->Files = $Files;
        $this->Status = $Status;
        $this->conndb = $conn;
        
        $stmt = $conn->execute("INSERT INTO `category`(`name`, `image`, `status`) VALUES ('$Name', '$Files', '$Status')");
        return $stmt;
    }
    
    function updateCategories($Name, $Files, $Status, $ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Files = $Files;
        $this->Status = $Status;
        $this->conndb = $conn;
        
        $stmt = $conn->execute("UPDATE `category` SET `name` = '$Name', `image` = '$Files', `status` = '$Status', `updated_at` = now() WHERE `id` = '$ID'");
        return $stmt;
    }
    
    function getAllCategories() 
    {  
        $conn = new dbClass;
        $this->conndb = $conn;
        
        $stmt = $conn->getAllData("SELECT * FROM `category` ORDER BY `id` DESC");
        return $stmt;
    }
    
    function getCategories($ID) 
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;
        
        $stmt = $conn->getData("SELECT * FROM `category` WHERE `id` = '$ID'");
        return $stmt;
    }
    
    function allCategories() 
    {  
        $conn = new dbClass;
        $this->conndb = $conn;
        
        $stmt = $conn->getAllData("SELECT * FROM `category` WHERE `status` = '1' ORDER BY `id` ASC");
        return $stmt;
    }
    
    function getCategoriesArray($categoryIds) {
        $conn = new dbClass;
        $this->conndb = $conn;
        $ids = array_filter((array)$categoryIds, 'is_numeric');
        if (empty($ids)) {
            return [];
        }
        $idsString = implode(',', array_map('intval', $ids));
        return $this->conndb->getAllData("SELECT id, name FROM category WHERE id IN ($idsString)");
    }
}
class Products
{
    private $Id;
    private $CategoryId;
    private $Name;
    private $Price;
    private $Discount;
    private $Stock;
    private $Sizes;
    private $Description;
    private $Lists;
    private $Trending;
    private $NewArrivals;
    private $BestSelling;
    private $Colors;
    private $ColorImages;
    private $Status;
    private $conndb;

    function addProducts($CategoryId, $Name, $Price, $Discount, $Stock, $Sizes, $Description, $Lists, $Trending, $NewArrivals, $BestSelling, $Status, $Colors, $ColorImages)
    {
        $conn = new dbClass;
        $this->CategoryId = $CategoryId;
        $this->Name = $Name;
        $this->Price = $Price;
        $this->Discount = $Discount;
        $this->Stock = $Stock;
        $this->Sizes = is_array($Sizes) ? $Sizes : [$Sizes];
        $this->Description = $Description;
        $this->Lists = is_array($Lists) ? $Lists : [$Lists];
        $this->Trending = $Trending;
        $this->NewArrivals = $NewArrivals;
        $this->BestSelling = $BestSelling;
        $this->Colors = is_array($Colors) ? $Colors : [$Colors];
        $this->ColorImages = $ColorImages;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("INSERT INTO `product` (`category_id`, `name`, `price`, `discount`, `stock`, `description`, `trending`, `new_arrivals`, `best_selling`, `status`) VALUES ('$CategoryId', '$Name', '$Price', '$Discount', '$Stock', '$Description', '$Trending', '$NewArrivals', '$BestSelling', '$this->Status')");
        $productId = $conn->lastInsertId();

        foreach ($this->Sizes as $size) {
            if ($size) {
                $conn->execute("INSERT INTO `product_sizes` (`product_id`, `size`) VALUES ('$productId', '$size')");
            }
        }

        foreach ($this->Lists as $list) {
            if ($list) {
                $conn->execute("INSERT INTO `product_lists` (`product_id`, `list_item`) VALUES ('$productId', '$list')");
            }
        }

        foreach ($this->Colors as $colorId) {
            if ($colorId) {
                $conn->execute("INSERT INTO `product_colors` (`product_id`, `color_id`) VALUES ('$productId', '$colorId')");
                foreach ($this->ColorImages[$colorId] as $image) {
                    if ($image) {
                        $conn->execute("INSERT INTO `product_images` (`product_id`, `color_id`, `image`) VALUES ('$productId', '$colorId', '$image')");
                    }
                }
            }
        }

        return $productId;
    }

    function updateProducts($Id, $CategoryId, $Name, $Price, $Discount, $Stock, $Sizes, $Description, $Lists, $Trending, $NewArrivals, $BestSelling, $Status, $Colors, $ColorImages)
    {
        $conn = new dbClass;
        $this->Id = $Id;
        $this->CategoryId = $CategoryId;
        $this->Name = $Name;
        $this->Price = $Price;
        $this->Discount = $Discount;
        $this->Stock = $Stock;
        $this->Sizes = is_array($Sizes) ? $Sizes : [$Sizes];
        $this->Description = $Description;
        $this->Lists = is_array($Lists) ? $Lists : [$Lists];
        $this->Trending = $Trending;
        $this->NewArrivals = $NewArrivals;
        $this->BestSelling = $BestSelling;
        $this->Colors = is_array($Colors) ? $Colors : [$Colors];
        $this->ColorImages = $ColorImages;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("UPDATE `product` SET `category_id`='$CategoryId', `name`='$Name', `price`='$Price', `discount`='$Discount', `stock`='$Stock', `description`='$Description', `trending`='$Trending', `new_arrivals`='$NewArrivals', `best_selling`='$BestSelling', `status`='$this->Status', `updated_at`=NOW() WHERE `product_id`='$Id'");

        $conn->execute("DELETE FROM `product_sizes` WHERE `product_id`='$Id'");
        $conn->execute("DELETE FROM `product_lists` WHERE `product_id`='$Id'");
        $conn->execute("DELETE FROM `product_colors` WHERE `product_id`='$Id'");
        $conn->execute("DELETE FROM `product_images` WHERE `product_id`='$Id'");

        foreach ($this->Sizes as $size) {
            if ($size) {
                $conn->execute("INSERT INTO `product_sizes` (`product_id`, `size`) VALUES ('$Id', '$size')");
            }
        }

        foreach ($this->Lists as $list) {
            if ($list) {
                $conn->execute("INSERT INTO `product_lists` (`product_id`, `list_item`) VALUES ('$Id', '$list')");
            }
        }

        foreach ($this->Colors as $colorId) {
            if ($colorId) {
                $conn->execute("INSERT INTO `product_colors` (`product_id`, `color_id`) VALUES ('$Id', '$colorId')");
                foreach ($this->ColorImages[$colorId] as $image) {
                    if ($image) {
                        $conn->execute("INSERT INTO `product_images` (`product_id`, `color_id`, `image`) VALUES ('$Id', '$colorId', '$image')");
                    }
                }
            }
        }

        return $stmt;
    }

    function getProducts($Id)
    {
        $conn = new dbClass;
        $this->Id = $Id;
        $this->conndb = $conn;

        $product = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$Id'");
        $sizes = $conn->getAllData("SELECT size FROM `product_sizes` WHERE `product_id` = '$Id'");
        $product['sizes'] = array_column($sizes, 'size');
        $lists = $conn->getAllData("SELECT list_item FROM `product_lists` WHERE `product_id` = '$Id'");
        $product['lists'] = array_column($lists, 'list_item');
        $colors = $conn->getAllData("SELECT color_id FROM `product_colors` WHERE `product_id` = '$Id'");
        $product['colors'] = array_column($colors, 'color_id');

        return $product;
    }

    function allProducts()
    {
        $conn = new dbClass;
        $this->conndb = $conn;

        $products = $conn->getAllData("SELECT * FROM `product` ORDER BY `product_id` DESC");

        foreach ($products as &$product) {
            $productId = $product['product_id'];
            $sizes = $conn->getAllData("SELECT size FROM `product_sizes` WHERE `product_id` = '$productId'");
            $product['sizes'] = array_column($sizes, 'size');
            $lists = $conn->getAllData("SELECT list_item FROM `product_lists` WHERE `product_id` = '$productId'");
            $product['lists'] = array_column($lists, 'list_item');
            $colors = $conn->getAllData("SELECT color_id FROM `product_colors` WHERE `product_id` = '$productId'");
            $product['colors'] = array_column($colors, 'color_id');
        }

        return $products;
    }

    function getProductImages($Id)
    {
        $conn = new dbClass;
        $this->Id = $Id;
        $this->conndb = $conn;

        return $conn->getAllData("SELECT * FROM `product_images` WHERE `product_id` = '$Id'");
    }

    function getProductImagesByColor($Id, $ColorId)
    {
        $conn = new dbClass;
        $this->Id = $Id;
        $this->conndb = $conn;

        return $conn->getAllData("SELECT * FROM `product_images` WHERE `product_id` = '$Id' AND `color_id` = '$ColorId'");
    }

    function getallColor()
    {
        $conn = new dbClass;
        $this->conndb = $conn;

        return $conn->getAllData("SELECT * FROM `color` WHERE `status` = 1 ORDER BY `id` DESC");
    }
}
    
// class Products
// {
//     private $Id;
//     private $CategoryIds; // Array of category IDs
//     private $SubCategoryIds; // Array of subcategory IDs
//     private $SubSubCategoryIds; // Array of sub-subcategory IDs
//     private $Image;
//     private $Name;
//     private $Price;
//     private $Discount;
//     private $Stock;
//     private $Sku;
//     private $PkgWeight;
//     private $PkgLength;
//     private $PkgWidth;
//     private $PkgHeight;
//     private $FrameMaterials; // Changed to array
//     private $Colors;
//     private $ShortDesc;
//     private $Description;
//     private $Description1;
//     private $Information;
//     private $Bestsellers;
//     private $Newarrivals;
//     private $Featured;
//     private $SpecialDiscount;
//     private $HomeVisibility;
//     private $Status;
//     private $conndb;

//     function addProducts($CategoryIds, $SubCategoryIds, $SubSubCategoryIds, $Image, $Name, $Price, $Discount, $Stock, $Sku, $PkgWeight, $PkgLength, $PkgWidth, $PkgHeight, $FrameMaterials, $Colors, $ShortDesc, $Description, $Description1, $Information, $Bestsellers, $Newarrivals, $Featured, $SpecialDiscount, $HomeVisibility, $Status)
//     {  
//         $conn = new dbClass;
//         $this->CategoryIds = is_array($CategoryIds) ? $CategoryIds : [$CategoryIds];
//         $this->SubCategoryIds = is_array($SubCategoryIds) ? $SubCategoryIds : [$SubCategoryIds];
//         $this->SubSubCategoryIds = is_array($SubSubCategoryIds) ? $SubSubCategoryIds : [$SubSubCategoryIds];
//         $this->Image = $Image;
//         $this->Name = $Name;
//         $this->Price = $Price;
//         $this->Discount = $Discount;
//         $this->Stock = $Stock;
//         $this->Sku = $Sku;
//         $this->PkgWeight = $PkgWeight;
//         $this->PkgLength = $PkgLength;
//         $this->PkgWidth = $PkgWidth;
//         $this->PkgHeight = $PkgHeight;
//         $this->FrameMaterials = is_array($FrameMaterials) ? $FrameMaterials : [$FrameMaterials];
//         $this->Colors = is_array($Colors) ? $Colors : [$Colors];
//         $this->ShortDesc = $ShortDesc;
//         $this->Description = $Description;
//         $this->Description1 = $Description1;
//         $this->Information = $Information;
//         $this->Bestsellers = $Bestsellers;
//         $this->Newarrivals = $Newarrivals;
//         $this->Featured = $Featured;
//         $this->SpecialDiscount = $SpecialDiscount;
//         $this->HomeVisibility = $HomeVisibility;
//         $this->Status = $Status;
//         $this->conndb = $conn;

//         // Insert into product table
//         $stmt = $conn->execute("INSERT INTO `product` (`image`, `name`, `price`, `discount`, `stock`, `sku`, `pkgweight`, `pkglength`, `pkgwidth`, `pkgheight`, `short_description`, `description`, `description1`, `information`, `best_sellers`, `new_arrivals`, `featured`, `special_discount`, `home_visibility`, `status`) VALUES ('$Image', '$Name', '$Price', '$Discount', '$Stock', '$Sku', '$PkgWeight', '$PkgLength', '$PkgWidth', '$PkgHeight', '$ShortDesc', '$Description', '$Description1', '$Information', '$Bestsellers', '$Newarrivals', '$Featured', '$SpecialDiscount', '$HomeVisibility', '$Status')");
        
//         $productId = $conn->lastInsertId();

//         // Insert into junction tables for categories
//         foreach ($this->CategoryIds as $categoryId) {
//             if ($categoryId) {
//                 $conn->execute("INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES ('$productId', '$categoryId')");
//             }
//         }
//         foreach ($this->SubCategoryIds as $subcategoryId) {
//             if ($subcategoryId) {
//                 $conn->execute("INSERT INTO `product_subcategories` (`product_id`, `subcategory_id`) VALUES ('$productId', '$subcategoryId')");
//             }
//         }
//         foreach ($this->SubSubCategoryIds as $subsubcategoryId) {
//             if ($subsubcategoryId) {
//                 $conn->execute("INSERT INTO `product_subsubcategories` (`product_id`, `subsubcategory_id`) VALUES ('$productId', '$subsubcategoryId')");
//             }
//         }

//         // Insert into product_materials table
//         foreach ($this->FrameMaterials as $material) {
//             if ($material) {
//                 $conn->execute("INSERT INTO `product_materials` (`product_id`, `material`) VALUES ('$productId', '$material')");
//             }
//         }

//         // Insert into product_colors table
//         foreach ($this->Colors as $color) {
//             if ($color) {
//                 $conn->execute("INSERT INTO `product_colors` (`product_id`, `color`) VALUES ('$productId', '$color')");
//             }
//         }

//         return $productId;
//     }

//     function updateProducts($CategoryIds, $SubCategoryIds, $SubSubCategoryIds, $Image, $Name, $Price, $Discount, $Stock, $Sku, $PkgWeight, $PkgLength, $PkgWidth, $PkgHeight, $FrameMaterials, $Colors, $ShortDesc, $Description, $Description1, $Information, $Bestsellers, $Newarrivals, $Featured, $SpecialDiscount, $HomeVisibility, $Status, $Id)
//     {  
//         $conn = new dbClass;
//         $this->Id = $Id;
//         $this->CategoryIds = is_array($CategoryIds) ? $CategoryIds : [$CategoryIds];
//         $this->SubCategoryIds = is_array($SubCategoryIds) ? $SubCategoryIds : [$SubCategoryIds];
//         $this->SubSubCategoryIds = is_array($SubSubCategoryIds) ? $SubSubCategoryIds : [$SubSubCategoryIds];
//         $this->Image = $Image;
//         $this->Name = $Name;
//         $this->Price = $Price;
//         $this->Discount = $Discount;
//         $this->Stock = $Stock;
//         $this->Sku = $Sku;
//         $this->PkgWeight = $PkgWeight;
//         $this->PkgLength = $PkgLength;
//         $this->PkgWidth = $PkgWidth;
//         $this->PkgHeight = $PkgHeight;
//         $this->FrameMaterials = is_array($FrameMaterials) ? $FrameMaterials : [$FrameMaterials];
//         $this->Colors = is_array($Colors) ? $Colors : [$Colors];
//         $this->ShortDesc = $ShortDesc;
//         $this->Description = $Description;
//         $this->Description1 = $Description1;
//         $this->Information = $Information;
//         $this->Bestsellers = $Bestsellers;
//         $this->Newarrivals = $Newarrivals;
//         $this->Featured = $Featured;
//         $this->SpecialDiscount = $SpecialDiscount;
//         $this->HomeVisibility = $HomeVisibility;
//         $this->Status = $Status;
//         $this->conndb = $conn;

//         // Update product table
//         $stmt = $conn->execute("UPDATE `product` SET `image`='$Image', `name`='$Name', `price`='$Price', `discount`='$Discount', `stock`='$Stock', `sku`='$Sku', `pkgweight`='$PkgWeight', `pkglength`='$PkgLength', `pkgwidth`='$PkgWidth', `pkgheight`='$PkgHeight', `short_description`='$ShortDesc', `description`='$Description', `description1`='$Description1', `information`='$Information', `best_sellers`='$Bestsellers', `new_arrivals`='$Newarrivals', `featured`='$Featured', `special_discount`='$SpecialDiscount', `home_visibility`='$HomeVisibility', `status`='$Status', `updated_at`=NOW() WHERE `product_id`='$Id'");

//         // Delete existing associations
//         $conn->execute("DELETE FROM `product_categories` WHERE `product_id`='$Id'");
//         $conn->execute("DELETE FROM `product_subcategories` WHERE `product_id`='$Id'");
//         $conn->execute("DELETE FROM `product_subsubcategories` WHERE `product_id`='$Id'");
//         $conn->execute("DELETE FROM `product_materials` WHERE `product_id`='$Id'");
//         $conn->execute("DELETE FROM `product_colors` WHERE `product_id`='$Id'");

//         // Insert new associations
//         foreach ($this->CategoryIds as $categoryId) {
//             if ($categoryId) {
//                 $conn->execute("INSERT INTO `product_categories` (`product_id`, `category_id`) VALUES ('$Id', '$categoryId')");
//             }
//         }
//         foreach ($this->SubCategoryIds as $subcategoryId) {
//             if ($subcategoryId) {
//                 $conn->execute("INSERT INTO `product_subcategories` (`product_id`, `subcategory_id`) VALUES ('$Id', '$subcategoryId')");
//             }
//         }
//         foreach ($this->SubSubCategoryIds as $subsubcategoryId) {
//             if ($subsubcategoryId) {
//                 $conn->execute("INSERT INTO `product_subsubcategories` (`product_id`, `subsubcategory_id`) VALUES ('$Id', '$subsubcategoryId')");
//             }
//         }

//         // Insert into product_materials table
//         foreach ($this->FrameMaterials as $material) {
//             if ($material) {
//                 $conn->execute("INSERT INTO `product_materials` (`product_id`, `material`) VALUES ('$Id', '$material')");
//             }
//         }

//         // Insert into product_colors table
//         foreach ($this->Colors as $color) {
//             if ($color) {
//                 $conn->execute("INSERT INTO `product_colors` (`product_id`, `color`) VALUES ('$Id', '$color')");
//             }
//         }

//         return $stmt;
//     }

//     function getProducts($Id) 
//     {  
//         $conn = new dbClass;
//         $this->Id = $Id;
//         $this->conndb = $conn;

//         // Get product details
//         $product = $conn->getData("SELECT * FROM `product` WHERE `product_id` = '$Id'");

//         // Get associated categories
//         $categories = $conn->getAllData("SELECT category_id FROM `product_categories` WHERE `product_id` = '$Id'");
//         $product['category_ids'] = array_column($categories, 'category_id');

//         // Get associated subcategories
//         $subcategories = $conn->getAllData("SELECT subcategory_id FROM `product_subcategories` WHERE `product_id` = '$Id'");
//         $product['subcategory_ids'] = array_column($subcategories, 'subcategory_id');

//         // Get associated sub-subcategories
//         $subsubcategories = $conn->getAllData("SELECT subsubcategory_id FROM `product_subsubcategories` WHERE `product_id` = '$Id'");
//         $product['subsubcategory_ids'] = array_column($subsubcategories, 'subsubcategory_id');

//         // Get frame materials
//         $materials = $conn->getAllData("SELECT material FROM `product_materials` WHERE `product_id` = '$Id'");
//         $product['frame_materials'] = array_column($materials, 'material');

//         // Get colors
//         $colors = $conn->getAllData("SELECT color FROM `product_colors` WHERE `product_id` = '$Id'");
//         $product['colors'] = array_column($colors, 'color');

//         return $product;
//     }

//     function allProducts() 
//     {  
//         $conn = new dbClass;
//         $this->conndb = $conn;

//         $products = $conn->getAllData("SELECT * FROM `product` ORDER BY `product_id` DESC");

//         foreach ($products as &$product) {
//             $productId = $product['product_id'];
//             // Get associated categories
//             $categories = $conn->getAllData("SELECT category_id FROM `product_categories` WHERE `product_id` = '$productId'");
//             $product['category_ids'] = array_column($categories, 'category_id');

//             // Get associated subcategories
//             $subcategories = $conn->getAllData("SELECT subcategory_id FROM `product_subcategories` WHERE `product_id` = '$productId'");
//             $product['subcategory_ids'] = array_column($subcategories, 'subcategory_id');

//             // Get associated sub-subcategories
//             $subsubcategories = $conn->getAllData("SELECT subsubcategory_id FROM `product_subsubcategories` WHERE `product_id` = '$productId'");
//             $product['subsubcategory_ids'] = array_column($subsubcategories, 'subsubcategory_id');

//             // Get frame materials
//             $materials = $conn->getAllData("SELECT material FROM `product_materials` WHERE `product_id` = '$productId'");
//             $product['frame_materials'] = array_column($materials, 'material');

//             // Get colors
//             $colors = $conn->getAllData("SELECT color FROM `product_colors` WHERE `product_id` = '$productId'");
//             $product['colors'] = array_column($colors, 'color');
//         }

//         return $products;
//     }

//     function getProdcutsImages($Id)
//     {
//         $conn = new dbClass;
//         $this->Id = $Id;
//         $this->conndb = $conn;

//         $output = $conn->getAllData("SELECT * FROM `product_images` WHERE `product_id` = '$Id'");
//         return $output;
//     }

//     function prodcutsImageCount($Id)
//     {
//         $conn = new dbClass;
//         $this->Id = $Id;
//         $this->conndb = $conn;

//         $output = $conn->getRowCount("SELECT image_id FROM `product_images` WHERE `product_id` = '$Id'");
//         return $output;
//     }
//         function getallColor()
//     {  
//         $conn = new dbClass;
//         $this->conndb = $conn;
    
//         $stmt = $conn->getAllData("SELECT * FROM `color` ORDER BY `id` DESC");
//         return $stmt;
//     }
//         function getallMaterial()
//     {  
//         $conn = new dbClass;
//         $this->conndb = $conn;
    
//         $stmt = $conn->getAllData("SELECT * FROM `material` ORDER BY `id` DESC");
//         return $stmt;
//     }
// }

    class userDetail
    {

        public $ID;
        public $Image;
        public $Username;
        public $Phone;
        public $Email;
        public $conndb;

        public function loginUserDetail($ID)
        {
            $conn = new dbClass;
            $this->ID = $ID;
            $this->conndb = $conn;

            $result = $conn->getData("SELECT * FROM `admin` WHERE `id` = '$ID'");
            return $result;
        }

        function updateProfile($Image, $Username, $Phone, $Email, $ID) 
        {  
            $conn = new dbClass;
            $this->ID = $ID;
            $this->Image = $Image;
            $this->Username = $Username;
            $this->Phone = $Phone;
            $this->Email = $Email;
            $this->conndb = $conn;
        
            $stmt = $conn->execute("UPDATE `admin` SET `image` = '$Image', `username` = '$Username', `phone` = '$Phone', `email` = '$Email', `updated_at` = now() WHERE `id` = '$ID'");
            return $stmt;
        }
    }

    class Customers{
        private $customerId;
        private $conndb;

        function allCustomers()
        {  
            $conn = new dbClass;
            $this->conndb = $conn;
            
            $stmt = $conn->getAllData("SELECT * FROM `customers` ORDER BY `customer_id` DESC");
            return $stmt;
        }

        function getCustomersDetail($customerId)
        {  
            $conn = new dbClass;
            $this->conndb = $conn;
            $this->customerId = $customerId;

            $stmt = $conn->getData("SELECT * FROM `customers` WHERE `customer_id` = '$customerId'");
            return $stmt;
        }
    }

    class ViewContact
    {
        function allContact()
        {	
            $conn = new dbClass();
            $stmt = $conn->getAllData("SELECT * FROM `contact` ORDER BY `id` DESC");
            return $stmt;
        }
    }

// class Banner
// {
//     private $ID;
//     private $Image;
//     private $Status;
//     private $Heading;
//     private $Subheading1;
//     private $Subheading2;
//     private $conndb;

//     function addBanner($Image, $Status, $Heading, $Subheading1, $Subheading2)
//     {  
//         $conn = new dbClass;
//         $this->Image = $Image;
//         $this->Status = $Status;
//         $this->Heading = $Heading;
//         $this->Subheading1 = $Subheading1;
//         $this->Subheading2 = $Subheading2;
//         $this->conndb = $conn;
    
//         $stmt = $conn->execute("INSERT INTO `banner` (`image`, `status`, `heading`, `subheading1`, `subheading2`) VALUES ('$Image', '$Status', '$Heading', '$Subheading1', '$Subheading2')");
//         return $stmt;
//     }
    
//     function updateBanner($Image, $Status, $ID, $Heading, $Subheading1, $Subheading2)
//     {  
//         $conn = new dbClass;
//         $this->ID = $ID;
//         $this->Image = $Image;
//         $this->Status = $Status;
//         $this->Heading = $Heading;
//         $this->Subheading1 = $Subheading1;
//         $this->Subheading2 = $Subheading2;
//         $this->conndb = $conn;
    
//         $stmt = $conn->execute("UPDATE `banner` SET `image` = '$Image', `status` = '$Status', `heading` = '$Heading', `subheading1` = '$Subheading1', `subheading2` = '$Subheading2', `updated_at` = now() WHERE `id` = '$ID'");
//         return $stmt;
//     }
    
//     function allBanner() 
//     {  
//         $conn = new dbClass;
//         $this->conndb = $conn;
    
//         $stmt = $conn->getAllData("SELECT * FROM `banner` ORDER BY `id` DESC");
//         return $stmt;
//     }
    
//     function getBanner($ID) 
//     {  
//         $conn = new dbClass;
//         $this->ID = $ID;
//         $this->conndb = $conn;
//         $sql="SELECT * FROM `banner` WHERE `id` = '$ID'";
//         $stmt = $conn->getData($sql);
//         return $stmt;
//     }	
// }
class Banner
{
    private $ID;
    private $Image;
    private $Status;
    private $Heading;
    private $Subheading;
    private $Button_link;
    private $conndb;

    function addBanner($Image, $Status, $Heading, $Subheading, $Button_link)
    {  
        $conn = new dbClass;
        $this->Image = $Image;
        $this->Status = $Status;
        $this->Heading = $Heading;
        $this->Subheading = $Subheading;
        $this->Button_link = $Button_link;
        $this->conndb = $conn;
    
        $stmt = $conn->execute("INSERT INTO `banner` (`image`, `status`, `heading`, `subheading`, `button_link`) VALUES ('$Image', '$Status', '$Heading', '$Subheading', '$Button_link')");
        return $stmt;
    }
    
    function updateBanner($Image, $Status, $ID, $Heading, $Subheading, $Button_link)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Image = $Image;
        $this->Status = $Status;
        $this->Heading = $Heading;
        $this->Subheading = $Subheading;
        $this->Button_link = $Button_link;
        $this->conndb = $conn;
    
        $stmt = $conn->execute("UPDATE `banner` SET `image` = '$Image', `status` = '$Status', `heading` = '$Heading', `subheading` = '$Subheading', `button_link` = '$Button_link', `updated_at` = now() WHERE `id` = '$ID'");
        return $stmt;
    }
    
    function allBanner() 
    {  
        $conn = new dbClass;
        $this->conndb = $conn;
    
        $stmt = $conn->getAllData("SELECT * FROM `banner` ORDER BY `id` DESC");
        return $stmt;
    }
    
    function getBanner($ID) 
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;
        $sql="SELECT * FROM `banner` WHERE `id` = '$ID'";
        $stmt = $conn->getData($sql);
        return $stmt;
    }	
}
class Material
{
    private $ID;
    private $Name;
    private $Status;
    private $conndb;

    // Add a new material
    function addMaterial($Name, $Status)
    {  
        $conn = new dbClass;
        $this->Name = $Name;
        $this->Status = $Status;
        $this->conndb = $conn;
    
        $stmt = $conn->execute("INSERT INTO `material` (`name`, `status`) VALUES ('$Name', '$Status')");
        return $stmt;
    }
    
    // Update an existing material
    function updateMaterial($ID, $Name, $Status)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Status = $Status;
        $this->conndb = $conn;
    
        $stmt = $conn->execute("UPDATE `material` SET `name` = '$Name', `status` = '$Status', `updated_at` = NOW() WHERE `id` = '$ID'");
        return $stmt;
    }
    
    // Retrieve all materials
    function allMaterial()
    {  
        $conn = new dbClass;
        $this->conndb = $conn;
    
        $stmt = $conn->getAllData("SELECT * FROM `material` ORDER BY `id` DESC");
        return $stmt;
    }
    
    // Retrieve a single material by ID
    function getMaterial($ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;
        $sql = "SELECT * FROM `material` WHERE `id` = '$ID'";
        $stmt = $conn->getData($sql);
        return $stmt;
    }	
}
class Color
{
    private $ID;
    private $Name;
    private $Color_code;
    private $Status;
    private $conndb;

    // Add a new color
    function addColor($Name, $Color_code, $Status)
    {  
        $conn = new dbClass;
        $this->Name = $Name;
        $this->Color_code = $Color_code;
        $this->Status = $Status;
        $this->conndb = $conn;
    
        $stmt = $conn->execute("INSERT INTO `color` (`name`, `color_code`, `status`) VALUES ('$Name', '$Color_code', '$Status')");
        return $stmt;
    }
    
    // Update an existing color
    function updateColor($ID, $Name, $Color_code, $Status)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Color_code = $Color_code;
        $this->Status = $Status;
        $this->conndb = $conn;
    
        $stmt = $conn->execute("UPDATE `color` SET `name` = '$Name', `color_code` = '$Color_code', `status` = '$Status', `updated_at` = NOW() WHERE `id` = '$ID'");
        return $stmt;
    }
    
    // Retrieve all colors
    function allColor()
    {  
        $conn = new dbClass;
        $this->conndb = $conn;
    
        $stmt = $conn->getAllData("SELECT * FROM `color` ORDER BY `id` DESC");
        return $stmt;
    }
    
    // Retrieve a single color by ID
    function getColor($ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;
        $sql = "SELECT * FROM `color` WHERE `id` = '$ID'";
        $stmt = $conn->getData($sql);
        return $stmt;
    }	
}
// class Color
// {
//     private $ID;
//     private $Name;
//     private $Status;
//     private $conndb;

//     // Add a new color
//     function addColor($Name, $Status)
//     {  
//         $conn = new dbClass;
//         $this->Name = $Name;
//         $this->Status = $Status;
//         $this->conndb = $conn;
    
//         $stmt = $conn->execute("INSERT INTO `color` (`name`, `status`) VALUES ('$Name', '$Status')");
//         return $stmt;
//     }
    
//     // Update an existing color
//     function updateColor($ID, $Name, $Status)
//     {  
//         $conn = new dbClass;
//         $this->ID = $ID;
//         $this->Name = $Name;
//         $this->Status = $Status;
//         $this->conndb = $conn;
    
//         $stmt = $conn->execute("UPDATE `color` SET `name` = '$Name', `status` = '$Status', `updated_at` = NOW() WHERE `id` = '$ID'");
//         return $stmt;
//     }
    
//     // Retrieve all colors
//     function allColor()
//     {  
//         $conn = new dbClass;
//         $this->conndb = $conn;
    
//         $stmt = $conn->getAllData("SELECT * FROM `color` ORDER BY `id` DESC");
//         return $stmt;
//     }
    
//     // Retrieve a single color by ID
//     function getColor($ID)
//     {  
//         $conn = new dbClass;
//         $this->ID = $ID;
//         $this->conndb = $conn;
//         $sql = "SELECT * FROM `color` WHERE `id` = '$ID'";
//         $stmt = $conn->getData($sql);
//         return $stmt;
//     }	
// }

    class policy
    {
        private $ID;
        private $Title;
        private $Description;
        private $Btn_name;
        private $Btn_link;
        private $conndb;
        
        function updatePolicy($Description, $ID)
        {  
            $conn = new dbClass;
            $this->ID = $ID;
            $this->Description = $Description;
            $this->conndb = $conn;
        
            $stmt = $conn->execute("UPDATE `policy` SET `description` = '$Description', `updated_at` = now() WHERE `privacy_id` = '$ID'");
            return $stmt;
        }
        
        function allPolicy() 
        {  
            $conn = new dbClass;
            $this->conndb = $conn;
        
            $stmt = $conn->getAllData("SELECT * FROM `policy` ORDER BY `privacy_id` DESC");
            return $stmt;
        }
        
        function getPolicy($ID) 
        {  
            $conn = new dbClass;
            $this->ID = $ID;
            $this->conndb = $conn;
        
            $stmt = $conn->getData("SELECT * FROM `policy` WHERE `privacy_id` = '$ID'");
            return $stmt;
        }	
    }
    class Notice
    {
        private $ID;
        private $Title;
        private $Description;
        private $Btn_name;
        private $Btn_link;
        private $conndb;
        
        function updateNotice($Title, $Description, $Btn_name, $Btn_link, $ID)
        {  
            $conn = new dbClass;
            $this->ID = $ID;
            $this->Title = $Title;
            $this->Description = $Description;
            $this->Btn_name = $Btn_name;
            $this->Btn_link = $Btn_link;
            $this->conndb = $conn;
        
            $stmt = $conn->execute("UPDATE `notice` SET `title` = '$Title', `description` = '$Description', `btn_name` = '$Btn_name', `btn_link` = '$Btn_link', `updated_at` = now() WHERE `id` = '$ID'");
            return $stmt;
        }
        
        function allNotice() 
        {  
            $conn = new dbClass;
            $this->conndb = $conn;
        
            $stmt = $conn->getAllData("SELECT * FROM `notice` ORDER BY `id` DESC");
            return $stmt;
        }
        
        function getNotice($ID) 
        {  
            $conn = new dbClass;
            $this->ID = $ID;
            $this->conndb = $conn;
        
            $stmt = $conn->getData("SELECT * FROM `notice` WHERE `id` = '$ID'");
            return $stmt;
        }	
    }

    class Password
    {
        private $ID;
        private $Password;
        private $conndb;
        
        function addPassword($Password)
        {  
            $conn = new dbClass;
            $this->Password = $Password;
        
            $stmt = $conn->execute("INSERT INTO `password`(`password`) VALUES ('$Password')");
            return $stmt;
        }

        function updatePassword($Password, $ID)
        {
            $conn = new dbClass;
            $this->ID = $ID;
            $this->Password = $Password;
            $this->conndb = $conn;

            $stmt = $conn->execute("UPDATE `password` SET `password` = '$Password', `updated_at` = now() WHERE `id` = '$ID'");
            return $stmt;
        }
        
        function allPassword() 
        {  
            $conn = new dbClass;
            $this->conndb = $conn;
        
            $stmt = $conn->getAllData("SELECT * FROM `password` ORDER BY `id` DESC");
            return $stmt;
        }
        
        function getPassword($ID) 
        {  
            $conn = new dbClass;
            $this->ID = $ID;
            $this->conndb = $conn;
        
            $stmt = $conn->getData("SELECT * FROM `password` WHERE `id` = '$ID'");
            return $stmt;
        }	
    }
    class Testimonials
{
    private $ID;
    private $Name;
    private $Testimonial;
    private $Image;
    private $Status;
    private $conndb;

    function addTestimonials($Name, $Testimonial, $Image, $Status)
    {  
        $conn = new dbClass;
        $this->Name = $Name;
        $this->Testimonial = $Testimonial;
        $this->Image = $Image;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("INSERT INTO `testimonial` (`name`, `testimonial`, `image`, `status`) VALUES ('$Name', '$Testimonial', '$Image', '$Status')");
        return $stmt;
    }

    function updateTestimonials($Name, $Testimonial, $Image, $Status, $ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Testimonial = $Testimonial;
        $this->Image = $Image;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("UPDATE `testimonial` SET `name` = '$Name', `testimonial` = '$Testimonial', `image` = '$Image', `status` = '$Status', `updated_at` = now() WHERE `id` = '$ID'");
        return $stmt;
    }

    function getAllTestimonials()
    {  
        $conn = new dbClass;
        $this->conndb = $conn;

        $stmt = $conn->getAllData("SELECT * FROM `testimonial` ORDER BY `id` DESC");
        return $stmt;
    }

    function getTestimonials($ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT * FROM `testimonial` WHERE `id` = '$ID'");
        return $stmt;
    }

    function checkTestimonials($Name, $type)
    {
        $conn = new dbClass;
        $this->Name = $Name;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT COUNT(*) as count FROM `testimonial` WHERE `name` = '$Name'");
        return $stmt['count'];
    }
}

class Faqs
{
    private $ID;
    private $Question;
    private $Answer;
    private $Status;
    private $conndb;

    function addFaqs($Question, $Answer, $Status)
    {  
        $conn = new dbClass;
        $this->Question = $Question;
        $this->Answer = $Answer;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("INSERT INTO `faq` (`question`, `answer`, `status`) VALUES ('$Question', '$Answer', '$Status')");
        return $stmt;
    }

    function updateFaqs($Question, $Answer, $Status, $ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Question = $Question;
        $this->Answer = $Answer;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("UPDATE `faq` SET `question` = '$Question', `answer` = '$Answer', `status` = '$Status', `updated_at` = now() WHERE `id` = '$ID'");
        return $stmt;
    }

    function allFaqs()
    {  
        $conn = new dbClass;
        $this->conndb = $conn;

        $stmt = $conn->getAllData("SELECT * FROM `faq` ORDER BY `id` DESC");
        return $stmt;
    }

    function getFaqs($ID)
    {  
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT * FROM `faq` WHERE `id` = '$ID'");
        return $stmt;
    }

    function checkFaqs($Question, $type)
    {
        $conn = new dbClass;
        $this->Question = $Question;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT COUNT(*) as count FROM `faq` WHERE `question` = '$Question'");
        return $stmt['count'];
    }
}
class Coupons {
    private $ID;
    private $Name;
    private $Code;
    private $ExpiryDate;
    private $Description;
    private $DiscountPercentage; // Added for discount_percentage
    private $Status;
    private $conndb;

    function addCoupons($Name, $Code, $ExpiryDate, $Description, $DiscountPercentage, $minimum, $Status) {
        $conn = new dbClass;
        $this->Name = $Name;
        $this->Code = $Code;
        $this->ExpiryDate = $ExpiryDate;
        $this->Description = $Description;
        $this->DiscountPercentage = $DiscountPercentage;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("INSERT INTO `coupon` (`name`, `code`, `expiry_date`, `description`, `discount_percentage`, `minimum`, `status`) VALUES ('$Name', '$Code', '$ExpiryDate', '$Description', '$DiscountPercentage', '$minimum', '$Status')");
        return $stmt;
    }

    function updateCoupons($Name, $Code, $ExpiryDate, $Description, $DiscountPercentage, $minimum, $Status, $ID) {
        $conn = new dbClass;
        $this->ID = $ID;
        $this->Name = $Name;
        $this->Code = $Code;
        $this->ExpiryDate = $ExpiryDate;
        $this->Description = $Description;
        $this->DiscountPercentage = $DiscountPercentage;
        $this->Status = $Status;
        $this->conndb = $conn;

        $stmt = $conn->execute("UPDATE `coupon` SET `name` = '$Name', `code` = '$Code', `expiry_date` = '$ExpiryDate', `description` = '$Description', `discount_percentage` = '$DiscountPercentage', `minimum` = '$minimum', `status` = '$Status', `updated_at` = NOW() WHERE `id` = '$ID'");
        return $stmt;
    }

    function allCoupons() {
        $conn = new dbClass;
        $this->conndb = $conn;

        $stmt = $conn->getAllData("SELECT * FROM `coupon` ORDER BY `id` DESC");
        return $stmt;
    }

    function getCoupons($ID) {
        $conn = new dbClass;
        $this->ID = $ID;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT * FROM `coupon` WHERE `id` = '$ID'");
        return $stmt;
    }

    function checkCoupons($Code, $type) {
        $conn = new dbClass;
        $this->Code = $Code;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT COUNT(*) as count FROM `coupon` WHERE `code` = '$Code'");
        return $stmt['count'];
    }

    function checkCouponUsage($couponId, $userId) {
        $conn = new dbClass;
        $this->ID = $couponId;
        $this->conndb = $conn;

        $stmt = $conn->getData("SELECT COUNT(*) as count FROM `coupon_usage` WHERE `coupon_id` = '$couponId' AND `user_id` = '$userId'");
        return $stmt['count'];
    }

    function recordCouponUsage($couponId, $userId) {
        $conn = new dbClass;
        $this->ID = $couponId;
        $this->conndb = $conn;

        $stmt = $conn->execute("INSERT INTO `coupon_usage` (`coupon_id`, `user_id`) VALUES ('$couponId', '$userId')");
        return $stmt;
    }

    function validateCoupon($code, $userId) {
        $conn = new dbClass;
        $this->Code = $code;
        $this->conndb = $conn;

        $code = $conn->addStr($code); // Sanitize input
        $couponData = $conn->getData("SELECT * FROM `coupon` WHERE `code` = '$code' AND `status` = 1 AND `expiry_date` > NOW()");
        
        if ($couponData) {
            $couponId = $couponData['id'];
            $isUsed = $this->checkCouponUsage($couponId, $userId);
            if ($isUsed == 0) {
                return ['valid' => true, 'coupon' => $couponData];
            } else {
                return ['valid' => false, 'error' => 'You have already used this coupon.'];
            }
        } else {
            return ['valid' => false, 'error' => 'Invalid or expired coupon code.'];
        }
    }
}
?>