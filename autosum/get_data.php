<?php
require 'config.php';

header('Content-Type: application/json');
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'getProductTypes':
        $query = "SELECT * FROM product_type";
        $result = $conn->query($query);
        $types = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($types);
        break;

    case 'getBrands':
        $productTypeId = isset($_GET['productTypeId']) ? intval($_GET['productTypeId']) : 0;
        if ($productTypeId === 1) {
            $query = "SELECT * FROM product_fobcase_brand ORDER BY `name`";
            $result = $conn->query($query);
            $brands = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($brands);
        } else {
            echo json_encode([]);
        }
        break;

    case 'getEngravingColor':
        $query = "SELECT * FROM product_color WHERE id_materials = '4'";
        $result = $conn->query($query);
        $engravingColor = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($engravingColor);
        break;

    case 'getMetalType':
        $productTypeId = isset($_GET['productTypeId']) ? intval($_GET['productTypeId']) : 0;
        if ($productTypeId === 4) {
        $query = "SELECT * FROM product WHERE id_product_type = $productTypeId";
        $result = $conn->query($query);
        $metalType = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($metalType);
    } else {
        echo json_encode([]);
    }
    break;

    case 'metalColor':
        $query = "SELECT * FROM product_color WHERE id_materials = '3'";
        $result = $conn->query($query);
        $metalColor = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($metalColor);
        break;

    case 'getRemoteCodes':
        $brandId = isset($_GET['brandId']) ? intval($_GET['brandId']) : 0;
        $query = "SELECT * FROM `product_fobcase_detail` WHERE `id_brand` = $brandId ORDER BY `sku` ASC";
        $result = $conn->query($query);
        $remoteCodes = $result->fetch_all(MYSQLI_ASSOC);
    
        // Loop through each remote code to fetch the slang_th
        foreach ($remoteCodes as &$code) {
            $sku = $conn->real_escape_string($code['sku']); // Escape the SKU to prevent SQL injection
            $queryProduct = "SELECT * FROM product WHERE sku = '$sku'";
            $productResult = $conn->query($queryProduct);
    
            if ($productResult) {
                $product = $productResult->fetch_assoc();
                $code['slang_th'] = $product['slang_th'];
                $code['price'] = $product['price'];
            }
        }
        
        echo json_encode($remoteCodes);
        break;

    case 'getLeatherTypes':
        $query = "SELECT * FROM product_color WHERE id_materials IN (1, 2)";
        $result = $conn->query($query);
        $leatherTypes = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($leatherTypes);
        break;

    case 'getColors':
        $leatherTypeID = isset($_GET['leatherTypeID']) ? intval($_GET['leatherTypeID']) : 0;
        $query = "SELECT * FROM product_color WHERE id_materials = $leatherTypeID";
        $result = $conn->query($query);
        $colors = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($colors);
        break;

    default:
        echo json_encode([]);
        break;
}

$conn->close();
